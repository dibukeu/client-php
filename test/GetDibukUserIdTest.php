<?php

namespace DibukEu\Test;

use DibukEu\Test\DibukTestCase;

class GetDibukUserIdTest extends DibukTestCase
{
    /** @var int  */
    private $userId = 1;

    /**
     * Set user
     * @return void
     */
    private function setUser(): void
    {
        $this->dibukClient->setUser(
            [
                'id' => $this->userId,
            ]
        );
    }

    /**
     * @throws \Exception
     * @return void
     */
    public function testValidResponse(): void
    {
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_OK,
                'id' => $this->userId,
            ]
        );
        $result = $this->dibukClient->getDibukUserId();
        $this->assertEquals($result, $this->userId);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testResponseError(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Dibuk getFakeId call failed with response {"status":"ERR","id":1}');
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_ERROR,
                'id' => $this->userId,
            ]
        );
        $result = $this->dibukClient->getDibukUserId();
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testResponseAlreadyExists(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Dibuk getFakeId call failed with response {"status":"HAVEYET","id":1}');
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_ALREADY_EXISTS,
                'id' => $this->userId,
            ]
        );
        $result = $this->dibukClient->getDibukUserId();
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testResponseUserNull(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Dibuk getFakeId call failed with response {"status":"OK","id":null}');
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_OK,
                'id' => null,
            ]
        );
        $result = $this->dibukClient->getDibukUserId();
    }

    /**
     * @throws \Exception
     * @return void
     */
    public function testValidRequest(): void
    {
        $this->setUser();
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_OK,
                'id' => $this->userId,
            ]
        );

        $result = $this->dibukClient->getDibukUserId();

        $this->assertIsSubarray(
            [
                'a' => 'getFakeId',
                'user_id' => $this->userId,
            ], $this->dibukClient->requestData['params']
        );
    }
}
