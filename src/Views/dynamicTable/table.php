<?php

namespace App\Views\dynamicTable;

/**
 * @var TableRow[] $tableRows
 */
?>

<table class="table mt-4">
    <tr>
        <?php foreach (array_keys($tableRows[0]->getAttributeArray()) as $attributeName) : ?>
            <th scope="col"><?= $attributeName; ?></th>
        <?php endforeach; ?>
        <th scope="col">Löschen</th>
        <th scope="col">Ändern</th>
    </tr>
    <?php foreach ($tableRows as $tableRow) : ?>
        <?php if ($tableRow->getId() !== null) : ?>
        <tr>
            <?php foreach (array_values($tableRow->getAttributeArray()) as $value) : ?>
              <td>
                <?= $value; ?>
              </td>
            <?php endforeach; ?>
        <td>
          <a href="index.php?area=dynamicTable&action=delete&id=<?= $tableRow->getId(); ?>&tableName=<?= $tableRow->getName(); ?>">
            <button class="btn btn-outline-danger">
              <i class="fa-regular fa-trash-can"></i>
            </button>
          </a>
        </td>
        <td><a href="index.php?area=dynamicTable&action=showForm&id=<?= $tableRow->getId(); ?>&tableName=<?= $tableRow->getName(); ?>">
          <button class="btn btn-outline-warning"><i class="fa-solid fa-pencil"></i></button>
        </a></td>
        </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table> 

<div>
  <a href="index.php?area=dynamicTable&action=showForm&tableName=<?= $tableRows[0]->getName(); ?>">
    Eintrag hinzufügen
  </a>
</div>
