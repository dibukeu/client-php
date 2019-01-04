<?php

namespace DibukEu\Test;

class SendByEmailTest extends DibukTestCase
{
    private function setValidClient()
    {
        $this->dibukClient->setItem(
            [
            'id' => 50
            ]
        );
        $this->dibukClient->setUser(
            [
            'id' => 101,
            'name' => 'test',
            'surname' => 'ington',
            'email' => 'abcd@xyz.com'
            ]
        );
    }

    public function testValidResponse()
    {
        $this->withValidResponse();
        $response = $this->dibukClient->sendByEmail();
        $this->assertTrue($response);
    }

    /**
     * @expectedException \Dibukeu\Exceptions\ExceededLimitException
     */
    public function testResponseExceededLimit()
    {
        $this->dibukClient->withResponse(
            [
            'status' => DibukTestClient::STATUS_ERROR,
            'eNum' => DibukTestClient::ERROR_NUM_EXCEEDED_LIMIT,
            'eData' => 'mock_data'
            ]
        );
        $response = $this->dibukClient->sendByEmail();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testResponseError()
    {
        $this->dibukClient->withResponse(
            [
            'status' => DibukTestClient::STATUS_ERROR,
            'eNum' => null,
            'eData' => 'mock_data'
            ]
        );
        $response = $this->dibukClient->sendByEmail();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testResponseAlreadyExists()
    {
        $this->dibukClient->withResponse(
            [
            'status' => DibukTestClient::STATUS_ALREADY_EXISTS,
            'eNum' => null,
            'eData' => 'mock_data'
            ]
        );
        $response = $this->dibukClient->sendByEmail();
    }

    public function testValidRequest()
    {
        $this->withValidResponse();
        $this->setValidClient();

        $result = $this->dibukClient->sendByEmail();
        $this->assertIsSubarray(
            [
            'a' => 'sendByEmail',
            'book_id' => 50,
            'send_to_email' => 'abcd@xyz.com',
            'user_id' => 101,
            'user_name' => 'test',
            'user_surname' => 'ington',
            'user_email' => 'abcd@xyz.com'
            ], $this->dibukClient->requestData['params']
        );

        $result = $this->dibukClient->sendByEmail("iny@aaa.sk");
        $this->assertIsSubarray(
            [
            'a' => 'sendByEmail',
            'book_id' => 50,
            'send_to_email' => 'iny@aaa.sk',
            'user_id' => 101,
            'user_name' => 'test',
            'user_surname' => 'ington',
            'user_email' => 'abcd@xyz.com'
            ], $this->dibukClient->requestData['params']
        );
    }

    public function testInvalidRequest()
    {
        $this->withValidResponse();

        $this->dibukClient->setItem(
            [
            'id' => 50
            ]
        );
        // Q: nemalo by toto padnut, kedze nemam nastavene id usera?
        $this->dibukClient->setUser(
            [
            'name' => 'test',
            'surname' => 'ington',
            'email' => 'abcd@xyz.com'
            ]
        );

        $result = $this->dibukClient->sendByEmail();
        $this->assertIsSubarray(
            [
            'a' => 'sendByEmail',
            'book_id' => 50,
            'send_to_email' => 'abcd@xyz.com',
            'user_name' => 'test',
            'user_surname' => 'ington',
            'user_email' => 'abcd@xyz.com'
            ], $this->dibukClient->requestData['params']
        );
    }
}
