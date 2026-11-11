<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo '<p>Database connection failed.</p>';
    exit;
}

$country = isset($_GET['country']) ? trim($_GET['country']) : '';
$lookup  = isset($_GET['lookup']) ? $_GET['lookup'] : 'country';
$like    = '%' . $country . '%';

if ($lookup === 'cities') {
    // Exercise 5: JOIN countries and cities
    $stmt = $conn->prepare(
        "SELECT ci.name, ci.district, ci.population
         FROM cities ci
         JOIN countries co ON ci.country_code = co.code
         WHERE co.name LIKE :country
         ORDER BY ci.population DESC"
    );
} else {
    // Exercises 3 & 4: country lookup
    $stmt = $conn->prepare(
        "SELECT name, continent, independence_year, head_of_state
         FROM countries
         WHERE name LIKE :country
         ORDER BY name"
    );
}

$stmt->execute([':country' => $like]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php if (count($results) === 0): ?>
  <p>No results found.</p>
<?php elseif ($lookup === 'cities'): ?>
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>District</th>
        <th>Population</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($results as $row): ?>
      <tr>
        <td><?= htmlspecialchars($row['name']); ?></td>
        <td><?= htmlspecialchars($row['district']); ?></td>
        <td><?= htmlspecialchars($row['population']); ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php else: ?>
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Continent</th>
        <th>Independence</th>
        <th>Head of State</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($results as $row): ?>
      <tr>
        <td><?= htmlspecialchars($row['name']); ?></td>
        <td><?= htmlspecialchars($row['continent']); ?></td>
        <td><?= htmlspecialchars($row['independence_year'] ?? ''); ?></td>
        <td><?= htmlspecialchars($row['head_of_state'] ?? ''); ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

