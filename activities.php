<?php require_once 'common.php'; page_header('Bookable Activities'); ?>
<section class="page-heading">
    <h1>Bookable Activities</h1>
    <p>CampusSpace bookings are based on the facilities in the catalogue. Choose a place from the catalogue, then select your own preferred date and time in the booking form.</p>
    <a class="button primary" href="catalog.php">Choose a Space from Catalogue</a>
</section>
<section class="table-wrap">
    <table>
        <thead><tr><th>Type</th><th>Bookable Space</th><th>Date & Time</th><th>Price</th><th>Venue</th></tr></thead>
        <tbody>
        <?php foreach (bookable_spaces() as $space): ?>
            <tr>
                <td><?php echo h($space['type']); ?></td>
                <td><?php echo h($space['title']); ?></td>
                <td><?php echo h($space['datetime']); ?></td>
                <td><?php echo h($space['price']); ?></td>
                <td><?php echo h($space['venue']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php page_footer(); ?>
