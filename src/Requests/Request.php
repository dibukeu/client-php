<?php


namespace DibukEu\Requests;


use Composer\CaBundle\CaBundle;
use RuntimeException;

abstract class Request
{
    private $apiVersion = '2.3';
    private $url = 'http://sandbox.dibuk.eu/call.php';

    abstract public function action(): string;

    abstract public function data(): array;

    /**
     * @param int $sellerId
     * @param string $signature
     * @return array|mixed
     * @throws \Exception
     */
    public function call(int $sellerId, string $signature)
    {
        $parameters = [
                'a' => $this->action(),
                'v' => $this->apiVersion,
                'did' => $sellerId,
            ] + $this->data();

        $base64signature = base64_decode((string)$signature);
        if (!is_string($base64signature)) {
            throw new \Exception('Invalid signature');
        }
        $parameters['ch'] = hash_hmac("sha1", http_build_query($parameters), $base64signature);

        $responseData = $this->request($this->url, $parameters);

        if (!is_array($responseData) || !isset($responseData['status'])) {
            // ok?
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
        if ($caPathOrFile !== false) {
            $isDir = is_dir($caPathOrFile);
            if (!$isDir && is_link($caPathOrFile)) {
                $link = readlink($caPathOrFile);
                assert($link !== false);
                $isDir = is_dir($link);
            }
            if ($isDir) {
                curl_setopt($ch, CURLOPT_CAPATH, $caPathOrFile);
            } else {
                curl_setopt($ch, CURLOPT_CAINFO, $caPathOrFile);
            }
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

}