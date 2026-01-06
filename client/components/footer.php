<?php
// Initialize empty variables for site info
$address = $email = $phone = '';
$hours_week = $hours_sat = $hours_holiday = '';
$service_week = $service_sat = $service_note = '';

$social_links = [];

// ✅ Fetch updated fields from the new structure
$result = $pdo->query("SELECT name, value, icon, is_visible, category FROM settings");

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

    $name      = $row['name'];
    $value     = htmlspecialchars($row['value']);
$icon = htmlspecialchars($row['icon'] ?? '', ENT_QUOTES, 'UTF-8');
    $visible   = (int)$row['is_visible'];
    $category  = $row['category'];

    // ✅ Assign values depending on the name
    switch ($name) {
        case 'address':          $address = $value; break;
        case 'email':            $email = $value; break;
        case 'phone':            $phone = $value; break;

        case 'hours_week':       $hours_week = $value; break;
        case 'hours_sat':        $hours_sat = $value; break;
        case 'hours_holiday':    $hours_holiday = $value; break;

        case 'service_week':     $service_week = $value; break;
        case 'service_sat':      $service_sat = $value; break;
        case 'service_note':     $service_note = $value; break;
    }

    // ✅ Social Media (NEW LOGIC)
    if ($category === 'social media' && $visible && !empty($icon)) {
        $social_links[] = [
            'url'  => $value,
            'icon' => $icon
        ];
    }
}
?>


<!-- Footer Start -->
 <footer class="footer">
<div class="container-fluid text-body footer mt-0 pt-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container py-4">
        <div class="row g-5 custom-footer-row">

            <div class="col-lg-2 col-md-6 logo-footer">
                <div class="footer-logo-img d-flex align-items-center">
  <div class="footer-logo"></div>
  <h2 class="footer-logo-text m-0">SUPERCAR</h2>
</div>
                <p>Pas besoin de réinventer la roue pour rouler mieux
</p>
                <div class="d-flex pt-2">
                    <?php
                    $count = 0;
                    foreach ($social_links as $link) {
                        if ($count >= 4) break; // Limit to 4 buttons
                        $url = htmlspecialchars($link['url']);
                        $icon = htmlspecialchars($link['icon']);
                        echo '<a class="btn btn-social me-2" href="' . $url . '" target="_blank"><i class="' . $icon . '"></i></a>';
                        $count++;
                    }
                    ?>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 address">
                <h5 class="footer-section__title">Contact</h5>
                <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i><?= $address ?></p>
                <p class="mb-2"><i class="fa fa-phone-alt me-3"></i><?= $phone ?></p>
                <p class="mb-2"><i class="fa fa-envelope me-3"></i><?= $email ?></p>
            </div>

            <div class="col-lg-3 col-md-6 links">
                <h5 class="footer-section__title">Liens rapides</h5>
                <a class="footer-link" href="cars.php">Nos Voitures</a>
                <a class="footer-link" href="testdrive.php">Demande d'essai</a>
                <a class="footer-link" href="index.php#services">Nos Services</a>
                <!-- <a class="btn btn-link" href="site_info.php">site_infoez-nous</a> -->
                <a class="footer-link" href="#" data-bs-toggle="modal" data-bs-target="#modalMentions">Mentions Légales</a>
            </div>

            <!-- Column: Nos Horaires -->
            <div class="col-lg-4 col-md-6 hours">
                <h5 class="footer-section__title">Nos Horaires</h5>

                <!-- Row 1: Sales + Service Hours side by side -->
                <div class="row mb-2">
                    <div class="col-6">
                        <p class="footer-hours__title">Ouverture</p>
                        <p class="mb-1"><?= $hours_week ?></p>
                        <p class="mb-1"><?= $hours_sat ?></p>
                        <p class="fst-italic mb-1"><?= $hours_holiday ?></p>
                    </div>
                    <div class="col-6">
                        <p class="footer-hours__title">Service</p>
                        <p class="mb-1"><?= $service_week ?></p>
                        <p class="mb-1"><?= $service_sat ?></p>
                    </div>
                </div>

                <!-- Row 2: Service Note -->
                <div class="row">
                    <div class="col-12">
                        <p class="fst-italic mb-0"><?= $service_note ?></p>
                    </div>
                </div> <!-- <div class="row"> -->
            </div>

        </div> <!-- end row -->

        <!-- Footer Bottom -->
        <div class="container mt-4">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; 2025 <a href="index.php">SuperCar</a>, Tous droits réservés.
                    </div>
                    <!-- <div class="col-md-6 text-center text-md-end">Link Text<a href="https://">Link</a> -->
                </div>
            </div>
        </div>
    </div>
</div>
                </footer>
<!-- Footer End -->

<!-- Back to Top -->
<a href="#" class="btn-top back-to-top"><i class="fa-solid fa-chevron-up"></i></a>







<!-- Modal Mentions légales -->
<div class="modal fade" id="modalMentions" tabindex="-1" aria-labelledby="modalMentionsLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalMentionsLabel">Mentions légales et Politique de confidentialité</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <h6>Éditeur du site</h6>
        <p>
            Projet pédagogique réalisé dans le cadre du BTS SIO – SLAM.<br>
            Entreprise fictive : SuperCar<br>
            Adresse : Rue des Guibies, Pailles, Île Maurice<br>
            Responsable de publication : Claude Morel
        </p>

        <h6>Hébergement</h6>
        <p>
            Alwaysdata – 62 rue Tiquetonne, 75002 Paris, France<br>
            <a href="https://www.alwaysdata.com/fr/" target="_blank">www.alwaysdata.com</a>
        </p>

        <h6>Propriété intellectuelle</h6>
        <p>
            Contenus protégés par le droit d’auteur. Images utilisées : libres de droits ou fournies à titre pédagogique.
        </p>

        <h6>Données personnelles</h6>
        <p>
            Les données collectées via les formulaires sont utilisées uniquement pour répondre aux demandes des utilisateurs.
            Conformément au RGPD, vous pouvez exercer vos droits à : <a href="mailto:contact@supercar.mu">contact@supercar.mu</a>
        </p>

        <h6>Cookies</h6>
        <p>
            Aucun cookie publicitaire. Seuls des cookies techniques peuvent être utilisés pour le bon fonctionnement du site.
        </p>

        <h6>Limitation de responsabilité</h6>
        <p>
            Ce site est fictif et réalisé uniquement à des fins éducatives dans le cadre du BTS SIO – SLAM.
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>












<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-1.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>


<script src="lib/wow/wow.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/slick/slick.js"></script>
<script src="lib/slick/slick.min.js"></script>
<script src="lib/slick/slick-init.js"></script>
<script src="js/main.js"></script>
<script src="js/activelink.js"></script>


</body>
