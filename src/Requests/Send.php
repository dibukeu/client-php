<?php


namespace DibukEu\Requests;


use DibukEu\Entity\Item;
use DibukEu\Entity\User;

class Send extends Request
{
    /** @var Item */
    private $item;
    /** @var User */
    private $user;
    /** @var string|null */
    private $emailTo;

    public function __construct(User $user, Item $item, ?string $emailTo = null)
    {
        $this->user = $user;
        $this->item = $item;
        $this->emailTo = $emailTo;
    }

    public function action(): string
    {
        return 'sendByEmail';
    }

    public function data(): array
    {
        return [
            'book_id' => $this->item->id,
            'send_to_email' => $this->emailTo ?: $this->user->email,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_surname' => $this->user->surname,
            'user_email' => $this->user->email,
        ];
    }
}