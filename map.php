
<?php
require_once 'includes/db.php';

$pdo = getDbConnection();

$stmt = $pdo->query(
    'SELECT id, name, country, site_type, lat, lng
     FROM sites
     WHERE lat IS NOT NULL AND lng IS NOT NULL
     ORDER BY name'
);

$sites = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Encode database data safely for JavaScript.
$siteData = json_encode(
    $sites,
    JSON_HEX_TAG |
    JSON_HEX_APOS |
    JSON_HEX_QUOT |
    JSON_HEX_AMP
);

if ($siteData === false) {
    $siteData = '[]';
}

$pageTitle = 'Interactive Dark Sky Sites Map';

require_once 'includes/header.php';
?>

<!-- Leaflet map library -->
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
>

<style>
    #dark-sky-map {
        width: 100%;
        height: 600px;
        border: 1px solid #2a405a;
        border-radius: 12px;
        z-index: 1;
    }

    .map-summary {
        margin-bottom: 16px;
    }

    .map-note {
        margin-top: 12px;
        font-size: 0.9rem;
        color: var(--muted);
    }

    @media (max-width: 600px) {
        #dark-sky-map {
            height: 420px;
        }
    }
</style>

<main>
    <h2>Interactive Dark Sky Sites Map</h2>

    <p>
        Explore the geographical distribution of dark-sky
        sites around the world. Select a marker to see more details.
    </p>

    <p class="map-summary">
        Sites displayed:
        <strong><?php echo count($sites); ?></strong>
    </p>

    <div
        id="dark-sky-map"
        role="region"
        aria-label="Interactive map of dark-sky sites"
    ></div>

    <p class="map-note">
        Map data © OpenStreetMap contributors.
        Site coordinates are representative locations from
        the supplied educational dataset.
    </p>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const darkSkySites = <?php echo $siteData; ?>;

    const map = L.map('dark-sky-map', {
        worldCopyJump: false,
        maxBounds: [
            [-85, -180],
            [85, 180]
        ],
        maxBoundsViscosity: 1.0
    }).setView([20, 0], 2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap contributors</a>',
        maxZoom: 19,
        noWrap: true,
        bounds: [
            [-90, -180],
            [90, 180]
        ]
    }).addTo(map);

    const markers = [];

    darkSkySites.forEach((site) => {
        const lat = Number(site.lat);
        const lng = Number(site.lng);

        if (
            !Number.isFinite(lat) ||
            !Number.isFinite(lng) ||
            lat < -90 || lat > 90 ||
            lng < -180 || lng > 180
        ) {
            return;
        }

        const marker = L.marker([lat, lng]).addTo(map);

        // Build popup content using DOM text nodes rather than
        // inserting database values as HTML.
        const popup = document.createElement('div');

        const title = document.createElement('strong');
        title.textContent = site.name;
        popup.appendChild(title);

        const country = document.createElement('p');
        country.textContent = 'Country: ' + site.country;
        popup.appendChild(country);

        const type = document.createElement('p');
        type.textContent = 'Type: ' + site.site_type;
        popup.appendChild(type);

        const detailsLink = document.createElement('a');
        detailsLink.textContent = 'View site details';
        detailsLink.href = 'site.php?id=' + encodeURIComponent(site.id);
        popup.appendChild(detailsLink);

        marker.bindPopup(popup);
        markers.push(marker);
    });

    // Keep all valid site markers in view.
    if (markers.length > 0) {
        const markerGroup = L.featureGroup(markers);
        map.fitBounds(markerGroup.getBounds().pad(0.1));
    }
</script>

<?php require_once 'includes/footer.php'; ?>
