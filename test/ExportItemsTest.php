<?php

namespace DibukEu\Test;

class ExportItemsTest extends DibukTestCase
{
    /**
     * @return void
     * @throws \Exception
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
     * @return void
     * @throws \Exception
     */
    public function testResponseError(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Dibuk export items call failed with response {"status":"ERR","data":true}');
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_ERROR,
                'data' => true,
            ]
        );
        $result = $this->dibukClient->exportItems();
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testResponseAlreadyExists(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Dibuk export items call failed with response {"status":"HAVEYET","data":true}');
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_ALREADY_EXISTS,
                'data' => true,
            ]
        );
        $result = $this->dibukClient->exportItems();
    }

    /**
     * @return void
     * @throws \Exception
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
