<?php
require_once 'common.php';
require_login();
ensure_data_store();
$message = '';
$errors = [];
$paymentOptions = ['Pay at Counter', 'Online Banking', 'E-Wallet', 'Free / No Payment Required'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first_name'] ?? '');
    $last = trim($_POST['last_name'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $datetime = trim($_POST['activity_datetime'] ?? '');
    $title = trim($_POST['activity_title'] ?? '');
    $paymentMethod = trim($_POST['payment_method'] ?? '');
    $selectedSpace = find_bookable_space($title);
    $price = $selectedSpace['price'] ?? 'To be confirmed';
    if ($price === 'Free') $paymentMethod = 'Free / No Payment Required';
    foreach (['First name' => $first, 'Last name' => $last, 'Contact number' => $contact, 'Email' => $email, 'Activity date and time' => $datetime, 'Activity title' => $title, 'Payment method' => $paymentMethod] as $label => $value) if ($value === '') $errors[] = "$label is required.";
    if ($first !== '' && !preg_match('/^[A-Za-z ]+$/', $first)) $errors[] = 'First name may only contain letters and spaces.';
    if ($last !== '' && !preg_match('/^[A-Za-z ]+$/', $last)) $errors[] = 'Last name may only contain letters and spaces.';
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email format is invalid.';
    if ($paymentMethod !== '' && !in_array($paymentMethod, $paymentOptions, true)) $errors[] = 'Payment method is invalid.';
    if ($email !== '' && $title !== '' && $datetime !== '' && file_exists(BOOKING_FILE)) {
        $existingBookings = file(BOOKING_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($existingBookings ?: [] as $line) {
            $booking = parse_record($line);
            $sameEmail = strtolower($booking['Email'] ?? '') === strtolower($email);
            $sameTitle = strtolower($booking['ActivityTitle'] ?? '') === strtolower($title);
            $sameDateTime = ($booking['ActivityDateTime'] ?? '') === $datetime;
            if ($sameEmail && $sameTitle && $sameDateTime) {
                $errors[] = 'You are already registered for this booking slot.';
                break;
            }
        }
    }
    if (!$errors) {
        $record = 'First Name:' . $first . '|LastName:' . $last . '|Contact:' . $contact . '|Email:' . $email . '|ActivityTitle:' . $title . '|ActivityDateTime:' . $datetime . '|Price:' . $price . '|PaymentMethod:' . $paymentMethod . PHP_EOL;
        $file = fopen(BOOKING_FILE, 'a');
        fwrite($file, $record);
        fclose($file);
        $message = 'Booking registration saved successfully.';
    }
}
$user = current_user();
$selected = $_GET['activity'] ?? ($_POST['activity_title'] ?? '');
$selectedDateTime = $_POST['activity_datetime'] ?? '';
$selectedSpace = find_bookable_space($selected);
$selectedPrice = $selectedSpace['price'] ?? 'To be confirmed';
$selectedPayment = $_POST['payment_method'] ?? ($selectedPrice === 'Free' ? 'Free / No Payment Required' : 'Pay at Counter');
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
        <label>Choose your preferred date and time <input class="datetime-field" type="datetime-local" name="activity_datetime" value="<?php echo h($selectedDateTime); ?>"></label>
        <label>Activity title <input type="text" name="activity_title" value="<?php echo h($selected); ?>"></label>
        <label>Price <input type="text" value="<?php echo h($selectedPrice); ?>" readonly></label>
        <label>Payment method <select id="payment_method" name="payment_method"><?php foreach ($paymentOptions as $option): ?><option value="<?php echo h($option); ?>" <?php echo $selectedPayment === $option ? 'selected' : ''; ?>><?php echo h($option); ?></option><?php endforeach; ?></select></label>
        <div class="booking-summary">
            <strong>Booking Summary</strong>
            <span>Selected Space: <?php echo h($selected ?: 'Choose from catalogue'); ?></span>
            <span>Price: <?php echo h($selectedPrice); ?></span>
            <span>Payment Method: <span id="summary-payment-method"><?php echo h($selectedPayment); ?></span></span>
        </div>
        <div class="actions"><button class="button primary" type="submit">Submit Booking</button><a class="button" href="catalog.php">Cancel</a></div>
    </form>
</section>
<script>
const paymentMethod = document.getElementById('payment_method');
const summaryPaymentMethod = document.getElementById('summary-payment-method');
if (paymentMethod && summaryPaymentMethod) {
    paymentMethod.addEventListener('change', () => {
        summaryPaymentMethod.textContent = paymentMethod.value;
    });
}
</script>
<?php page_footer(); ?>
