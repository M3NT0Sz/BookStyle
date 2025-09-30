@extends('layouts.app')

@section('title', 'Meu Perfil - BookStyle')

@section('content')
<style>
/* Design moderno para página de perfil */
* {
    box-sizing: border-box;
}

.profile-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow-x: hidden;
}

.profile-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="0.5"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
    pointer-events: none;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
    position: relative;
    z-index: 1;
}

/* Header do perfil */
.profile-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 25px;
    padding: 3rem 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 30px 60px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.profile-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 120px;
    background: linear-gradient(45deg, #667eea, #764ba2);
    border-radius: 25px 25px 0 0;
    z-index: 1;
}

.profile-content {
    position: relative;
    z-index: 2;
}

.profile-avatar-container {
    position: relative;
    display: inline-block;
    margin-bottom: 1.5rem;
}

.profile-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: 6px solid white;
    object-fit: cover;
    background: #f8fafc;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

.profile-avatar:hover {
    transform: scale(1.05);
    box-shadow: 0 25px 50px rgba(0,0,0,0.2);
}

.avatar-edit-btn {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 40px;
    height: 40px;
    background: linear-gradient(45deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    border: 3px solid white;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.avatar-edit-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
}

.profile-name {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 0.5rem;
    background: linear-gradient(45deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.profile-email {
    font-size: 1.2rem;
    color: #64748b;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.profile-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
    margin-top: 2rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 15px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 800;
    color: #667eea;
    display: block;
}

.stat-label {
    font-size: 0.9rem;
    color: #64748b;
    margin-top: 0.25rem;
}

/* Navegação principal */
.profile-navigation {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
}

.nav-tabs {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.nav-tab {
    padding: 1rem 2rem;
    border-radius: 15px;
    background: transparent;
    color: #64748b;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.nav-tab:hover {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
    border-color: rgba(102, 126, 234, 0.2);
}

.nav-tab.active {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    transform: translateY(-2px);
}

/* Seções de conteúdo */
.profile-section {
    display: none;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
    animation: fadeInUp 0.6s ease-out;
}

.profile-section.active {
    display: block;
}

.section-title {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.section-title i {
    color: #667eea;
}

/* Seção de dados pessoais */
.personal-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.info-card {
    background: #f8fafc;
    border-radius: 15px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-value {
    color: #64748b;
    font-weight: 500;
}

/* Seção de livros */
.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.book-card {
    background: #f8fafc;
    border-radius: 15px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    text-align: center;
}

.book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    border-color: #667eea;
}

.book-image {
    width: 120px;
    height: 160px;
    object-fit: cover;
    border-radius: 10px;
    margin: 0 auto 1rem;
    display: block;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.book-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.book-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: #059669;
    margin-bottom: 1rem;
}

.book-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border: none;
}

.btn-primary {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.btn-danger {
    background: linear-gradient(45deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
}

.btn-success {
    background: linear-gradient(45deg, #10b981, #059669);
    color: white;
    box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
}

/* Estado vazio */
.empty-state {
    text-align: center;
    padding: 3rem;
    color: #64748b;
}

.empty-icon {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 1rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.empty-description {
    margin-bottom: 2rem;
}

/* Seção de configurações */
.settings-form {
    max-width: 600px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-input {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.form-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.file-input-wrapper {
    position: relative;
    display: inline-block;
    width: 100%;
}

.file-input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.file-input-display {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 2px dashed #d1d5db;
    border-radius: 10px;
    background: #f9fafb;
    transition: all 0.3s ease;
    cursor: pointer;
}

.file-input-display:hover {
    border-color: #667eea;
    background: #f0f4ff;
}

.file-icon {
    font-size: 2rem;
    color: #667eea;
}

.file-text {
    flex: 1;
}

.file-title {
    font-weight: 600;
    color: #374151;
}

.file-subtitle {
    font-size: 0.875rem;
    color: #64748b;
}

/* Seção de pedidos */
.orders-preview {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.order-card {
    background: #f8fafc;
    border-radius: 15px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.order-number {
    font-weight: 600;
    color: #1e293b;
}

.order-status {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-delivered {
    background: linear-gradient(45deg, #10b981, #059669);
    color: white;
}

.status-pending {
    background: linear-gradient(45deg, #f59e0b, #d97706);
    color: white;
}

.order-total {
    font-size: 1.5rem;
    font-weight: 700;
    color: #059669;
    text-align: center;
}

/* Animações */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsividade */
@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .profile-header {
        padding: 2rem 1rem;
    }
    
    .profile-name {
        font-size: 2rem;
    }
    
    .nav-tabs {
        flex-direction: column;
    }
    
    .nav-tab {
        text-align: center;
        justify-content: center;
    }
    
    .personal-info {
        grid-template-columns: 1fr;
    }
    
    .books-grid {
        grid-template-columns: 1fr;
    }
    
    .profile-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .profile-stats {
        grid-template-columns: 1fr;
    }
    
    .book-actions {
        flex-direction: column;
    }
}
</style>

<div class="profile-wrapper">
    <div class="container">
        <!-- Header do Perfil -->
        <div class="profile-header animate-fade-in">
            <div class="profile-content">
                <div class="profile-avatar-container">
                    <img class="profile-avatar" 
                         src="{{ asset('storage/' . (isset($user->image) ? $user->image : 'perfil.png')) }}" 
                         alt="{{ $user->name }}"
                         id="avatar-preview">
                    <label for="avatar-input" class="avatar-edit-btn">
                        <i class="fas fa-camera"></i>
                    </label>
                    <input type="file" id="avatar-input" style="display: none;" accept="image/*">
                </div>
                
                <h1 class="profile-name">{{ $user->name }}</h1>
                <p class="profile-email">
                    <i class="fas fa-envelope"></i>
                    {{ $user->email }}
                </p>
                
                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-value">{{ $books ? $books->count() : 0 }}</span>
                        <span class="stat-label">Livros</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">0</span>
                        <span class="stat-label">Pedidos</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ isset($user->created_at) ? (is_string($user->created_at) ? date('Y', strtotime($user->created_at)) : $user->created_at->format('Y')) : date('Y') }}</span>
                        <span class="stat-label">Membro desde</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navegação -->
        <div class="profile-navigation animate-fade-in">
            <div class="nav-tabs">
                <button class="nav-tab active" data-section="dados">
                    <i class="fas fa-user"></i>
                    Dados Pessoais
                </button>
                <button class="nav-tab" data-section="livros">
                    <i class="fas fa-book"></i>
                    Meus Livros
                </button>
                <button class="nav-tab" data-section="pedidos">
                    <i class="fas fa-shopping-bag"></i>
                    Pedidos
                </button>
                <button class="nav-tab" data-section="configuracoes">
                    <i class="fas fa-cog"></i>
                    Configurações
                </button>
            </div>
        </div>

        <!-- Seção: Dados Pessoais -->
        <div id="dados" class="profile-section active animate-fade-in">
            <h2 class="section-title">
                <i class="fas fa-user"></i>
                Dados Pessoais
            </h2>
            
            <div class="personal-info">
                <div class="info-card">
                    <h3 style="margin-bottom: 1rem; color: #1e293b;">Informações Básicas</h3>
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-user"></i>
                            Nome Completo
                        </span>
                        <span class="info-value">{{ $user->name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-envelope"></i>
                            E-mail
                        </span>
                        <span class="info-value">{{ $user->email }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-calendar"></i>
                            Membro desde
                        </span>
                        <span class="info-value">{{ isset($user->created_at) ? (is_string($user->created_at) ? date('d/m/Y', strtotime($user->created_at)) : $user->created_at->format('d/m/Y')) : date('d/m/Y') }}</span>
                    </div>
                </div>
                
                <div class="info-card">
                    <h3 style="margin-bottom: 1rem; color: #1e293b;">Estatísticas da Conta</h3>
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-book"></i>
                            Total de Livros
                        </span>
                        <span class="info-value">{{ $books ? $books->count() : 0 }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-shopping-cart"></i>
                            Total de Pedidos
                        </span>
                        <span class="info-value">0</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-star"></i>
                            Status da Conta
                        </span>
                        <span class="info-value" style="color: #059669; font-weight: 600;">Ativa</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção: Meus Livros -->
        <div id="livros" class="profile-section">
            <h2 class="section-title">
                <i class="fas fa-book"></i>
                Meus Livros
            </h2>
            
            @if($books && $books->count() > 0)
                <div class="books-grid">
                    @foreach($books as $book)
                        <div class="book-card">
                            @php
                                $images = is_array($book->images) ? $book->images : json_decode($book->images, true);
                            @endphp
                            @if(!empty($images))
                                <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $book->name }}" class="book-image">
                            @else
                                <div class="book-image" style="background: #f1f5f9; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-book" style="font-size: 2rem; color: #d1d5db;"></i>
                                </div>
                            @endif
                            
                            <h3 class="book-title">{{ Str::limit($book->name, 30) }}</h3>
                            <p class="book-price">R$ {{ number_format($book->price, 2, ',', '.') }}</p>
                            
                            <div class="book-actions">
                                <a href="{{ route('books.edit', $book->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i>
                                    Editar
                                </a>
                                <form action="{{ route('books.destroy', $book->id) }}" method="post" style="display: inline;" 
                                      onsubmit="return confirm('Tem certeza que deseja deletar este livro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i>
                                        Deletar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div style="text-align: center;">
                    <a href="{{ route('books.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i>
                        Cadastrar Novo Livro
                    </a>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3 class="empty-title">Nenhum livro cadastrado</h3>
                    <p class="empty-description">Você ainda não cadastrou nenhum livro. Que tal começar agora?</p>
                    <a href="{{ route('books.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i>
                        Cadastrar Primeiro Livro
                    </a>
                </div>
            @endif
        </div>

        <!-- Seção: Pedidos -->
        <div id="pedidos" class="profile-section">
            <h2 class="section-title">
                <i class="fas fa-shopping-bag"></i>
                Meus Pedidos
            </h2>
            
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h3 class="empty-title">Funcionalidade em Desenvolvimento</h3>
                <p class="empty-description">A seção de pedidos está sendo desenvolvida. Em breve você poderá acompanhar seus pedidos aqui!</p>
                <a href="{{ route('books.index') }}" class="btn btn-primary">
                    <i class="fas fa-book"></i>
                    Explorar Livros
                </a>
            </div>
        </div>

        <!-- Seção: Configurações -->
        <div id="configuracoes" class="profile-section">
            <h2 class="section-title">
                <i class="fas fa-cog"></i>
                Configurações da Conta
            </h2>
            
            <form class="settings-form" action="{{ route('user.update', $user->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="fas fa-user"></i>
                        Nome Completo
                    </label>
                    <input type="text" name="name" id="name" value="{{ $user->name }}" 
                           placeholder="Digite seu nome completo" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        E-mail
                    </label>
                    <input type="email" name="email" id="email" value="{{ $user->email }}" 
                           placeholder="Digite seu e-mail" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="image" class="form-label">
                        <i class="fas fa-camera"></i>
                        Foto de Perfil
                    </label>
                    <div class="file-input-wrapper">
                        <input type="file" name="image" id="image" accept="image/*" class="file-input">
                        <div class="file-input-display">
                            <div class="file-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="file-text">
                                <div class="file-title">Clique para selecionar uma imagem</div>
                                <div class="file-subtitle">PNG, JPG ou JPEG até 2MB</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div style="text-align: center; margin-top: 2rem;">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i>
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>

        <!-- Botão Voltar -->
        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ route('index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i>
                Voltar ao Início
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Navegação entre seções
    const navTabs = document.querySelectorAll('.nav-tab');
    const sections = document.querySelectorAll('.profile-section');

    navTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetSection = this.getAttribute('data-section');
            
            // Remove classe active de todas as tabs
            navTabs.forEach(t => t.classList.remove('active'));
            // Adiciona classe active na tab clicada
            this.classList.add('active');
            
            // Esconde todas as seções
            sections.forEach(section => {
                section.classList.remove('active');
            });
            
            // Mostra a seção target
            const targetElement = document.getElementById(targetSection);
            if (targetElement) {
                targetElement.classList.add('active');
            }
        });
    });

    // Preview da imagem do avatar
    const avatarInput = document.getElementById('avatar-input');
    const avatarPreview = document.getElementById('avatar-preview');
    const fileInput = document.getElementById('image');
    const fileDisplay = document.querySelector('.file-input-display');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Atualizar display do arquivo selecionado
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileName = file.name;
                const fileTitle = fileDisplay.querySelector('.file-title');
                const fileSubtitle = fileDisplay.querySelector('.file-subtitle');
                
                fileTitle.textContent = fileName;
                fileSubtitle.textContent = `${(file.size / 1024 / 1024).toFixed(2)} MB`;
                
                fileDisplay.style.borderColor = '#667eea';
                fileDisplay.style.background = '#f0f4ff';
            }
        });
    }

    // Animação de entrada suave
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
            }
        });
    }, observerOptions);
    
    // Observar elementos para animação
    document.querySelectorAll('.animate-fade-in').forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = `all 0.6s ease-out ${index * 0.1}s`;
        observer.observe(el);
    });
});
</script>

@endsection