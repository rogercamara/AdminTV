<?php

namespace App\Core;

// Classe que decide pra onde vai cada requisição
// O guarda de trânsito do sistema
// Roger
class Roteador {
    public function despachar($url) {
        // Limpa a URL
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $parametros = explode('/', $url);

        // Define qual controlador chamar
        // Se não tiver nada, chama o PlayerControlador
        $nomeControlador = 'App\\Controllers\\' . ucfirst($parametros[0] ?: 'Player') . 'Controlador';
        
        // Mapeamento de nomes em inglês pra português se precisar (gambiarra leve)
        if ($parametros[0] == 'admin') {
            $nomeControlador = 'App\\Controllers\\AdminControlador';
        } elseif ($parametros[0] == 'auth') {
            $nomeControlador = 'App\\Controllers\\AuthControlador';
        } elseif ($parametros[0] == 'player' || empty($parametros[0])) {
            $nomeControlador = 'App\\Controllers\\PlayerControlador';
        }

        $metodo = $parametros[1] ?? 'index';

        // Verifica se a classe existe
        if (class_exists($nomeControlador)) {
            $controlador = new $nomeControlador();
            if (method_exists($controlador, $metodo)) {
                unset($parametros[0], $parametros[1]);
                // Chama o método do controlador passando os parâmetros
                call_user_func_array([$controlador, $metodo], $parametros);
                return;
            }
        }
        
        // Se não achar nada, tenta o Player (segurança pra TV não ficar preta)
        // Ou mostra erro se for admin
        if ($parametros[0] == 'admin') {
             echo "404 Página Admin Não Encontrada (Deu ruim)";
        } else {
            $nomeControlador = 'App\\Controllers\\PlayerControlador';
            if (class_exists($nomeControlador)) {
                $controlador = new $nomeControlador();
                $controlador->index();
            } else {
                echo "Erro Crítico: Controlador do Player sumiu!";
            }
        }
    }
}
