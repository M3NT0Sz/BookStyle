// Sistema de Wishlist (Favoritos)
window.WishlistManager = {
    // Adicionar livro à wishlist
    add: function(bookId, priceAlert = null) {
        return fetch('/wishlist/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                book_id: bookId,
                price_alert: priceAlert
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateHeartIcon(bookId, true);
                this.showNotification(data.message, 'success');
            } else {
                this.showNotification(data.message, 'error');
            }
            return data;
        })
        .catch(error => {
            console.error('Erro:', error);
            this.showNotification('Erro ao adicionar aos favoritos', 'error');
        });
    },

    // Remover livro da wishlist
    remove: function(bookId) {
        return fetch(`/wishlist/${bookId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateHeartIcon(bookId, false);
                this.showNotification(data.message, 'success');
            } else {
                this.showNotification(data.message, 'error');
            }
            return data;
        })
        .catch(error => {
            console.error('Erro:', error);
            this.showNotification('Erro ao remover dos favoritos', 'error');
        });
    },

    // Toggle (adicionar ou remover)
    toggle: function(bookId) {
        const heartBtn = document.querySelector(`[data-wishlist-btn="${bookId}"]`);
        const isFavorited = heartBtn && heartBtn.classList.contains('favorited');

        if (isFavorited) {
            return this.remove(bookId);
        } else {
            return this.add(bookId);
        }
    },

    // Verificar se livro está na wishlist
    check: function(bookId) {
        return fetch(`/wishlist/check/${bookId}`)
            .then(response => response.json())
            .then(data => {
                this.updateHeartIcon(bookId, data.in_wishlist);
                return data.in_wishlist;
            })
            .catch(error => {
                console.error('Erro:', error);
                return false;
            });
    },

    // Atualizar ícone de coração
    updateHeartIcon: function(bookId, isFavorited) {
        const heartBtn = document.querySelector(`[data-wishlist-btn="${bookId}"]`);
        if (!heartBtn) return;

        if (isFavorited) {
            heartBtn.classList.add('favorited');
            heartBtn.innerHTML = '<i class="fas fa-heart"></i>';
            heartBtn.style.color = '#ef4444';
        } else {
            heartBtn.classList.remove('favorited');
            heartBtn.innerHTML = '<i class="far fa-heart"></i>';
            heartBtn.style.color = '#6b7280';
        }
    },

    // Mostrar notificação toast
    showNotification: function(message, type = 'success') {
        // Remover notificação anterior se existir
        const existingToast = document.getElementById('wishlist-toast');
        if (existingToast) {
            existingToast.remove();
        }

        // Criar notificação
        const toast = document.createElement('div');
        toast.id = 'wishlist-toast';
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10b981' : '#ef4444'};
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            z-index: 10000;
            font-size: 14px;
            font-weight: 500;
            animation: slideIn 0.3s ease-out;
        `;
        toast.textContent = message;

        document.body.appendChild(toast);

        // Remover após 3 segundos
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    },

    // Inicializar verificação de favoritos na página
    initializePage: function() {
        const wishlistBtns = document.querySelectorAll('[data-wishlist-btn]');
        wishlistBtns.forEach(btn => {
            const bookId = btn.getAttribute('data-wishlist-btn');
            this.check(bookId);
        });
    }
};

// Adicionar animações CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Inicializar ao carregar a página
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        WishlistManager.initializePage();
    });
} else {
    WishlistManager.initializePage();
}
