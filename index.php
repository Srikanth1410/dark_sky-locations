<?php
/**
 * Dark Sky Reserves - site listing page.
 *
 * Displays every site from the sites table. Students should add searching
 * and filtering as an intermediate enhancement.
 */

require_once 'includes/db.php';

$pageTitle = 'All Sites';
$pdo = getDbConnection();

$stmt = $pdo->query('
    SELECT
        id,
        name,
        country,
        site_type,
        designation_year,
        sky_quality_index,
        lat,
        lng
    FROM sites
    ORDER BY name
');
$sites = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<h2>Dark Sky Sites</h2>

<p>Explore certified dark sky places and their locations.</p>

<?php if (empty($sites)): ?>
    <p>No dark sky sites found in the database.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Site Name</th>
                <th>Country</th>
                <th>Type</th>
                <th>Designation Year</th>
                <th>Sky Quality Index</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sites as $site): ?>
                <tr>
                    <td><?php echo e($site['name']); ?></td>
                    <td><?php echo e($site['country']); ?></td>
                    <td><?php echo e($site['site_type']); ?></td>
                    <td><?php echo e($site['designation_year']); ?></td>
                    <td><?php echo $site['sky_quality_index'] !== null ? e($site['sky_quality_index']) : 'Not recorded'; ?></td>
                    <td><?php echo e($site['lat']); ?></td>
                    <td><?php echo e($site['lng']); ?></td>
                    <td><a href="site.php?id=<?php echo e($site['id']); ?>">View Details</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
