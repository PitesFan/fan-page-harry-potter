<?php

session_start();

$logo = 'images/logo.png';
$lang = [];
$selected_lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'en';
$allowed_langs = ['en', 'ro'];
if (!in_array($selected_lang, $allowed_langs)) {
    $selected_lang = 'en';
}

$_SESSION['lang'] = $selected_lang;

require_once "data/{$selected_lang}/account.php";

$theme = [];
$allowed_themes = ['dark', 'light'];
$selected_theme = $_GET['theme'] ?? $_SESSION['theme'] ?? 'dark';
if (!in_array($selected_theme, $allowed_themes)) {
    $selected_theme = 'dark';
}

$_SESSION['theme'] = $selected_theme;
$theme = $selected_theme;
$next_theme = $theme === 'dark' ? 'light' : 'dark';

?>

<?php

if (!isset($_SESSION['user'])) {
    header("location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    header("location: login.php");
    exit();
}
?>

<?php
class userAccount
{
    private $email;
    private $username;
    private $password;
    public $error;
    public $success;
    private $storage = "data/users.json";
    private $stored_users;

    public function __construct($email = '', $password = '', $username = '')
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->stored_users = json_decode(file_get_contents($this->storage), true);
    }

    public static function fromSession()
    {
        $stored_users = json_decode(file_get_contents("data/users.json"), true) ?: [];

        foreach ($stored_users as $stored_user) {
            if (!is_array($stored_user) || !isset($stored_user['email'])) {
                continue;
            }

            if ($stored_user['email'] === $_SESSION['user']) {
                return new self(
                    $stored_user['email'],
                    $stored_user['password'] ?? '',
                    $stored_user['username'] ?? ''
                );
            }
        }

        return new self();
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }
}

$user = userAccount::fromSession();

?>

<?php
$editing = isset($_GET['editProfile']);
$feedback_key = $_GET['msg'] ?? '';
$feedback_type = $_GET['type'] ?? '';
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/desktop.css">
    <link rel="stylesheet" href="css/adaptive.css">
</head>

<body class="<?php echo $theme === 'dark' ? 'black-bg' : 'white-bg'; ?>">

    <section class="section">
        <div class="header-adaptive <?php echo $theme === 'dark' ? 'white-bg' : 'black-bg'; ?>">
            <div class="header-up">
                <a class="logo white-bg " href="index.php"><img class="logo white-bg " src=" <?php echo $logo; ?> "></a>
                <a id="header-btn">
                    <img src="images/menu.svg">
                </a>
            </div>
            <div id="header-down">
                <ul class="nav-links">
                    <li>
                        <a class="<?php echo $theme === 'dark' ? 'black' : 'white'; ?>" href="<?php echo $lang['navLinks'][0]['url']; ?>"><?php echo $lang['navLinks'][0]['label']; ?></a>
                    </li>
                    <li>
                        <a class="<?php echo $theme === 'dark' ? 'black' : 'white'; ?>" href="<?php echo $lang['navLinks'][1]['url']; ?>"><?php echo $lang['navLinks'][1]['label']; ?></a>
                    </li>
                    <li>
                        <a class="<?php echo $theme === 'dark' ? 'black' : 'white'; ?>" href="<?php echo $lang['navLinks'][2]['url']; ?>"><?php echo $lang['navLinks'][2]['label']; ?></a>
                    </li>
                    <li>
                        <a class="<?php echo $theme === 'dark' ? 'black' : 'white'; ?>" href="<?php echo $lang['navLinks'][3]['url']; ?>"><?php echo $lang['navLinks'][3]['label']; ?></a>
                    </li>
                </ul>
                <div class="header-buttons">
                    <a href="account.php" class="button profile-btn <?php echo $theme === 'dark' ? 'black-bg' : 'white-bg'; ?>"><img src="<?php echo $theme === 'dark' ? 'images/profile-icon.svg' : 'images/profile-icon-black.svg'; ?>" alt=""></a>
                    <a class="button theme-btn <?php echo $theme === 'dark' ? 'black-bg' : 'white-bg'; ?>" href="?theme=<?php echo $next_theme; ?>"><?php if ($theme == 'dark') {
                                                                                                                                                        echo '☀️';
                                                                                                                                                    } else {
                                                                                                                                                        echo '🌑';
                                                                                                                                                    } ?></a>
                    <a class="lang-btn" href="?lang=<?php echo $lang['switchLang']; ?>">
                        <img src="<?php echo $lang['langImg'] ?>" alt="<?php echo $lang['langAlt'] ?>">
                    </a>
                </div>
            </div>
        </div>
        <div class="header <?php echo $theme === 'dark' ? 'white-bg' : 'black-bg'; ?>">
            <a class="logo white-bg " href="index.php"><img class="logo white-bg " src=" <?php echo $logo; ?> "></a>

            <ul class="nav-links">
                <li>
                    <a class="<?php echo $theme === 'dark' ? 'black' : 'white'; ?>" href="<?php echo $lang['navLinks'][0]['url']; ?>"><?php echo $lang['navLinks'][0]['label']; ?></a>
                </li>
                <li>
                    <a class="<?php echo $theme === 'dark' ? 'black' : 'white'; ?>" href="<?php echo $lang['navLinks'][1]['url']; ?>"><?php echo $lang['navLinks'][1]['label']; ?></a>
                </li>
                <li>
                    <a class="<?php echo $theme === 'dark' ? 'black' : 'white'; ?>" href="<?php echo $lang['navLinks'][2]['url']; ?>"><?php echo $lang['navLinks'][2]['label']; ?></a>
                </li>
                <li>
                    <a class="<?php echo $theme === 'dark' ? 'black' : 'white'; ?>" href="<?php echo $lang['navLinks'][3]['url']; ?>"><?php echo $lang['navLinks'][3]['label']; ?></a>
                </li>
            </ul>
            <div class="header-buttons">
                <a href="account.php" class="button profile-btn <?php echo $theme === 'dark' ? 'black-bg' : 'white-bg'; ?>"><img src="<?php echo $theme === 'dark' ? 'images/profile-icon.svg' : 'images/profile-icon-black.svg'; ?>" alt=""></a>
                <a class="button theme-btn <?php echo $theme === 'dark' ? 'black-bg' : 'white-bg'; ?>" href="?theme=<?php echo $next_theme; ?>"><?php if ($theme == 'dark') {
                                                                                                                                                    echo '☀️';
                                                                                                                                                } else {
                                                                                                                                                    echo '🌑';
                                                                                                                                                } ?></a>
                <a class="lang-btn" href="?lang=<?php echo $lang['switchLang']; ?>">
                    <img src="<?php echo $lang['langImg'] ?>" alt="<?php echo $lang['langAlt'] ?>">
                </a>
            </div>
        </div>
    </section>
    <section class="section section-margin profile-section">
        <div class="section-texts-profile">
            <h2 class="<?php echo $theme === 'dark' ? 'white' : 'dark'; ?>"><?php echo $lang['h2'] ?></h2>
            <p class="<?php echo $theme === 'dark' ? 'light-gray' : 'dark-gray'; ?>"><?php echo $lang['p'] ?></p>
        </div>
        <div class="profile <?php echo $theme === 'dark' ? 'white-bg' : 'black-bg'; ?>">
            <?php if (!$editing): ?>
                <div class="profile-details">
                    <div class="profile-details-row">
                        <h5 class="<?php echo $theme === 'dark' ? 'black' : 'white'; ?>"><?php echo $lang['username'] ?></h5>
                        <p class="<?php echo $theme === 'dark' ? 'black' : 'white'; ?>"><?php echo $user->getUsername(); ?></p>
                    </div>
                    <div class="profile-details-row">
                        <h5 class="<?php echo $theme === 'dark' ? 'black' : 'white'; ?>"><?php echo $lang['email'] ?></h5>
                        <p class="<?php echo $theme === 'dark' ? 'black' : 'white'; ?>"><?php echo $user->getEmail(); ?></p>
                    </div>
                </div>
                <div class="profile-buttons">
                    <a class="button sign-up-btn white accent-bg" href="?editProfile"><?php echo $lang['edit'] ?></a>
                    <a class="button log-in-btn accent" href="?logout"><?php echo $lang['logout'] ?></a>
                </div>
            <?php else: ?>
                <?php if ($feedback_key): ?>
                    <?php $feedback = $lang[$feedback_key] ?? $feedback_key; ?>
                    <p class="<?php echo $feedback_type === 'success' ? 'slytherin' : 'accent'; ?>"><?php echo htmlspecialchars($feedback); ?></p>
                <?php endif; ?>
                <form method="post" action="php/update_profile.php" class="profile-details">
                    <div class="profile-details">
                        <div class="profile-details-row">
                            <h5 class="<?php echo $theme === 'dark' ? 'black' : 'white'; ?>"><?php echo $lang['username'] ?? 'Username' ?></h5>
                            <div class="form-input">
                                <input class="<?php echo $theme === 'dark' ? 'dark-gray-bg' : 'light-gray-bg'; ?>" name="username" value="<?php echo htmlspecialchars($user->getUsername()); ?>" required>
                            </div>
                        </div>
                        <div class="profile-details-row">
                            <h5 class="<?php echo $theme === 'dark' ? 'black' : 'white'; ?>"><?php echo $lang['email'] ?? 'Email' ?></h5>
                            <div class="form-input">
                                <input class="<?php echo $theme === 'dark' ? 'dark-gray-bg' : 'light-gray-bg'; ?>" name="email" type="email" value="<?php echo htmlspecialchars($user->getEmail()); ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="profile-buttons">
                        <button type="submit" class="button sign-up-btn white accent-bg"><?php echo $lang['save'] ?? 'Save' ?></button>
                        <a class="button log-in-btn accent" href="account.php"><?php echo $lang['cancel'] ?? 'Cancel' ?></a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </section>

    <section class="section">
        <div class="footer  <?php echo $theme === 'dark' ? 'white-bg' : 'black-bg'; ?>">
            <a class="logo white-bg" href="index.php"><img class="logo white-bg" src=" <?php echo $logo; ?> "></a>
            <a class="<?php echo $theme === 'dark' ? 'black' : 'white'; ?>" target="_blank" href="https://github.com/PitesFan">Made by PitesFan</a>
        </div>
    </section>

    <style>
        <?php echo $theme === 'dark' ? '
        .form-input input {
            color: #f8f8f8;
        }
        .form-input input:focus {
             color: #f8f8f8;
        }' : '.form-input input {
            color: #0d0000;
        }
        .form-input input:focus {
            color: #0d0000;
        }'; ?>
    </style>
    <script src="js/main.js"></script>

</body>

</html>