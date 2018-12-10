<?php

namespace Dibuk\Test;

use Dibuk\DibukClient;

class DibukTestClient extends DibukClient
{
    private $response;
    public $requestData;
    
    public function withResponse($response)
    {
        $this->response = $response;
    }

    protected function request($url, $params, $type = 'post')
    {
        $this->requestData = [
            'url' => $url,
            'params' => $params,
            'type' => $type
        ];
        return $this->response;
    }
}
