<?php

namespace App\Controllers;

use App\Core\Controlador;
use App\Models\Slide;

// Controlador do Player (TV)
// É aqui que a mágica aparece na tela grande

class PlayerControlador extends Controlador {
    
    // Mostra a tela do player
    public function index() {
        $this->visualizacao('player/index');
    }

    // API que devolve a playlist em JSON pro JS consumir
    public function playlist() {
        $modeloSlide = new Slide();
        $slides = $modeloSlide->tudoOrdenado();
        $this->json($slides);
    }
}
