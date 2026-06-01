<?php
class RegisterUser
{
    private $username;
    private $raw_password;
    private $encrypted_password;
    public $error;
    public $success;
    private $storage = "data/users.json";
    private $stored_users;
    private $new_user;

    public function __construct($username, $password)
    {
        $this->username = trim((string) $username);

        $this->raw_password = trim((string) $password);
        $this->stored_users = json_decode(file_get_contents($this->storage), true) ?: [];

        if ($this->checkFieldValues()) {
            $this->encrypted_password = password_hash($this->raw_password, PASSWORD_DEFAULT);
            $this->new_user = [
                "username" => $this->username,
                "password" => $this->encrypted_password,
            ];
            $this->insertUser();
        }
    }

    private function checkFieldValues()
    {
        if (empty($this->username) && empty($this->raw_password)) {
            $this->error = "Name and password are required";
            return false;
        }

        if (empty($this->username)) {
            $this->error = "Name is required";
            return false;
        }

        if (empty($this->raw_password)) {
            $this->error = "Password is required";
            return false;
        } else {
            return true;
        }
    }

    private function usernameExists()
    {
        foreach ($this->stored_users as $user) {
            if (!is_array($user) || !isset($user['username'])) {
                continue;
            }

            if ($this->username == $user['username']) {
                $this->error = "Username already taken";
                return true;
            }
        }
        return false;
    }

    private function insertUser()
    {
        if ($this->usernameExists() == FALSE) {
            array_push($this->stored_users, $this->new_user);
            if (file_put_contents($this->storage, json_encode($this->stored_users, JSON_PRETTY_PRINT))) {
                return $this->success = "Your registration was successful";
            } else {
                return $this->error = "Something went wrong, please try again";
            }
        }
    }
}
