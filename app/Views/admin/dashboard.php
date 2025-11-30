<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador Willy </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
            --text-main: #111827;
            --text-muted: #6b7280;
        }
        body {
            background-color: var(--bg-color);
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
        }
        .navbar {
            background-color: var(--card-bg);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.6rem 1.2rem;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #e5e7eb;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .nav-tabs .nav-link {
            color: var(--text-muted);
            border: none;
            border-bottom: 2px solid transparent;
            padding: 1rem 1.5rem;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            background: transparent;
        }
        .nav-tabs {
            border-bottom: 1px solid #e5e7eb;
        }
        
        /* Playlist Grid */
        .playlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
            padding: 1.5rem;
        }
        .slide-card {
            background: white;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: move;
            position: relative;
        }
        .slide-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .slide-preview {
            height: 280px; /* Vertical aspect ratio roughly */
            background: #f9fafb;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        .slide-preview img, .slide-preview video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .slide-info {
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
        }
        .slide-title {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .slide-meta {
            font-size: 0.8rem;
            color: var(--text-muted);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .slide-actions {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            top: 0.5rem;
            right: 0.5rem;
            opacity: 1; /* Always visible */
            z-index: 10;
        }
        .slide-card:hover .slide-actions {
            opacity: 1;
        }
        .btn-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            color: #ef4444;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border: none;
            cursor: pointer;
        }
        .btn-icon:hover {
            background: #fee2e2;
        }
        .upload-area {
            border: 2px dashed #e5e7eb;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        .upload-area:hover {
            border-color: var(--primary-color);
        }
        .ghost {
            opacity: 0.5;
            background: #e5e7eb;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="bi bi-display"></i> Willy TV</a>
        <div class="d-flex align-items-center">
            <a href="/" target="_blank" class="btn btn-outline-primary me-3"><i class="bi bi-play-fill"></i> Abrir Player</a>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> Admin
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="/auth/logout">Sair</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="container py-5">
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-2"></i>
                <div>
                    <strong>Limites do Servidor:</strong> 
                    Upload Máximo: <?= ini_get('upload_max_filesize') ?> | 
                    Post Máximo: <?= ini_get('post_max_size') ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Content Creator -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-plus-circle me-2"></i> Adicionar Conteúdo
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item w-50 text-center" role="presentation">
                            <button class="nav-link active w-100" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button">
                                <i class="bi bi-collection-play"></i> Mídia
                            </button>
                        </li>
                        <li class="nav-item w-50 text-center" role="presentation">
                            <button class="nav-link w-100" id="text-tab" data-bs-toggle="tab" data-bs-target="#text" type="button">
                                <i class="bi bi-file-text"></i> Criador de Texto
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content p-4" id="myTabContent">
                        <!-- Media Upload -->
                        <div class="tab-pane fade show active" id="media" role="tabpanel">
                            <form action="/admin/upload" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tipo de Conteúdo</label>
                                    <select name="type" class="form-select mb-3" id="mediaType">
                                        <option value="video">Playlist de Vídeo</option>
                                        <option value="image">Galeria de Imagens</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Título (Opcional)</label>
                                    <input type="text" name="title" class="form-control" placeholder="Nome do lote">
                                </div>

                                <div class="mb-3" id="durationField" style="display:none;">
                                    <label class="form-label fw-bold">Duração por Slide (seg)</label>
                                    <input type="number" name="duration" class="form-control" value="10">
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold" for="fileInput">Selecionar Arquivos</label>
                                    <input type="file" name="files[]" class="form-control" multiple required id="fileInput">
                                    <div class="form-text">Segure Ctrl/Cmd para selecionar vários arquivos.</div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    <i class="bi bi-cloud-upload me-2"></i> Enviar para Playlist
                                </button>
                            </form>
                        </div>
                        
                        <!-- Text Builder -->
                        <div class="tab-pane fade" id="text" role="tabpanel">
                            <form action="/admin/upload" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="type" value="html">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Título do Slide</label>
                                    <input type="text" name="title" class="form-control" required placeholder="ex: Promoção de Verão">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Duração (seg)</label>
                                    <input type="number" name="duration" class="form-control" value="15">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Manchete</label>
                                    <input type="text" name="headline" class="form-control" placeholder="Grande Chamada de Atenção">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Texto do Corpo</label>
                                    <textarea name="body" class="form-control" rows="4" placeholder="Detalhes sobre a promoção..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Logo (Opcional)</label>
                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label fw-bold">Fundo</label>
                                        <input type="color" name="bg_color" class="form-control form-control-color w-100" value="#4f46e5">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold">Cor do Texto</label>
                                        <input type="color" name="text_color" class="form-control form-control-color w-100" value="#ffffff">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 mt-4 py-2">
                                    <i class="bi bi-plus-lg me-2"></i> Criar Slide
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Playlist Manager -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-list-ol me-2"></i> Playlist Ativa</span>
                    <span class="badge bg-primary rounded-pill"><?= count($slides) ?> Itens</span>
                </div>
                <div class="card-body p-0 bg-light">
                    <?php if (empty($slides)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-collection fs-1 mb-3 d-block"></i>
                            <p>A playlist está vazia. Adicione algum conteúdo!</p>
                        </div>
                    <?php else: ?>
                        <div class="playlist-grid" id="playlist">
                            <?php foreach ($slides as $slide): ?>
                                <div class="slide-card" data-id="<?= $slide['id'] ?>">
                                    <div class="slide-actions">
                                        <a href="/admin/delete/<?= $slide['id'] ?>" class="btn-icon" onclick="return confirm('Remover este slide?')" title="Excluir">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                    <div class="slide-preview">
                                        <?php if ($slide['type'] == 'video'): ?>
                                            <video src="/uploads/<?= $slide['content'] ?>#t=0.1" preload="metadata"></video>
                                            <div class="position-absolute bottom-0 start-0 p-2 text-white w-100" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                                <i class="bi bi-play-circle-fill"></i> Vídeo
                                            </div>
                                        <?php elseif ($slide['type'] == 'image'): ?>
                                            <img src="/uploads/<?= $slide['content'] ?>" loading="lazy">
                                            <div class="position-absolute bottom-0 start-0 p-2 text-white w-100" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                                <i class="bi bi-image"></i> Imagem
                                            </div>
                                        <?php else: 
                                            $content = json_decode($slide['content'], true);
                                        ?>
                                            <div style="width:100%; height:100%; background:<?= $content['bg_color'] ?>; color:<?= $content['text_color'] ?>; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:10px; text-align:center;">
                                                <h4 style="font-size:1rem; margin:0;"><?= htmlspecialchars($content['headline']) ?></h4>
                                            </div>
                                            <div class="position-absolute bottom-0 start-0 p-2 text-white w-100" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                                <i class="bi bi-type"></i> Texto
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="slide-info">
                                        <div class="slide-title" title="<?= htmlspecialchars($slide['title']) ?>">
                                            <?= htmlspecialchars($slide['title']) ?>
                                        </div>
                                        <div class="slide-meta">
                                            <span><?= $slide['type'] === 'video' ? 'Auto' : $slide['duration'] . 's' ?></span>
                                            <i class="bi bi-grip-vertical text-muted" style="cursor: grab;"></i>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script>
    // Toggle Duration field based on type
    const mediaType = document.getElementById('mediaType');
    const durationField = document.getElementById('durationField');
    const fileInput = document.getElementById('fileInput');
    
    function updateForm() {
        if (mediaType.value === 'image') {
            durationField.style.display = 'block';
            fileInput.accept = 'image/*';
        } else {
            durationField.style.display = 'none';
            fileInput.accept = 'video/mp4,video/webm';
        }
    }
    
    mediaType.addEventListener('change', updateForm);
    updateForm(); // Init

    // Sortable JS
    const playlist = document.getElementById('playlist');
    if (playlist) {
        new Sortable(playlist, {
            animation: 150,
            ghostClass: 'ghost',
            onEnd: function (evt) {
                const order = [];
                document.querySelectorAll('.slide-card').forEach((card, index) => {
                    order.push(card.getAttribute('data-id'));
                });
                
                // Send new order to server
                fetch('/admin/reorder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ order: order })
                }).then(res => res.json())
                  .then(data => {
                      if(data.success) {
                          console.log('Ordem atualizada');
                      } else {
                          alert('Falha ao salvar ordem');
                      }
                  });
            }
        });
    }
</script>
</body>
</html>
