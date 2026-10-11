<?php require_once 'common.php'; page_header('Facilities Catalogue'); ?>
<section class="page-heading">
    <h1>Facilities Catalogue</h1>
    <p>CampusSpace offers practical study, event and specialist facilities that students can browse before checking available booking slots.</p>
</section>
<?php foreach (facilities() as $category => $group): ?>
<?php $categoryDetails = booking_category_details()[$category]; ?>
<section class="catalog-section">
    <h2><?php echo h($category); ?></h2>
    <p class="category-intro"><?php echo h($group['intro']); ?></p>
    <div class="card-grid">
        <?php foreach ($group['items'] as $item): ?>
        <article class="space-card">
            <img src="<?php echo h($item['img']); ?>" alt="<?php echo h($item['title']); ?>">
            <h3><?php echo h($item['title']); ?></h3>
            <p><?php echo h($item['desc']); ?></p>
            <p class="item-meta"><strong>Capacity:</strong> <?php echo h($item['capacity']); ?></p>
            <p class="item-meta"><strong>Equipment:</strong> <?php echo h($item['equipment']); ?></p>
            <p class="item-meta"><strong>Price:</strong> <?php echo h($categoryDetails['price']); ?></p>
            <a class="text-link" href="activity_reg.php?activity=<?php echo urlencode($item['title']); ?>">Book This Space</a>
        </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endforeach; page_footer(); ?>
