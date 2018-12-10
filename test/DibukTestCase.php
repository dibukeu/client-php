<?php

namespace Dibuk\Test;

use PHPUnit\Framework\TestCase;

class DibukTestCase extends TestCase 
{
    protected $dibukClient;

    public function setUp()
    {
        $this->dibukClient = new DibukTestClient([
            'signature' => 'test_secret',
            'sellerId' => 1,
            'version' => '2.3',
            'url' => 'api.dibuk.lsk'
        ]);
    }

    protected function assertIsSubarray($example, $result)
    {
        if (!is_array($example)) {
            $this->assertEquals($example, $result);
            return;
        }
        foreach ($example as $key => $value) {
            $this->assertArrayHasKey($key, $result);
            $this->assertIsSubarray($value, $result[$key]);
        }
    }

    public function withValidResponse()
    {
        $this->dibukClient->withResponse([
            'status' => DibukTestClient::STATUS_OK,
            'data' => true
        ]);
    }
}