<?php

namespace App\Views\dynamicTable;

/**
 * @var TableRow[] $tableRows
 * @var Dataset $dataset
 */

?>

<table class="table mt-4">
  <tr>
    <?php foreach ($dataset->getAttributeNames() as $attributeName) : ?>
        <th scope="col"><?= $attributeName; ?></th>
    <?php endforeach; ?>
    <th scope="col">Löschen</th>
    <th scope="col">Ändern</th>
  </tr>
  <?php foreach ($tableRows as $tableRow) : ?>
    <tr>
        <?php foreach (array_values($tableRow->getAttributeValues()) as $value) : ?>
          <td>
            <?= $value; ?>
          </td>
        <?php endforeach; ?>
        <td><a href="index.php?area=dynamicTable&action=delete&id=<?= $tableRow->getId(); ?>">
          <button class="btn btn-outline-danger"><i class="fa-regular fa-trash-can"></i></button>
        </a></td>
        <td><a href="index.php?area=dynamicTable&action=showForm&id=<?= $tableRow->getId(); ?>">
          <button class="btn btn-outline-warning"><i class="fa-solid fa-pencil"></i></button>
        </a></td>
    </tr>
  <?php endforeach; ?>
</table> 
