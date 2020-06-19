<?php


namespace DibukEu\Requests;

use DibukEu\Entity\Item;
use DibukEu\Entity\User;

class Transfer extends Request
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var Item
     */
    private $item;
    /**
     * @var User
     */
    private $newUser;


    public function __construct(User $user, Item $item, User $newUser)
    {
        $this->user = $user;
        $this->item = $item;
        $this->newUser = $newUser;
    }

    public function action(): string
    {
        return 'changeOwnership';
    }

    public function data(): array
    {
        return [
            'book_id' => $this->item->id,
            'user_id' => $this->user->id,
            'new_user_id' => $this->newUser->id,
            'new_user_email' => $this->newUser->email,
        ];
    }
}