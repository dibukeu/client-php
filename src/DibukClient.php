<?php

namespace DibukEu;

use DateTime;
use DibukEu\Entity\Format;
use DibukEu\Entity\Item;
use DibukEu\Entity\User;
use DibukEu\Requests\Export;
use DibukEu\Requests\License;
use DibukEu\Requests\Links;
use DibukEu\Requests\Report;
use DibukEu\Requests\Request;
use DibukEu\Requests\Send;
use DibukEu\Requests\Transfer;
use DibukEu\Responses\Exported;
use DibukEu\Responses\Licensed;
use DibukEu\Responses\Linked;
use DibukEu\Responses\Reported;
use DibukEu\Responses\Response;
use DibukEu\Responses\Sent;
use DibukEu\Responses\Transfered;

class DibukClient
{

    const STATUS_OK = 'OK';
    const STATUS_ERROR = 'ERR';
    const STATUS_ALREADY_EXISTS = 'HAVEYET';

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
        $this->apiVersion = '2.3';

        $this->validateConfig();
    }

    /**
     * @return Exported
     * @throws \Exception
     */
    public function exportItems(): Exported
    {
        $request = new Export();
        return new Exported($this->call($request));
    }

    /**
     * @param DateTime $from
     * @param DateTime|null $to
     * @return Reported
     * @throws \Exception
     */
    public function getReport(DateTime $from, ?DateTime $to = null):Reported
    {
        $request = new Report($from, $to);
        return new Reported($this->call($request));
    }

    /**
     * @param User $user
     * @param Item $item
     * @param string $emailTo
     * @param bool $repeated
     * @param bool $free
     * @return Sent
     */
    public function send(User $user, Item $item, ?string $emailTo = null, $repeated = false, $free = false): Sent
    {
        $request = new Send($user, $item, $emailTo);
        return new Sent($this->call($request));
    }

    /**
     * @param User $user
     * @param Item $item
     * @param User $newUser
     * @return Transfered
     */
    public function changeOwnership(User $user, Item $item, User $newUser): Transfered
    {
        $request = new Transfer($user, $item, $newUser);
        return new Transfered($this->call($request));
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getDibukUserId()
    {
//        $data = $this->call(
//            'getFakeId',
//            [
//                'user_id' => $this->user->id,
//            ]
//        );
//
//        if ($data['status'] != self::STATUS_OK || is_null($data['id'])) {
//            throw new RuntimeException("Dibuk getFakeId call failed with response " . json_encode($data));
//        }
//
//        return (int)$data['id'];
    }

    /**
     * @param null|int $bookId
     * @return array|mixed
     * @throws \Exception
     */
    public function isProductAvailable($bookId = null)
    {
//        return $this->call('available', ['book_id' => $bookId ?: $this->item->id, 'user_id' => $this->user->id]);
    }

    /**
     * @param User $user
     * @param Item $item
     * @return Linked
     * @throws \Exception
     */
    public function links(User $user, Item $item): Linked
    {
        $request = new Links($user, $item);
        return new Linked($this->call($request));
    }

    /**
     * @param User $user
     * @param Item $item
     * @param bool $free
     * @return array|bool
     * @throws \Exception
     */
    public function license(User $user, Item $item): Response
    {
//        $this->user->checkValid('full');
//        $this->item->checkValid('full');

        $license = new License($user, $item);
        return new Licensed($this->call($license));
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

    private function call(Request $request)
    {
        return $request->call($this->sellerId, $this->signature);
    }
}
