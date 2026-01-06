<?php
require_once 'php/dbconnect.php';

// Fetch makes
$makes = $pdo->query("SELECT id, name FROM car_make ORDER BY name")->fetchAll();

// Fetch fuels
$fuels = $pdo->query("SELECT id, name FROM car_fuel ORDER BY name")->fetchAll();

// Fetch transmissions
$transmissions = $pdo->query("SELECT id, name FROM car_transmission ORDER BY name")->fetchAll();
?>

<!-- Car Search start-->
<div class="section-area bg-light">
  <div class="container car-search__wrapper">
    <div class="car-search">
      <!-- Tabs -->
      <ul class="car-search__nav-tabs nav" id="findTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="tab-allCar" data-bs-toggle="tab" href="#content-allCar" role="tab" aria-controls="content-allCar" aria-selected="true">
            <div class="car-search__corner"></div>
            <i class="fas fa-search"></i> Tous nos véhicules
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab-newCars" data-bs-toggle="tab" href="#content-newCars" role="tab" aria-controls="content-newCars" aria-selected="false">
            <div class="car-search__corner"></div>
            Voitures neuves
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab-usedCars" data-bs-toggle="tab" href="#content-usedCars" role="tab" aria-controls="content-usedCars" aria-selected="false">
            <div class="car-search__corner"></div>
            Voitures d'occasion
          </a>
        </li>
      </ul>

      <!-- Tab Contents -->
      <div class="tab-content" id="findTabContent">

        <!-- TAB 1: ALL -->
        <div class="tab-pane fade show active" id="content-allCar" role="tabpanel" aria-labelledby="tab-allCar">
          <form class="car-search__form d-flex flex-wrap align-items-end gap-3" method="GET" action="cars.php">
            <input type="hidden" name="condition" value="all">

            <div class="flex-grow-1">
              <label class="car-search__label" for="makeAll">01 Marque</label>
<select name="make" class="form-select car-search__select">
  <option selected disabled>Sélectionnez la marque</option>
  <?php foreach ($makes as $make): ?>
    <option value="<?= $make['id'] ?>" <?= ($_GET['make'] ?? '') == $make['id'] ? 'selected' : '' ?>>
      <?= htmlspecialchars($make['name']) ?>
    </option>
  <?php endforeach; ?>
</select>
            </div>

            <div class="flex-grow-1">
              <label class="car-search__label" for="modelAll">02 Carburant</label>
<select name="fuel" class="form-select car-search__select">
  <option selected disabled>Type de carburant</option>
  <?php foreach ($fuels as $fuel): ?>
    <option value="<?= $fuel['id'] ?>" <?= ($_GET['fuel'] ?? '') == $fuel['id'] ? 'selected' : '' ?>>
      <?= htmlspecialchars($fuel['name']) ?>
    </option>
  <?php endforeach; ?>
</select>
            </div>

            <div class="flex-grow-1">
              <label class="car-search__label" for="fuelAll">03 Transmission</label>
<select name="transmission" class="form-select car-search__select">
  <option selected disabled>Type de transmission</option>
  <?php foreach ($transmissions as $trans): ?>
    <option value="<?= $trans['id'] ?>" <?= ($_GET['transmission'] ?? '') == $trans['id'] ? 'selected' : '' ?>>
      <?= htmlspecialchars($trans['name']) ?>
    </option>
  <?php endforeach; ?>
</select>
            </div>

            <div>
              <button class="car-search__btn w-100" type="submit">RECHERCHER</button>
            </div>
          </form>
        </div>

        <!-- TAB 2: NEW -->
        <div class="tab-pane fade" id="content-newCars" role="tabpanel" aria-labelledby="tab-newCars">
          <form class="car-search__form d-flex flex-wrap align-items-end gap-3" method="GET" action="cars.php">
            <input type="hidden" name="condition" value="new">

            <div class="flex-grow-1">
              <label class="car-search__label" for="makeAll">01 Marque</label>
<select name="make" class="form-select">
  <option selected disabled>Sélectionnez la marque</option>
  <?php foreach ($makes as $make): ?>
    <option value="<?= $make['id'] ?>" <?= ($_GET['make'] ?? '') == $make['id'] ? 'selected' : '' ?>>
      <?= htmlspecialchars($make['name']) ?>
    </option>
  <?php endforeach; ?>
</select>
            </div>

            <div class="flex-grow-1">
              <label class="car-search__label" for="modelAll">02 Carburant</label>
<select name="fuel" class="form-select">
  <option selected disabled>Type de carburant</option>
  <?php foreach ($fuels as $fuel): ?>
    <option value="<?= $fuel['id'] ?>" <?= ($_GET['fuel'] ?? '') == $fuel['id'] ? 'selected' : '' ?>>
      <?= htmlspecialchars($fuel['name']) ?>
    </option>
  <?php endforeach; ?>
</select>
            </div>

            <div class="flex-grow-1">
              <label class="car-search__label" for="fuelAll">03 Transmission</label>
<select name="transmission" class="form-select">
  <option selected disabled>Type de transmission</option>
  <?php foreach ($transmissions as $trans): ?>
    <option value="<?= $trans['id'] ?>" <?= ($_GET['transmission'] ?? '') == $trans['id'] ? 'selected' : '' ?>>
      <?= htmlspecialchars($trans['name']) ?>
    </option>
  <?php endforeach; ?>
</select>
            </div>

            <div>
              <button class="car-search__btn w-100" type="submit">Rechercher</button>
            </div>
          </form>
        </div>

        <!-- TAB 3: USED -->
        <div class="tab-pane fade" id="content-usedCars" role="tabpanel" aria-labelledby="tab-usedCars">
          <form class="car-search__form d-flex flex-wrap align-items-end gap-3" method="GET" action="cars.php">
            <input type="hidden" name="condition" value="preowned">

            <div class="flex-grow-1">
              <label class="car-search__label" for="makeAll">01 Marque</label>
<select name="make" class="form-select">
  <option selected disabled>Sélectionnez la marque</option>
  <?php foreach ($makes as $make): ?>
    <option value="<?= $make['id'] ?>" <?= ($_GET['make'] ?? '') == $make['id'] ? 'selected' : '' ?>>
      <?= htmlspecialchars($make['name']) ?>
    </option>
  <?php endforeach; ?>
</select>
            </div>

            <div class="flex-grow-1">
              <label class="car-search__label" for="modelAll">02 Carburant</label>
<select name="fuel" class="form-select">
  <option selected disabled>Type de carburant</option>
  <?php foreach ($fuels as $fuel): ?>
    <option value="<?= $fuel['id'] ?>" <?= ($_GET['fuel'] ?? '') == $fuel['id'] ? 'selected' : '' ?>>
      <?= htmlspecialchars($fuel['name']) ?>
    </option>
  <?php endforeach; ?>
</select>
            </div>

            <div class="flex-grow-1">
              <label class="car-search__label" for="fuelAll">03 Transmission</label>
<select name="transmission" class="form-select">
  <option selected disabled>Type de transmission</option>
  <?php foreach ($transmissions as $trans): ?>
    <option value="<?= $trans['id'] ?>" <?= ($_GET['transmission'] ?? '') == $trans['id'] ? 'selected' : '' ?>>
      <?= htmlspecialchars($trans['name']) ?>
    </option>
  <?php endforeach; ?>
</select>
            </div>

            <div>
              <button class="car-search__btn w-100" type="submit">Rechercher</button>
            </div>
          </form>
        </div>

      </div> <!-- /.tab-content -->
    </div> <!-- /.car-search -->
  </div> <!-- /.container -->
</div>
<!-- Car Search end -->
