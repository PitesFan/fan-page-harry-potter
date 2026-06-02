<?php
class RegisterUser
{
    private $username;
    private $email;
    private $raw_password;
    private $encrypted_password;
    public $error;
    public $success;
    public $userLang;
    private $storage = "data/users.json";
    private $stored_users;
    private $new_user;

    public function __construct($username, $email, $password,  $userLang)
    {
        $this->userLang = $userLang;
        $this->username = trim((string) $username);
        $this->email = trim((string) $email);

        $this->raw_password = trim((string) $password);
        $this->stored_users = json_decode(file_get_contents($this->storage), true) ?: [];

        if ($this->checkFieldValues()) {
            $this->encrypted_password = password_hash($this->raw_password, PASSWORD_DEFAULT);
            $this->new_user = [
                "username" => $this->username,
                "email" => $this->email,
                "password" => $this->encrypted_password,
            ];
            $this->insertUser();
        }
    }

    private function checkFieldValues()
    {
        if (empty($this->username) || empty($this->raw_password) || empty($this->email)) {
            if ($this->userLang == 'ro') {
                $this->error = "Toate câmpurile sunt necesare";
            }
            if ($this->userLang == 'en') {
                $this->error = "All fields are required";
            }
            return false;
        } else {
            return true;
        }
    }

    private function emailExists()
    {
        foreach ($this->stored_users as $user) {
            if (!is_array($user) || !isset($user['email'])) {
                continue;
            }

            if ($this->email == $user['email']) {
                $this->error = "Email already used";
                if ($this->userLang == 'ro') {
                    $this->error = "Email deja folosit";
                }
                if ($this->userLang == 'en') {
                    $this->error = "Email already used";
                }
                return true;
            }
        }
        return false;
    }

    private function insertUser()
    {
        if ($this->emailExists() == FALSE) {
            array_push($this->stored_users, $this->new_user);
            if (file_put_contents($this->storage, json_encode($this->stored_users, JSON_PRETTY_PRINT))) {
                return $this->success = "Your registration was successful";
                if ($this->userLang == 'ro') {
                    return $this->success = "Înregistrare cu succes";
                }
                if ($this->userLang == 'en') {
                    return $this->success = "Your registration was successful";
                }
            } else {
                return $this->error = "Something went wrong, please try again";
                if ($this->userLang == 'ro') {
                    return $this->error = "Ceva a mers greșit, încearcă din nou";
                }
                if ($this->userLang == 'en') {
                    return $this->error = "Something went wrong, please try again";
                }
            }
        }
    }
}
