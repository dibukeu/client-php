<?php

namespace DibukEu;

use Composer\CaBundle\CaBundle;
use DibukEu\Entity\Format;
use DibukEu\Entity\Item;
use DibukEu\Entity\User;
use DibukEu\Exceptions\ExceededLimitException;
use RuntimeException;

class DibukClient
{

    const STATUS_OK = 'OK';
    const STATUS_ERROR = 'ERR';
    const STATUS_ALREADY_EXISTS = 'HAVEYET';

    const ERROR_NUM_NOT_BUYED = 2004;
    const ERROR_NUM_EXCEEDED_LIMIT = 2015;

    /** @var null|string */
    protected $apiVersion;

    /** @var  User */
    protected $user;
    /** @var  User */
    protected $newUser;
    /** @var  Item */
    protected $item;

    /** @var int|null */
    protected $sellerId;
    /** @var string|null */
    protected $signature;
    /** @var string */
    protected $url = '';

    /**
     * DibukClient constructor.
     *
     * @param array $config
     * @throws \Exception
     */
    public function __construct($config)
    {
        $this->sellerId = isset($config['sellerId']) ? $config['sellerId'] : null;
        $this->signature = isset($config['signature']) ? $config['signature'] : null;
        $this->url = isset($config['url']) ? $config['url'] : "";
        $this->apiVersion = isset($config['version']) ? $config['version'] : "";

        $this->initUser();
        $this->initItem();

        $this->validateConfig();
    }

    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function exportItems()
    {
        $data = $this->call(
            'export', [
                'export' => 'categories',

            ]
        );

        if ($data['status'] != self::STATUS_OK) {
            throw new RuntimeException("Dibuk export items call failed with response " . json_encode($data));
        }

        return $data;
    }

    /**
     * @param string $dateFrom
     * @param null|string $dateTo
     * @return array|mixed
     * @throws \Exception
     */
    public function getReport($dateFrom, $dateTo = null)
    {
        $data = $this->call(
            'report',
            [
                'date_from' => strtotime($dateFrom),
                'date_to' => ($dateTo ? strtotime($dateTo) : null),
            ]
        );

        if ($data['status'] != self::STATUS_OK) {
            throw new RuntimeException("Dibuk report call failed with response " . json_encode($data));
        }

        return $data;
    }

    /**
     * @param null|string $emailTo
     * @param bool $repeated
     * @param bool $free
     * @return bool
     * @throws ExceededLimitException
     */
    public function sendByEmail($emailTo = null, $repeated = false, $free = false)
    {
        $data = $this->call(
            'sendByEmail',
            [
                'book_id' => $this->item->id,
                'send_to_email' => $emailTo ?: $this->user->email,
                'user_id' => $this->user->id,
                'user_name' => $this->user->name,
                'user_surname' => $this->user->surname,
                'user_email' => $this->user->email,
            ]
        );

        if (!$repeated && $data['status'] == self::STATUS_ERROR && $data['eNum'] == self::ERROR_NUM_NOT_BUYED) {
            if ($free) {
                $this->createFreeLicense();

                return $this->sendByEmail($emailTo, true, true);
            } else {
                $this->createLicense(true);

                return $this->sendByEmail($emailTo, true);
            }
        } elseif ($data['status'] == self::STATUS_ERROR && $data['eNum'] == self::ERROR_NUM_EXCEEDED_LIMIT) {
            throw new ExceededLimitException(
                [
                    'message' => "Download limit per 24h exceeded, next download will be available on " . $data['eData'],
                    'nextAttemptAvailable' => $data['eData'],
                ]
            );
        } elseif ($data['status'] != self::STATUS_OK) {
            throw new RuntimeException("Dibuk sendByEmail call failed with response " . json_encode($data));
        }

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function changeOwnership(): bool
    {
        $data = $this->call(
            'changeOwnership',
            [
                'book_id' => $this->item->id,
                'user_id' => $this->user->id,
                'new_user_id' => $this->newUser->id,
            ]
        );

        if ($data['status'] != self::STATUS_OK) {
            throw new RuntimeException("Dibuk changeOwnership call failed with response " . json_encode($data));
        }

        return true;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getDibukUserId()
    {
        $data = $this->call(
            'getFakeId',
            [
                'user_id' => $this->user->id,
            ]
        );

        if ($data['status'] != self::STATUS_OK || is_null($data['id'])) {
            throw new RuntimeException("Dibuk getFakeId call failed with response " . json_encode($data));
        }

        return (int)$data['id'];
    }

    /**
     * @param null|int $bookId
     * @return array|mixed
     * @throws \Exception
     */
    public function isProductAvailable($bookId = null)
    {
        return $this->call('available', ['book_id' => $bookId ?: $this->item->id, 'user_id' => $this->user->id]);
    }

    /**
     * @param bool $repeated
     * @return array
     * @throws \Exception
     */
    public function getAllDownloadLinks($repeated = false)
    {
        if ($this->item->download_links) {
            return $this->item->download_links;
        }
        $this->user->checkValid('minimal');
        $this->item->checkValid('minimal');

        $data = $this->call(
            'downloadLinks', [
                'book_id' => $this->item->id,
                'user_id' => $this->user->id,
                'user_name' => $this->user->name,
                'user_surname' => $this->user->surname,
                'user_email' => $this->user->email,
            ]
        );

        if (!$repeated && $data['status'] == self::STATUS_ERROR && $data['eNum'] == self::ERROR_NUM_NOT_BUYED) {
            $this->createLicense(true);

            return $this->getAllDownloadLinks(true);
        } elseif ($data['status'] != self::STATUS_OK && $data['status'] != self::STATUS_ALREADY_EXISTS) {
            throw new RuntimeException("Dibuk getDownloadLinks call " . json_encode($data) . " failed with response " . json_encode($data));
        }

        $links = [];
        $format = new Format();

        if (isset($data['data'][0])) {   //eaudiobook - have chapters
            return $data['data'][0]['formats'];
        } else {
            foreach ($data['data'] as $formatId => $url) {
                $links[$format->getFormatCode($formatId)] = $url;
            }
        }

        $this->item->setDownloadLinks($links);

        return $links;
    }

    /**
     * @param string $format_code
     * @return mixed
     * @throws \Exception
     */
    public function getDownloadLink($format_code)
    {
        $links = $this->getAllDownloadLinks();
        if (isset($links[$format_code])) {
            return $links[$format_code];
        }

        throw new \Exception('Item has not ' . $format_code . ' format. Available: ' . json_encode($links));
    }

    /**
     * @param bool $createLicenseForce
     * @return array|bool
     * @throws \Exception
     */
    public function createLicense($createLicenseForce = false)
    {
        if ($this->item->license_created && !$createLicenseForce) {
            return true;
        }
        $this->user->checkValid('full');
        $this->item->checkValid('full');

        $data = $this->call(
            'buy', [
                'book_id' => $this->item->id,
                'user_id' => $this->user->id,
                'user_email' => $this->user->email,
                'user_order' => $this->item->order_id,
                'seller_price' => $this->item->price,
                'payment_channel' => $this->item->payment_id,
                'user_name' => $this->user->name,
                'user_surname' => $this->user->surname,
                'uniq_license_id' => $this->item->unique_id,
            ]
        );

        if ($data['status'] != self::STATUS_OK && $data['status'] != self::STATUS_ALREADY_EXISTS) {
            throw new RuntimeException("Dibuk Buy call failed with response " . json_encode($data));
        }

        $this->item->setLicenseCreated();

        return [    //todo zjednotit return format, v zasade nepotrebujem rozpisane jednotlive entity
            'status' => $data['status'],
            'item' => $this->item,
            'user' => $this->user,
        ];
    }

    /**
     * @return array|bool
     * @throws \Exception
     */
    public function createFreeLicense()
    {
        if ($this->item->license_created) {
            return true;
        }
        $this->user->checkValid('full');
        $this->item->checkValid('full');

        $data = $this->call(
            'buy', [
                'book_id' => $this->item->id,
                'user_id' => $this->user->id,
                'user_email' => $this->user->email,
                'user_order' => $this->item->order_id,
                'seller_price' => $this->item->price,
                'payment_channel' => $this->item->payment_id,
                'user_name' => $this->user->name,
                'user_surname' => $this->user->surname,
                'uniq_license_id' => $this->item->unique_id,
            ]
        );

        if ($data['status'] != self::STATUS_OK && $data['status'] != self::STATUS_ALREADY_EXISTS) {
            throw new RuntimeException("Dibuk Buy call failed with response " . json_encode($data));
        }

        $this->item->setLicenseCreated();

        return [    //todo zjednotit return format, v zasade nepotrebujem rozpisane jednotlive entity
            'status' => $data['status'],
            'item' => $this->item,
            'user' => $this->user,
        ];
    }

    /**
     * @return void
     */
    protected function initUser()
    {
        $this->user = new User([]);
    }

    /**
     * @param array $user Set User
     * @return void
     */
    public function setUser($user)
    {
        $this->user = new User($user);
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param array $user New user
     * @return void
     */
    public function setNewUser($user)
    {
        $this->newUser = new User($user);
    }

    /**
     * @return User
     */
    public function getNewUser()
    {
        return $this->newUser;
    }

    /**
     * @return void
     */
    protected function initItem()
    {
        $this->item = new Item([]);
    }

    /**
     * @param array $item
     * @return void
     */
    public function setItem($item)
    {

        $this->item = new Item($item);
    }

    /**
     * @return Item
     */
    public function getItem()
    {

        return $this->item;
    }

    /**
     * @return array
     */
    public function getAllFormats()
    {
        $format = new Format();

        return $format->getAllFormats();
    }

    /**
     * @param string $action
     * @param array $additional_parameters
     * @return array|mixed
     * @throws \Exception
     */
    protected function call($action, $additional_parameters = [])
    {
        $parameters = [
                'a' => $action,
                'v' => $this->apiVersion,
                'did' => $this->sellerId,
            ] + $additional_parameters;

        $base64signature = base64_decode((string)$this->signature);
        if (!is_string($base64signature)) {
            throw new \Exception('Invalid signature');
        }
        $parameters['ch'] = hash_hmac("sha1", http_build_query($parameters), $base64signature);

        $responseData = $this->request($this->url, $parameters);

        if (!is_array($responseData) || !isset($responseData['status'])) {
            throw new RuntimeException("Dibuk returned malformed response: '" . json_encode($responseData) . "'");
        }

        return $responseData;
    }

    /**
     * @param string $url
     * @param array $params
     * @param string $type
     * @return array|mixed
     * @throws \Exception
     */
    protected function request($url, $params, $type = 'post')
    {
        //setting the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        $caPathOrFile = CaBundle::getSystemCaRootBundlePath();
        if (is_dir($caPathOrFile) || (is_link($caPathOrFile) && is_dir(readlink($caPathOrFile)))) {
            curl_setopt($ch, CURLOPT_CAPATH, $caPathOrFile);
        } else {
            curl_setopt($ch, CURLOPT_CAINFO, $caPathOrFile);
        }

        //turning off the server and peer verification(TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->createUrlParams($params));

        $sResponse = curl_exec($ch);

        if (curl_errno($ch)) {
            return [
                'status' => 'ERR',
                'eNum' => curl_errno($ch),
                'eMsg' => curl_error($ch),
            ];
        } else {
            curl_close($ch);
        }

        if (is_bool($sResponse)) {
            throw new \Exception('Api call failed');
        }

        return json_decode($sResponse, true);
    }

    /**
     * @param array $params
     * @return string
     */
    protected function createUrlParams($params = [])
    {
        return http_build_query($params);
    }

    /**
     * @param string $base
     * @param array $params
     * @return string
     */
    protected function createUrl($base, $params = [])
    {
        $url = $base;
        $query = $this->createUrlParams($params);
        if (!empty($query)) {
            $url .= "?" . $query;
        }

        return $url;
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function validateConfig()
    {
        if (empty($this->sellerId)) {
            throw new \Exception('SellerId not specified');
        }
        if (empty($this->signature)) {
            throw new \Exception('Signature not specified');
        }
    }
}
