<?php
require_once 'common.php';
page_header('Bookable Activities');
?>
<section class="page-heading">
    <h1>Bookable Activities</h1>
    <p>These sample slots show the type, date and time, price and venue for campus space registrations.</p>
</section>
<section class="table-wrap">
    <table>
        <thead><tr><th>Type</th><th>Activity Title</th><th>Date & Time</th><th>Price</th><th>Venue</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach (activities() as $activity): ?>
            <tr>
                <td><?php echo h($activity['type']); ?></td>
                <td><?php echo h($activity['title']); ?></td>
                <td><?php echo h($activity['datetime']); ?></td>
                <td><?php echo h($activity['price']); ?></td>
                <td><?php echo h($activity['venue']); ?></td>
                <td><a class="small-button" href="activity_reg.php?activity=<?php echo urlencode($activity['title']); ?>">Register</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php page_footer(); ?>
