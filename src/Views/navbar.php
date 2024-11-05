<?php

namespace App\Views;

/**
 * @var string $area
 * @var TableRow[] $tableRows
 */
?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php?area=dataset&action=showTable">Home</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="index.php?area=dataset&action=showForm">
            <i class="fa-solid fa-plus"></i> Tabelle erstellen
          </a>
        </li>
      </ul>
      <!-- Fulltext search -->
      <?php if ($area === 'dynamicTable' && isset($tableRows)) : ?>
        <form class="d-flex" action="index.php" method="POST">
          <input class="form-control me-2" type="search" placeholder="Search" name="searchTerm" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
          <!-- Set action in hidden field -->
          <input type="hidden" name="action" value="search">
          <!-- Set area in hidden field -->
          <input type="hidden" name="area" value="<?= $area; ?>">
          <!-- Set table name in hidden field -->
          <input type="hidden" name="tableName" value="<?= isset($tableRows) ? $tableRows[0]->getName() : ''; ?>">
          <!-- Reset button -->
          <button class="btn btn-outline-secondary ms-2" type="submit">Reset</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</nav>
