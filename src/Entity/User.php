<?php

namespace Dibuk\Entity;

class User
{
    public $id, $email, $name, $surname;

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
     * @throws \Exception
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
            throw new \Exception('User is not properly setted');
        }

        return true;

    }

    private function isValidId()
    {
        return !empty($this->id) && is_numeric($this->id);
    }

    private function isValidEmail()
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL);
    }

    private function isValidName()
    {
        return !empty($this->name);
    }

    private function isValidSurname()
    {
        return !empty($this->surname);
    }
}
