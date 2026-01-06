<?php
    // Fetch site_info from the database
    $adresse = $heures_ventes = $telephone = '';
    $social_links = [];

    $result = $pdo->query("SELECT name, value, icon, is_visible, category FROM settings");

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $name = $row['name'];
    $value = htmlspecialchars($row['value'] ?? '', ENT_QUOTES, 'UTF-8');
    $icon = $row['icon'] ?? '';
    $visible = (int)$row['is_visible'];
    $category = $row['category'];

    // Address
    if ($name === 'address')  $adresse = $value;
    elseif ($name === 'hours_week') $heures_semaine = $value;
    elseif ($name === 'phone') $telephone = $value;

    // Social media
    if ($category === 'social media' && !empty($icon) && $visible) {
        $social_links[] = [
            'url' => $value,
            'icon' => $icon

        ];
    }
 }
?>

<!-- Topbar Start -->
<div class="container-fluid bg-dark p-0">
    <div class="row gx-0 d-none d-lg-flex">
        <!-- site info Start -->
        <div class="col-lg-7 px-5 text-start">
            <div class="h-100 d-inline-flex align-items-center me-4">
                <small class="fa fa-map-marker-alt text-primary me-2"></small>
                <small><?= $adresse ?: 'Adresse inconnue' ?></small>
            </div>
            <div class="h-100 d-inline-flex align-items-center me-4">
                <small class="far fa-clock text-primary me-2"></small>
                <small><?= $heures_semaine ?: 'Horaires non définis' ?></small>
            </div>
            <div class="h-100 d-inline-flex align-items-center me-4">
                <small class="fa fa-phone-alt text-primary me-2"></small>
                <small><?= $telephone ?: '+000000000' ?></small>
            </div>
        </div><!-- site info End -->
        


        <!-- Social Media links Start -->
        <div class="col-lg-5 px-5 text-end">
            <div class="h-100 d-inline-flex align-items-center mx-n2">
             <?php
             $count = 0;
             $total = count($social_links);

             foreach ($social_links as $link):
             if ($count >= 4) break;  // stop after 4 icons

             $class = "social-btn";

             if ($count == 3 || $count == $total - 1) {
                $class .= " no-border";
             }
            ?>

            <a class="<?= $class ?>" href="<?= $link['url'] ?>" target="_blank">
            <i class="<?= $link['icon'] ?>"></i>
            </a>

             <?php $count++; endforeach;?>

            </div>
        </div><!-- Social Media links End -->
    </div>
</div><!-- Topbar End -->



<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top p-0">
    <a href="index.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5 border-end">
        <div class="nav-logo-img"></div>
        <h3 class="m-0 text-primary nav-logo-text">SUPERCAR</h3>
    </a>
    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <a href="index.php" class="nav-item nav-link <?php if ($page === 'index') echo 'active'; ?>" id="home-link">Accueil</a>
                            <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle <?php if ($page === 'cars') echo 'active'; ?>" data-bs-toggle="dropdown">Voitures</a>
                    <div class="dropdown-menu m-0">
                        <a href="cars.php?condition=new" class="dropdown-item">Neuf</a>
                        <a href="cars.php?condition=preowned" class="dropdown-item">Occasion</a>
                    </div>
            </div>
            <a href="testdrive.php" class="nav-item nav-link <?php if ($page === 'testdrive') echo 'active'; ?>">Essai</a>
            <a href="index.php#services" class="nav-item nav-link <?php if ($page === 'service') echo 'active'; ?>" id="services-link">Services</a>
            <a href="contact.php" class="nav-item nav-link <?php if ($page === 'contact') echo 'active'; ?>">Contact</a>

<div class="nav-item dropdown user-dropdown">
  <a href="#" class="nav-link dropdown-toggle blank" data-bs-toggle="dropdown">
    <i class="fa-light fa-circle-user" style="font-size:1.46em;"></i>
  </a>
  <div class="dropdown-menu dropdown-menu-end">
    <?php if (isset($_SESSION['customer_id'])): ?>
         <span class="dropdown-item">Bonjour, <?php echo htmlspecialchars($_SESSION['customer_name']); ?></span>
      <!-- <a href="profile.php" class="dropdown-item">Mon profil</a> -->
      <a href="controllers/logout.php" class="dropdown-item">Se déconnecter</a>
    <?php else: ?>
      <a href="login.php" class="dropdown-item">Se connecter</a>
      <a href="signup.php" class="dropdown-item">S’inscrire</a>
    <?php endif; ?>
  </div>
</div>



            <!-- <a href="contact.php" class="nav-item nav-link"><i class="fa-light fa-magnifying-glass"  style="font-size:1.46em;"></i></a> -->

        </div>
    </div>
</nav><!-- Navbar End -->