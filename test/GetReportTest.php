<?php

namespace DibukEu\Test;

use DibukEu\Test\DibukTestCase;

class GetReportTest extends DibukTestCase
{
    /** @var string */
    private $from = "2018-01-01 01:00:00";
    /** @var string */
    private $to = "2018-01-02 01:00:00";

    /**
     * @throws \Exception
     * @return void
     */
    public function testValidResponse(): void
    {
        $this->withValidResponse();
        $result = $this->dibukClient->getReport($this->from);
        $this->assertTrue($result['data']);
    }

    /**
     * @expectedException \RuntimeException
     * @return void
     * @throws \Exception
     */
    public function testResponseError(): void
    {
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_ERROR,
                'data' => true,
            ]
        );
        $result = $this->dibukClient->getReport($this->from);
    }

    /**
     * @expectedException \RuntimeException
     * @return void
     * @throws \Exception
     */
    public function testResponseAlreadyExists(): void
    {
        $this->dibukClient->withResponse(
            [
                'status' => DibukTestClient::STATUS_ALREADY_EXISTS,
                'data' => true,
            ]
        );
        $result = $this->dibukClient->getReport($this->from);
    }

    /**
     * @throws \Exception
     * @return void
     */
    public function testValidRequest(): void
    {
        $this->withValidResponse();

        $result = $this->dibukClient->getReport($this->from);

        $this->assertIsSubarray(
            [
                'a' => 'report',
                'date_from' => strtotime($this->from),
                'date_to' => null,
            ],
            $this->dibukClient->requestData['params']
        );

        $result = $this->dibukClient->getReport($this->from, $this->to);

        $this->assertIsSubarray(
            [
                'a' => 'report',
                'date_from' => strtotime($this->from),
                'date_to' => strtotime($this->to),
            ],
            $this->dibukClient->requestData['params']
        );
    }
}
