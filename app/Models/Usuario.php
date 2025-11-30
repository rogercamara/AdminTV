<?php

namespace App\Models;

use App\Core\Modelo;

// Modelo do Usuário
// Pra quem manda no pedaço
// Roger
class Usuario extends Modelo {
    protected $tabela = 'users'; // Mantive o nome da tabela em inglês pra não quebrar o banco já criado

    // Verifica se o login tá certo
    public function autenticar($usuario, $senha) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->tabela} WHERE username = :username");
        $stmt->execute(['username' => $usuario]);
        $user = $stmt->fetch();

        if ($user && password_verify($senha, $user['password'])) {
            return $user;
        }
        return false;
    }
}
