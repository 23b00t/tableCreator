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

<div class="row justify-content-center">
  <div class="col-md-4">
    <h2 class="text-center mt-5">Tabelleneigenschaften</h2>
    <form action="index.php" method="POST">
      <!-- Dataset name -->
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="datasetName" name="datasetName"
          value="<?= $datasetExists ? $dataset->getName() : ''; ?>">
      </div>

        <!-- datasetAttribute names -->
        <!-- Edit route -->
        <?php if ($datasetExists) : ?>
            <?php foreach ($dataset->getAttributes() as $attribute) : ?>
                <div class="form-group">
                    <label for="datasetAttribute"><?= $attribute->getAttributeName(); ?></label>
                    <!-- Create associative array, named attributes, 
                    with datasetAttribute->id as key and its name as value -->
                    <input type="text" class="form-control" id="<?= $attribute->getId(); ?>"
                           name="attributes[<?= $attribute->getId(); ?>]"
                           value="<?= $attribute->getAttributeName(); ?>">
                </div>
            <?php endforeach; ?>
        <!-- Insert route -->
        <?php else : ?>
          <div id="attributeFields">
            <div id=fieldControll>
              <div class="form-group">
                  <label for="numberOfFields">Spaltenanzahl</label>
                  <input type="number" id="numberOfFields" class="form-control" name="numberOfFields" min="1">
              </div>
              <button type="" id="submitAttributeNumber" class="btn btn-primary mt-2">Spalten erzeugen</button>
            </div>
            <!-- Use JS to generate n fields specified in input field -->
            <script src="./js/generateFields.js"></script>
          </div>
        <?php endif; ?>

      <!-- Set area in hidden field -->
      <input type="hidden" name="area" value="dataset">

      <!-- Set action in hidden field -->
      <input type="hidden" name="action" value="<?= $action; ?>">

      <!-- Set id in hidden field -->
      <input type="hidden" name="id" value="<?= !$datasetExists ?: $dataset->getId(); ?>">

      <!-- Set old name in hidden field -->
      <input type="hidden" name="oldName" value="<?= !$datasetExists ?: $dataset->getName(); ?>">

      <!-- Set old attributes in hidden field -->
      <input type="hidden" name="oldAttributes[]" value="<?= !$datasetExists ?: $dataset->getAttributes(); ?>">

      <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block mt-3">Speichern</button>
        <button type="reset" class="btn btn-outline-warning btn-block mt-3">Reset</button>
      </div>
    </form>
  </div>
</div>
