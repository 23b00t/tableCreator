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
    <th scope="col" style="width: 5%;">Löschen</th>
    <th scope="col" style="width: 5%;">Ändern</th>
  </tr>
    <?php foreach ($tableRows as $tableRow) : ?>
        <?php if ($tableRow->getId() !== null) : ?>
        <tr>
            <?php foreach (array_values($tableRow->getAttributeArray()) as $value) : ?>
              <td>
                <?= nl2br(htmlspecialchars($value)); ?>
              </td>
            <?php endforeach; ?>

            <?php
            $id = $tableRow->getId();
            $tableName = $tableRow->getName();
            $baseUrl = "index.php?area=dynamicTable";
            ?>

          <td>
            <a href="<?= "$baseUrl&action=delete&id=$id&tableName=$tableName"; ?>">
              <button class="btn btn-outline-danger">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </a>
          </td>
          <td>
            <a href="<?= "$baseUrl&action=showForm&id=$id&tableName=$tableName"; ?>">
              <button class="btn btn-outline-warning">
                <i class="fa-solid fa-pencil"></i>
              </button>
            </a>
          </td>
        </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table> 

<div>
  <a href="index.php?area=dynamicTable&action=showForm&tableName=<?= $tableRows[0]->getName(); ?>" 
     class="btn btn-outline-success">
     <i class="fa-solid fa-plus"></i> Eintrag hinzufügen
  </a>
</div>
