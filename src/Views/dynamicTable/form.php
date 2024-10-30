<?php

namespace App\Views\dynamicTable;

use App\Models\TableRow;

/**
 * @var TableRow $tableRow
 * @var array $columns
 * @var string $action
 */

// Check if $tableRow exists and is an instance of TableRow (edit route)
$tableRowExists = isset($tableRow) && $tableRow instanceof TableRow;
?>

<div class="row justify-content-center">
  <div class="col-md-4">
    <h2 class="text-center mt-5">Datensatz</h2>
    <form action="index.php" method="POST">
      <!-- TableRow fields -->
        <?php if ($tableRowExists) : ?>
            <?php foreach ($tableRow->getAttributeValues() as $attributeName => $attributeValue) : ?>
              <div class="form-group">
                <label for="name"><?= $attributeName; ?></label>
                <input type="text" class="form-control"
                  value="<?= $attributeValue; ?>">
              </div>
            <?php endforeach; ?>
        <?php else : ?>
            <?php foreach ($columns as $column) : ?>
              <div class="form-group">
                <label for="name"><?= $column['COLUMN_NAME']; ?></label>
                <input type="text" class="form-control"
                  value="">
              </div>
            <?php endforeach; ?>
        <?php endif; ?>
      <!-- Set area in hidden field -->
      <input type="hidden" name="area" value="tableRow">

      <!-- Set action in hidden field -->
      <input type="hidden" name="action" value="<?= $action; ?>">

      <!-- Set id in hidden field -->
      <input type="hidden" name="id" value="<?= $tableRowExists ? $tableRow->getId() : ''; ?>">

      <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block mt-3">Speichern</button>
        <button type="reset" class="btn btn-outline-warning btn-block mt-3">Reset</button>
      </div>
    </form>
  </div>
</div>
