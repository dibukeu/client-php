<?php


namespace DibukEu\Requests;

class Export extends Request
{

    public function action(): string
    {
        return 'export';
    }

    public function data(): array
    {
        return [
            'export' => 'categories',
        ];
    }
}