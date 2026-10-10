<?php
require_once 'common.php';
page_header('Facilities Catalogue');
?>
<section class="page-heading">
    <h1>Facilities Catalogue</h1>
    <p>Choose from three practical categories, each with spaces students can browse before booking.</p>
</section>
<?php foreach (facilities() as $category => $items): ?>
<section class="catalog-section">
    <h2><?php echo h($category); ?></h2>
    <div class="card-grid">
        <?php foreach ($items as $item): ?>
        <article class="space-card">
            <img src="<?php echo h($item['img']); ?>" alt="<?php echo h($item['title']); ?>">
            <h3><?php echo h($item['title']); ?></h3>
            <p><?php echo h($item['desc']); ?></p>
            <a class="text-link" href="activities.php">View booking options</a>
        </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endforeach; ?>
<?php page_footer(); ?>
