<?php

namespace Dibuk\Test;

use Dibuk\Test\DibukTestCase;

class GetDibukUserIdTest extends DibukTestCase {
    private $userId = 1;

    private function setUser()
    {
        $this->dibukClient->setUser([
            'id' => $this->userId
        ]);
    }

    public function testValidResponse()
    {
        $this->dibukClient->withResponse([
            'status' => DibukTestClient::STATUS_OK,
            'id' => $this->userId
        ]);
        $result = $this->dibukClient->getDibukUserId();
        $this->assertEquals($result, $this->userId);
    }

    public function testResponseError()
    {
        $this->dibukClient->withResponse([
            'status' => DibukTestClient::STATUS_ERROR,
            'id' => $this->userId
        ]);
        $this->expectException(\RuntimeException::class);
        $result = $this->dibukClient->getDibukUserId();
    }

    public function testResponseAlreadyExists()
    {
        $this->dibukClient->withResponse([
            'status' => DibukTestClient::STATUS_ALREADY_EXISTS,
            'id' => $this->userId
        ]);
        $this->expectException(\RuntimeException::class);
        $result = $this->dibukClient->getDibukUserId();
    }

    public function testResponseUserNull()
    {
        $this->dibukClient->withResponse([
            'status' => DibukTestClient::STATUS_OK,
            'id' => null
        ]);
        $this->expectException(\RuntimeException::class);
        $result = $this->dibukClient->getDibukUserId();
    }

    public function testValidRequest()
    {
        $this->setUser();
        $this->dibukClient->withResponse([
            'status' => DibukTestClient::STATUS_OK,
            'id' => $this->userId
        ]);
        
        $result = $this->dibukClient->getDibukUserId();
        
        $this->assertIsSubarray([
            'a' => 'getFakeId',
            'user_id' => $this->userId,
        ], $this->dibukClient->requestData['params']);
    }
}