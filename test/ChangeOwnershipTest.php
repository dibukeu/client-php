<?php

namespace DibukEu\Test;

class ChangeOwnershipTest extends DibukTestCase
{

    /** @var int */
    private $bookId = 50;
    /** @var int */
    private $userId = 101;

    /**
     * @return void
     * @throws \Exception
     */
    public function testValidResponse(): void
    {
        $this->withValidResponse();
        $this->setValidClient();
        $result = $this->dibukClient->changeOwnership();
        $this->assertTrue($result);
    }

    /**
     * @return void
     * @throws \Exception
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Dibuk changeOwnership call failed with response {"status":"ERR"}
     */
    public function testResponseError(): void
    {
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_ERROR,
            ]
        );
        $this->setValidClient();
        $this->dibukClient->changeOwnership();
    }

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
                'email' => 'user@example.com',
            ]
        );

        $this->dibukClient->setNewUser(
            [
                'id' => 202,
                'name' => 'new',
                'surname' => 'tester',
                'email' => 'new@example.com',
            ]
        );
    }
}
