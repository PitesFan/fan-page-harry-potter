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

require_once "data/{$selected_lang}/about.php";

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
                        <a class="accent" href="<?php echo $lang['navLinks'][1]['url']; ?>"><?php echo $lang['navLinks'][1]['label']; ?></a>
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
        </div>
        <div class="header <?php echo $theme === 'dark' ? 'white-bg' : 'black-bg'; ?>">
            <a class="logo white-bg " href="index.php"><img class="logo white-bg " src=" <?php echo $logo; ?> "></a>

            <ul class="nav-links">
                <li>
                    <a class="<?php echo $theme === 'dark' ? 'black' : 'white'; ?>" href="<?php echo $lang['navLinks'][0]['url']; ?>"><?php echo $lang['navLinks'][0]['label']; ?></a>
                </li>
                <li>
                    <a class="accent" href="<?php echo $lang['navLinks'][1]['url']; ?>"><?php echo $lang['navLinks'][1]['label']; ?></a>
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

    </section>

    <section class="section section-margin">
        <div class="about-section">
            <h2 class="<?php echo $theme === 'dark' ? 'white' : 'dark'; ?>"><?php echo $lang['h2-about']; ?></h2>
            <div class="about-cards section">
                <div class="about-cards-column-1">
                    <div class="about-card">
                        <img src="images/about-img-1.svg" alt="">
                        <p class="white"><?php echo $lang['cards'][0]['label']; ?></p>
                    </div>
                    <div class="about-card">
                        <img src="images/about-img-2.svg" alt="">
                        <p class="white"><?php echo $lang['cards'][1]['label']; ?></p>
                    </div>
                </div>
                <img class="about-img" src="images/about-img.jpg" alt="">
                <div class="about-cards-column-2">
                    <div class="about-card">
                        <img src="images/about-img-3.svg" alt="">
                        <p class="white"><?php echo $lang['cards'][2]['label']; ?></p>
                    </div>
                    <div class="about-card">
                        <img src="images/about-img-4.svg" alt="">
                        <p class="white"><?php echo $lang['cards'][3]['label']; ?></p>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section class="section">
        <div class="footer  <?php echo $theme === 'dark' ? 'white-bg' : 'black-bg'; ?>">
            <a class="logo white-bg" href="index.php"><img class="logo white-bg" src=" <?php echo $logo; ?> "></a>
            <a class="<?php echo $theme === 'dark' ? 'black' : 'white'; ?>" target="_blank" href="https://github.com/PitesFan">Made by PitesFan</a>
        </div>
    </section>
    <script src="js/main.js"></script>
</body>

</html>