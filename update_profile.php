<?php
require_once 'common.php';
require_login();
ensure_data_store();
$user = current_user();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updated = [
        'First Name' => trim($_POST['first_name'] ?? ''),
        'LastName' => trim($_POST['last_name'] ?? ''),
        'DOB' => trim($_POST['dob'] ?? ''),
        'Gender' => trim($_POST['gender'] ?? 'Female'),
        'Email' => $_SESSION['user_email'],
        'Hometown' => trim($_POST['hometown'] ?? ''),
        'Password' => $user['Password'] ?? '',
    ];
    $users = read_users();
    $lines = [];
    foreach ($users as $stored) {
        $record = strtolower($stored['Email'] ?? '') === strtolower($_SESSION['user_email']) ? $updated : $stored;
        $lines[] = 'First Name:' . $record['First Name'] . '|LastName:' . $record['LastName'] . '|DOB:' . $record['DOB'] . '|Gender:' . $record['Gender'] . '|Email:' . $record['Email'] . '|Hometown:' . $record['Hometown'] . '|Password:' . $record['Password'];
    }
    file_put_contents(USER_FILE, implode(PHP_EOL, $lines) . PHP_EOL);
    $user = current_user();
    $message = 'Profile updated successfully.';
}
$gender = $user['Gender'] ?? 'Female';
$profileImage = strtolower($gender) === 'male' ? 'profile_images/male.svg' : 'profile_images/female.svg';
page_header('Update Profile');
?>
<section class="form-panel">
    <h1>View / Update Profile</h1>
    <img class="avatar" src="<?php echo h($profileImage); ?>" alt="Default profile image">
    <?php if ($message): ?><p class="notice"><?php echo h($message); ?></p><?php endif; ?>
    <form action="update_profile.php" method="post">
        <label>First name <input type="text" name="first_name" value="<?php echo h($user['First Name'] ?? ''); ?>"></label>
        <label>Last name <input type="text" name="last_name" value="<?php echo h($user['LastName'] ?? ''); ?>"></label>
        <label>Date of birth <input type="date" name="dob" value="<?php echo h($user['DOB'] ?? ''); ?>"></label>
        <label>Gender <select name="gender"><option <?php if ($gender === 'Female') echo 'selected'; ?>>Female</option><option <?php if ($gender === 'Male') echo 'selected'; ?>>Male</option></select></label>
        <label>Email <input type="text" value="<?php echo h($user['Email'] ?? ''); ?>" disabled></label>
        <label>Hometown <input type="text" name="hometown" value="<?php echo h($user['Hometown'] ?? ''); ?>"></label>
        <div class="actions"><button class="button primary" type="submit">Update</button><a class="button" href="main_menu.php">Cancel</a></div>
    </form>
</section>
<?php page_footer(); ?>
