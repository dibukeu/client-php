<?php

namespace DibukEu\Test;

use DibukEu\DibukClient;

class ChangeOwnershipTest extends DibukTestCase
{

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
     * @throws \Exception
     * @return void
     */
    public function testCallApi(): void
    {
        $this->dibukClient = $this->getMockBuilder(DibukClient::class)
            ->setConstructorArgs(
                [[
                    'signature' => 'test_secret',
                    'sellerId' => 1,
                    'version' => '2.3',
                    'url' => 'api.dibuk.lsk',
                ]]
            )
            ->setMethods(['call'])->getMock();
        $this->dibukClient->expects($this->once())
            ->method('call')
            ->with(
                'changeOwnership', [
                    'book_id' => 50,
                    'user_id' => 101,
                    'new_user_id' => 202,
                ]
            )->willReturn(['status' => DibukClient::STATUS_OK]);

        $this->setValidClient();
        $this->assertTrue($this->dibukClient->changeOwnership());
    }

    /**
     * @return                   void
     * @throws                   \Exception
     * @expectedException        \RuntimeException
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
     *
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
