<?php
require_once 'common.php';
$items = contributions();
$id = $_GET['id'] ?? 'coding-night';
$item = $items[$id] ?? reset($items);
page_header('Contribution Detail');
?>
<section class="detail-layout">
    <img src="<?php echo h($item['media']); ?>" alt="<?php echo h($item['title']); ?>">
    <div>
        <p class="eyebrow">Contribution Detail</p>
        <h1><?php echo h($item['title']); ?></h1>
        <p><strong>Contributor:</strong> <?php echo h($item['contributor']); ?></p>
        <p><strong>Related activity/service:</strong> <?php echo h($item['related']); ?></p>
        <p><?php echo h($item['desc']); ?></p>
        <a class="button" href="community.php">Back to Community</a>
    </div>
</section>
<?php page_footer(); ?>
