<?php

namespace App\Views;

use App\Models\Dataset;

/**
 * @var Dataset $dataset
 * @var string $action
 */

// Check if $dataset exists and is an instance of Dataset (edit route)
$datasetExists = isset($dataset) && $dataset instanceof Dataset;
?>

<div class="row justify-content-center">
  <div class="col-md-4">
    <h2 class="text-center mt-5">Tabelleneigenschaften</h2>
    <form action="index.php" method="POST">
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="datasetName" name="datasetName"
          value="<?= $datasetExists ? $dataset->getName() : ''; ?>">
      </div>

    <?php if ($datasetExists) : ?>
        <?php foreach ($dataset->getAttributes() as $attribute) : ?>
            <div class="form-group">
                <label for="datasetAttributes"><?= $attribute->getAttributeName(); ?></label>
                <input type="text" class="form-control" id="datasetAttribute<?= $attribute->getId(); ?>"
                       name="datasetAttribute<?= $attribute->getId(); ?>"
                       value="<?= $attribute->getAttributeName(); ?>">
            </div>
        <?php endforeach; ?>
    <?php else : ?>
      <div id="attributeFields">
        <div id=fieldControll>
          <div class="form-group">
              <label for="numberOfFields">Spaltenanzahl</label>
              <input type="number" id="numberOfFields" class="form-control" name="numberOfFields" min="1">
          </div>
          <button type="" id="submitAttributeNumber" class="btn btn-primary mt-2">Spalten erzeugen</button>
        </div>
        <script src="./js/generateFields.js"></script>
      </div>
    <?php endif; ?>

      <!-- Set area in hidden field -->
      <input type="hidden" name="area" value="dataset">

      <!-- Set action in hidden field -->
      <input type="hidden" name="action" value="<?= $action; ?>">

      <!-- Set id in hidden field -->
      <input type="hidden" name="id" value="<?= $datasetExists ? $dataset->getId() : ''; ?>">

      <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block mt-3">Speichern</button>
        <button type="reset" class="btn btn-outline-warning btn-block mt-3">Reset</button>
      </div>
    </form>
  </div>
</div>
