<?php  $this->layout('layout-login') ?>

<form method="post">
    <h2>Olá, bem-vindo!</h2>
    <p class="form_text">Informe seus dados para criar uma conta</p>

    <?php if (array_key_exists('error_message', $_SESSION)): ?>
        <p class="error_message_login">
            <?= $_SESSION['error_message'] ?>

            <?php unset($_SESSION['error_message']); ?>
        </p>
    <?php endif; ?>

    <div>
        <label for="name">Nome completo:</label>
        <input
            type="text"
            name="name"
            id="name"
            value="<?= isset($_SESSION['user_name_sign']) ? htmlspecialchars($_SESSION['user_name_sign']) : ''; ?>"
            placeholder="Informe seu nome completo"
        />
        <?php unset($_SESSION['user_name_sign']); ?>
    </div>

    <div>
        <label for="email">E-mail:</label>
        <input
            type="text"
            name="email"
            id="email"
            value="<?= isset($_SESSION['user_email_sign']) ? htmlspecialchars($_SESSION['user_email_sign']) : ''; ?>"
            placeholder="Informe seu e-mail"
        />
        <?php unset($_SESSION['user_email_sign']); ?>
    </div>

    <div>
        <label for="confirmEmail">Confirme seu e-mail:</label>
        <input type="text" name="confirm_email" id="confirmEmail" placeholder="Confirme seu e-mail" />
    </div>

    <div>
        <label for="password">Senha:</label>
        <input type="password" name="password" id="password" placeholder="Informe sua senha" />
    </div>

    <button type="submit">Cadastrar</button>

    <a href="/login" class="other-form">Já tem conta? Entre</a>

</form>

<?php
if (
    array_key_exists('error_title_message', $_SESSION) &&
    array_key_exists('error_text_message', $_SESSION)
):
?>
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