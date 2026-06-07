<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

$storage = __DIR__ . '/../data/users.json';
$users = json_decode(file_get_contents($storage), true) ?: [];

$current_email = $_SESSION['user'];
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$username || !$email) {
    header('Location: ../account.php?editProfile&msg=fill_required&type=error');
    exit();
}

$foundIndex = null;
foreach ($users as $idx => $u) {
    if (!isset($u['email'])) continue;
    if ($u['email'] === $current_email) {
        $foundIndex = $idx;
        break;
    }
}

if ($foundIndex === null) {
    header('Location: ../account.php?msg=user_not_found&type=error');
    exit();
}

if ($email !== $current_email) {
    foreach ($users as $u) {
        if (isset($u['email']) && $u['email'] === $email) {
            header('Location: ../account.php?editProfile&msg=email_in_use&type=error');
            exit();
        }
    }
}

$users[$foundIndex]['username'] = $username;
$users[$foundIndex]['email'] = $email;
if ($password !== '') {
    $users[$foundIndex]['password'] = $password;
}

file_put_contents($storage, json_encode($users, JSON_PRETTY_PRINT));

$_SESSION['user'] = $email;

header('Location: ../account.php?msg=profile_updated&type=success');
exit();
