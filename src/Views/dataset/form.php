<?php

namespace App\Views\dataset;

use App\Models\Dataset;

/**
 * @var Dataset $dataset
 * @var string $action
 */

// Check if $dataset exists and is an instance of Dataset (edit route)
$datasetExists = isset($dataset) && $dataset instanceof Dataset;
?>

<h2 class="text-center mt-5">Tabelleneigenschaften</h2>
<form action="index.php" method="POST">
<div class="row justify-content-center">
  <div class="col-md-4">
    <!-- Dataset name -->
    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" class="form-control" id="datasetName" name="datasetName" required
        value="<?= $datasetExists ? $dataset->getName() : ''; ?>">
    </div>
    <!-- datasetAttribute names -->
    <!-- Edit route -->
    <?php if ($datasetExists) : ?>
        <label for="datasetAttributes">Spaltennamen:</label>
        <?php foreach ($dataset->getAttributes() as $attribute) : ?>
            <div class="form-group d-flex align-items-center">
                <input type="text" class="form-control me-2" id="<?= $attribute->getId(); ?>"
                       name="attributes[<?= $attribute->getId(); ?>]" 
                       value="<?= $attribute->getAttributeName(); ?>">

                <?php
                $baseUrl = "index.php?area=datasetAttribute&action=delete";
                $idParam = "id=" . $attribute->getId();
                $tableNameParam = "tableName=" . $dataset->getName();
                ?>
                <a href="<?= $baseUrl . '&' . $idParam . '&' . $tableNameParam; ?>" 
                  class="btn btn-outline-danger" onclick="event.stopPropagation();">
                    <i class="fa-regular fa-trash-can"></i>
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
  </div>
  <div class="col-md-4">
      <!-- Insert route -->
      <div id="attributeFields" class="ms-auto">
        <div id=fieldControll>
          <div class="form-group">
              <label for="numberOfFields">Spaltenanzahl</label>
              <input type="number" id="numberOfFields" class="form-control" name="numberOfFields" min="1">
          </div>
          <button id="submitAttributeNumber" class="btn btn-outline-success mt-2">Spalten erzeugen</button>
        </div>
        <!-- Use JS to generate n fields specified in input field -->
        <script src="./js/generateFields.js"></script>
      </div>
  </div>
  <div class="col-md-8">
    <div class="form-group">
      <button type="submit" class="btn btn-outline-success btn-block mt-3">Speichern</button>
      <button type="reset" class="btn btn-outline-warning btn-block mt-3">Reset</button>
    </div>
  </div>
  <!-- Set area in hidden field -->
  <input type="hidden" name="area" value="dataset">

  <!-- Set action in hidden field -->
  <input type="hidden" name="action" value="<?= $action; ?>">

  <!-- Set id in hidden field -->
  <input type="hidden" name="id" value="<?= !$datasetExists ?: $dataset->getId(); ?>">
</form>
