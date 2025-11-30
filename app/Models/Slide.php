<?php

namespace App\Models;

use App\Core\Modelo;

// Modelo dos Slides
// Aqui a gente mexe com os vídeos, imagens e textos
// Roger
class Slide extends Modelo {
    protected $tabela = 'slides';

    // Cria um novo slide no banco
    public function criar($dados) {
        $sql = "INSERT INTO slides (type, content, title, duration, display_order) 
                VALUES (:type, :content, :title, :duration, (SELECT COALESCE(MAX(display_order), 0) + 1 FROM slides))";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'type' => $dados['type'],
            'content' => $dados['content'],
            'title' => $dados['title'],
            'duration' => $dados['duration']
        ]);
    }

    // Pega todos os slides na ordem certa
    public function tudoOrdenado() {
        $stmt = $this->db->query("SELECT * FROM slides ORDER BY display_order ASC");
        return $stmt->fetchAll();
    }

    // Atualiza a ordem dos slides (drag and drop)
    public function atualizarOrdem($id, $ordem) {
        $stmt = $this->db->prepare("UPDATE slides SET display_order = :order WHERE id = :id");
        return $stmt->execute(['order' => $ordem, 'id' => $id]);
    }
}
