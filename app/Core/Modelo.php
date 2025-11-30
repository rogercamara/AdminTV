<?php

namespace App\Core;

use PDO;

// Classe base pros Modelos
// Todo modelo vai herdar dessa aqui pra ter acesso ao banco
// Roger
class Modelo {
    protected $db;
    protected $tabela;

    public function __construct() {
        // Pega a conexão lá da classe BancoDeDados
        $this->db = BancoDeDados::getInstancia()->getConexao();
    }

    // Pega tudo da tabela
    public function tudo() {
        $stmt = $this->db->query("SELECT * FROM {$this->tabela}");
        return $stmt->fetchAll();
    }

    // Busca um registro pelo ID
    public function encontrar($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->tabela} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    // Deleta um registro pelo ID (cuidado hein!)
    public function deletar($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->tabela} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
