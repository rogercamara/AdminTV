<?php

namespace App\Core;

// Classe de configuração do sistema
// Aqui a gente define onde ficam as coisas importantes
// Roger
class Configuracao {
    // Caminho do banco de dados (aquele arquivo sqlite maroto)
    const CAMINHO_BANCO = ROOT_PATH . '/database/database.sqlite';
    
    // Onde a gente joga os arquivos que a galera sobe
    const CAMINHO_UPLOAD = ROOT_PATH . '/public/uploads/';
}
