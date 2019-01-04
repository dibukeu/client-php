<?php

namespace Dibuk\Test;

use Dibuk\Test\DibukTestCase;

class IsProductAvailableTest extends DibukTestCase
{
    private $bookId = 50;
    private $userId = 101;

    public function testValidResponse()
    {
        $this->withValidResponse();
        $result = $this->dibukClient->isProductAvailable($this->bookId);
        $this->assertTrue($result['data']);
    }

    public function testResponseError()
    {
        // Q: toto by podla mna malo padnut
        $this->dibukClient->withResponse(
            [
            'status' => DibukTestClient::STATUS_ERROR,
            'data' => true
            ]
        );
        $result = $this->dibukClient->isProductAvailable($this->bookId);
        $this->assertTrue($result['data']);
    }

    public function testResponseAlreadyExists()
    {
        // Q: toto by podla mna malo padnut
        $this->dibukClient->withResponse(
            [
            'status' => DibukTestClient::STATUS_ALREADY_EXISTS,
            'data' => true
            ]
        );
        $result = $this->dibukClient->isProductAvailable($this->bookId);
        $this->assertTrue($result['data']);
    }

    public function testValidRequest()
    {
        $this->withValidResponse();

        $this->dibukClient->setUser(
            [
            'id' => $this->userId
            ]
        );
        $result = $this->dibukClient->isProductAvailable($this->bookId);
        
        $this->assertIsSubarray(
            [
            'a' => 'available',
            'book_id' => $this->bookId,
            'user_id' => $this->userId
            ], $this->dibukClient->requestData['params']
        );
    }
}
