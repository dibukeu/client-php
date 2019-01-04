<?php

namespace Dibukeu\Test;

use Dibukeu\Test\DibukTestCase;

class GetReportTest extends DibukTestCase
{
    private $from = "2018-01-01 01:00:00";
    private $to = "2018-01-02 01:00:00";

    public function testValidResponse()
    {
        $this->withValidResponse();
        $result = $this->dibukClient->getReport($this->from);
        $this->assertTrue($result['data']);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testResponseError()
    {
        $this->dibukClient->withResponse(
            [
            'status' => DibukTestClient::STATUS_ERROR,
            'data' => true
            ]
        );
        $result = $this->dibukClient->getReport($this->from);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testResponseAlreadyExists()
    {
        $this->dibukClient->withResponse(
            [
            'status' => DibukTestClient::STATUS_ALREADY_EXISTS,
            'data' => true
            ]
        );
        $result = $this->dibukClient->getReport($this->from);
    }

    public function testValidRequest()
    {
        $this->withValidResponse();

        $result = $this->dibukClient->getReport($this->from);
        
        $this->assertIsSubarray(
            [
            'a' => 'report',
            'date_from' => strtotime($this->from),
            'date_to' => null
            ], $this->dibukClient->requestData['params']
        );

        $result = $this->dibukClient->getReport($this->from, $this->to);
        
        $this->assertIsSubarray(
            [
            'a' => 'report',
            'date_from' => strtotime($this->from),
            'date_to' => strtotime($this->to)
            ], $this->dibukClient->requestData['params']
        );
    }
}
