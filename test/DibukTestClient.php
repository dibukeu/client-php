<?php

namespace DibukEu\Test;

use DibukEu\DibukClient;

class DibukTestClient extends DibukClient
{
    /** @var array */
    private $response;
    /** @var array */
    public $requestData;

    /**
     * @param array $response Response from test client
     * @return void
     */
    public function withResponse($response): void
    {
        $this->response = $response;
    }

    /**
     * @param string $url
     * @param array $params
     * @param string $type
     * @return array
     */
    protected function request($url, $params, $type = 'post'): array
    {
        $this->requestData = [
            'url' => $url,
            'params' => $params,
            'type' => $type
        ];
        return $this->response;
    }
}
