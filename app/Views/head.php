<?php
/** @var string|null $pageTitle */
$pageTitle = $pageTitle ?? 'La Capsule | Centre de ressources';
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#332F80">
<title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
<link rel="icon" type="image/webp" href="assets/img/brand/favicon.webp">
<link rel="apple-touch-icon" href="assets/img/brand/apple-touch-icon.webp">
<link rel="stylesheet" href="assets/vendor/fontawesome/css/all.min.css">
<link rel="stylesheet" href="css/theme.css">
