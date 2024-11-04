<?php

namespace App\Views;

/**
 * @var string $area
 * @var string $view
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
    <?php include __DIR__ . '/navbar.php'; ?>
    <div class="container-md">
      <?php include __DIR__ . '/' . $area . '/' . $view . '.php'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
  </body>
</html>
