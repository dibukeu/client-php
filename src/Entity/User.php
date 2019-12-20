<?php

namespace DibukEu\Entity;

use Exception;

class User
{
    /** @var int */
    public $id;
    /** @var string */
    public $email;
    /** @var string */
    public $name;
    /** @var string */
    public $surname;

    /**
     * User constructor.
     * @param array $user
     */
    public function __construct($user)
    {
        $user += [
            'id' => null,
            'email' => null,
            'name' => null,
            'surname' => null
        ];
        $this->id = $user['id'];
        $this->email = $user['email'];
        $this->name = $user['name'];
        $this->surname = $user['surname'];
    }

    /**
     * Types: minimal, email, full
     *
     * @param  string $type
     * @return bool
     * @throws Exception
     */
    public function checkValid($type = 'minimal')
    {

        if ($type == 'full') {
            $valid = $this->isValidId() && $this->isValidEmail() && $this->isValidName() && $this->isValidSurname();
        } elseif ($type == 'email') {
            $valid = $this->isValidId() && $this->isValidEmail();
        } else {
            $valid = $this->isValidId();
        }

        if (!$valid) {
            throw new Exception('User is not properly setted');
        }

        return true;

    }

    /**
     * @return bool
     */
    private function isValidId()
    {
        return !empty($this->id) && is_numeric($this->id);
    }

    /**
     * @return bool
     */
    private function isValidEmail()
    {
        return boolval(filter_var($this->email, FILTER_VALIDATE_EMAIL));
    }

    /**
     * @return bool
     */
    private function isValidName()
    {
        return !empty($this->name);
    }

    /**
     * @return bool
     */
    private function isValidSurname()
    {
        return !empty($this->surname);
    }
}
