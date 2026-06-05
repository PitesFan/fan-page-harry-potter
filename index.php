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

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/desktop.css">
    <link rel="stylesheet" href="css/adaptive.css">
</head>

<body class="<?php echo $theme === 'dark' ? 'black-bg' : 'white-bg'; ?>">
    <section class="hero-section">
        <div class="header <?php echo $theme === 'dark' ? 'white-bg' : 'black-bg'; ?>">
            <a class="logo white-bg " href="index.php"><img class="logo white-bg " src=" <?php echo $logo; ?> "></a>

            <ul class="nav-links">
                <li>
                    <a class="accent" href="<?php echo $lang['navLinks'][0]['url']; ?>"><?php echo $lang['navLinks'][0]['label']; ?></a>
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
            <?php if (isset($_SESSION['user'])) { ?> <div class="header-buttons">
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
            <?php } else { ?>
                <div class="header-buttons">
                    <a href="login.php" class="button log-in-btn accent"><?php echo $lang['logIn'] ?></a>
                    <a href="register.php" class="button sign-up-btn accent-bg white"><?php echo $lang['signUp'] ?></a>
                    <a class="button theme-btn <?php echo $theme === 'dark' ? 'black-bg' : 'white-bg'; ?>" href="?theme=<?php echo $next_theme; ?>"><?php if ($theme == 'dark') {
                                                                                                                                                        echo '☀️';
                                                                                                                                                    } else {
                                                                                                                                                        echo '🌑';
                                                                                                                                                    } ?></a>
                    <a class="lang-btn" href="?lang=<?php echo $lang['switchLang']; ?>">
                        <img src="<?php echo $lang['langImg'] ?>" alt="<?php echo $lang['langAlt'] ?>">
                    </a>
                </div> <?php } ?>
        </div>

        <video autoplay muted loop playsinline class="background-video">
            <source src="images/hero-image.webm" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <div class="hero-info">
            <h1 class="white"><?php echo $lang['h1'] ?></h1>
            <p class="light-gray"><?php echo $lang['p'] ?></p>
        </div>

    </section>

</body>

</html>