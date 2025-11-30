<?php

namespace App\Core;

use PDO;
use PDOException;

// Classe pra mexer no banco de dados
// Onde a mágica acontece (ou os erros kkk)
// Roger
class BancoDeDados {
    private static $instancia = null;
    private $pdo;

    // Construtor privado pra ninguém ficar criando instância adoidado
    private function __construct() {
        try {
            // Conecta no arquivo do banco usando a configuração
            $this->pdo = new PDO('sqlite:' . Configuracao::CAMINHO_BANCO);
            // Se der erro, joga na cara
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Já traz tudo como array associativo pra facilitar a vida
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->inicializar();
        } catch (PDOException $e) {
            die("Deu ruim na conexão com o banco: " . $e->getMessage());
        }
    }

    // Padrão Singleton (chique né?)
    public static function getInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    // Pega a conexão PDO pra usar nas queries
    public function getConexao() {
        return $this->pdo;
    }

    // Cria as tabelas se não existirem
    private function inicializar() {
        // Tabela de usuários (pra logar no sistema)
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL
        )");

        // Tabela de slides (os vídeos e imagens que rodam na TV)
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS slides (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            type TEXT NOT NULL, -- 'video', 'image', 'html'
            content TEXT NOT NULL, -- caminho do arquivo ou json do html
            title TEXT,
            duration INTEGER DEFAULT 10,
            display_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Cria o usuário admin se não tiver nenhum
        // A senha é 'admin', super segura kkkk
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = 'admin'");
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            $senha = password_hash('admin', PASSWORD_DEFAULT);
            $this->pdo->exec("INSERT INTO users (username, password) VALUES ('admin', '$senha')");
        }
    }
}
