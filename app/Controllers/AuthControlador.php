<?php

namespace App\Controllers;

use App\Core\Controlador;
use App\Models\Usuario;

// Controlador de Autenticação
// Cuida do login e logout da galera

class AuthControlador extends Controlador {
    
    // Mostra a tela de login
    public function login() {
        $this->visualizacao('auth/login');
    }

    // Processa o login (verifica se a senha bate)
    public function autenticar() {
        $usuario = $_POST['username'] ?? '';
        $senha = $_POST['password'] ?? '';

        $modeloUsuario = new Usuario();
        $user = $modeloUsuario->autenticar($usuario, $senha);

        if ($user) {
            // Se deu bom, salva na sessão e manda pro admin
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $this->redirecionar('/admin');
        } else {
            // Se deu ruim, volta pro login com erro
            $this->visualizacao('auth/login', ['error' => 'Credenciais inválidas']);
        }
    }

    // Tchau brigado
    public function logout() {
        session_destroy();
        $this->redirecionar('/auth/login');
    }
}
