<?php

/** @var \Smart\Resale\Entity\User $userData */
$this->layout('layout');

?>

<section id="container">
    <h2 class="title"><i class="fa-solid fa-pen-to-square"></i> Atualizar Dados</h2>

    <form action="/config/user-update" method="post">
        <h3 class="sub_title">Dados cadastrados</h3>

        <div class="input_box">
            <label for="name">Nome completo: </label>
            <input name="name" id="name" value="<?= $userData->getName(); ?>">
        </div>

        <div class="input_box">
            <label for="email">E-mail: </label>
            <input name="email" id="email" value="<?= $userData->getEmail(); ?>">
        </div>

        <div class="form_button">
            <button class="update_button">Editar</button>
        </div>
    </form>

    <hr>

    <form action="/config/password-update" method="post">
        <h3 class="sub_title">Redefinir senha</h3>

        <div class="input_box">
            <label for="password">Senha atual: </label>
            <input type="password" name="password" id="password">
        </div>

        <div class="input_box">
            <label for="new_password">Nova senha: </label>
            <input type="password" name="new_password" id="new_password">
        </div>

        <div class="form_button">
            <button class="update_button">Editar</button>
        </div>
    </form>

    <hr>

    <form action="/config/disable-account" method="post">
        <h3 class="sub_title">Desativar conta</h3>

        <div class="disable_text">
            <p>Atenção! Ao desativar a conta, você só poderá reativar entrando em contato com o suporte</p>
            <p>Digite a senha e clique em "Desativar" se você realmente deseja fazer essa ação</p>
        </div>

        <div class="input_box">
            <label for="delete_password">Senha:</label>
            <input type="password" name="password" id="delete_password">
        </div>

        <div class="form_button">
            <button class="delete_button">Desativar</button>
        </div>
    </form>
</section>
