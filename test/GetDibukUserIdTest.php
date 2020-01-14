<?php

namespace DibukEu\Test;

use DibukEu\Test\DibukTestCase;

class GetDibukUserIdTest extends DibukTestCase
{
    /** @var int  */
    private $userId = 1;

    private function setUser():void
    {
        $this->dibukClient->setUser(
            [
                'id' => $this->userId
            ]
        );
    }

    public function testValidResponse(): void
    {
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_OK,
                'id' => $this->userId
            ]
        );
        $result = $this->dibukClient->getDibukUserId();
        $this->assertEquals($result, $this->userId);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testResponseError(): void
    {
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_ERROR,
                'id' => $this->userId
            ]
        );
        $result = $this->dibukClient->getDibukUserId();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testResponseAlreadyExists(): void
    {
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_ALREADY_EXISTS,
                'id' => $this->userId
            ]
        );
        $result = $this->dibukClient->getDibukUserId();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testResponseUserNull(): void
    {
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_OK,
                'id' => null
            ]
        );
        $result = $this->dibukClient->getDibukUserId();
    }

    public function testValidRequest(): void
    {
        $this->setUser();
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_OK,
                'id' => $this->userId
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
