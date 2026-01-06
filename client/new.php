<?php require_once __DIR__ . '/php/config.php'; ?>

<!DOCTYPE html>
<html lang="en">

<?php
  $page = 'cars';
  $page_title = 'Nos Véhicules';
  $page_bg = '../images/site/about.jpg';

  include('components/header.php');
  include('components/navbar.php');
  include('components/title.php');

  // Set pagination values
  $limit = 12;
  $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
  $page = max($page, 1);
  $offset = ($page - 1) * $limit;

  $total = $pdo->query("SELECT COUNT(*) FROM cars WHERE is_visible = 1")->fetchColumn();

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

  $query = $pdo->query("
    SELECT
    c.id,
    c.car_id,
    mk.name AS make,
    c.model,
    c.year,
    c.mileage,
    c.price,
    t.name AS transmission,
    f.name AS fuel,
    c.car_id,
    img.path AS image
      FROM cars c
      JOIN car_make mk ON c.make_id = mk.id
      JOIN car_fuel f ON c.fuel_id = f.id
      JOIN car_transmission t ON c.transmission_id = t.id
      LEFT JOIN car_images img ON img.car_id = c.id AND img.is_main = 1
      WHERE c.is_visible = 1
      ORDER BY $orderBy
      LIMIT $limit OFFSET $offset
  ");
?>





















<!-- Wrapper container -->
<div class="container my-5">
  <div class="row">







    <!-- Sidebar Filters -->





<aside class="col-lg-3 mb-4">
  <div class="bg-white border p-4">
    <div class="d-flex align-items-center mb-4">
      <i class="fas fa-filter text-danger fs-4 me-2"></i>
      <h5 class="mb-0 fw-bold">Find Your Dream Car</h5>
    </div>

    <form method="GET">
      <div class="mb-3">
        <select class="form-select bg-light">
          <option selected>Type of Car</option>
        </select>
      </div>

      <div class="mb-3">
        <select class="form-select bg-light">
          <option selected>Select Brand</option>
        </select>
      </div>


      <div class="mb-3">
        <select class="form-select bg-light">
          <option selected>Select Fuel Type</option>
        </select>
      </div>

      <div class="mb-3">
        <select class="form-select bg-light">
          <option selected>Select Transmission Type</option>
        </select>
      </div>

      <div class="d-grid mt-4">
        <button type="submit" class="btn btn-danger text-white fw-bold text-uppercase">
          <i class="fas fa-search me-2"></i>Search Car
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
      <hr class="car-item-separator">

      <!-- Grid Section -->
      <div class="row g-4">
        <?php while ($car = $query->fetch(PDO::FETCH_ASSOC)) : ?>
          <!-- Car Listing -->
          <div class="col-md-6 col-lg-4">
            <div class="car-item">
              <div class="car-item__img-wrapper position-relative">
                <a class="car-item__img" href="details.php?id=<?= $car['id'] ?>">
                  <img class="img-fluid" src="../assets/cars/owned/<?= htmlspecialchars($car['car_id']) ?>/00.webp" alt="<?= $car['make'] . ' ' . $car['model'] ?>">
                  <div class="car-item__overlay d-flex align-items-center justify-content-center"><i class="fa-solid fa-eye fa-lg"></i></div>
                </a>
              </div>
              <div class="car-item__main">
                <div class="row no-gutters align-items-center text-center">
                  <a class="car-item__title" href="#"><?= $car['make'] . ' ' . $car['model'] ?></a>
                </div>
                <div class="car-item__separator"></div>
                <div class="car-item-descrip row no-gutters text-center align-items-center mt-2">
                  <div class="col" title="<?= number_format($car['mileage'], 0, ',', ' ') ?> km"><i class="ic flaticon-speedometer"></i>

                      <?= round($car['mileage'] / 1000) . 'k km' ?>

                  </div>
                  <div class="col"><i class="ic flaticon2-fuel-station-pump"></i>
                    <div><?= $car['fuel'] ?></div>
                  </div>
                  <div class="col"><i class="ic flaticon-gearshift"></i>
                    <div><?= $car['transmission'] ?></div>
                  </div>
                  <div class="col"><i class="ic flaticon3-calendar-interface-symbol-with-squares-in-rounded-rectangular-shape-with-spring-on-top-border"></i>
                    <div><?= $car['year'] ?></div>
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

<?php include 'components/footer.php'; ?>

</html>