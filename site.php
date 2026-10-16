
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

$mapLat = is_numeric($site['lat']) ? (float) $site['lat'] : null;
$mapLng = is_numeric($site['lng']) ? (float) $site['lng'] : null;

$hasValidCoordinates =
    $mapLat !== null &&
    $mapLng !== null &&
    $mapLat >= -90 && $mapLat <= 90 &&
    $mapLng >= -180 && $mapLng <= 180;

$mapSiteName = json_encode(
    $site['name'],
    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
);

require_once 'includes/header.php';
?>

<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
>

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

    <section class="site-location">
        <h2>Site Location</h2>

        <p>
            Explore the geographical location of
            <?php echo e($site['name']); ?>.
        </p>

        <?php if ($hasValidCoordinates): ?>
            <div
                id="site-location-map"
                role="region"
                aria-label="Map showing the location of <?php echo e($site['name']); ?>"
            ></div>

            <p class="map-note">
                Coordinates:
                <?php echo e($site['lat']); ?>,
                <?php echo e($site['lng']); ?>
            </p>

        <?php else: ?>
            <p>Map unavailable because valid coordinates have not been recorded.</p>
        <?php endif; ?>
    </section>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <?php if ($hasValidCoordinates): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const latitude = <?php echo json_encode($mapLat); ?>;
        const longitude = <?php echo json_encode($mapLng); ?>;
        const siteName = <?php echo $mapSiteName ?: '""'; ?>;

        const locationMap = L.map('site-location-map', {
            scrollWheelZoom: false
        }).setView([latitude, longitude], 8);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            noWrap: true,
            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright">' +
                'OpenStreetMap contributors</a>'
        }).addTo(locationMap);

        const marker = L.marker([latitude, longitude]).addTo(locationMap);

        const popup = document.createElement('div');
        const title = document.createElement('strong');
        title.textContent = siteName;
        popup.appendChild(title);

        const coordinates = document.createElement('p');
        coordinates.textContent =
            'Coordinates: ' + latitude + ', ' + longitude;
        popup.appendChild(coordinates);

        marker.bindPopup(popup).openPopup();
    });
    </script>
    <?php endif; ?>

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
