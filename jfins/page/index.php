<?php require_once __DIR__ . '/partials/session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/partials/headtags.php'; ?>
  <body>
<?php include __DIR__ . '/partials/header.php'; ?>

    <!-- main -->
    <main>
<?php include __DIR__ . '/partials/nav.php'; ?>

<?php include __DIR__ . '/partials/sections/summary.php'; ?>
<?php include __DIR__ . '/partials/sections/income.php'; ?>
<?php include __DIR__ . '/partials/sections/expense.php'; ?>
<?php include __DIR__ . '/partials/sections/jar_config.php'; ?>

    </main>
<?php include __DIR__ . '/partials/footer.php'; ?>

