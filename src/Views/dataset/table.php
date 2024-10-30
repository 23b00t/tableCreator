<?php

namespace App\Views\dataset;

/** @var Dataset[] $datasets  */
?>

<table class="table mt-4">
  <tr>
    <th scope="col">Name</th>
    <th scope="col">Löschen</th>
    <th scope="col">Ändern</th>
  </tr>
  <?php foreach ($datasets as $dataset) : ?>
    <tr>
      <td>
        <a href="index.php?area=dynamicTable&action=showTable&tableName=<?= $dataset->getName(); ?>">
            <?= $dataset->getName(); ?>
        </a>
      </td>
      <td><a href="index.php?area=dataset&action=delete&id=<?= $dataset->getId(); ?>">
        <button class="btn btn-outline-danger"><i class="fa-regular fa-trash-can"></i></button>
      </a></td>
      <td><a href="index.php?area=dataset&action=showForm&id=<?= $dataset->getId(); ?>">
        <button class="btn btn-outline-warning"><i class="fa-solid fa-pencil"></i></button>
      </a></td>
    </tr>
  <?php endforeach; ?>
</table> 
