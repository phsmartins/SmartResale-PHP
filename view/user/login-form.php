<?php  $this->layout('layout-login') ?>

<form method="post">
    <h2>Olá, bem-vindo!</h2>
    <p class="form_text">Digite as credênciais para acessar o sistema</p>

    <?php if (array_key_exists('error_message', $_SESSION)): ?>
        <p class="error-message-login">
            <?= $_SESSION['error_message'] ?>

            <?php unset($_SESSION['error_message']); ?>
        </p>
    <?php endif; ?>

    <label for="email">E-mail:</label>
    <input
        type="text"
        name="email"
        id="email"
        value="<?= isset($_SESSION['user_email_login']) ? htmlspecialchars($_SESSION['user_email_login']) : ''; ?>"
        placeholder="Informe seu e-mail"
    />
    <?php unset($_SESSION['user_email_login']); ?>

    <div>
        <label for="password">Senha:</label>
        <input type="password" name="password" id="password" placeholder="Informe sua senha" required />
    </div>

    <button type="submit">Acessar</button>

    <a href="/signup" class="other-form">Não tem conta? Cadastre-se</a>

</form>