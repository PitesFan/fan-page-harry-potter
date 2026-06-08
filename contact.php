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

require_once "data/{$selected_lang}/contact.php";

$theme = [];
$allowed_themes = ['dark', 'light'];
$selected_theme = $_GET['theme'] ?? $_SESSION['theme'] ?? 'dark';
if (!in_array($selected_theme, $allowed_themes)) {
    $selected_theme = 'dark';
}

$_SESSION['theme'] = $selected_theme;
$theme = $selected_theme;
$next_theme = $theme === 'dark' ? 'light' : 'dark';

$contact_message = '';
$contact_message_type = '';
$contact_form_values = [
    'name' => '',
    'email' => '',
    'message' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $contact_form_values['name'] = trim((string) ($_POST['name'] ?? ''));
    $contact_form_values['email'] = trim((string) ($_POST['email'] ?? ''));
    $contact_form_values['message'] = trim((string) ($_POST['message'] ?? ''));

    if ($contact_form_values['name'] === '' || $contact_form_values['email'] === '' || $contact_form_values['message'] === '') {
        $contact_message = $lang['contactRequired'];
        $contact_message_type = 'accent';
    } elseif (!filter_var($contact_form_values['email'], FILTER_VALIDATE_EMAIL)) {
        $contact_message = $lang['contactInvalidEmail'];
        $contact_message_type = 'accent';
    } else {
        $contact_storage = __DIR__ . '/data/contact_messages.json';
        $contact_messages = [];

        if (file_exists($contact_storage)) {
            $decoded_messages = json_decode(file_get_contents($contact_storage), true);
            if (is_array($decoded_messages)) {
                $contact_messages = $decoded_messages;
            }
        }

        $contact_messages[] = [
            'name' => $contact_form_values['name'],
            'email' => $contact_form_values['email'],
            'message' => $contact_form_values['message'],
            'lang' => $selected_lang,
            'theme' => $selected_theme,
            'created_at' => date('c'),
        ];

        $saved = file_put_contents($contact_storage, json_encode($contact_messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        if ($saved !== false) {
            $_SESSION['contact_success'] = $lang['contactSuccess'];
            header('Location: contact.php?lang=' . urlencode($selected_lang) . '&theme=' . urlencode($selected_theme));
            exit();
        }

        $contact_message = $lang['contactFailed'];
        $contact_message_type = 'accent';
    }
}

if (isset($_SESSION['contact_success'])) {
    $contact_message = $_SESSION['contact_success'];
    $contact_message_type = 'slytherin';
    unset($_SESSION['contact_success']);
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
                    <a class="accent" href="<?php echo $lang['navLinks'][3]['url']; ?>"><?php echo $lang['navLinks'][3]['label']; ?></a>
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

    <section class="section section-margin contact-container">
        <img class="contact-img-left" src="images/contact-img-left.png" alt="">
        <div class="contact-section">
            <div class="contact-texts">
                <h2 class="<?php echo $theme === 'dark' ? 'white' : 'dark'; ?>"><?php echo $lang['h2'] ?></h2>
                <p class="<?php echo $theme === 'dark' ? 'light-gray' : 'dark-gray'; ?>"><?php echo $lang['p'] ?></p>
            </div>
            <form class="contact-form" action="" method="post">
                <div class="contact-form-inputs">
                    <div class="contact-form-column">
                        <div class="form-input">
                            <label class="<?php echo $theme === 'dark' ? 'light-gray' : 'dark-gray'; ?>" for=""><?php echo $lang['forms'][0]['label']; ?></label>
                            <input class="<?php echo $theme === 'dark' ? 'light-gray-bg' : 'dark-gray-bg'; ?>" type="text" name="name" value="<?php echo htmlspecialchars($contact_form_values['name']); ?>">
                        </div>
                        <div class="form-input">
                            <label class="<?php echo $theme === 'dark' ? 'light-gray' : 'dark-gray'; ?>" for=""><?php echo $lang['forms'][1]['label']; ?></label>
                            <input class="<?php echo $theme === 'dark' ? 'light-gray-bg' : 'dark-gray-bg'; ?>" type="email" name="email" value="<?php echo htmlspecialchars($contact_form_values['email']); ?>">
                        </div>
                    </div>
                    <div class="contact-textarea">
                        <label class="<?php echo $theme === 'dark' ? 'light-gray' : 'dark-gray'; ?>" for=""><?php echo $lang['forms'][2]['label']; ?></label>
                        <textarea class="<?php echo $theme === 'dark' ? 'light-gray-bg' : 'dark-gray-bg'; ?>" type="text" name="message"><?php echo htmlspecialchars($contact_form_values['message']); ?></textarea>
                    </div>
                </div>
                <button class="button contact-btn accent-bg white" type="submit" name="submit"><?php echo $lang['send'] ?></button>

                <p class="<?php echo $contact_message_type === 'accent' ? 'accent' : 'slytherin'; ?>"><?php echo htmlspecialchars($contact_message); ?></p>

            </form>


        </div>
        <img class="contact-img-right" src="images/contact-img-right.png" alt="">
    </section>

    <section class="section">
        <div class="footer  <?php echo $theme === 'dark' ? 'white-bg' : 'black-bg'; ?>">
            <a class="logo white-bg" href="index.php"><img class="logo white-bg" src=" <?php echo $logo; ?> "></a>
            <a class="<?php echo $theme === 'dark' ? 'black' : 'white'; ?>" target="_blank" href="https://github.com/PitesFan">Made by PitesFan</a>
        </div>
    </section>

    <style>
        <?php echo $theme === 'dark' ? '
        .form-input input, textarea {
            color: #0d0000;
        }
        .form-input input, textarea:focus {
             color: #0d0000;
        }' : '.form-input input, textarea {
            color: #f8f8f8;
        }
        .form-input input, textarea:focus {
            color: #f8f8f8;
        }'; ?>
    </style>

</body>

</html>