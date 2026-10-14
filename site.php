
<?php
/**
 * Dark Sky Reserves - site detail page.
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

// Retrieve observations for this site.
$observationStmt = $pdo->prepare('
    SELECT
        object_name,
        object_type,
        observation_date,
        notes
    FROM observations
    WHERE site_id = ?
    ORDER BY observation_date DESC
');

$observationStmt->execute([$siteId]);
$observations = $observationStmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = $site['name'];
require_once 'includes/header.php';
?>

<main class="site-detail">

    <p>
        <a href="index.php">&larr; Back to all sites</a>
    </p>

    <h2><?php echo e($site['name']); ?></h2>

    <dl>
        <dt>Country</dt>
        <dd><?php echo e($site['country']); ?></dd>

        <dt>Site Type</dt>
        <dd><?php echo e($site['site_type']); ?></dd>

        <dt>Designation Year</dt>
        <dd><?php echo e($site['designation_year']); ?></dd>

        <dt>Sky Quality Index</dt>
        <dd>
            <?php
            echo $site['sky_quality_index'] !== null
                ? e($site['sky_quality_index'])
                : 'Not recorded';
            ?>
        </dd>

        <dt>Latitude</dt>
        <dd><?php echo e($site['lat']); ?></dd>

        <dt>Longitude</dt>
        <dd><?php echo e($site['lng']); ?></dd>
    </dl>

    <section class="observations">
        <h2>Astronomical Observations</h2>

        <p>
            Explore the celestial objects associated with
            <?php echo e($site['name']); ?>.
        </p>

        <p>
            Total observations: <?php echo count($observations); ?>
        </p>

        <?php if (empty($observations)): ?>

            <p>No observations are currently available for this site.</p>

        <?php else: ?>      
        <div class="table-wrapper observations-table-wrapper">
            <table class="observations-table">
                <colgroup>
                    <col class="col-object">
                    <col class="col-type">
                    <col class="col-date">
                    <col class="col-notes">
                </colgroup>

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
                                <?php echo e($observation['observation_date']); ?>
                            </td>

                            <td class="observation-notes">
                                <?php echo e($observation['notes'] ?? 'No notes available'); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </section>

</main>

<?php require_once 'includes/footer.php'; ?>
