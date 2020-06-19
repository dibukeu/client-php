<?php

namespace DibukEu\Test;

use DibukEu\Exceptions\ExceededLimitException;

class SendByEmailTest extends DibukTestCase
{
    /**
     * set up valid client
     * @return void
     */
    private function setValidClient(): void
    {
        $this->dibukClient->setItem(
            [
                'id' => 50,
            ]
        );
        $this->dibukClient->setUser(
            [
                'id' => 101,
                'name' => 'test',
                'surname' => 'ington',
                'email' => 'abcd@xyz.com',
            ]
        );
    }

    /**
     * @return void
     * @throws \DibukEu\Exceptions\ExceededLimitException
     */
    public function testValidResponse(): void
    {
        $this->withValidResponse();
        $response = $this->dibukClient->send();
        $this->assertTrue($response);
    }

    /**
     * @return void
     */
    public function testResponseExceededLimit(): void
    {
        $this->expectException(ExceededLimitException::class);
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_ERROR,
                'eNum' => DibukTestClient::ERROR_NUM_EXCEEDED_LIMIT,
                'eData' => 'mock_data',
            ]
        );
        $response = $this->dibukClient->send();
    }

    public function testResponseError(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_ERROR,
                'eNum' => null,
                'eData' => 'mock_data',
            ]
        );
        $response = $this->dibukClient->send();
    }

    /**
     * @return void
     * @throws \DibukEu\Exceptions\ExceededLimitException
     */
    public function testResponseAlreadyExists(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_ALREADY_EXISTS,
                'eNum' => null,
                'eData' => 'mock_data',
            ]
        );
        $response = $this->dibukClient->send();
    }

    /**
     * @return void
     * @throws \DibukEu\Exceptions\ExceededLimitException
     */
    public function testValidRequest(): void
    {
        $this->withValidResponse();
        $this->setValidClient();

        $result = $this->dibukClient->send();
        $this->assertIsSubarray(
            [
                'a' => 'sendByEmail',
                'book_id' => 50,
                'send_to_email' => 'abcd@xyz.com',
                'user_id' => 101,
                'user_name' => 'test',
                'user_surname' => 'ington',
                'user_email' => 'abcd@xyz.com',
            ],
            $this->dibukClient->requestData['params']
        );

        $result = $this->dibukClient->send("iny@aaa.sk");
        $this->assertIsSubarray(
            [
                'a' => 'sendByEmail',
                'book_id' => 50,
                'send_to_email' => 'iny@aaa.sk',
                'user_id' => 101,
                'user_name' => 'test',
                'user_surname' => 'ington',
                'user_email' => 'abcd@xyz.com',
            ],
            $this->dibukClient->requestData['params']
        );
    }

    /**
     * @return void
     * @throws \DibukEu\Exceptions\ExceededLimitException
     */
    public function testInvalidRequest(): void
    {
        $this->withValidResponse();

        $this->dibukClient->setItem(
            [
                'id' => 50,
            ]
        );
        // Q: nemalo by toto padnut, kedze nemam nastavene id usera?
        $this->dibukClient->setUser(
            [
                'name' => 'test',
                'surname' => 'ington',
                'email' => 'abcd@xyz.com',
            ]
        );

        $result = $this->dibukClient->send();
        $this->assertIsSubarray(
            [
                'a' => 'sendByEmail',
                'book_id' => 50,
                'send_to_email' => 'abcd@xyz.com',
                'user_name' => 'test',
                'user_surname' => 'ington',
                'user_email' => 'abcd@xyz.com',
            ],
            $this->dibukClient->requestData['params']
        );
    }
}
