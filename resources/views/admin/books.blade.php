@extends('layouts.app')

@section('title', 'Gerenciar Livros - Admin BookStyle')

@section('content')
<style>
/* Design moderno para gerenciamento de livros */
* {
    box-sizing: border-box;
}

.admin-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow-x: hidden;
}

.admin-wrapper::before {
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

/* Header */
.page-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Controles de ação */
.controls-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
}

.controls-grid {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.export-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

/* Tabela moderna */
.table-container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.3);
    overflow: hidden;
}

.books-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
}

.books-table thead {
    background: linear-gradient(45deg, #667eea, #764ba2);
}

.books-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: white;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border: none;
}

.books-table th:first-child {
    border-radius: 12px 0 0 0;
}

.books-table th:last-child {
    border-radius: 0 12px 0 0;
}

.books-table tbody tr {
    border-bottom: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.books-table tbody tr:hover {
    background: #f8fafc;
    transform: scale(1.01);
}

.books-table td {
    padding: 1rem;
    color: #374151;
    font-size: 0.9rem;
}

.book-title-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.book-title-link:hover {
    color: #764ba2;
    text-decoration: none;
}

.user-badge {
    background: #f0f4ff;
    color: #667eea;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

/* Botões */
.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.875rem;
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
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
    text-decoration: none;
    color: white;
}

.btn-success {
    background: linear-gradient(45deg, #10b981, #059669);
    color: white;
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
    text-decoration: none;
    color: white;
}

.btn-secondary {
    background: white;
    color: #64748b;
    border: 2px solid #e5e7eb;
}

.btn-secondary:hover {
    border-color: #667eea;
    color: #667eea;
    text-decoration: none;
}

.btn-info {
    background: linear-gradient(45deg, #3b82f6, #2563eb);
    color: white;
    box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
}

.btn-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    text-decoration: none;
    color: white;
}

.btn-danger {
    background: linear-gradient(45deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
    text-decoration: none;
    color: white;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.8rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

/* Estado vazio */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
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

/* Breadcrumb */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #64748b;
    margin-bottom: 1rem;
}

/* Responsividade */
@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .controls-grid {
        flex-direction: column;
        align-items: stretch;
    }
    
    .books-table {
        font-size: 0.8rem;
    }
    
    .books-table th,
    .books-table td {
        padding: 0.75rem 0.5rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
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
</style>

<div class="admin-wrapper">
    <div class="container">
        <!-- Header -->
        <div class="page-header animate-fade-in">
            <div class="breadcrumb">
                <i class="fas fa-home"></i>
                <span>Admin</span>
                <i class="fas fa-chevron-right"></i>
                <span>Dashboard</span>
                <i class="fas fa-chevron-right"></i>
                <span>Livros</span>
            </div>
            
            <div class="header-content">
                <h1 class="page-title">
                    <i class="fas fa-book"></i>
                    Gerenciar Livros
                </h1>
                <div class="header-actions">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Controles -->
        <div class="controls-section animate-fade-in">
            <div class="controls-grid">
                <div>
                    <h3 style="margin: 0; color: #374151; font-size: 1.1rem;">
                        <i class="fas fa-download"></i>
                        Exportar Dados
                    </h3>
                    <p style="margin: 0.5rem 0 0; color: #64748b; font-size: 0.9rem;">
                        Baixe a lista completa de livros em diferentes formatos
                    </p>
                </div>
                <div class="export-actions">
                    <a href="{{ route('admin.books.export', 'json') }}" class="btn btn-success">
                        <i class="fas fa-file-code"></i>
                        Exportar JSON
                    </a>
                    <a href="{{ route('admin.books.export', 'csv') }}" class="btn btn-primary">
                        <i class="fas fa-file-csv"></i>
                        Exportar CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Tabela de Livros -->
        <div class="table-container animate-fade-in">
            @if(count($books) > 0)
                <div style="margin-bottom: 1.5rem;">
                    <h2 style="margin: 0; color: #1e293b; font-size: 1.5rem; font-weight: 700;">
                        <i class="fas fa-list"></i>
                        Livros Cadastrados
                    </h2>
                    <p style="margin: 0.5rem 0 0; color: #64748b;">
                        Total de {{ count($books) }} {{ count($books) === 1 ? 'livro encontrado' : 'livros encontrados' }}
                    </p>
                </div>

                <div style="overflow-x: auto;">
                    <table class="books-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-book"></i> Título</th>
                                <th><i class="fas fa-user-edit"></i> Autor</th>
                                <th><i class="fas fa-user"></i> Cadastrado por</th>
                                <th><i class="fas fa-cogs"></i> Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($books as $book)
                                @php
                                    $isArr = is_array($book);
                                    $id = $isArr && isset($book['id']) ? $book['id'] : ($book->id ?? '');
                                    $title = $isArr && isset($book['title']) ? $book['title'] : ($book->title ?? '');
                                    $author = $isArr && isset($book['author']) ? $book['author'] : ($book->author ?? '');
                                    $userId = $isArr && isset($book['user_id']) ? $book['user_id'] : ($book->user_id ?? null);
                                    $user = $userId ? \App\Models\User::find($userId) : null;
                                @endphp
                                <tr>
                                    <td>
                                        <strong style="color: #667eea;">#{{ $id }}</strong>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.books.show', $id) }}" class="book-title-link">
                                            {{ Str::limit($title, 40) }}
                                        </a>
                                    </td>
                                    <td>{{ Str::limit($author, 30) }}</td>
                                    <td>
                                        @if($user)
                                            <span class="user-badge">
                                                <i class="fas fa-user"></i>
                                                {{ is_array($user) ? ($user['name'] ?? 'Desconhecido') : $user->name }}
                                            </span>
                                        @else
                                            <span style="color: #9ca3af;">
                                                <i class="fas fa-question-circle"></i>
                                                Desconhecido
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.books.show', $id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                                Ver
                                            </a>
                                            <form action="{{ route('admin.books.destroy', $id) }}" method="POST" style="display:inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Tem certeza que deseja deletar este livro?\n\nTítulo: {{ $title }}\nAutor: {{ $author }}\n\nEsta ação não pode ser desfeita.')">
                                                    <i class="fas fa-trash"></i>
                                                    Deletar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3 class="empty-title">Nenhum livro cadastrado</h3>
                    <p class="empty-description">
                        Ainda não há livros cadastrados na plataforma. 
                        Os usuários podem cadastrar livros através da área de usuário.
                    </p>
                    <a href="{{ route('books.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Cadastrar Primeiro Livro
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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

    // Hover effects nas linhas da tabela
    const tableRows = document.querySelectorAll('.books-table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
            this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = 'none';
        });
    });
});
</script>

@endsection
