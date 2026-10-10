<?php
require_once 'common.php';
page_header('Main Menu');
?>
<section class="page-heading">
    <h1>Main Menu</h1>
    <p>Browse spaces, register for booking slots, and view how the campus community uses shared facilities.</p>
    <?php if (isset($_GET['login'])): ?><p class="notice">Please log in or register before submitting a booking.</p><?php endif; ?>
</section>
<section class="menu-grid">
    <a class="feature-card" href="catalog.php"><span>01</span><h2>Facilities Catalogue</h2><p>Browse study, event and specialist campus spaces.</p></a>
    <a class="feature-card" href="activities.php"><span>02</span><h2>Bookable Activities</h2><p>View dates, times, prices and venues.</p></a>
    <a class="feature-card" href="community.php"><span>03</span><h2>Community Showcase</h2><p>See photos and stories from campus users.</p></a>
    <a class="feature-card muted" href="smart_feature.php"><span>04</span><h2>Smart Room Finder</h2><p>Reserved for Assignment 2 recommendations.</p></a>
</section>
<?php page_footer(); ?>
