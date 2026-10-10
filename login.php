<?php
require_once 'common.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $user = find_user_by_email($email);
    if ($user && ($user['Password'] ?? '') === $password) {
        $_SESSION['user_email'] = $email;
        header('Location: main_menu.php');
        exit;
    }
    $error = 'Incorrect email or password.';
}
page_header('Login');
?>
<section class="form-panel">
    <h1>Log In</h1>
    <?php if (isset($_GET['registered'])): ?><p class="notice">Registration successful. Please log in.</p><?php endif; ?>
    <?php if ($error): ?><p class="error"><?php echo h($error); ?></p><?php endif; ?>
    <form action="login.php" method="post">
        <label>Email <input type="email" name="email"></label>
        <label>Password <input type="password" name="password"></label>
        <div class="actions"><button class="button primary" type="submit">Login</button><a class="button" href="registration.php">Register</a></div>
    </form>
</section>
<?php page_footer(); ?>
