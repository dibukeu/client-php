<?php


namespace DibukEu\Responses;


abstract class Response
{


    const ERROR_NUM_NOT_BUYED = 2004;
    const ERROR_NUM_EXCEEDED_LIMIT = 2015;

    protected $successStatues = ['OK'];
    /**
     * @var array
     */
    protected $data;
    /**
     * @var string
     */
    private $status;
    /**
     * @var int
     */
    private $errorNum;
    /**
     * @var bool
     */
    private $success;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->errorNum = intval($data['eNum'] ?? 0);
        $this->status = $data['status'];

        $this->success = in_array($this->status, $this->successStatues);
    }

    public function success(): bool
    {
        return $this->success;
    }

    public function error(): bool
    {
        return !$this->success();
    }

    public function notBuyed(): bool
    {
        return $this->error() && $this->errorNum == self::ERROR_NUM_NOT_BUYED;
    }

    public function exceededLimit(): bool
    {
        return $this->error() && $this->errorNum == self::ERROR_NUM_EXCEEDED_LIMIT;
    }
}