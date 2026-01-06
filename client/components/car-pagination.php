<?php
$totalPages = ceil($total / $limit);

if ($totalPages > 1): ?>
  <nav class="pagination-wrapper mt-5" aria-label="Page navigation">
    <ul class="pagination justify-content-center custom-pagination">

      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= ($i === $page ? 'active' : '') ?>">
          <a class="page-link" 
             href="?page=<?= $i ?>&condition=<?= urlencode($condition) ?>">
             <?= $i ?>
          </a>
        </li>
      <?php endfor; ?>

    </ul>
  </nav>
<?php endif; ?>
