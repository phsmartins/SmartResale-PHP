<?php

/** @var \Smart\Resale\Entity\User $userData */
$this->layout('layout');

?>

<form action="/config/user-update" method="post">
    <input name="name" id="name" value="<?= $userData->getName(); ?>">
    <input name="email" id="email" value="<?= $userData->getEmail(); ?>">
    <button>Editar</button>
</form>

<form action="/config/password-update" method="post">
    <input type="password" name="password" id="password">
    <input type="password" name="new_password" id="new_password">
    <button>Editar</button>
</form>

<form action="/config/disable-account" method="post">
    <input type="password" name="password" id="password">
    <button>Desativar</button>
</form>
