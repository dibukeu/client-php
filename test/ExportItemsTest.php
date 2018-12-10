<?php

namespace Dibuk\Test;

use Dibuk\Test\DibukTestCase;

class ExportItemsTest extends DibukTestCase {
    public function testValidResponse()
    {
        $this->dibukClient->withResponse([
            'status' => DibukTestClient::STATUS_OK,
            'data' => true
        ]);
        $result = $this->dibukClient->exportItems();
        
        $this->assertTrue($result['data']);
    }

    public function testResponseError()
    {
        $this->expectException(\RuntimeException::class);
        $this->dibukClient->withResponse([
            'status' => DibukTestClient::STATUS_ERROR,
            'data' => true
        ]);
        $result = $this->dibukClient->exportItems();
    }

    public function testResponseAlreadyExists()
    {
        $this->expectException(\RuntimeException::class);

        $this->dibukClient->withResponse([
            'status' => DibukTestClient::STATUS_ALREADY_EXISTS,
            'data' => true
        ]);
        $result = $this->dibukClient->exportItems();
    }

    public function testValidRequest()
    {
        $this->withValidResponse();
        $result = $this->dibukClient->exportItems();
        
        $this->assertIsSubarray([
            'a' => 'export',
            'export' => 'categories'
        ], $this->dibukClient->requestData['params']);
    }
}