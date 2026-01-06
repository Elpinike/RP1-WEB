<?php
session_start();
require_once __DIR__ . '/php/config.php';

// Set page variable for navbar
  $page = 'Details';
  $page_title = 'Détails du véhicule';
  $page_bg = '../assets/site/details.webp';

    $breadcrumbs = [
  ['label' => 'Accueil', 'url' => 'index.php'],
  ['label' => 'Voitures', 'url'=> 'cars.php'],
  ['label' => 'Détails'],
];

// Get car_id from URL
$car_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($car_id <= 0) {
    header('Location: index.php');
    exit;
}



// Fetch car details with all related information
$query = "SELECT 
    c.id,
    c.car_id,
    c.model,
    c.variant,
    c.color,
    c.year,
    c.engine,
    c.seats_no,
    c.mileage,
    c.price,
    c.discount_pct,
    c.description,
    c.is_new,
    m.name AS make_name,
    f.name AS fuel_name,
    t.name AS transmission_name,
    typ.name AS type_name,
    cond.name AS condition_name,
    stat.name AS status_name
FROM cars c
LEFT JOIN car_make m ON c.make_id = m.id
LEFT JOIN car_fuel f ON c.fuel_id = f.id
LEFT JOIN car_transmission t ON c.transmission_id = t.id
LEFT JOIN car_type typ ON c.type_id = typ.id
LEFT JOIN car_condition cond ON c.condition_id = cond.id
LEFT JOIN car_status stat ON c.status_id = stat.id
WHERE c.id = ? AND c.is_visible = 1";

$stmt = $pdo->prepare($query);
$stmt->execute([$car_id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$car) {
    header('Location: index.php');
    exit;
}

// Fetch car images
$imgQuery = $pdo->prepare("SELECT path FROM car_img WHERE car_id = ? ORDER BY is_main DESC");
$imgQuery->execute([$car_id]);
$images = $imgQuery->fetchAll(PDO::FETCH_COLUMN);

// Determine image folder based on condition
$imagePath = "../assets/cars/{$car['car_id']}/";

// Calculate discounted price if applicable
$finalPrice = $car['price'];
if ($car['discount_pct'] && $car['discount_pct'] > 0) {
    $finalPrice = $car['price'] - ($car['price'] * $car['discount_pct'] / 100);
}

// Format numbers for display
$formattedPrice = number_format($finalPrice, 0, ',', ' ');
$formattedOriginalPrice = number_format($car['price'], 0, ',', ' ');
$formattedMileage = number_format($car['mileage'], 0, ',', ' ');
?>
<!DOCTYPE html>
<html lang="fr">



    <?php
include('components/header.php');
include('components/navbar.php');
include('components/title.php');
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Car Header -->
            <div class="car-header">
                <!-- Info Badges: Sale, Make, Model, Body -->
                <div class="car-info-badges">
                    <?php if ($car['discount_pct'] !== null && $car['discount_pct'] > 0): ?>
                        <span class="sale-badge"><i class="fas fa-tag"></i> -<?php echo $car['discount_pct']; ?>%</span>
                    <?php endif; ?>
                    
                    <div class="info-badge">
                        <span class="info-badge-label">Marque:</span>
                        <span class="info-badge-value"><?php echo htmlspecialchars($car['make_name']); ?></span>
                    </div>
                    
                    <div class="info-badge">
                        <span class="info-badge-label">Modèle:</span>
                        <span class="info-badge-value"><?php echo htmlspecialchars($car['model']); ?></span>
                    </div>
                    
                    <div class="info-badge">
                        <span class="info-badge-label">Carrosserie:</span>
                        <span class="info-badge-value"><?php echo htmlspecialchars($car['type_name']); ?></span>
                    </div>
                </div>
                
                <h1 class="car-title">
                    <?php 
                    echo htmlspecialchars($car['make_name']) . ' ' . 
                         htmlspecialchars($car['model']);
                    if ($car['variant']) {
                        echo ' ' . htmlspecialchars($car['variant']);
                    }
                    ?>
                </h1>
            </div>

            <!-- Image Slider -->
            <div class="car-slider">
                <?php if (!empty($images)): ?>
                    <?php $imageCount = count($images); ?>
                    
                    <!-- Main Slider -->
                    <div class="slider-for">
                        <?php foreach ($images as $image): ?>
                            <div>
                                <img src="<?php echo htmlspecialchars($imagePath . $image); ?>" 
                                     alt="<?php echo htmlspecialchars($car['model']); ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Thumbnail Navigation (only show if more than 1 image) -->
                    <?php if ($imageCount > 1): ?>
                    <div class="slider-nav">
                        <?php foreach ($images as $image): ?>
                            <div>
                                <img src="<?php echo htmlspecialchars($imagePath . $image); ?>" 
                                     alt="<?php echo htmlspecialchars($car['model']); ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Aucune image disponible pour ce véhicule.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Specifications -->
            <h2 class="specs-title">Caractéristiques</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="spec-row"><div class="spec-icon"><i class="fa-solid fa-calendar"></i></div>
                        <div class="spec-label">Année</div>
                        <div class="spec-value"><?php echo htmlspecialchars($car['year']); ?></div>
                    </div>
                    <div class="spec-row"><div class="spec-icon"><i class="fa-solid fa-brush"></i></div>
                        <div class="spec-label">Couleur</div>
                        <div class="spec-value"><?php echo htmlspecialchars($car['color'] ?? 'Non spécifiée'); ?></div>
                    </div>
                    <div class="spec-row"><div class="spec-icon"><i class="ic flaticon2-fuel-station-pump"></i></div>
                        <div class="spec-label">Type de carburant</div>
                        <div class="spec-value"><?php echo htmlspecialchars($car['fuel_name']); ?></div>
                    </div>
                    <?php if ($car['engine']): ?>
                    <div class="spec-row"><div class="spec-icon"><i class="fa-solid fa-engine"></i></div>
                        <div class="spec-label">Moteur</div>
                        <div class="spec-value"><?php echo htmlspecialchars($car['engine']); ?> cc</div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <div class="spec-row"><div class="spec-icon"><i class="fa-solid fa-car"></i></div>
                        <div class="spec-label">État</div>
                        <div class="spec-value">
                            <?php echo htmlspecialchars($car['condition_name']); ?>
                        </div>
                    </div>
                    <div class="spec-row"><div class="spec-icon"><i class="ic flaticon-speedometer"></i></div>
                        <div class="spec-label"> Kilométrage</div>
                        <div class="spec-value"><?php echo $formattedMileage; ?> km</div>
                    </div>
                    <div class="spec-row"><div class="spec-icon"><i class="ic flaticon-gearshift"></i></div>
                        <div class="spec-label">Transmission</div>
                        <div class="spec-value"><?php echo htmlspecialchars($car['transmission_name']); ?></div>
                    </div>
                    <div class="spec-row"><div class="spec-icon"><i class="fa-solid fa-circle-info"></i></div>
                        <div class="spec-label">
                             Statut</div>
                        <div class="spec-value">
                            <?php 
                            $statusClass = $car['status_name'] === 'Disponible' ? 'success' : 'danger';
                            ?>
                            <span class="badge bg-<?php echo $statusClass; ?>">
                                <?php echo htmlspecialchars($car['status_name']); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <ul class="nav car-details-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#overview">Aperçu</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#features">Tab</a>
                </li> -->
            </ul>

            <div class="car-details-content tab-content">
                <div class="tab-pane fade show active" id="overview">
                    <?php if ($car['description']): ?>
                        <p><?php echo nl2br(htmlspecialchars($car['description'])); ?></p>
                    <?php else: ?>
                        <p>
                            <strong><?php echo htmlspecialchars($car['make_name'] . ' ' . $car['model']); ?></strong> 
                            - Un véhicule de qualité disponible chez SuperCar.
                        </p>
                        <p>
                            Ce <?php echo htmlspecialchars($car['type_name']); ?> de 
                            <?php echo htmlspecialchars($car['year']); ?> offre une excellente combinaison 
                            de performance, de confort et de fiabilité.
                        </p>
                    <?php endif; ?>
                </div>
                <div class="tab-pane fade" id="features">
                    <!-- content -->
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="col-lg-3">
            <!-- Price Box -->
            <div class="price-box">
                <div class="price-header">
                    <span class="price-main">Rs <?php echo $formattedPrice; ?></span>
                </div>
                <?php if ($car['discount_pct'] && $car['discount_pct'] > 0): ?>
                <div class="price-body">
                    
                        <div class="text-center">Prix initial : Rs <?php echo $formattedOriginalPrice; ?></div>
                    

                </div>
                <?php endif; ?>
            </div>

            <!-- Contact CTA -->
            <div class="car-card mb-3">
                <div class="car-card-body text-center">
                    
<div class="car-card-title">Intéressé par ce véhicule?</div>

                    <div class="car-card-contact">
    <div class="car-card-contact-icon">
        <i class="fas fa-phone"></i>
    </div>
    <div class="car-card-contact-text">
        <div class="car-card-contact-label d-flex justify-content-start">Contactez nous au</div>
        <a href="tel:+2302037961" class="car-card-contact-phone">
            +230 454 8950
        </a>
    </div>
</div>

                    <a href="testdrive-quick.php?car_id=<?php echo $car_id; ?>" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-car"></i> Réserver un essai
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include('components/footer.php'); ?>



</body>
</html>
