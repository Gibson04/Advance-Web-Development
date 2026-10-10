<?php
require_once 'common.php';
ensure_data_store();
$errors = [];
$first = trim($_POST['first_name'] ?? '');
$last = trim($_POST['last_name'] ?? '');
$dob = trim($_POST['dob'] ?? '');
$gender = trim($_POST['gender'] ?? 'Female');
$email = trim($_POST['email'] ?? '');
$hometown = trim($_POST['hometown'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirm = trim($_POST['confirm_password'] ?? '');

foreach (['First name' => $first, 'Last name' => $last, 'Date of birth' => $dob, 'Gender' => $gender, 'Email' => $email, 'Hometown' => $hometown, 'Password' => $password, 'Confirm password' => $confirm] as $label => $value) {
    if ($value === '') {
        $errors[] = "$label is required.";
    }
}
if ($first !== '' && !preg_match('/^[A-Za-z ]+$/', $first)) $errors[] = 'First name may only contain letters and spaces.';
if ($last !== '' && !preg_match('/^[A-Za-z ]+$/', $last)) $errors[] = 'Last name may only contain letters and spaces.';
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email format is invalid.';
if ($email !== '' && find_user_by_email($email)) $errors[] = 'Email is already registered.';
if ($password !== '' && !preg_match(PASSWORD_PATTERN, $password)) $errors[] = 'Password must be at least 8 characters with 1 number and 1 symbol.';
if ($password !== $confirm) $errors[] = 'Confirm password must match password.';

if ($errors) {
    page_header('Registration Error');
    echo '<section class="form-panel"><h1>Registration Error</h1><ul class="error-list">';
    foreach ($errors as $error) echo '<li>' . h($error) . '</li>';
    echo '</ul><a class="button" href="registration.php">Return to form</a></section>';
    page_footer();
    exit;
}

$record = 'First Name:' . $first . '|LastName:' . $last . '|DOB:' . $dob . '|Gender:' . $gender . '|Email:' . $email . '|Hometown:' . $hometown . '|Password:' . $password . PHP_EOL;
$file = fopen(USER_FILE, 'a');
fwrite($file, $record);
fclose($file);
header('Location: login.php?registered=1');
exit;
?>
