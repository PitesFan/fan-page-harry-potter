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

require_once "data/{$selected_lang}/register.php";

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
require("php/register.class.php");

?>

<?php
$user = null;
if (isset($_POST['submit'])) {
    $user = new RegisterUser($_POST['username'], $_POST['email'], $_POST['password'], $selected_lang);
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/desktop.css">
    <link rel="stylesheet" href="css/adaptive.css">
</head>

<body class="<?php echo $theme === 'dark' ? 'black-bg' : 'white-bg'; ?>">
    <section class="section">
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
                <a href="login.php" class="button log-in-btn accent"><?php echo $lang['logIn'] ?></a>
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

    <section class="section section-margin">
        <div class="form-section">
            <h2 class="<?php echo $theme === 'dark' ? 'white' : 'black'; ?>"><?php echo $lang['h2']; ?></h2>
            <form class="register-form" action="" method="post">
                <div class="form-input">
                    <label class="<?php echo $theme === 'dark' ? 'light-gray' : 'dark-gray'; ?>" for=""><?php echo $lang['forms'][0]['label']; ?></label>
                    <input class="<?php echo $theme === 'dark' ? 'light-gray-bg' : 'dark-gray-bg'; ?>" type="text" name="username">
                </div>
                <div class="form-input">
                    <label class="<?php echo $theme === 'dark' ? 'light-gray' : 'dark-gray'; ?>" for=""><?php echo $lang['forms'][1]['label']; ?></label>
                    <input class="<?php echo $theme === 'dark' ? 'light-gray-bg' : 'dark-gray-bg'; ?>" type="email" name="email">
                </div>
                <div class="form-input">
                    <label class="<?php echo $theme === 'dark' ? 'light-gray' : 'dark-gray'; ?>" for=""><?php echo $lang['forms'][2]['label']; ?></label>
                    <input class="<?php echo $theme === 'dark' ? 'light-gray-bg' : 'dark-gray-bg'; ?>" type="password" name="password" minlength="8">
                </div>
                <button class="button submit-btn accent-bg white" type="submit" name="submit"><?php echo $lang['signUp'] ?></button>

                <p class="<?php echo !empty($user?->error) ? 'accent' : 'slytherin'; ?>"><?php echo htmlspecialchars($user?->error ?? $user?->success ?? ''); ?></p>

            </form>

        </div>

        <img class="signup-img" src="images/signup-img.jpg" alt="">
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
            color: #0d0000;
        }
        .form-input input:focus {
             color: #0d0000;
        }' : '.form-input input {
            color: #f8f8f8;
        }
        .form-input input:focus {
            color: #f8f8f8;
        }'; ?>
    </style>


</body>

</html>