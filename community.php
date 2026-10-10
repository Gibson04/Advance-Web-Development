<?php
require_once 'common.php';
page_header('Community Showcase');
?>
<section class="page-heading">
    <h1>Community Showcase</h1>
    <p>Sample contributions show how students and clubs use booked spaces. Uploads are planned for Assignment 2.</p>
</section>
<section class="card-grid">
    <?php foreach (contributions() as $id => $item): ?>
    <a class="space-card linked-card" href="community_detail.php?id=<?php echo urlencode($id); ?>">
        <img src="<?php echo h($item['media']); ?>" alt="<?php echo h($item['title']); ?>">
        <h2><?php echo h($item['title']); ?></h2>
        <p><?php echo h($item['contributor']); ?></p>
    </a>
    <?php endforeach; ?>
</section>
<?php page_footer(); ?>
