<?php

namespace DibukEu\Test;

use PHPUnit\Framework\TestCase;

abstract class DibukTestCase extends TestCase
{
    /** @var DibukTestClient */
    protected $dibukClient;

    /**
     * setUp
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->dibukClient = new DibukTestClient(
            [
                'signature' => 'test_secret',
                'sellerId' => 1,
                'version' => '2.3',
                'url' => 'api.dibuk.lsk',
            ]
        );
    }

    /**
     * @param array|string $example Input
     * @param array $result Asserted result
     * @return void
     */
    protected function assertIsSubarray($example, $result): void
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

    /**
     * @return void
     */
    public function withValidResponse(): void
    {
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_OK,
                'data' => true,
            ]
        );
    }
}
