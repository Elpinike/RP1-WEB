<?php
require_once __DIR__ . '/../php/dbconnect.php'; // adjust if needed

$stmt = $pdo->prepare("SELECT * FROM carousel WHERE is_visible = 1 ORDER BY created_at ASC");
$stmt->execute();
$slides = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!-- Carousel Start -->
<div class="container-fluid p-0 pb-5 wow fadeIn" data-wow-delay="0.1s">
  <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">

    <!-- Indicators -->
    <div class="carousel-indicators">
      <?php foreach ($slides as $index => $slide): ?>
        <button type="button"
                data-bs-target="#heroCarousel"
                data-bs-slide-to="<?= $index ?>"
                class="<?= $index === 0 ? 'active' : '' ?>"
                aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                aria-label="Slide <?= $index + 1 ?>"></button>
      <?php endforeach; ?>
    </div>

    <!-- Slides -->
    <div class="carousel-inner">
      <?php foreach ($slides as $index => $slide): ?>
        <div class="carousel-item position-relative <?= $index === 0 ? 'active' : '' ?>">
          <img class="img-fluid w-100" src="../assets/carousel/<?= htmlspecialchars($slide['image']) ?>" alt="Slide <?= $index + 1 ?>">
          
          
          <div class="carousel-overlay">
            <div class="container">
              <div class="row justify-content-start">
                <div class="col-10 col-lg-8">
                  <div class="carousel-text-box p-4 rounded-3">
                    <h1 class="display-2 text-white animated slideInDown"><?= htmlspecialchars($slide['title']) ?></h1>
                    <p class="fs-5 fw-medium text-white mb-4 pb-3">
                      <?= nl2br(htmlspecialchars($slide['content'])) ?>
                    </p>
                    <?php if (!empty($slide['link']) && $slide['link_visible']): ?>
                      <a href="<?= htmlspecialchars($slide['url']) ?>"
                         class="carousel-btn animated slideInLeft">
                        <?= htmlspecialchars($slide['link']) ?>
                      </a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      <?php endforeach; ?>
    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>

  </div>
</div>
<!-- Carousel End -->