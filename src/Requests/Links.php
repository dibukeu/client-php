<?php


namespace DibukEu\Requests;


use DibukEu\Entity\Item;
use DibukEu\Entity\User;

class Links extends Request
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var Item
     */
    private $item;

    public function __construct(User $user, Item $item)
    {
        $this->user = $user;
        $this->item = $item;
    }

    public function action(): string
    {
        return 'downloadLinks';
    }

    public function data(): array
    {
        return [
            'book_id' => $this->item->id,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_surname' => $this->user->surname,
            'user_email' => $this->user->email,
        ];
    }
}