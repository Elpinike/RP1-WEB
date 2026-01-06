<?php require_once __DIR__ . '/php/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<?php
  $page = 'cars';
  $page_title = 'Nos Véhicules';
  $page_bg = '../assets/site/cars.webp';

  $breadcrumbs = [
    ['label' => 'Accueil', 'url' => 'index.php'],
    ['label' => 'Voitures']
  ];

  include('components/header.php');
  include('components/navbar.php');
  include('components/title.php');

  // Set pagination values
  $limit = 12;
  $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
  $page = max($page, 1);
  $offset = ($page - 1) * $limit;

$condition = $_GET['condition'] ?? 'all';

switch ($condition) {
    case 'new':
        $condition_sql = "AND c.condition_id = 1";
        $page_title = 'Nos Véhicules Neufs';
        break;
    case 'preowned':
        $condition_sql = "AND c.condition_id = 2";
        $page_title = 'Nos Véhicules d\'Occasion';
        break;
    default:
        $condition_sql = ""; // No filtering on condition
        $page_title = 'Tous nos Véhicules';
        break;
}

  // Load filter options from DB
$types = $pdo->query("SELECT id, name FROM car_type")->fetchAll();
$makes = $pdo->query("SELECT id, name FROM car_make")->fetchAll();
$fuels = $pdo->query("SELECT id, name FROM car_fuel")->fetchAll();
$transmissions = $pdo->query("SELECT id, name FROM car_transmission")->fetchAll();

/******/
// Get filter selections from URL
$type_id = isset($_GET['type']) && is_numeric($_GET['type']) ? (int) $_GET['type'] : null;
$make_id = isset($_GET['make']) && is_numeric($_GET['make']) ? (int) $_GET['make'] : null;
$fuel_id = isset($_GET['fuel']) && is_numeric($_GET['fuel']) ? (int) $_GET['fuel'] : null;
$trans_id = isset($_GET['transmission']) && is_numeric($_GET['transmission']) ? (int) $_GET['transmission'] : null;

// Build WHERE clause
$where = "WHERE c.is_visible = 1 $condition_sql";

if (!is_null($type_id))  $where .= " AND c.type_id = $type_id";
if (!is_null($make_id))  $where .= " AND c.make_id = $make_id";
if (!is_null($fuel_id))  $where .= " AND c.fuel_id = $fuel_id";
if (!is_null($trans_id)) $where .= " AND c.transmission_id = $trans_id";

// Count total rows for pagination
$total = $pdo->query("SELECT COUNT(*) FROM cars c $where")->fetchColumn();

// Sorting
$sort = $_GET['sort'] ?? 'newest';

switch ($sort) {
  case 'oldest':
    $orderBy = 'c.created_at ASC';
    break;
  case 'price_asc':
    $orderBy = 'c.price ASC';
    break;
  case 'price_desc':
    $orderBy = 'c.price DESC';
    break;
  case 'newest':
  default:
    $orderBy = 'c.created_at DESC';
    break;
}

// Final query
$query = $pdo->query("
  SELECT
    c.id, c.car_id, mk.name AS make, c.model, c.year, c.engine,
    c.mileage, c.price, t.name AS transmission,
    f.name AS fuel, img.path AS image,
    car_condition.id AS condition_id
  FROM cars c
  JOIN car_make mk ON c.make_id = mk.id
  JOIN car_fuel f ON c.fuel_id = f.id
  JOIN car_transmission t ON c.transmission_id = t.id
  JOIN car_condition ON c.condition_id = car_condition.id
  LEFT JOIN car_img img ON img.car_id = c.id AND img.is_main = 1
  $where
  ORDER BY $orderBy
  LIMIT $limit OFFSET $offset
");

?>





<!-- Wrapper container -->
<div class="container my-5">
  <div class="row">







    <!-- Sidebar Filters -->





<aside class="filter-sidebar">
  <div class="filter-box">

    <!-- Header -->
    <div class="filter-header angled mb-3">
            <h5>Trouvez votre Supercar</h5>
            <i class="fa-regular fa-magnifying-glass"></i>
    </div>

    <!-- Filter form -->
    <form method="GET">

      <!-- Keep sort + condition -->
      <input type="hidden" name="sort" value="<?= htmlspecialchars($_GET['sort'] ?? '') ?>">
      <input type="hidden" name="condition" value="<?= htmlspecialchars($condition) ?>">

      <!-- Type -->
      <div class="filter-group">
        <select class="form-select" name="type">
          <option value="">Type of Car</option>
          <?php foreach ($types as $type): ?>
            <option value="<?= $type['id'] ?>"
              <?= ($type['id'] == ($_GET['type'] ?? '')) ? 'selected' : '' ?>>
              <?= htmlspecialchars($type['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Make -->
      <div class="filter-group">
        <select class="form-select" name="make">
          <option value="">Select Brand</option>
          <?php foreach ($makes as $make): ?>
            <option value="<?= $make['id'] ?>"
              <?= ($make['id'] == ($_GET['make'] ?? '')) ? 'selected' : '' ?>>
              <?= htmlspecialchars($make['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Fuel -->
      <div class="filter-group">
        <select class="form-select" name="fuel">
          <option value="">Select Fuel Type</option>
          <?php foreach ($fuels as $fuel): ?>
            <option value="<?= $fuel['id'] ?>"
              <?= ($fuel['id'] == ($_GET['fuel'] ?? '')) ? 'selected' : '' ?>>
              <?= htmlspecialchars($fuel['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Transmission -->
      <div class="filter-group pb-1">
        <select class="form-select" name="transmission">
          <option value="">Select Transmission Type</option>
          <?php foreach ($transmissions as $trans): ?>
            <option value="<?= $trans['id'] ?>"
              <?= ($trans['id'] == ($_GET['transmission'] ?? '')) ? 'selected' : '' ?>>
              <?= htmlspecialchars($trans['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Search + Reset Buttons -->
      <div class="filter-actions">
        <button type="button" class="btn-reset" id="resetFilters">
          Réinitialiser les filtres
        </button>


      </div>

    </form>
  </div>
</aside>
















    <!-- Main Content -->
    <div class="col-lg-9">

      <!-- Sort -->
    <?php include 'components/car-sort.php'; ?>

      <!-- Separator line -->
      <hr class="car-sort__separator">

      <!-- Grid Section -->
      <div class="row g-4">
        <?php while ($car = $query->fetch(PDO::FETCH_ASSOC)) : ?>
          <!-- Car Listing -->
          <div class="col-md-6 col-lg-4">
            <div class="car-item">
              <div class="car-item__img-wrapper position-relative">
                <a class="car-item__img" href="details.php?id=<?= $car['id'] ?>">
                  <?php
?>
<img 
  class="img-fluid" 
  src="../assets/cars/<?= htmlspecialchars($car['car_id']) ?>/00.webp" 
  alt="<?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?>">

                  <div class="car-item__overlay d-flex align-items-center justify-content-center"><i class="fa-solid fa-eye fa-lg"></i></div>
                </a>
              </div>
              <div class="car-item__main">
                <div class="row no-gutters align-items-center text-center">
                  <a class="car-item__title" href="details.php?id=<?= $car['id'] ?>"><?= $car['make'] . ' ' . $car['model'] ?></a>
                </div>
                <div class="car-item__separator"></div>
                <div class="car-item-descrip row no-gutters text-center align-items-center mt-2">
                  <?php if ($car['condition_id'] == 1): ?>
    <div class="col" title="<?= htmlspecialchars($car['engine']) ?>">
        <i class="fa-solid fa-engine" style="padding: 5px 0;"></i>
        <div style="font-weight:700;"><?= htmlspecialchars($car['engine']) ?></div>
    </div>
<?php else: ?>
    <div class="col" title="<?= number_format($car['mileage'], 0, ',', ' ') ?> km">
        <i class="ic flaticon-speedometer"></i>
        <div style="font-weight:700;"><?= round($car['mileage'] / 1000) . 'k km' ?></div>
    </div>
<?php endif; ?>


                  <div class="col"><i class="ic flaticon2-fuel-station-pump"></i>
                    <div style="font-weight:700;"><?= $car['fuel'] ?></div>
                  </div>
                  <div class="col"><i class="ic flaticon-gearshift"></i>
                    <div style="font-weight:700;"><?= $car['transmission'] ?></div>
                  </div>
                  <div class="col"><i class="fa-solid fa-calendar" style="padding: 5px 0;"></i>
                    <div style="font-weight:700;"><?= $car['year'] ?></div>
                  </div>
                </div>
                <div class="car-item__separator"></div>
                <div class="car-item__price text-primary text-center"><?= number_format($car['price']) ?> MUR</div>
              </div>
            </div>
          </div>
          <?php endwhile; ?>
        <!-- Car Listing End -->
      </div>
      <!-- Pagination-->
    <?php include 'components/car-pagination.php'; ?>
    </div>
  </div>
</div>

<script>
document.querySelectorAll(".filter-sidebar select").forEach(select => {
    select.addEventListener("change", () => {
        select.form.submit();
    });
});


document.getElementById("resetFilters").addEventListener("click", () => {
    window.location.href = "cars.php";
});

document.getElementById("resetFilters").addEventListener("click", () => {

    // Read the current condition
    const condition = document.querySelector("input[name='condition']").value;

    // Redirect back to cars.php while preserving condition
    window.location.href = "cars.php?condition=" + encodeURIComponent(condition);
});
</script>


<?php include 'components/footer.php'; ?>

</html>