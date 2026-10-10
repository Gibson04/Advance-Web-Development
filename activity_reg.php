<?php
require_once 'common.php';
require_login();
ensure_data_store();
$message = '';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first_name'] ?? '');
    $last = trim($_POST['last_name'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $datetime = trim($_POST['activity_datetime'] ?? '');
    $title = trim($_POST['activity_title'] ?? '');
    foreach (['First name' => $first, 'Last name' => $last, 'Contact number' => $contact, 'Email' => $email, 'Activity date and time' => $datetime, 'Activity title' => $title] as $label => $value) {
        if ($value === '') $errors[] = "$label is required.";
    }
    if ($first !== '' && !preg_match('/^[A-Za-z ]+$/', $first)) $errors[] = 'First name may only contain letters and spaces.';
    if ($last !== '' && !preg_match('/^[A-Za-z ]+$/', $last)) $errors[] = 'Last name may only contain letters and spaces.';
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email format is invalid.';
    $existing = file_exists(BOOKING_FILE) ? file(BOOKING_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
    foreach ($existing ?: [] as $line) {
        if (stripos($line, 'Email:' . $email . '|ActivityTitle:' . $title . '|ActivityDateTime:' . $datetime) !== false) {
            $errors[] = 'You have already registered for this activity slot.';
        }
    }
    if (!$errors) {
        $record = 'First Name:' . $first . '|LastName:' . $last . '|Contact:' . $contact . '|Email:' . $email . '|ActivityTitle:' . $title . '|ActivityDateTime:' . $datetime . PHP_EOL;
        $file = fopen(BOOKING_FILE, 'a');
        fwrite($file, $record);
        fclose($file);
        $message = 'Booking registration saved successfully.';
    }
}
$user = current_user();
$selected = $_GET['activity'] ?? ($_POST['activity_title'] ?? '');
page_header('Activity Registration');
?>
<section class="form-panel">
    <h1>Register for a Booking</h1>
    <?php if ($message): ?><p class="notice"><?php echo h($message); ?></p><?php endif; ?>
    <?php if ($errors): ?><ul class="error-list"><?php foreach ($errors as $error) echo '<li>' . h($error) . '</li>'; ?></ul><?php endif; ?>
    <form action="activity_reg.php" method="post">
        <label>First name <input type="text" name="first_name" value="<?php echo h($user['First Name'] ?? ''); ?>"></label>
        <label>Last name <input type="text" name="last_name" value="<?php echo h($user['LastName'] ?? ''); ?>"></label>
        <label>Contact number <input type="text" name="contact"></label>
        <label>Email <input type="text" name="email" value="<?php echo h($_SESSION['user_email'] ?? ''); ?>"></label>
        <label>Activity date and time <input type="datetime-local" name="activity_datetime"></label>
        <label>Activity title <input type="text" name="activity_title" value="<?php echo h($selected); ?>"></label>
        <div class="actions"><button class="button primary" type="submit">Submit Booking</button><a class="button" href="activities.php">Cancel</a></div>
    </form>
</section>
<?php page_footer(); ?>
