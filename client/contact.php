<?php
require_once __DIR__ . '/php/config.php';

$page = 'contact';
$page_title = 'Contact';
$page_bg = '../assets/site/contact.webp';

$breadcrumbs = [
  ['label' => 'Accueil', 'url' => 'index.php'],
  ['label' => 'Contact']
];

// Pre-fill form if user is logged in
if (isset($_SESSION['customer_id'])) {
  $stmt = $pdo->prepare("SELECT name, surname, email, phone FROM customers WHERE id = ?");
  $stmt->execute([$_SESSION['customer_id']]);
  $user = $stmt->fetch();
} else {
  $user = [];
}

// Get any flash errors from failed submission
$errors = $_SESSION['contact_errors'] ?? [];
$old = $_SESSION['contact_old'] ?? [];
unset($_SESSION['contact_errors'], $_SESSION['contact_old']);

// Fetch contact settings from database
$stmt = $pdo->prepare("
    SELECT * 
    FROM settings 
    WHERE name IN ('address', 'phone', 'email', 'hours_week', 'hours_sat')
      AND is_visible = 1
");
$stmt->execute();

$settings = [];
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $settings[$row['name']] = $row;
}
?>
<!DOCTYPE html>
<html lang="fr">

<?php
include('components/header.php');
include('components/navbar.php');
include('components/title.php');
?>

<!-- Contact Section -->
<section class="contact-section py-5 px-5 mx-5">
  <div class="container ps-5 ml-5">
    <div class="row">

      <!-- Left Column: Contact Info -->
      <div class="col-lg-4 col-md-5 mb-4 mb-md-0">
        <div class="contact-info-box">
          <h2 class="ui-title-inner">Nos Coordonnées</h2>
          <div class="title-line"></div>

          <p class="contact-intro">
            Une question ? Un projet d'achat ? N'hésitez pas à nous contacter, notre équipe vous répondra dans les plus brefs délais.
          </p>

          <!-- Address -->
          <?php if (!empty($settings['address'])): ?>
            <div class="contact-item pb-3">
              <div class="contact-item-icon">
                <i class="fa-light fa-location-dot"></i>
              </div>
              <div class="contact-item-content">
                <span class="contact-item-label">ADRESSE :</span>
                <p><?= nl2br(htmlspecialchars($settings['address']['value'])) ?></p>
              </div>
            </div>
          <?php endif; ?>

          <!-- Hours -->
          <?php if (!empty($settings['hours_week'])): ?>
            <div class="contact-item">
              <div class="contact-item-icon">
                <i class="fa-light fa-clock"></i>
              </div>
              <div class="contact-item-content">
                <span class="contact-item-label">HORAIRES :</span>
                <p>
                  <?= htmlspecialchars($settings['hours_week']['value']) ?><br>
                  <?= htmlspecialchars($settings['hours_sat']['value'] ?? '') ?>
                </p>
              </div>
            </div>
          <?php endif; ?>

          <!-- Phone -->
          <?php if (!empty($settings['phone'])): ?>
            <div class="contact-item">
              <div class="contact-item-icon">
                <i class="fa-light fa-phone"></i>
              </div>
              <div class="contact-item-content">
                <span class="contact-item-label">TÉLÉPHONE :</span>
                <p><?= htmlspecialchars($settings['phone']['value']) ?></p>
              </div>
            </div>
          <?php endif; ?>

        </div>
      </div>

      <!-- Right Column: Contact Form -->
      <div class="col-lg-8 col-md-7">
        <div class="contact-form-box">
          <h2 class="ui-title-inner">Écrivez-nous</h2>
          <div class="title-line"></div>

          <!-- Error messages -->
          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger mb-4">
              <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                  <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form action="controllers/contactcontroller.php" method="POST" class="form">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <div class="input-icon-wrapper">
                    <i class="fa-light fa-user input-icon"></i>
                    <input
                      type="text"
                      name="first_name"
                      class="form-control has-icon"
                      placeholder="Prénom"
                      value="<?= htmlspecialchars($old['first_name'] ?? $user['name'] ?? '') ?>"
                      maxlength="50"
                      required>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <div class="input-icon-wrapper">
                    <i class="fa-light fa-user input-icon"></i>
                    <input
                      type="text"
                      name="last_name"
                      class="form-control has-icon"
                      placeholder="Nom"
                      value="<?= htmlspecialchars($old['last_name'] ?? $user['surname'] ?? '') ?>"
                      maxlength="50"
                      required>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <div class="input-icon-wrapper">
                    <i class="fa-light fa-at input-icon"></i>
                    <input
                      type="email"
                      name="email"
                      class="form-control has-icon"
                      placeholder="Email"
                      value="<?= htmlspecialchars($old['email'] ?? $user['email'] ?? '') ?>"
                      maxlength="100"
                      required>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <div class="input-icon-wrapper">
                    <i class="fa-light fa-phone input-icon"></i>
                    <input
                      type="tel"
                      name="phone"
                      class="form-control has-icon"
                      placeholder="Téléphone (optionnel)"
                      value="<?= htmlspecialchars($old['phone'] ?? $user['phone'] ?? '') ?>"
                      maxlength="20"
                      pattern="[0-9+\s\-]{7,20}"
                      title="Numéro de téléphone valide">
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group mb-3">
              <textarea
                name="message"
                class="form-control"
                placeholder="Votre Message"
                rows="6"
                maxlength="2000"
                required><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn-contact">ENVOYER</button>
          </form>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Map Section -->
<section class="map-section">
  <div class="map-container-full">
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3743.3006031008176!2d57.48672367523547!3d-20.246364181214368!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x217c5b1ef2170f63%3A0xd1a78020fc096491!2sMCCI%20BUSINESS%20SCHOOL%20(Mauritius%20Chamber%20of%20Commerce%20and%20Industry)!5e0!3m2!1sen!2smu!4v1767436895477!5m2!1sen!2smu"
      width="100%"
      height="450"
      style="border:0;"
      allowfullscreen=""
      loading="lazy"
      referrerpolicy="no-referrer-when-downgrade">
    </iframe>
  </div>
</section>

<?php include 'components/footer.php'; ?>

</html>
