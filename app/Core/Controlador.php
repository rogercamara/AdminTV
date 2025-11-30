<?php

namespace App\Core;

// Classe pai de todos os controladores
// Todo mundo herda daqui pra poder chamar as views e redirecionar
// Roger
class Controlador {
    // Chama a view (tela)
    public function visualizacao($visualizacao, $dados = []) {
        Visualizacao::renderizar($visualizacao, $dados);
    }

    // Manda o usuário pra outro lugar
    public function redirecionar($url) {
        header("Location: " . $url);
        exit;
    }
    
    // Devolve um JSON (bom pra API)
    public function json($dados) {
        header('Content-Type: application/json');
        echo json_encode($dados);
        exit;
    }
}
