<?php
require_once __DIR__ . '/php/config.php';

$page = 'Success';
$page_title = 'Success';
$hide_title = true;

$type = $_GET['type'] ?? 'default';

// Configure based on type
switch ($type) {
    case 'signup':
        $page_title = 'Inscription réussie';
        $icon = 'fa-user-check';
        $title = 'Inscription réussie !';
        $message = 'Félicitations ! Votre compte a été créé avec succès.';
        // $submessage = 'Veuillez vérifier votre e-mail pour activer votre compte avant de vous connecter.';
        $redirect = false;
        $button_text = 'Se connecter';
        $button_link = 'login.php';
        break;

    case 'login':
        $page_title = 'Connexion réussie';
        $icon = 'fa-circle-check';
        $title = 'Connexion réussie !';
        $message = 'Bon retour parmi nous.';
        $submessage = 'Redirection vers l\'accueil...';
        $redirect = true;
        $redirect_delay = 2;
        $redirect_url = 'index.php';
        $button_text = 'Aller à l\'accueil';
        $button_link = 'index.php';
        break;

    case 'testdrive':
        $page_title = 'Demande envoyée';
        $icon = 'fa-car';
        $title = 'Demande d\'essai envoyée !';
        $message = 'Nous avons bien reçu votre demande.';
        $submessage = 'Notre équipe vous contactera dans les plus brefs délais pour confirmer votre rendez-vous.';
        $redirect = true;
        $redirect_delay = 4;
        $redirect_url = 'index.php';
        $button_text = 'Retour à l\'accueil';
        $button_link = 'index.php';
        break;

    case 'contact':
        $page_title = 'Message envoyé';
        $icon = 'fa-envelope-circle-check';
        $title = 'Message envoyé !';
        $message = 'Merci de nous avoir contactés.';
        $submessage = 'Nous vous répondrons dans les plus brefs délais.';
        $redirect = true;
        $redirect_delay = 4;
        $redirect_url = 'index.php';
        $button_text = 'Retour à l\'accueil';
        $button_link = 'index.php';
        break;

    default:
        $page_title = 'Succès';
        $icon = 'fa-circle-check';
        $title = 'Action complétée !';
        $message = 'Votre action a été effectuée avec succès.';
        $submessage = '';
        $redirect = true;
        $redirect_delay = 3;
        $redirect_url = 'index.php';
        $button_text = 'Retour à l\'accueil';
        $button_link = 'index.php';
}

$page = 'success';
$hide_title = true;
?>
<!DOCTYPE html>
<html lang="fr">

<?php include('components/header.php'); ?>

<?php if ($redirect): ?>
<meta http-equiv="refresh" content="<?= $redirect_delay ?>;url=<?= $redirect_url ?>">
<?php endif; ?>

<?php
include('components/navbar.php');
include('components/title.php');
?>

<!-- Success Section -->
<section class="success-section py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 col-sm-10">

        <div class="success-box text-center">
          <!-- Icon -->
          <div>
              <video autoplay muted playsinline>
    <source src="../assets/site/success.webm" type="video/webm">
</video>
          </div>

          <!-- Title -->
          <h1 class="success-title"><?= htmlspecialchars($title) ?></h1>

          <!-- Message -->
          <p class="success-message"><?= htmlspecialchars($message) ?></p>

          <?php if (!empty($submessage)): ?>
            <p class="success-submessage"><?= htmlspecialchars($submessage) ?></p>
          <?php endif; ?>

          <!-- Countdown (if redirecting) -->
          <?php if ($redirect): ?>
            <p class="success-countdown">
              Redirection dans <span id="countdown"><?= $redirect_delay ?></span> secondes...
            </p>
          <?php endif; ?>

          <!-- Button -->
          <a href="<?= $button_link ?>" class="btn btn-primary mt-3">
            <?= htmlspecialchars($button_text) ?>
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

<?php if ($redirect): ?>
<script>
  // Countdown timer
  let seconds = <?= $redirect_delay ?>;
  const countdownEl = document.getElementById('countdown');
  
  const interval = setInterval(function() {
    seconds--;
    if (countdownEl) {
      countdownEl.textContent = seconds;
    }
    if (seconds <= 0) {
      clearInterval(interval);
    }
  }, 1000);
</script>
<?php endif; ?>

<?php include 'components/footer.php'; ?>

</html>
