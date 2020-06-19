<?php


namespace DibukEu\Responses;

use DibukEu\Entity\Format;

class Linked extends Response
{
    /** @var array */
    private $links = null;

    /**
     * @return array
     * @throws \Exception
     */
    public function all(): array
    {
        $this->init();
        return $this->links;
    }

    /**
     * @throws \Exception
     */
    public function init(): void
    {
        if (!is_null($this->links)) {
            return;
        }
        $format = new Format();
        if (isset($this->data['data'][0])) {   //eaudiobook - have chapters
            $this->links = $this->data['data'][0]['formats'];
        } else {
            $this->links = [];
            foreach ($this->data['data'] as $formatId => $url) {
                $this->links[$format->getFormatCode($formatId)] = $url;
            }
        }
    }
}