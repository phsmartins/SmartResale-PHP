<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/style/main.css">

    <title>SmartResale</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/javascript/alertFunctions.js"></script>
</head>
<body>
    <?= $this->section('content'); ?>
</body>
</html>

<?php if (
    array_key_exists('success_title_message', $_SESSION) &&
    array_key_exists('success_text_message', $_SESSION)
): ?>
    <script>
        successMessage(
            "<?= $_SESSION['success_title_message'] ?>",
            "<?= $_SESSION['success_text_message'] ?>"
        );
    </script>
<?php endif; ?>

<?php
unset($_SESSION['success_title_message']);
unset($_SESSION['success_text_message']);
?>

<?php if (
    array_key_exists('error_title_message', $_SESSION) &&
    array_key_exists('error_text_message', $_SESSION)
): ?>
    <script>
        errorMessage(
            "<?= $_SESSION['error_title_message'] ?>",
            "<?= $_SESSION['error_text_message'] ?>"
        );
    </script>
<?php endif; ?>

<?php
unset($_SESSION['error_title_message']);
unset($_SESSION['error_text_message']);
?>