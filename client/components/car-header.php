<!-- Page Header Start -->
<section class="page-header text-white"
    <?php if (!empty($page_bg)): ?>
        style="background: url('<?= $page_bg ?>') center center / cover no-repeat;"
    <?php endif; ?>>

  

    <!-- Parallelogram decor -->
    <div class="page-header__decor"></div>
    
    <div class="page-header__content text-center">
        <div class="container">
            <h1 class="page-header__title"><?= $page_title ?? 'SuperCar' ?></h1>
            
            <?php if (!empty($breadcrumbs) && is_array($breadcrumbs)): ?>
                <nav aria-label="breadcrumb" class="page-header__breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <?php foreach ($breadcrumbs as $crumb): ?>
                            <?php if (!empty($crumb['url'])): ?>
                                <li class="breadcrumb-item"><a href="<?= $crumb['url'] ?>"><?= $crumb['label'] ?></a></li>
                            <?php else: ?>
                                <li class="breadcrumb-item active" aria-current="page"><?= $crumb['label'] ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                </nav>
            <?php endif; ?>
            
        </div>
    </div>
</section>
<!-- Page Header End -->