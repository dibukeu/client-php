<?php

namespace DibukEu\Test;

use DibukEu\Test\DibukTestCase;

class DibukClientTest extends DibukTestCase
{
    public function testValidRequest(): void
    {
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_OK,
            ]
        );
        $result = $this->dibukClient->exportItems();

        $this->assertIsSubarray(
            [
                'url' => 'api.dibuk.lsk',
                'params' => [
                    'v' => '2.3',
                    'did' => 1,
                ],
                'type' => 'post',
            ],
            $this->dibukClient->requestData
        );
        $this->assertArrayhasKey('ch', $this->dibukClient->requestData['params']);
    }
}
