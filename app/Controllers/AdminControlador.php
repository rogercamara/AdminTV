<?php

namespace App\Controllers;

use App\Core\Controlador;
use App\Core\Configuracao;
use App\Models\Slide;

// Controlador do Admin
// Onde o chefe manda em tudo
class AdminControlador extends Controlador {

    public function __construct() {
        // Verifica se o cara tá logado, senão chuta pro login
        if (!isset($_SESSION['user_id'])) {
            $this->redirecionar('/auth/login');
        }
    }

    // Tela principal do painel
    public function index() {
        $modeloSlide = new Slide();
        $slides = $modeloSlide->tudoOrdenado();
        $this->visualizacao('admin/dashboard', ['slides' => $slides]);
    }

    // Upload de arquivos (a parte tensa)
    public function upload() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo = $_POST['type'];
            $tituloInput = $_POST['title'] ?? '';
            $duracao = $_POST['duration'] ?? 10;
            
            if ($tipo === 'html') {
                // Se for slide de texto/html
                $corFundo = $_POST['bg_color'] ?? '#000000';
                $corTexto = $_POST['text_color'] ?? '#ffffff';
                $manchete = $_POST['headline'] ?? '';
                $corpo = $_POST['body'] ?? '';
                $logo = '';

                // Verifica se tem logo pra subir
                if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                    $caminhoTemp = $_FILES['logo']['tmp_name'];
                    $nomeArquivo = $_FILES['logo']['name'];
                    $partesNome = explode(".", $nomeArquivo);
                    $extensao = strtolower(end($partesNome));
                    
                    // Gera um nome único pra não dar conflito
                    $novoNome = md5(time() . $nomeArquivo . 'logo') . '.' . $extensao;
                    $caminhoDestino = Configuracao::CAMINHO_UPLOAD . $novoNome;
                    
                    if(move_uploaded_file($caminhoTemp, $caminhoDestino)) {
                        $logo = $novoNome;
                    }
                }
                
                // Salva tudo num JSON maroto
                $conteudo = json_encode([
                    'bg_color' => $corFundo,
                    'text_color' => $corTexto,
                    'headline' => $manchete,
                    'body' => $corpo,
                    'logo' => $logo
                ]);
                
                $modeloSlide = new Slide();
                $modeloSlide->criar([
                    'type' => $tipo,
                    'content' => $conteudo,
                    'title' => $tituloInput ?: 'Slide de Texto',
                    'duration' => $duracao
                ]);
                
                $_SESSION['success'] = "Slide de texto criado com sucesso!";

            } else {
                // Upload de arquivos em massa (Vídeo ou Imagem)
                if (isset($_FILES['files'])) {
                    $contagemArquivos = count($_FILES['files']['name']);
                    $sucessos = 0;
                    $erros = 0;
                    
                    for ($i = 0; $i < $contagemArquivos; $i++) {
                        $erro = $_FILES['files']['error'][$i];
                        if ($erro === UPLOAD_ERR_OK) {
                            $caminhoTemp = $_FILES['files']['tmp_name'][$i];
                            $nomeArquivo = $_FILES['files']['name'][$i];
                            $partesNome = explode(".", $nomeArquivo);
                            $extensao = strtolower(end($partesNome));
                            
                            $novoNome = md5(time() . $nomeArquivo . $i) . '.' . $extensao;
                            $caminhoDestino = Configuracao::CAMINHO_UPLOAD . $novoNome;
                            
                            if(move_uploaded_file($caminhoTemp, $caminhoDestino)) {
                                $modeloSlide = new Slide();
                                // Usa o nome do arquivo se não tiver título
                                $titulo = $tituloInput ? "$tituloInput (" . ($i+1) . ")" : $nomeArquivo;
                                
                                $modeloSlide->criar([
                                    'type' => $tipo,
                                    'content' => $novoNome,
                                    'title' => $titulo,
                                    'duration' => $duracao
                                ]);
                                $sucessos++;
                            } else {
                                $erros++;
                            }
                        } elseif ($erro === UPLOAD_ERR_INI_SIZE || $erro === UPLOAD_ERR_FORM_SIZE) {
                            $erros++;
                            $_SESSION['error'] = "Arquivo muito grande: " . $_FILES['files']['name'][$i];
                        } else {
                            $erros++;
                        }
                    }
                    
                    if ($sucessos > 0) {
                        $_SESSION['success'] = "$sucessos arquivos enviados com sucesso.";
                    }
                    if ($erros > 0 && !isset($_SESSION['error'])) {
                        $_SESSION['error'] = "$erros arquivos falharam ao enviar.";
                    }
                    
                } else {
                    // Verifica se estourou o limite do POST
                    if (empty($_FILES) && empty($_POST) && isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > 0) {
                        $tamanhoMaximo = ini_get('post_max_size');
                        $_SESSION['error'] = "O envio excedeu o limite do servidor (post_max_size = $tamanhoMaximo). Tente enviar menos arquivos ou arquivos menores.";
                    }
                }
            }
            
            $this->redirecionar('/admin');
        }
    }

    // Apaga o slide
    public function delete($id) {
        $modeloSlide = new Slide();
        $slide = $modeloSlide->encontrar($id);
        
        if ($slide) {
            // Se não for HTML, tenta apagar o arquivo físico
            if ($slide['type'] !== 'html') {
                $caminhoArquivo = Configuracao::CAMINHO_UPLOAD . $slide['content'];
                if (file_exists($caminhoArquivo)) {
                    unlink($caminhoArquivo);
                }
            }
            $modeloSlide->deletar($id);
            $_SESSION['success'] = "Conteúdo removido!";
        }
        
        $this->redirecionar('/admin');
    }

    // Reordena os slides (AJAX)
    public function reorder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = json_decode(file_get_contents('php://input'), true);
            if (isset($dados['order']) && is_array($dados['order'])) {
                $modeloSlide = new Slide();
                foreach ($dados['order'] as $posicao => $id) {
                    $modeloSlide->atualizarOrdem($id, $posicao);
                }
                $this->json(['success' => true]);
            }
        }
        $this->json(['success' => false]);
    }
}
