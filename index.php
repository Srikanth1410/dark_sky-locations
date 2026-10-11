<?php
require_once 'includes/db.php';

$pageTitle = 'Explore Dark Sky Sites';
$pdo = getDbConnection();

// Read search and filter values from the URL.
$search = trim($_GET['search'] ?? '');
$country = trim($_GET['country'] ?? '');
$siteType = trim($_GET['site_type'] ?? '');
$year = trim($_GET['year'] ?? '');

// Load dropdown options from the database.
$countries = $pdo->query(
    'SELECT DISTINCT country FROM sites ORDER BY country'
)->fetchAll(PDO::FETCH_COLUMN);

$siteTypes = $pdo->query(
    'SELECT DISTINCT site_type FROM sites ORDER BY site_type'
)->fetchAll(PDO::FETCH_COLUMN);

// Build a parameterised query based on selected filters.
$sql = 'SELECT id, name, country, site_type,
               designation_year, sky_quality_index, lat, lng
        FROM sites
        WHERE 1 = 1';

$params = [];

if ($search !== '') {
    $sql .= ' AND name LIKE ?';
    $params[] = '%' . $search . '%';
}

if ($country !== '') {
    $sql .= ' AND country = ?';
    $params[] = $country;
}

if ($siteType !== '') {
    $sql .= ' AND site_type = ?';
    $params[] = $siteType;
}

if ($year !== '' && ctype_digit($year)) {
    $sql .= ' AND designation_year = ?';
    $params[] = (int) $year;
}

$sql .= ' ORDER BY name';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$sites = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<section>
    <h2>Explore Dark Sky Sites</h2>

    <p>
        Discover certified dark sky places, explore their locations
        and find destinations for astronomical observation.
    </p>

    <form method="GET" action="index.php">
        <div class="form-field">
            <label for="search">Search by site name</label>
            <input
                type="search"
                id="search"
                name="search"
                placeholder="e.g. National Park"
                value="<?php echo e($search); ?>"
            >
        </div>

        <div class="form-field">
            <label for="country">Country</label>
            <select id="country" name="country">
                <option value="">All countries</option>

                <?php foreach ($countries as $item): ?>
                    <option
                        value="<?php echo e($item); ?>"
                        <?php echo $country === $item ? 'selected' : ''; ?>
                    >
                        <?php echo e($item); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-field">
            <label for="site_type">Site type</label>
            <select id="site_type" name="site_type">
                <option value="">All types</option>

                <?php foreach ($siteTypes as $item): ?>
                    <option
                        value="<?php echo e($item); ?>"
                        <?php echo $siteType === $item ? 'selected' : ''; ?>
                    >
                        <?php echo e($item); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-field">
            <label for="year">Designation year</label>
            <input
                type="number"
                id="year"
                name="year"
                min="1900"
                max="2100"
                placeholder="e.g. 2019"
                value="<?php echo e($year); ?>"
            >
        </div>

        <button type="submit">Search</button>
        <a href="index.php">Clear filters</a>
    </form>

    <h3>Search results: <?php echo count($sites); ?> sites</h3>

    <?php if (empty($sites)): ?>
        <p>No sites match your search. Try changing your filters.</p>
    <?php else: ?>
        <div class="table-wrapper">
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
                            <td>
                                <?php
                                echo $site['sky_quality_index'] !== null
                                    ? e($site['sky_quality_index'])
                                    : 'Not recorded';
                                ?>
                            </td>
                            <td><?php echo e($site['lat']); ?></td>
                            <td><?php echo e($site['lng']); ?></td>
                            <td>
                                <a href="site.php?id=<?php
                                    echo (int) $site['id'];
                                ?>">View details</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php require_once 'includes/footer.php'; ?>