<?php
require_once 'common.php';
$photos = ['img/hero-library.svg', 'img/hero-lab.svg', 'img/hero-studio.svg'];
$photo = $photos[array_rand($photos)];
page_header('Home');
?>
<section class="hero">
    <div class="hero-copy">
        <p class="eyebrow">Campus facility booking</p>
        <h1>Book the right campus space without the back-and-forth.</h1>
        <p>CampusSpace helps students find study rooms, event venues, specialist labs and activity spaces, then register for a suitable booking slot from one simple website.</p>
        <div class="actions">
            <a class="button primary" href="main_menu.php">Open Main Menu</a>
            <a class="button" href="login.php">Log In</a>
            <a class="button" href="registration.php">Register Account</a>
        </div>
    </div>
    <img class="hero-image" src="<?php echo h($photo); ?>" alt="Campus booking space preview">
</section>
<?php page_footer(); ?>
