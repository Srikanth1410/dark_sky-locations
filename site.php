<?php
/**
 * Dark Sky Reserves - site detail page.
 *
 * Displays one site from the sites table. Students should add the related
 * observations display as an intermediate enhancement.
 */

require_once 'includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$siteId = (int) $_GET['id'];
$pdo = getDbConnection();

$stmt = $pdo->prepare('
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
    WHERE id = ?
');
$stmt->execute([$siteId]);
$site = $stmt->fetch();

if (!$site) {
    header('Location: index.php');
    exit;
}

$pageTitle = $site['name'];

require_once 'includes/header.php';
?>

<p><a href="index.php">&larr; Back to all sites</a></p>

<h2><?php echo e($site['name']); ?></h2>

<dl>
    <dt>Country</dt>
    <dd><?php echo e($site['country']); ?></dd>

    <dt>Site Type</dt>
    <dd><?php echo e($site['site_type']); ?></dd>

    <dt>Designation Year</dt>
    <dd><?php echo e($site['designation_year']); ?></dd>

    <dt>Sky Quality Index</dt>
    <dd><?php echo $site['sky_quality_index'] !== null ? e($site['sky_quality_index']) : 'Not recorded'; ?></dd>

    <dt>Latitude</dt>
    <dd><?php echo e($site['lat']); ?></dd>

    <dt>Longitude</dt>
    <dd><?php echo e($site['lng']); ?></dd>
</dl>

<!-- Students: Add a JOIN to display related observations here as an intermediate enhancement. -->
<!-- <?php require_once 'includes/footer.php'; ?> -->

<?php
// Retrieve observations associated with this site.
// LEFT JOIN allows the site to appear even when it has no observations.
$observationStmt = $pdo->prepare('
    SELECT
        o.object_name,
        o.object_type,
        o.observation_date,
        o.notes
    FROM sites AS s
    LEFT JOIN observations AS o
        ON s.id = o.site_id
    WHERE s.id = ?
    ORDER BY o.observation_date DESC
');

$observationStmt->execute([$siteId]);
$observations = $observationStmt->fetchAll();

// Remove the empty observation row returned by LEFT JOIN
// when a site has no related observations.
$observations = array_filter(
    $observations,
    fn($observation) => $observation['object_name'] !== null
);
?>

<section class="observations">
    <h2>Astronomical Observations</h2>

    <p>
        Explore the celestial objects associated with
        <?php echo e($site['name']); ?>.
    </p>

    <?php if (empty($observations)): ?>
        <p>No observations are currently available for this site.</p>
    <?php else: ?>
        <p>
            Total observations: <?php echo count($observations); ?>
        </p>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Object Name</th>
                        <th>Object Type</th>
                        <th>Observation Date</th>
                        <th>Notes</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($observations as $observation): ?>
                        <tr>
                            <td>
                                <?php echo e($observation['object_name']); ?>
                            </td>

                            <td>
                                <?php echo e($observation['object_type']); ?>
                            </td>

                            <td>
                                <?php
                                echo e(
                                    $observation['observation_date']
                                );
                                ?>
                            </td>

                            <td>
                                <?php echo e($observation['notes']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>