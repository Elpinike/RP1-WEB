<?php
require_once __DIR__ . '/php/config.php';

$page = 'signup';
$page_title = 'Inscription';
$hide_title = true;

$breadcrumbs = [
  ['label' => 'Accueil', 'url' => 'index.php'],
  ['label' => 'Inscription']
];

// Get any flash errors from failed signup
$errors = $_SESSION['signup_errors'] ?? [];
$old = $_SESSION['signup_old'] ?? [];
unset($_SESSION['signup_errors'], $_SESSION['signup_old']);
?>
<!DOCTYPE html>
<html lang="fr">

<?php
include('components/header.php');
include('components/navbar.php');
include('components/title.php');
?>

<!-- Signup Section -->
<section class="auth-section py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 col-sm-10">

        <div class="auth-box">
          <!-- Header -->
          <div class="auth-header text-center mb-4">
            <h2 class="auth-title">Créer un compte</h2>
            <div class="title-decor"></div>
          </div>

          <!-- Error messages -->
          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
              <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                  <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <!-- Signup Form -->
          <form id="signupForm" method="post" action="controllers/signupcontroller.php" class="form">

            <!-- Name row -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-icon-wrapper">
                    <i class="fa-light fa-user input-icon"></i>
                    <input
                      type="text"
                      name="first_name"
                      class="form-control has-icon"
                      placeholder="Entrez votre prénom"
                      value="<?= htmlspecialchars($old['first_name'] ?? '') ?>"
                      maxlength="50"
                      autocomplete="given-name"
                      required>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-icon-wrapper">
                    <i class="fa-light fa-user input-icon"></i>
                    <input
                      type="text"
                      name="last_name"
                      class="form-control has-icon"
                      placeholder="Entrez votre nom"
                      value="<?= htmlspecialchars($old['last_name'] ?? '') ?>"
                      maxlength="50"
                      autocomplete="family-name"
                      required>
                  </div>
                </div>
              </div>
            </div>

            <!-- Email -->
            <div class="form-group">
              <div class="input-icon-wrapper">
                <i class="fa-light fa-envelope input-icon"></i>
                <input
                  type="email"
                  name="email"
                  class="form-control has-icon"
                  placeholder="Entrez votre e-mail"
                  value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                  maxlength="100"
                  autocomplete="email"
                  required>
              </div>
            </div>

            <!-- Phone -->
            <div class="form-group">
              <div class="input-icon-wrapper">
                <i class="fa-light fa-phone input-icon"></i>
                <input
                  type="tel"
                  name="phone"
                  class="form-control has-icon"
                  placeholder="Entrez votre numéro de téléphone"
                  value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                  maxlength="20"
                  pattern="[0-9+\s\-]{7,20}"
                  title="Numéro de téléphone valide (7-20 caractères, chiffres uniquement)"
                  autocomplete="tel">
              </div>
            </div>

            <!-- Password -->
            <div class="form-group">
              <div class="password-wrapper">
                <div class="input-icon-wrapper">
                  <i class="fa-light fa-lock input-icon"></i>
                  <input
                    type="password"
                    name="password"
                    id="signupPass"
                    class="form-control has-icon"
                    placeholder="Créez un mot de passe"
                    minlength="8"
                    maxlength="255"
                    autocomplete="new-password"
                    required>
                </div>
                <i class="fa-solid fa-eye toggle-password" data-target="signupPass"></i>
              </div>
              <small class="form-text text-muted">Min. 8 caractères avec majuscule, minuscule et chiffre.</small>
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
              <div class="password-wrapper">
                <div class="input-icon-wrapper">
                  <i class="fa-light fa-lock input-icon"></i>
                  <input
                    type="password"
                    name="password_confirm"
                    id="signupPassConfirm"
                    class="form-control has-icon"
                    placeholder="Confirmez votre mot de passe"
                    minlength="8"
                    maxlength="255"
                    autocomplete="new-password"
                    required>
                </div>
                <i class="fa-solid fa-eye toggle-password" data-target="signupPassConfirm"></i>
              </div>
            </div>

            <!-- Terms checkbox -->
            <!-- <div class="form-check mb-4">
              <input type="checkbox" class="form-check-input" id="acceptTerms" name="terms" required>
              <label class="form-check-label" for="acceptTerms">
                J'accepte les <a href="terms.php" class="auth-link">conditions d'utilisation</a>
              </label>
            </div> -->

            <!-- Submit -->
            <button type="submit" class="btn btn-primary w-100">
              Créer mon compte
            </button>

          </form>

          <!-- Login link -->
          <div class="auth-footer text-center mt-4">
            <p class="mb-0">
              Déjà un compte?
              <a href="login.php" class="auth-link fw-bold">Se connecter</a>
            </p>
          </div>

        </div>

      </div>
    </div>
  </div>
</section>

<?php include 'components/footer.php'; ?>

<script>
  // Password visibility toggle
  document.querySelectorAll('.toggle-password').forEach(function(icon) {
    icon.addEventListener('click', function() {
      const targetId = this.getAttribute('data-target');
      const input = document.getElementById(targetId);

      if (input.type === 'password') {
        input.type = 'text';
        this.classList.remove('fa-eye');
        this.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        this.classList.remove('fa-eye-slash');
        this.classList.add('fa-eye');
      }
    });
  });
</script>

</html>