<?php
require_once 'common.php';
page_header('About');
?>
<section class="page-heading">
    <h1>About CampusSpace</h1>
</section>
<section class="content-panel">
    <ul>
        <li><strong>Problem solved:</strong> Students often need rooms for study, events or specialist work, but finding the right space can be slow. CampusSpace centralises facility browsing and booking registration.</li>
        <li><strong>Reason chosen:</strong> It is practical for campus life and matches the assignment's booking, login and community requirements.</li>
        <li><strong>PHP version:</strong> <?php echo h(phpversion()); ?></li>
        <li><strong>Tasks completed:</strong> Home, main menu, catalogue, activities, community, detail, profile, update profile, registration, registration processing, activity registration, login and about pages.</li>
        <li><strong>Tasks not attempted:</strong> User upload and smart recommendation are reserved for Assignment 2.</li>
        <li><strong>Frameworks/libraries:</strong> No third-party framework. Plain HTML, CSS and PHP.</li>
        <li><strong>Video presentation:</strong> <a href="https://youtu.be/ATvJCJ6hOYE?si=unyOLiEZ5zpBiMSQ" target="_blank">Unlisted YouTube demo link</a></li>
    </ul>
    <a class="button" href="index.php">Back Home</a>
</section>
<?php page_footer(); ?>
