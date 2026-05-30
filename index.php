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

require_once "data/{$selected_lang}/home.php";
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/desktop.css">
    <link rel="stylesheet" href="css/adaptive.css">
</head>

<body class="black-bg">
    <section class="hero-section">
        <div class="header white-bg">
            <a href="index.php"><img class="logo white-bg" src=" <?php echo $logo; ?> "></a>

            <ul class="nav-links">
                <li>
                    <a class="accent" href="<?php echo $lang['navLinks'][0]['url']; ?>"><?php echo $lang['navLinks'][0]['label']; ?></a>
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
                <a href="login.php" class="button log-in-btn accent"><?php echo $lang['logIn'] ?></a>
                <a href="register.php" class="button sign-up-btn accent-bg white"><?php echo $lang['signUp'] ?></a>
                <a class="button theme-btn black-bg"><?php echo $lang['theme'] ?></a>
                <a class="lang-btn" href="?lang=<?php echo $lang['switchLang']; ?>">
                    <img src="<?php echo $lang['langImg'] ?>" alt="<?php echo $lang['langAlt'] ?>">
                </a>
            </div>
        </div>

        <div class="hero-info">
            <h1 class="white"><?php echo $lang['h1'] ?></h1>
            <p class="light-gray"><?php echo $lang['p'] ?></p>
        </div>

    </section>

</body>

</html>