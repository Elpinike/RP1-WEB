<?php
require_once __DIR__ . '/php/config.php';

$page = 'login';
$page_title = 'Connexion';
$hide_title = true;

$breadcrumbs = [
  ['label' => 'Accueil', 'url' => 'index.php'],
  ['label' => 'Connexion']
];

// Get any flash errors from failed login
$errors = $_SESSION['login_errors'] ?? [];
$old_email = $_SESSION['old_email'] ?? '';
unset($_SESSION['login_errors'], $_SESSION['old_email']);
?>
<!DOCTYPE html>
<html lang="fr">

<?php
include('components/header.php');
include('components/navbar.php');
include('components/title.php');
?>

<!-- Login Section -->
<section class="auth-section py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7 col-sm-10">

        <div class="auth-box">
          <!-- Header -->
          <div class="auth-header text-center mb-4">
            <h2 class="auth-title">Accédez à votre compte</h2>
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

          <!-- Login Form -->
          <form method="post" action="controllers/logincontroller.php" class="form-sc">

            <!-- Email -->
            <div class="form-group">
              <div class="input-icon-wrapper">
                <i class="fa-light fa-user input-icon"></i>
                <input
                  type="email"
                  name="email"
                  class="form-control has-icon"
                  placeholder="Entrez votre e-mail"
                  value="<?= htmlspecialchars($old_email) ?>"
                  maxlength="100"
                  autocomplete="email"
                  required>
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
                    id="loginPass"
                    class="form-control has-icon"
                    placeholder="Entrez votre mot de passe"
                    maxlength="255"
                    autocomplete="current-password"
                    required>
                </div>
                <i class="fa-solid fa-eye toggle-password" data-target="loginPass"></i>
              </div>
            </div>

            <!-- Remember me + Forgot -->
            <!-- <div class="d-flex justify-content-between align-items-center mb-4">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                <label class="form-check-label" for="rememberMe">Se souvenir de moi</label>
              </div>
              <a href="forgot.php" class="auth-link">Mot de passe oublié?</a>
            </div> -->

            <!-- Submit -->
            <button type="submit" class="btn btn-primary w-100">
              Se connecter
            </button>

          </form>

          <!-- Signup link -->
          <div class="auth-footer text-center mt-4">
            <p class="mb-0">
              Pas encore de compte?
              <a href="signup.php" class="auth-link fw-bold">Créer un compte</a>
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