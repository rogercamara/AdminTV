<?php

namespace App\Core;

// Classe pra renderizar as telas (Views)
// É aqui que o HTML ganha vida
// Roger
class Visualizacao {
    // Função estática pra chamar a tela
    // $visualizacao é o nome do arquivo (sem .php)
    // $dados é o array de coisas que a gente quer passar pra tela
    public static function renderizar($visualizacao, $dados = []) {
        // Transforma o array em variáveis soltas
        // Ex: ['nome' => 'Roginho'] vira $nome = 'Roginho'
        extract($dados);
        
        $arquivoVisualizacao = ROOT_PATH . '/app/Views/' . $visualizacao . '.php';
        
        if (file_exists($arquivoVisualizacao)) {
            require $arquivoVisualizacao;
        } else {
            // Se não achar o arquivo, grita!
            die("Visualização não encontrada: " . $visualizacao);
        }
    }
}
