<!DOCTYPE html>
<html lang="en">

<?php

require_once __DIR__ . '/php/config.php';

// Set the current page name
$page = 'index';

// Get the 'à propos' section (1 row)
$stmt = $pdo->prepare("SELECT * FROM content WHERE section = 'à propos' LIMIT 1");
$stmt->execute();
$about = $stmt->fetch(PDO::FETCH_ASSOC);

// Get all rows for feature highlights
$stmt = $pdo->prepare("SELECT * FROM content WHERE section = 'Atouts clés' ORDER BY id ASC");
$stmt->execute();
$icon_items = $stmt->fetchAll(PDO::FETCH_ASSOC);



$stmt = $pdo->prepare("SELECT * FROM content WHERE section = 'Maintenance' LIMIT 1");
$stmt->execute();
$maint = $stmt->fetch(PDO::FETCH_ASSOC);



$stmt = $pdo->prepare("SELECT * FROM services WHERE is_active = 1 ORDER BY id ASC LIMIT 4");
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sql = "
SELECT 
    cars.*, 
    car_make.name AS make_name,
    car_transmission.name AS transmission_name,
    car_condition.id AS condition_id
FROM cars
JOIN car_make ON cars.make_id = car_make.id
JOIN car_transmission ON cars.transmission_id = car_transmission.id
JOIN car_condition ON cars.condition_id = car_condition.id
WHERE cars.is_visible = 1 AND cars.is_featured = 1
LIMIT 8
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

/////////////////////////////////////////////////////////////////////////////////////////

$sql = "
SELECT 
    cars.*, 
    car_make.name AS make_name,
    car_transmission.name AS transmission_name,
    car_condition.id AS condition_id
FROM cars
JOIN car_make ON cars.make_id = car_make.id
JOIN car_transmission ON cars.transmission_id = car_transmission.id
JOIN car_condition ON cars.condition_id = car_condition.id
WHERE cars.is_visible = 1
  AND cars.condition_id = 2        -- PRE-OWNED ONLY
ORDER BY cars.created_at DESC
LIMIT 20
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$usedCars = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Car brands from car_make
$stmt = $pdo->prepare("
    SELECT 
        name,
        CONCAT('../assets/logos/', path) AS fullpath
    FROM car_make
    WHERE is_visible = 1
    ORDER BY name ASC
");
$stmt->execute();
$car_makes = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Partner brands (tyres, oils, parts)
$stmt = $pdo->prepare("
    SELECT 
        name,
        CONCAT('../assets/brands/', path) AS fullpath
    FROM brands
    WHERE is_visible = 1
    ORDER BY name ASC
");
$stmt->execute();
$partners = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Merge both into marquee list
$all_brands = array_merge($car_makes, $partners);



// Page components and layout parts
include('components/header.php');
include('components/navbar.php');
include('components/carousel.php');
include('components/carfinder.php');
?>

<!-- About Start -->
<section class="about section-default">
  <div class="container">
    <div class="row">
      <div class="col-xl-6">

      <!-- Text Section Start -->
      <div class="text-center d-inline-block">
            <div class="title-logo">SUPERCAR</div>
            <h2 class="title"><?= htmlspecialchars($about['title']) ?></h2>
          </div>


        <?php if (!empty($about['content'])): ?>
          <p><?= nl2br(htmlspecialchars($about['content'])) ?></p>
        <?php endif; ?>
        <!-- Text Section End -->

        <!-- Feature Grid Start -->
        <?php if (!empty($icon_items)): ?>
        <ul class="about-list list-unstyled">
          <?php foreach ($icon_items as $item): ?>
            <li class="about-list__item">
              <i class="<?= htmlspecialchars($item['image']) ?>"></i>
              <div class="about-list__text"><?= htmlspecialchars($item['content']) ?></div>
            </li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
        <!-- Feature Grid End -->

      </div>
    </div>
  </div>
</section>
<!-- About End -->



<!-- Featured Start -->
  <!-- Header Start-->
      <div class="mt-5 pt-4">
            <div class="text-center title-logo">SUPERCAR</div>
            <h2 class="text-center title">Découvrez nos véhicules</h2>
          </div> <!-- Header End-->
  <!-- Grid -->
<section class="bg-white pt-4 pb-5 mb-2">
  <div class="container">
    <div class="row g-4 justify-content-center">

<?php foreach ($cars as $car): ?>
  <?php
    $carId  = (int)$car['id'];
    $code   = htmlspecialchars($car['car_id']);

    // map the status to folder name, just like cars.php
$condition = strtolower(trim($car['condition_id']));


$img = "../assets/cars/{$car['car_id']}/00.webp";

    $make      = htmlspecialchars($car['make_name']);
    $model     = htmlspecialchars($car['model']);
    $year      = htmlspecialchars($car['year']);
    $engine   = htmlspecialchars($car['engine']);
    $price     = number_format($car['price']);
    $fuelId    = (int)$car['fuel_id'];
    $transName = htmlspecialchars($car['transmission_name']);

    $fuelNames = [
      1 => 'Essence',
      2 => 'Diesel',
      3 => 'Électrique',
      4 => 'Hybride',
    ];
    $fuel = $fuelNames[$fuelId] ?? 'N/A';
  ?>
  <div class="col-md-3 col-sm-6">
    <div class="card shadow featured-item text-center h-100">
      <div class="featured-item__img-wrapper position-relative">
        <a class="featured-item__img" href="details.php?id=<?= $carId ?>">
          <img class="img-fluid"
               src="<?= $img ?>"
               alt="<?= $make . ' ' . $model ?>">
          <div class="featured-item__overlay d-flex align-items-center justify-content-center">
            <i class="fa-solid fa-eye fa-lg"></i>
          </div>
        </a>
      </div>

            <!-- Specs -->
            <div class="featured-item__main">

              <div class="row no-gutters align-items-center text-center">
                <a class="featured-item__title" href="details.php?id=<?= $carId ?>">
                  <?= $make . ' ' . $model ?>
                </a>
              </div>

              <div class="featured-item__separator"></div>

              <div class="featured-item-descrip row no-gutters text-center align-items-center mt-2">

                <!-- Mileage -->
                <div class="col" title="<?= $engine ?> km">
                  <i class="fa-solid fa-engine" style="padding: 5px 0;"></i>
                  <div style="font-weight:700;"><?= number_format($engine) ?></div>
                </div>

                <!-- Fuel -->
                <div class="col">
                  <i class="ic flaticon2-fuel-station-pump"></i>
                  <div style="font-weight:700;"><?= $fuel ?></div>
                </div>

                <!-- Transmission -->
                <div class="col">
                  <i class="ic flaticon-gearshift"></i>
                  <div style="font-weight:700;"><?= $transName ?></div>
                </div>

                <!-- Year -->
                <div class="col">
                  <i class="fa-solid fa-calendar" style="padding: 5px 0;"></i>
                  <div style="font-weight:700;"><?= $year ?></div>
                </div>

              </div>

              <div class="featured-item__separator"></div>

              <!-- Price -->
              <div class="featured-item__price text-primary text-center">
                <?= $price ?> MUR
              </div>

            </div>
          </div>
        </div>

      <?php endforeach; ?>

    </div>

    <div class="pt-5 text-center"><a href="cars.php?condition=new" class="btn btn-supercar">Accéder au catalogue</a></div>  

  </div>
</section>
<!-- Featured End -->






















<!-- DARK BACKGROUND SECTION (banner only) -->
<section class="services-hero">
    <div class="overlay"></div>

    <div class="container text-center text-white position-relative">
        <div class="mb-3">
            <div class="title-logo title-logo_dark">SUPERCAR</div>
            <h2 class="title-dark" id="services">Services & Expertise</h2>
            <div class="title-decor"></div>
        </div>

        <?php if (!empty($maint['content'])): ?>
            <p class="services-subtitle mb-4">
                <?= nl2br(htmlspecialchars($maint['content'])) ?>
            </p>
        <?php endif; ?>
    </div>
</section>

<!-- FLOATING CARDS SECTION -->
<section class="services-cards">
    <div class="container">
        <div class="row g-4">

            <?php foreach ($services as $service): ?>
                <div class="col-md-3 col-sm-6">
                    <div class="service-card h-100">
                        <img class="service-icon" 
                             src="../assets/icons/<?= htmlspecialchars($service['icon']) ?>" 
                             alt="<?= htmlspecialchars($service['name']) ?>">

                        <h3 class="service-title">
                            <?= htmlspecialchars($service['name']) ?>
                        </h3>

                        <p class="service-info">
                            <?= htmlspecialchars($service['description']) ?>
                        </p>

                        <span class="service-underline"></span>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>


<section>
  <div class="pt-0 pb-3 text-center"><a href="services.php" class="btn btn-outline-primary">Accéder aux services</a></div>
</section>






<section>
 <!-- Header Start-->
  <div class="container text-center pt-5">
    <div class="text-center title-logo">SUPERCAR</div>
            <h2 class="text-center title">Saisissez l’occasion. C’est le moment!</h2>
            <p>Trouvez la voiture qui correspond à vos besoins parmi nos modèles d’occasion disponibles.</p>

  </div> <!-- Header End-->
</section>


<!-- Featured Start -->

  <!-- Grid -->
<section class="bg-white pt-4 pb-5 mb-2">
  <div class="container">
    <div class="preowned">

<?php foreach ($usedCars as $car): ?>
    <?php
        $carId  = (int)$car['id'];
        $code   = htmlspecialchars($car['car_id']);

        // All pre-owned → always the 'owned' folder
        $img = "../assets/cars/{$car['car_id']}/00.webp";

        $make      = htmlspecialchars($car['make_name']);
        $model     = htmlspecialchars($car['model']);
        $year      = htmlspecialchars($car['year']);
        $mileage    = htmlspecialchars($car['mileage']);
        $price     = number_format($car['price']);
        $fuelId    = (int)$car['fuel_id'];
        $transName = htmlspecialchars($car['transmission_name']);

        // Fuel label
        $fuelNames = [
            1 => 'Essence',
            2 => 'Diesel',
            3 => 'Électrique',
            4 => 'Hybride',
        ];
        $fuel = $fuelNames[$fuelId] ?? 'N/A';
    ?>

    <div class="item">
        <div class="card shadow featured-item text-center h-100">

            <div class="featured-item__img-wrapper position-relative">
                <a class="featured-item__img" href="details.php?id=<?= $carId ?>">
                    <img class="img-fluid"
                         src="<?= $img ?>"
                         alt="<?= $make . ' ' . $model ?>">
                    <div class="featured-item__overlay d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-eye fa-lg"></i>
                    </div>
                </a>
            </div>

            <div class="featured-item__main">

                <div class="row no-gutters align-items-center text-center">
                    <a class="featured-item__title" href="details.php?id=<?= $carId ?>">
                        <?= $make . ' ' . $model ?>
                    </a>
                </div>

                <div class="featured-item__separator"></div>

                <div class="featured-item-descrip row no-gutters text-center align-items-center mt-2">

                    <!-- Engine -->
                    <div class="col preowned-col" title="<?= $mileage ?> km">
                        <i class="ic flaticon-speedometer"></i>
                        <?= number_format($mileage) ?>
                    </div>

                    <!-- Fuel -->
                    <div class="col preowned-col">
                        <i class="ic flaticon2-fuel-station-pump"></i>
                        <div><?= $fuel ?></div>
                    </div>

                    <!-- Transmission -->
                    <div class="col preowned-col">
                        <i class="ic flaticon-gearshift"></i>
                        <div><?= $transName ?></div>
                    </div>

                    <!-- Year -->
                    <div class="col preowned-col">
                        <i class="fa-solid fa-calendar" style="padding: 5px 0;"></i>
                        <div><?= $year ?></div>
                    </div>

                </div>

                <div class="featured-item__separator"></div>

                <!-- Price -->
                <div class="featured-item__price text-primary text-center">
                    <?= $price ?> MUR
                </div>

            </div>

        </div>
    </div>

<?php endforeach; ?>
    </div>

    

  </div>

  <div class="pt-5 text-center"><a href="cars.php?condition=preowned" class="btn btn-outline-accent">Voir les voitures d’occasion</a></div>

</section>
<!-- Featured End -->






<!-- Brands strip -->
<section class="brand bg-light">
  <div class="container brand-inner">

    <!-- Left title block -->
<div class="brand-label">
    <h5>Nos<br>Partenaires</h5>
</div>

    <!-- Logos row -->
<div class="brand-slider autoplay">
    <?php foreach ($all_brands as $b): ?>
        <div>
            <img src="<?= htmlspecialchars($b['fullpath']) ?>" 
                 alt="<?= htmlspecialchars($b['name']) ?>">
        </div>
    <?php endforeach; ?>
</div>
</div>
</section>
<!-- /Brands strip -->



<?php include 'components/footer.php'; ?>

</html>