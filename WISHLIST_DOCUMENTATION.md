# Sistema de Lista de Desejos (Wishlist) - Documentação

## ✅ Implementado

### 1. **Database & Models**
- ✅ Migration `wishlists` table com campos: user_id, book_id, price_alert, notified
- ✅ Model `Wishlist` com relationships (User, Book)
- ✅ Métodos no User model: `hasInWishlist()`, `addToWishlist()`, `removeFromWishlist()`

### 2. **Strategy Pattern - Recomendações**
- ✅ `RecommendationStrategyInterface` - Interface base
- ✅ `PurchaseHistoryStrategy` - Recomenda baseado em compras anteriores
- ✅ `WishlistBasedStrategy` - Recomenda baseado na wishlist
- ✅ `PopularBooksStrategy` - Recomenda livros populares
- ✅ `RecommendationService` - Serviço que orquestra as estratégias

### 3. **Controller & Routes**
- ✅ `WishlistController` com métodos:
  - `index()` - Listar favoritos com recomendações
  - `store()` - Adicionar à wishlist
  - `destroy()` - Remover da wishlist
  - `check()` - Verificar se está na wishlist
  - `updatePriceAlert()` - Atualizar alerta de preço
  - `count()` - Contar itens
- ✅ Rotas registradas em `web.php`

### 4. **Views**
- ✅ `wishlist/index.blade.php` - Página completa com:
  - Grid de livros favoritos
  - Alerta de preço individual
  - Botões de ação (remover, ver detalhes, adicionar ao carrinho)
  - Seção de recomendações baseadas em Strategy pattern
- ✅ Link "Favoritos" adicionado no navbar

### 5. **JavaScript**
- ✅ `public/js/wishlist.js` - Gerenciador global com:
  - `WishlistManager.add(bookId, priceAlert)` - Adicionar
  - `WishlistManager.remove(bookId)` - Remover
  - `WishlistManager.toggle(bookId)` - Toggle favorito
  - `WishlistManager.check(bookId)` - Verificar status
  - Sistema de notificações toast
  - Inicialização automática de ícones

---

## 📝 Como Adicionar Botão de Favoritar nos Cards

### Exemplo 1: Card Simples (HTML)

```html
<div class="book-card">
    <img src="..." alt="...">
    
    <!-- Botão de Favoritar -->
    @auth
        <button onclick="WishlistManager.toggle({{ $book->id }}); event.stopPropagation();" 
                data-wishlist-btn="{{ $book->id }}"
                style="position: absolute; top: 10px; right: 10px; background: white; border: none; border-radius: 50%; width: 40px; height: 40px; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s;"
                title="Adicionar aos favoritos">
            <i class="far fa-heart"></i>
        </button>
    @endauth
    
    <h3>{{ $book->name }}</h3>
    <p>R$ {{ number_format($book->price, 2, ',', '.') }}</p>
</div>
```

### Exemplo 2: Com Ícone Estilizado

```html
<div class="relative">
    <img src="..." class="...">
    
    @auth
        <button onclick="WishlistManager.toggle({{ $book->id }}); event.stopPropagation();" 
                data-wishlist-btn="{{ $book->id }}"
                class="absolute top-3 right-3 bg-white text-gray-600 hover:text-red-500 p-2 rounded-full shadow-lg transition duration-300 hover:scale-110"
                title="Favoritar">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </button>
    @endauth
</div>
```

### Exemplo 3: Com Badge de Status

```html
<div class="book-card">
    <div class="relative">
        <img src="...">
        
        @auth
            <button onclick="WishlistManager.toggle({{ $book->id }}); event.stopPropagation();" 
                    data-wishlist-btn="{{ $book->id }}"
                    class="wishlist-btn">
                <i class="far fa-heart"></i>
            </button>
            
            @if(Auth::user()->hasInWishlist($book->id))
                <span class="absolute top-12 right-3 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                    ❤️ Favorito
                </span>
            @endif
        @endauth
    </div>
</div>

<style>
.wishlist-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    cursor: pointer;
    transition: all 0.3s;
    color: #6b7280;
}

.wishlist-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.wishlist-btn.favorited {
    color: #ef4444;
}
</style>
```

---

## 🔧 Funcionalidades Extras

### 1. Alerta de Preço
Usuários podem definir um preço desejado e serão notificados quando o livro ficar mais barato:

```php
WishlistManager.add(bookId, 25.00); // Adiciona com alerta de R$ 25,00
```

### 2. Verificar Múltiplos Livros
```javascript
// Ao carregar página com vários livros
WishlistManager.initializePage(); // Já é chamado automaticamente
```

### 3. Contagem de Favoritos
```javascript
fetch('/wishlist/count')
    .then(r => r.json())
    .then(data => console.log(`Você tem ${data.count} favoritos`));
```

---

## 🎨 Estilos Recomendados (CSS)

```css
/* Botão de Favoritar - Estilo moderno */
.wishlist-heart-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 44px;
    height: 44px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(239, 68, 68, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 10;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.wishlist-heart-btn:hover {
    transform: scale(1.15);
    box-shadow: 0 4px 16px rgba(239, 68, 68, 0.25);
    border-color: rgba(239, 68, 68, 0.3);
}

.wishlist-heart-btn i {
    font-size: 20px;
    color: #6b7280;
    transition: all 0.3s;
}

.wishlist-heart-btn.favorited i {
    color: #ef4444;
}

.wishlist-heart-btn:active {
    transform: scale(0.95);
}

/* Animação de coração batendo */
@keyframes heartBeat {
    0%, 100% { transform: scale(1); }
    25% { transform: scale(1.2); }
    50% { transform: scale(1); }
}

.wishlist-heart-btn.favorited {
    animation: heartBeat 0.5s ease-in-out;
}
```

---

## 📊 Strategy Pattern - Detalhes

### Como Funciona

```php
$service = new RecommendationService();

// 1. Usar estratégia específica
$service->setStrategy(new PurchaseHistoryStrategy());
$recommendations = $service->getRecommendations($userId, 5);

// 2. Recomendações inteligentes (tenta múltiplas estratégias)
$recommendations = $service->getSmartRecommendations($userId, 5);

// 3. Recomendações mistas (combina todas)
$recommendations = $service->getMixedRecommendations($userId, 2);
```

### Criar Nova Estratégia

```php
namespace App\Strategies;

class GenreBasedStrategy implements RecommendationStrategyInterface
{
    public function getRecommendations(int $userId, int $limit = 5): array
    {
        // Sua lógica aqui
        return $books;
    }
}
```

---

## 🚀 Próximos Passos (Opcional)

- [ ] Sistema de notificação quando preço baixar (já tem alerta de preço)
- [ ] Compartilhar wishlist (link público)
- [ ] Estatísticas de wishlist (gêneros favoritos, etc)
- [ ] Wishlist colaborativa (família/amigos)
- [ ] Exportar wishlist para PDF
- [ ] Integração com cupons (desconto em livros da wishlist)

---

## ✅ Testes

### Testar no navegador:

1. **Adicionar favorito**: Faça login, clique no coração em qualquer livro
2. **Ver favoritos**: Acesse `/wishlist`
3. **Alerta de preço**: Na página de favoritos, defina um preço e clique "Salvar"
4. **Recomendações**: Veja sugestões baseadas em suas preferências
5. **Remover**: Clique no botão de remoção ou clique novamente no coração

### Testar via Tinker:

```php
php artisan tinker

$user = User::find(1);

// Adicionar livro
$user->addToWishlist(10, 25.00);

// Verificar
$user->hasInWishlist(10);

// Listar wishlist
$user->wishlist;

// Remover
$user->removeFromWishlist(10);

// Testar recomendações
$service = new App\Services\RecommendationService();
$recommendations = $service->getSmartRecommendations(1, 5);
```
