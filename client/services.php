<?php
require_once __DIR__ . '/php/config.php';

$page = 'services';
$page_title = 'Services';
$page_bg = '../assets/site/services.webp';

$breadcrumbs = [
  ['label' => 'Accueil', 'url' => 'index.php'],
  ['label' => 'Services']
];
?>

<!DOCTYPE html>
<html lang="fr">

<?php
include('components/header.php');
include('components/navbar.php');
include('components/title.php');

// Fetch active services from database
try {
    $stmt = $pdo->prepare("
        SELECT id, service_id, name, description, icon, image 
        FROM services 
        WHERE is_active = 1 
        ORDER BY id ASC
    ");
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching services: " . $e->getMessage());
    $services = [];
}

?>


    
    
    <!-- Reliable Services Section - From Autixir -->
    <section class="about-services">
        <div class="container">
            <div class="row">
                <div class="col-lg-5  align-self-center d-flex justify-content-end">
                    <div class="about-services-img-wrap ltn__img-shape-left about-img-left">
                        <img src="../assets/services/00.webp" style="height: 380px; width: auto;">
                    </div>
                </div>
                <div class="col-lg-7 align-self-center">
                    <div class="about-services-info-wrap">
                        <div class="section-title-area">
                            <?php $years = date('Y') - 2009; ?>
                            <h2><?php echo $years; ?> ans d'expertise automobile</h2>
                            <div class="title-line"></div>
                            <p class="about-description">Depuis 2009, SuperCar assure bien plus que la vente de voitures neuves importées de partout dans le monde. Notre atelier automobile propose entretien et réparation pour tous types de véhicules.</p>
                        </div>
                        <div class="about-services-info-wrap-inner">
                            <p class="about-description">Notre équipe traite chaque voiture comme si c'était la leur – enfin, comme si c'était la leur ET qu'ils devaient la rendre à leur belle-mère exigeante. Nos techniciens utilisent des équipements de diagnostic modernes pour identifier et résoudre vos problèmes mécaniques avec précision. Maintenance préventive, réparations complexes ou simple vidange : chaque intervention est traitée avec rigueur et professionnalisme.</p>
                            <div class="list-item-with-icon">
                                <ul>
                                    <li title="Des mécaniciens qui savent faire la différence entre un turbo et une turbulence">Expertise technique</li>
                                    <li title="Parce que réparer une Porsche avec un marteau, c'est 2005">Équipement de pointe</li>
                                    <li title="Si ça casse après notre passage, c'est qu'un météorite l'a frappée">Garantie solide</li>
                                    <li title="On vous explique tout. Oui, même les trucs ennuyeux">Transparence totale</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Services We Offer Section - From Revus -->
    <section class="services">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <ul class="services-nav" id="servicesTab" role="tablist">
                        <?php 
                        $counter = 1;
                        foreach ($services as $service) {
                            // Format number: 01, 02, 03, etc.
                            if ($counter < 10) {
                                $number = '0' . $counter;
                            } else {
                                $number = $counter;
                            }
                            
                            // First service is active
                            if ($counter == 1) {
                                $active = 'active';
                            } else {
                                $active = '';
                            }
                        ?>
                        <li class="services-nav__item">
                            <a class="services-nav__link <?php echo $active; ?>" 
                               data-bs-toggle="tab" 
                               href="#service<?php echo $service['id']; ?>">
                                <span class="services-nav__number"><?php echo $number; ?></span>
                                <img class="service-icon" 
                                     src="../assets/icons/<?php echo $service['icon']; ?>" 
                                     alt="<?php echo $service['name']; ?>">
                                <span class="services-nav__info"><?php echo $service['name']; ?></span>
                            </a>
                        </li>
                        <?php 
                            $counter++;
                        } 
                        ?>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="services__main">
                        <h2 class="ui-title-inner">Nos Services</h2>
                        <div class="title-line"></div>
                        <div class="services-content tab-content">
                            <?php 
                            $counter = 1;
                            foreach ($services as $service) {
                                // First service is active
                                if ($counter == 1) {
                                    $active = 'show active';
                                } else {
                                    $active = '';
                                }
                            ?>
                            <div class="services-content__item tab-pane fade <?php echo $active; ?>" 
                                 id="service<?php echo $service['id']; ?>">
                                <img class="img-fluid" 
                                     src="../assets/services/<?php echo $service['image']; ?>" 
                                     alt="<?php echo $service['name']; ?>" />
                                <h3 class="services-content__title"><?php echo $service['name']; ?></h3>
                                <p><?php echo $service['description']; ?></p>
                            </div>
                            <?php 
                                $counter++;
                            } 
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Bootstrap JS -->
    
    <script>
        // Bootstrap 5 handles the tab switching automatically with data-bs-toggle="tab"
        // This additional script adds the active class styling
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('.services-nav__link');
            
            tabLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Remove active class from all links
                    tabLinks.forEach(l => l.classList.remove('active'));
                    // Add active class to clicked link
                    this.classList.add('active');
                });
            });
        });
    </script>

<?php include 'components/footer.php'; ?>

</html>
