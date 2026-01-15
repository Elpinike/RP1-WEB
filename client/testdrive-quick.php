<?php
require_once __DIR__ . '/php/config.php';
require_once __DIR__ . '/php/testdrive-quick.php';

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['redirect_after_login'] = 'testdrive-quick.php?car_id=' . $car_id;
    header('Location: login.php');
    exit;
}

$page = 'testdrive';
$page_title = 'Réserver un essai';
$page_bg = '../assets/site/testdrive.webp';

$breadcrumbs = [
    ['label' => 'Accueil', 'url' => 'index.php'],
    ['label' => 'Détails', 'url' => 'details.php?id=' . $car_id],
    ['label' => 'Réserver un essai']
];

// Get any flash errors
$errors = $_SESSION['testdrive_errors'] ?? [];
$old = $_SESSION['testdrive_old'] ?? [];
unset($_SESSION['testdrive_errors'], $_SESSION['testdrive_old']);
?>

<!DOCTYPE html>
<html lang="fr">

<?php
include('components/header.php');
include('components/navbar.php');
include('components/title.php');
?>

<!-- Test Drive Section -->
<section class="auth-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9 col-sm-11">

                <div class="auth-box">
                    <!-- Header -->
                    <div class="auth-header text-center mb-4">
                        <h5 class="mb-3">Réserver un essai pour:</h5>
                        <h4 class="text-primary"><?= htmlspecialchars($car['make_name'] . ' ' . $car['model']) ?></h4>
                        <p class="text-muted small mt-2">Choisissez votre créneau préféré</p>
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

                    <!-- Test Drive Form -->
                    <form action="controllers/testdrivequickcontroller.php" method="POST" class="form">
                        
                        <!-- Hidden car_id -->
                        <input type="hidden" name="car_id" value="<?= $car['id'] ?>">

                        <!-- Condition (Read-only radio buttons) -->
                        <div class="form-group">
                            <label class="form-label fw-bold">Condition du véhicule</label>
                            <div class="condition-options d-flex gap-4 mt-2">
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="radio" 
                                        name="condition_display" 
                                        id="condNew" 
                                        <?= $car['condition_id'] == 1 ? 'checked' : '' ?>
                                        disabled>
                                    <label class="form-check-label text-muted" for="condNew">
                                        <i class="fa-light fa-sparkles me-1"></i> Neuve
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="radio" 
                                        name="condition_display" 
                                        id="condPre" 
                                        <?= $car['condition_id'] == 2 ? 'checked' : '' ?>
                                        disabled>
                                    <label class="form-check-label text-muted" for="condPre">
                                        <i class="fa-light fa-certificate me-1"></i> Occasion
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Make (Read-only) -->
                        <div class="form-group">
                            <label for="make" class="form-label">Marque</label>
                            <div class="input-icon-wrapper">
                                <i class="fa-light fa-car input-icon"></i>
                                <input 
                                    type="text" 
                                    id="make" 
                                    class="form-control has-icon" 
                                    value="<?= htmlspecialchars($car['make_name']) ?>"
                                    readonly>
                            </div>
                        </div>

                        <!-- Model (Read-only) -->
                        <div class="form-group">
                            <label for="model" class="form-label">Modèle</label>
                            <div class="input-icon-wrapper">
                                <i class="fa-light fa-car-side input-icon"></i>
                                <input 
                                    type="text" 
                                    id="model" 
                                    class="form-control has-icon" 
                                    value="<?= htmlspecialchars($car['model']) ?>"
                                    readonly>
                            </div>
                        </div>

                        <!-- Date row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date" class="form-label">Date préférée <span class="text-danger">*</span></label>
                                    <div class="input-icon-wrapper">
                                        <i class="fa-light fa-calendar input-icon"></i>
                                        <input 
                                            type="date" 
                                            name="date" 
                                            id="date" 
                                            class="form-control has-icon"
                                            value="<?= htmlspecialchars($old['date'] ?? '') ?>"
                                            min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="alternate_date" class="form-label">Date alternative</label>
                                    <div class="input-icon-wrapper">
                                        <i class="fa-light fa-calendar-plus input-icon"></i>
                                        <input 
                                            type="date" 
                                            name="alternate_date" 
                                            id="alternate_date" 
                                            class="form-control has-icon"
                                            value="<?= htmlspecialchars($old['alternate_date'] ?? '') ?>"
                                            min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Time Slot -->
                        <div class="form-group">
                            <label for="time_slot" class="form-label">Créneau horaire <span class="text-danger">*</span></label>
                            <div class="input-icon-wrapper">
                                <i class="fa-light fa-clock input-icon"></i>
                                <select name="time_slot" id="time_slot" class="form-control has-icon" required>
                                    <option value="">-- Choisir une plage horaire --</option>
                                    <?php foreach ($time_slots as $key => $label): ?>
                                        <option value="<?= htmlspecialchars($key) ?>" <?= ($old['time_slot'] ?? '') === $key ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($label) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="form-group">
                            <label for="message" class="form-label">Message (facultatif)</label>
                            <textarea 
                                name="message" 
                                id="message" 
                                class="form-control" 
                                rows="4" 
                                maxlength="1000"
                                placeholder="Précisez vos préférences ou questions..."><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa-light fa-paper-plane me-2"></i>Envoyer la demande
                        </button>

                    </form>

                </div>

            </div>
        </div>
    </div>
</section>

<?php include 'components/footer.php'; ?>

</html>
