// ============================================
// BOOKS PAGE INTERACTIVE FEATURES
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all books page functionality
    initializeFilters();
    initializeSearch();
    initializeViewToggle();
    initializeMobileFilters();
    initializePriceSlider();
    initializeBookActions();
});

// ============================================
// FILTERS FUNCTIONALITY
// ============================================

function initializeFilters() {
    const filterCheckboxes = document.querySelectorAll('.filter-checkbox input');
    const clearFiltersBtn = document.querySelector('.clear-filters-btn');
    const applyFiltersBtn = document.querySelector('.apply-filters-btn');
    
    // Handle filter checkbox changes
    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateFilterResults();
        });
    });
    
    // Clear all filters
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function(e) {
            e.preventDefault();
            clearAllFilters();
        });
    }
    
    // Apply filters
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function(e) {
            e.preventDefault();
            applyFilters();
        });
    }
}

function updateFilterResults() {
    const activeFilters = getActiveFilters();
    const resultsCount = document.querySelector('.results-count');
    
    // Simulate filter results (in real app, this would make an API call)
    const mockResults = Math.floor(Math.random() * 150) + 10;
    
    if (resultsCount) {
        resultsCount.textContent = `${mockResults} livros encontrados`;
    }
    
    // Add visual feedback
    showNotification('Filtros aplicados com sucesso!', 'success');
}

function getActiveFilters() {
    const activeFilters = [];
    const checkboxes = document.querySelectorAll('.filter-checkbox input:checked');
    
    checkboxes.forEach(checkbox => {
        activeFilters.push({
            type: checkbox.name,
            value: checkbox.value
        });
    });
    
    return activeFilters;
}

function clearAllFilters() {
    const checkboxes = document.querySelectorAll('.filter-checkbox input');
    const priceSlider = document.querySelector('.price-slider');
    const searchInput = document.querySelector('.search-input');
    
    // Uncheck all checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Reset price slider
    if (priceSlider) {
        priceSlider.value = priceSlider.max;
        updatePriceDisplay(priceSlider.max);
    }
    
    // Clear search input
    if (searchInput) {
        searchInput.value = '';
    }
    
    // Update results
    updateFilterResults();
    showNotification('Filtros limpos com sucesso!', 'info');
}

function applyFilters() {
    const activeFilters = getActiveFilters();
    console.log('Applying filters:', activeFilters);
    
    // In a real application, this would send the filters to the server
    // For now, we'll just show a success message
    updateFilterResults();
}

// ============================================
// SEARCH FUNCTIONALITY
// ============================================

function initializeSearch() {
    const searchForm = document.querySelector('.books-search-form');
    const searchInput = document.querySelector('.search-input');
    const quickFilters = document.querySelectorAll('.quick-filter');
    
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performSearch(searchInput.value);
        });
    }
    
    // Handle quick filter clicks
    quickFilters.forEach(filter => {
        filter.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all quick filters
            quickFilters.forEach(f => f.classList.remove('active'));
            
            // Add active class to clicked filter
            this.classList.add('active');
            
            // Perform search with filter value
            const filterValue = this.textContent.trim();
            performSearch(filterValue);
        });
    });
    
    // Real-time search (optional)
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3) {
                    performSearch(this.value);
                }
            }, 500);
        });
    }
}

function performSearch(query) {
    if (!query.trim()) return;
    
    console.log('Searching for:', query);
    
    // Simulate search results
    const mockResults = Math.floor(Math.random() * 100) + 5;
    const resultsCount = document.querySelector('.results-count');
    
    if (resultsCount) {
        resultsCount.textContent = `${mockResults} resultados para "${query}"`;
    }
    
    // Add loading effect (optional)
    showSearchLoading();
    
    // Simulate API delay
    setTimeout(() => {
        hideSearchLoading();
        showNotification(`Encontrados ${mockResults} livros para "${query}"`, 'success');
    }, 800);
}

function showSearchLoading() {
    const booksGrid = document.querySelector('.books-grid');
    if (booksGrid) {
        booksGrid.style.opacity = '0.6';
        booksGrid.style.pointerEvents = 'none';
    }
}

function hideSearchLoading() {
    const booksGrid = document.querySelector('.books-grid');
    if (booksGrid) {
        booksGrid.style.opacity = '1';
        booksGrid.style.pointerEvents = 'auto';
    }
}

// ============================================
// VIEW TOGGLE (GRID/LIST)
// ============================================

function initializeViewToggle() {
    const viewBtns = document.querySelectorAll('.view-btn');
    const booksGrid = document.querySelector('.books-grid');
    
    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const viewType = this.dataset.view;
            
            // Update active button
            viewBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Update grid view
            if (booksGrid) {
                booksGrid.classList.remove('grid-view', 'list-view');
                booksGrid.classList.add(`${viewType}-view`);
                
                // Save preference to localStorage
                localStorage.setItem('preferredView', viewType);
                
                showNotification(`Visualização alterada para ${viewType === 'grid' ? 'grade' : 'lista'}`, 'info');
            }
        });
    });
    
    // Load saved view preference
    const savedView = localStorage.getItem('preferredView');
    if (savedView && booksGrid) {
        const viewBtn = document.querySelector(`[data-view="${savedView}"]`);
        if (viewBtn) {
            viewBtn.click();
        }
    }
}

// ============================================
// MOBILE FILTERS
// ============================================

function initializeMobileFilters() {
    const mobileFiltersBtn = document.querySelector('.mobile-filters-btn');
    const filtersSidebar = document.querySelector('.filters-sidebar');
    const filtersToggle = document.querySelector('.filters-toggle');
    
    // Open mobile filters
    if (mobileFiltersBtn) {
        mobileFiltersBtn.addEventListener('click', function() {
            if (filtersSidebar) {
                filtersSidebar.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        });
    }
    
    // Close mobile filters
    if (filtersToggle) {
        filtersToggle.addEventListener('click', function() {
            if (filtersSidebar) {
                filtersSidebar.classList.remove('open');
                document.body.style.overflow = '';
            }
        });
    }
    
    // Close on backdrop click
    if (filtersSidebar) {
        filtersSidebar.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('open');
                document.body.style.overflow = '';
            }
        });
    }
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && filtersSidebar && filtersSidebar.classList.contains('open')) {
            filtersSidebar.classList.remove('open');
            document.body.style.overflow = '';
        }
    });
}

// ============================================
// PRICE SLIDER
// ============================================

function initializePriceSlider() {
    const priceSlider = document.querySelector('.price-slider');
    
    if (priceSlider) {
        priceSlider.addEventListener('input', function() {
            updatePriceDisplay(this.value);
        });
        
        priceSlider.addEventListener('change', function() {
            updateFilterResults();
        });
        
        // Initialize display
        updatePriceDisplay(priceSlider.value);
    }
}

function updatePriceDisplay(value) {
    const priceDisplay = document.querySelector('.price-display');
    if (priceDisplay) {
        priceDisplay.textContent = `Até R$ ${parseFloat(value).toFixed(2)}`;
    }
}

// ============================================
// BOOK ACTIONS
// ============================================

function initializeBookActions() {
    // Initialize all book interaction buttons
    initializeQuickView();
    initializeAddToCart();
    initializeWishlist();
}

function initializeQuickView() {
    const quickViewBtns = document.querySelectorAll('.quick-view-btn');
    
    quickViewBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const bookCard = this.closest('.book-card');
            const bookTitle = bookCard.querySelector('.book-title').textContent;
            
            // Simulate quick view modal (you would implement a real modal here)
            showNotification(`Visualização rápida: ${bookTitle}`, 'info');
            
            console.log('Quick view for book:', bookTitle);
        });
    });
}

function initializeAddToCart() {
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');
    
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = this.querySelector('.add-to-cart-btn');
            const bookId = btn.getAttribute('data-book-id');
            const bookName = btn.getAttribute('data-book-name');
            const formAction = this.getAttribute('action');
            const formData = new FormData(this);
            
            // Aplicar estado de loading imediatamente
            setButtonState(btn, 'loading');
            form.classList.add('submitting');
            
            // Enviar requisição AJAX
            fetch(formAction, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta do servidor');
                }
                return response.json();
            })
            .then(data => {
                // Sucesso
                setButtonState(btn, 'success');
                form.classList.remove('submitting');
                
                showNotification(`"${bookName}" foi adicionado ao carrinho!`, 'success');
                
                // Atualizar contador do carrinho
                updateCartCounter();
                
                // Voltar ao estado normal após 3 segundos
                setTimeout(() => {
                    setButtonState(btn, 'normal');
                }, 3000);
            })
            .catch(error => {
                console.error('Erro ao adicionar ao carrinho:', error);
                
                setButtonState(btn, 'error');
                form.classList.remove('submitting');
                
                showNotification('Erro ao adicionar ao carrinho. Tente novamente.', 'error');
                
                // Voltar ao estado normal após 2 segundos
                setTimeout(() => {
                    setButtonState(btn, 'normal');
                }, 2000);
                
                // Em caso de erro persistente, fazer submit tradicional após delay
                setTimeout(() => {
                    if (confirm('Deseja tentar novamente com recarregamento da página?')) {
                        form.classList.remove('submitting');
                        form.submit();
                    }
                }, 3000);
            });
        });
        
        // Adicionar eventos de acessibilidade
        const btn = form.querySelector('.add-to-cart-btn');
        if (btn) {
            // Suporte para navegação por teclado
            btn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
            
            // Feedback visual para focus
            btn.addEventListener('focus', function() {
                this.setAttribute('aria-pressed', 'false');
            });
            
            btn.addEventListener('blur', function() {
                this.removeAttribute('aria-pressed');
            });
        }
    });
    
    // Para botões antigos sem formulário (compatibilidade)
    const legacyAddToCartBtns = document.querySelectorAll('button[onclick*="addToCart"]');
    legacyAddToCartBtns.forEach(btn => {
        // Remove o onclick antigo
        btn.removeAttribute('onclick');
        
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            showNotification('Por favor, recarregue a página para usar a funcionalidade atualizada.', 'warning');
        });
    });
}

// Função para gerenciar estados visuais do botão
function setButtonState(btn, state) {
    if (!btn) return;
    
    // Remove todas as classes de estado
    btn.classList.remove('loading', 'success', 'error');
    
    const iconElement = btn.querySelector('i');
    const textElement = btn.querySelector('.btn-text');
    
    switch (state) {
        case 'loading':
            btn.classList.add('loading');
            btn.disabled = true;
            btn.setAttribute('aria-busy', 'true');
            
            if (iconElement) {
                iconElement.className = 'fas fa-spinner';
            }
            if (textElement) {
                textElement.textContent = 'Adicionando...';
            }
            break;
            
        case 'success':
            btn.classList.add('success');
            btn.disabled = false;
            btn.setAttribute('aria-busy', 'false');
            
            if (iconElement) {
                iconElement.className = 'fas fa-check';
            }
            if (textElement) {
                textElement.textContent = 'Adicionado!';
            }
            break;
            
        case 'error':
            btn.classList.add('error');
            btn.disabled = false;
            btn.setAttribute('aria-busy', 'false');
            
            if (iconElement) {
                iconElement.className = 'fas fa-exclamation-triangle';
            }
            if (textElement) {
                textElement.textContent = 'Erro!';
            }
            break;
            
        case 'normal':
        default:
            btn.disabled = false;
            btn.removeAttribute('aria-busy');
            
            if (iconElement) {
                iconElement.className = 'fas fa-cart-plus';
            }
            if (textElement) {
                textElement.textContent = 'Carrinho';
            }
            break;
    }
}

function initializeWishlist() {
    const wishlistBtns = document.querySelectorAll('.book-btn.secondary');
    
    wishlistBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const bookCard = this.closest('.book-card');
            const bookTitle = bookCard.querySelector('.book-title').textContent;
            
            // Toggle wishlist state
            const isInWishlist = this.classList.contains('in-wishlist');
            
            if (isInWishlist) {
                this.classList.remove('in-wishlist');
                this.innerHTML = '<i class="fas fa-heart"></i> Lista de Desejos';
                showNotification(`"${bookTitle}" removido da lista de desejos`, 'info');
            } else {
                this.classList.add('in-wishlist');
                this.innerHTML = '<i class="fas fa-heart-broken"></i> Remover da Lista';
                showNotification(`"${bookTitle}" adicionado à lista de desejos!`, 'success');
            }
        });
    });
}

function addBookToCart(bookTitle) {
    // Simulate adding to cart
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.push({
        title: bookTitle,
        addedAt: new Date().toISOString()
    });
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // Update cart counter if exists
    updateCartCounter();
}

function updateCartCounter() {
    const cartCounters = document.querySelectorAll('.cart-counter, .cart-count');
    
    if (cartCounters.length > 0) {
        // Fazer uma requisição para pegar o número real de itens do carrinho
        fetch('/cart/count', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            cartCounters.forEach(counter => {
                const newCount = data.count || 0;
                counter.textContent = newCount;
                
                // Animação de atualização
                counter.style.transform = 'scale(1.3)';
                counter.style.background = '#10b981';
                
                setTimeout(() => {
                    counter.style.transform = 'scale(1)';
                    counter.style.background = '';
                }, 200);
                
                // Mostrar/esconder contador baseado na quantidade
                if (newCount > 0) {
                    counter.style.display = 'inline-flex';
                } else {
                    counter.style.display = 'none';
                }
            });
        })
        .catch(error => {
            console.log('Não foi possível atualizar contador do carrinho:', error);
            // Fallback para localStorage
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            cartCounters.forEach(counter => {
                counter.textContent = cart.length;
                if (cart.length > 0) {
                    counter.style.display = 'inline-flex';
                } else {
                    counter.style.display = 'none';
                }
            });
        });
    }
}

// Função global para adicionar ao carrinho (para uso direto)
window.addToCart = function(bookId, bookName = 'Livro') {
    if (!bookId) {
        showNotification('ID do livro não informado', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
    formData.append('quantity', '1');
    
    // Mostrar loading
    showNotification('Adicionando ao carrinho...', 'info');
    
    fetch(`/cart/add/${bookId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }
        return response.json();
    })
    .then(data => {
        showNotification(`"${bookName}" foi adicionado ao carrinho!`, 'success');
        updateCartCounter();
    })
    .catch(error => {
        console.error('Erro ao adicionar ao carrinho:', error);
        showNotification('Erro ao adicionar ao carrinho. Tente novamente.', 'error');
    });
};

// ============================================
// NOTIFICATION SYSTEM
// ============================================

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${getNotificationIcon(type)}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${getNotificationColor(type)};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 1rem;
        max-width: 400px;
        animation: slideInRight 0.3s ease;
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Close button functionality
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        notification.remove();
    });
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }
    }, 4000);
}

function getNotificationIcon(type) {
    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };
    return icons[type] || 'info-circle';
}

function getNotificationColor(type) {
    const colors = {
        success: 'linear-gradient(135deg, #1abc9c, #16a085)',
        error: 'linear-gradient(135deg, #e74c3c, #c0392b)',
        warning: 'linear-gradient(135deg, #f39c12, #e67e22)',
        info: 'linear-gradient(135deg, #3498db, #2980b9)'
    };
    return colors[type] || colors.info;
}

// Add notification animations to CSS
const notificationStyles = document.createElement('style');
notificationStyles.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex: 1;
    }
    
    .notification-close {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        padding: 0.25rem;
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }
    
    .notification-close:hover {
        opacity: 1;
    }
`;
document.head.appendChild(notificationStyles);

// ============================================
// UTILITY FUNCTIONS
// ============================================

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Format price function
function formatPrice(price) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(price);
}

// Initialize cart counter on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartCounter();
});
