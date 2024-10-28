<?php

namespace App\Views;

/** @var Main[] $mains  */
?>

<table class="table mt-4">
  <tr>
    <th scope="col">Name</th>
    <th scope="col">Löschen</th>
    <th scope="col">Ändern</th>
  </tr>
  <?php foreach ($mains as $main) : ?>
    <tr>
      <td><?= $main->getName(); ?></td>
      <td><a href="index.php?area=main&action=delete&id=<?= $main->getId(); ?>">
        <button class="btn btn-outline-danger"><i class="fa-regular fa-trash-can"></i></button>
      </a></td>
      <td><a href="index.php?area=main&action=showForm&id=<?= $main->getId(); ?>">
        <button class="btn btn-outline-warning"><i class="fa-solid fa-pencil"></i></button>
      </a></td>
    </tr>
  <?php endforeach; ?>
</table> 
