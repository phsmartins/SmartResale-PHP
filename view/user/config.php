<?php

/** @var \Smart\Resale\Entity\User $userData */

?>

<form action="/config/user-update" method="post">
    <input name="name" id="name" value="<?= $userData->getName(); ?>">
    <input name="email" id="email" value="<?= $userData->getEmail(); ?>">
    <button>Editar</button>
</form>
