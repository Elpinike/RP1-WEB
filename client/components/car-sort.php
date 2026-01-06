      <div class="d-flex justify-content-between align-items-center mb-4">
        <?php
        $start = $offset + 1;
        $end = min($offset + $limit, $total);
        ?>
        <div class="text-dark">Showing results <strong><?= $start ?> to <?= $end ?></strong> of total <?= $total ?></div>
        <div class="d-flex align-items-center">
          <form method="GET" class="d-flex align-items-center">

          <?php if (!empty($_GET['condition'])): ?>
        <input type="hidden" name="condition" value="<?= htmlspecialchars($_GET['condition']) ?>">
    <?php endif; ?>

            <label for="sortSelect" class="me-2 mb-0">Sort by</label>
            <select id="sortSelect" name="sort" class="form-select w-auto sort-select" onchange="this.form.submit()">
              <option value="newest" <?= ($_GET['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Newest First</option>
              <option value="oldest" <?= ($_GET['sort'] ?? '') === 'oldest' ? 'selected' : '' ?>>Oldest First</option>
              <option value="price_asc" <?= ($_GET['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
              <option value="price_desc" <?= ($_GET['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
            </select>
          </form>
        </div>
      </div>