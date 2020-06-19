<?php


namespace DibukEu\Requests;


use DibukEu\Entity\Item;
use DibukEu\Entity\User;

class License extends Request
{
    /** @var Item */
    private $item;
    /** @var User */
    private $user;

    public function __construct(User $user, Item $item)
    {
        $this->user = $user;
        $this->item = $item;
    }

    public function action(): string
    {
        return 'buy';
    }

    public function data(): array
    {
        return [
            'book_id' => $this->item->id,
            'user_id' => $this->user->id,
            'user_email' => $this->user->email,
            'user_order' => $this->item->order_id,
            'seller_price' => $this->item->price,
            'payment_channel' => $this->item->payment_id,
            'user_name' => $this->user->name,
            'user_surname' => $this->user->surname,
            'uniq_license_id' => $this->item->unique_id,
        ];

    }
}