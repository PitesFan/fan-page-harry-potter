<?php
class LoginUser
{
    private $email;
    private $username;
    private $password;
    public $error;
    public $success;
    public $userLang;
    private $storage = "data/users.json";
    private $stored_users;

    public function __construct($email, $password, $userLang)
    {
        $this->userLang = $userLang;
        $this->email = $email;
        $this->password = $password;
        $this->stored_users = json_decode(file_get_contents($this->storage), true);
        $this->login();
    }

    private function login()
    {
        foreach ($this->stored_users as $user) {
            if ($user['email'] == $this->email) {
                if (password_verify($this->password, $user['password'])) {
                    session_start();
                    $_SESSION['user'] = $this->email;
                    header("location: account.php");
                    exit();
                }
            }
        }

        $this->checkFieldValues();
    }

    private function checkFieldValues()
    {
        if (empty($this->password) || empty($this->email)) {
            if ($this->userLang == 'ro') {
                $this->error = "Toate câmpurile sunt necesare";
            }
            if ($this->userLang == 'en') {
                $this->error = "All fields are required";
            }
        }
    }
}
