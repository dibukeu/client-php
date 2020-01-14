<?php

namespace DibukEu\Test;

class ExportItemsTest extends DibukTestCase
{
    /**
     * @throws \Exception
     * @return void
     */
    public function testValidResponse(): void
    {
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_OK,
                'data' => true,
            ]
        );
        $result = $this->dibukClient->exportItems();

        $this->assertTrue($result['data']);
    }

    /**
     * @expectedException \RuntimeException
     * @return void
     * @throws \Exception
     */
    public function testResponseError(): void
    {
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_ERROR,
                'data' => true,
            ]
        );
        $result = $this->dibukClient->exportItems();
    }

    /**
     * @expectedException \RuntimeException
     * @return void
     * @throws \Exception
     */
    public function testResponseAlreadyExists(): void
    {
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_ALREADY_EXISTS,
                'data' => true,
            ]
        );
        $result = $this->dibukClient->exportItems();
    }

    /**
     * @throws \Exception
     * @return void
     */
    public function testValidRequest(): void
    {
        $this->withValidResponse();
        $result = $this->dibukClient->exportItems();

        $this->assertIsSubarray(
            [
                'a' => 'export',
                'export' => 'categories',
            ],
            $this->dibukClient->requestData['params']
        );
    }
}
