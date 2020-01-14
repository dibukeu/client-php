<?php

namespace DibukEu\Test;

class ExportItemsTest extends DibukTestCase
{
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
