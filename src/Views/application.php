<?php

namespace App\Views;

/**
 * @var string $area
 * @var string $view
 * @var string $msg
 * @var string $errorMsg
 */
?>

<!DOCTYPE html>
<html lang="de-DE">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Language" content="de-DE">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tabellen Manager</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'
      integrity='sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH' crossorigin='anonymous'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  </head>
  <body>
    <div class="message alert alert-dismissible fade show text-center mb-0" style="display: none;" role="alert">
      <?= $msg; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php include __DIR__ . '/navbar.php'; ?>
    <div class="container-md">
      <?php include __DIR__ . '/' . $area . '/' . $view . '.php'; ?>
    </div>
    <script type="module" src="./js/main.js"></script>
  </body>
</html>
