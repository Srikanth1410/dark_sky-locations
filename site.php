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

<?php require_once 'includes/footer.php'; ?>
