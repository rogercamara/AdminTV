## Sistema de gerenciamento de conteudo para TV

## 1. Requisitos
- PHP 7.4 ou superior
- Extensão SQLite3 ativada no PHP
- Extensão PDO ativada


**Importante:** A pasta que deve ser acessível publicamente é a `public`. Apontar o subdominio para public_html/nome-do-app/public

## 3. Permissões

1.  **Pasta de Uploads**: Onde ficam os vídeos e imagens.
    -   Caminho: `public_html/public/uploads`
    -   Permissão: **775** ou **777** (Leitura, Escrita e Execução)

2.  **Pasta do Banco de Dados**: Onde fica o arquivo `.sqlite`.
    -   Caminho: `public_html/database`
    -   Permissão: **775** ou **777**

3.  **Arquivo do Banco**: O arquivo em si.
    -   Caminho: `public_html/database/database.sqlite`
    -   Permissão: **666** ou **777** (Leitura e Escrita)



## 4. Configuração do PHP (Opcional mas Recomendado)
Se for subir vídeos grandes, crie um arquivo chamado `.user.ini` ou `php.ini` dentro da pasta `public` com o seguinte conteúdo:

```ini
upload_max_filesize = 500M
post_max_size = 500M
memory_limit = 512M
max_execution_time = 300
```


