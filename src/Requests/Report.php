<?php


namespace DibukEu\Requests;


use DateTime;

class Report extends Request
{
    /**
     * @var DateTime
     */
    private $from;
    /**
     * @var DateTime|null
     */
    private $to;

    public function __construct(DateTime $from, ?DateTime $to = null)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function action(): string
    {
        return 'report';
    }

    public function data(): array
    {
        return [
            'date_from' => $this->from->getTimestamp(),
            'date_to' => $this->to ? $this->to->getTimestamp() : null,
        ];
    }
}