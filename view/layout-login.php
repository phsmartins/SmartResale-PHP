<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/style/main.css">
    <link rel="stylesheet" href="/style/login.css">

    <title>SmartResale | Entre ou Cadastre-se</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/javascript/alertFunctions.js"></script>
</head>
<body>

<main>
    <div class="welcome__box">
        <img
            class="welcome__logo"
            src="/images/logo/logo-branca-SmartResale.png"
            alt="Logo da SmartResale" />

        <img
            class="welcome__image_login"
            src="/images/image-login.png"
            alt="Imagem para representar gestão em relação ao sistema" />
        <h1>Controle suas vendas do começo ao fim</h1>
    </div>

    <div class="form__box">
        <?= $this->section('content'); ?>

        <div class="developed">
            <p>
                Desenvolvido por
                <a target="_blank" href="https://github.com/phsmartins">Pedro Martins</a> |
                <?= date("Y"); ?>
            </p>
        </div>
    </div>
</main>

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
</body>
</html>