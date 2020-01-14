<?php

namespace DibukEu\Test;

use DibukEu\Test\DibukTestCase;

class IsProductAvailableTest extends DibukTestCase
{
    /** @var int */
    private $bookId = 50;
    /** @var int */
    private $userId = 101;

    /**
     * @throws \Exception
     */
    public function testValidResponse(): void
    {
        $this->withValidResponse();
        $result = $this->dibukClient->isProductAvailable($this->bookId);
        $this->assertTrue($result['data']);
    }

    /**
     * @throws \Exception
     */
    public function testResponseError(): void
    {
        // Q: toto by podla mna malo padnut
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_ERROR,
                'data' => true,
            ]
        );
        $result = $this->dibukClient->isProductAvailable($this->bookId);
        $this->assertTrue($result['data']);
    }

    /**
     * @throws \Exception
     */
    public function testResponseAlreadyExists(): void
    {
        // Q: toto by podla mna malo padnut
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_ALREADY_EXISTS,
                'data' => true,
            ]
        );
        $result = $this->dibukClient->isProductAvailable($this->bookId);
        $this->assertTrue($result['data']);
    }

    /**
     * @throws \Exception
     */
    public function testValidRequest(): void
    {
        $this->withValidResponse();

        $this->dibukClient->setUser(
            [
                'id' => $this->userId,
            ]
        );
        $result = $this->dibukClient->isProductAvailable($this->bookId);

        $this->assertIsSubarray(
            [
                'a' => 'available',
                'book_id' => $this->bookId,
                'user_id' => $this->userId,
            ],
            $this->dibukClient->requestData['params']
        );
    }
}
