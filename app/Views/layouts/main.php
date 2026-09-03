<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $this->renderSection('title') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<main class="container py-4">
    <?= $this->renderSection('content') ?>
</main>

</body>
</html>