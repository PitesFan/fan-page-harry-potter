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

require_once "data/{$selected_lang}/login.php";

?>

<?php
require("php/login.class.php");
?>

<?php
$user = null;
if (isset($_POST['submit'])) {
    $user = new LoginUser($_POST['email'], $_POST['password'], $selected_lang);
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/desktop.css">
    <link rel="stylesheet" href="css/adaptive.css">
</head>

<body class="black-bg">
    <section class="section">
        <div class="header white-bg">
            <a href="index.php"><img class="logo white-bg" src=" <?php echo $logo; ?> "></a>

            <ul class="nav-links">
                <li>
                    <a class="black" href="<?php echo $lang['navLinks'][0]['url']; ?>"><?php echo $lang['navLinks'][0]['label']; ?></a>
                </li>
                <li>
                    <a class="black" href="<?php echo $lang['navLinks'][1]['url']; ?>"><?php echo $lang['navLinks'][1]['label']; ?></a>
                </li>
                <li>
                    <a class="black" href="<?php echo $lang['navLinks'][2]['url']; ?>"><?php echo $lang['navLinks'][2]['label']; ?></a>
                </li>
                <li>
                    <a class="black" href="<?php echo $lang['navLinks'][3]['url']; ?>"><?php echo $lang['navLinks'][3]['label']; ?></a>
                </li>
            </ul>

            <div class="header-buttons">
                <a href="register.php" class="button sign-up-btn accent-bg white"><?php echo $lang['signUp'] ?></a>
                <a class="button theme-btn black-bg"><?php echo $lang['theme'] ?></a>
                <a class="lang-btn" href="?lang=<?php echo $lang['switchLang']; ?>">
                    <img src="<?php echo $lang['langImg'] ?>" alt="<?php echo $lang['langAlt'] ?>">
                </a>
            </div>
        </div>
    </section>

    <section class="section section-margin">
        <div class="form-section">
            <h2 class="white"><?php echo $lang['h2']; ?></h2>
            <form class="register-form" action="" method="post">
                <div class="form-input">
                    <label class="light-gray" for=""><?php echo $lang['forms'][0]['label']; ?></label>
                    <input class="light-gray-bg" type="email" name="email">
                </div>
                <div class="form-input">
                    <label class="light-gray" for=""><?php echo $lang['forms'][1]['label']; ?></label>
                    <input class="light-gray-bg" type="text" name="password">
                </div>
                <button class="button submit-btn accent-bg white" type="submit" name="submit"><?php echo $lang['signUp'] ?></button>

                <p class="accent"><?php echo @$user->error ?></p>

                <p class="slytherin"><?php echo @$user->success ?></p>

            </form>

        </div>

        <img class="login-img" src="images/login-img.jpg" alt="">
    </section>

    <section class="section">
        <div class="footer  white-bg">
            <a href="index.php"><img class="logo white-bg" src=" <?php echo $logo; ?> "></a>
            <a class="black" target="_blank" href="https://github.com/PitesFan">Made by PitesFan</a>
        </div>
    </section>


</body>

</html>