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
     * @param array $response
     */
    public function withResponse($response): void
    {
        $this->response = $response;
    }

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
