// ===============================================
// BOOK DETAILS PAGE - JAVASCRIPT MODERNO
// ===============================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('=== INICIALIZANDO PÁGINA DE DETALHES ===');
    
    // Funcionalidades da Galeria de Imagens
    initializeGallery();
    
    // Funcionalidades do Formulário
    initializePurchaseForm();
    
    // Funcionalidades dos Modais
    initializeModals();
    
    // Animações e Efeitos
    initializeAnimations();
    
    console.log('=== PÁGINA INICIALIZADA ===');
});

// ===============================================
// GALERIA DE IMAGENS
// ===============================================
function initializeGallery() {
    const mainImage = document.querySelector('.main-image img');
    const thumbnails = document.querySelectorAll('.thumbnail');
    
    if (!mainImage || thumbnails.length === 0) {
        console.log('Galeria não encontrada ou sem miniaturas');
        return;
    }
    
    console.log(`Galeria inicializada: ${thumbnails.length} thumbnails`);
    
    // Adicionar eventos de clique nas thumbnails
    thumbnails.forEach((thumb, index) => {
        thumb.addEventListener('click', function() {
            changeMainImage(this.querySelector('img').src, this);
        });
    });
    
    // Garantir que a primeira thumbnail esteja ativa
    if (thumbnails.length > 0) {
        thumbnails[0].classList.add('active');
    }
}

function changeMainImage(newSrc, clickedThumb) {
    const mainImage = document.querySelector('.main-image img');
    const thumbnails = document.querySelectorAll('.thumbnail');
    
    if (!mainImage) return;
    
    // Remover classe active de todas as thumbnails
    thumbnails.forEach(thumb => thumb.classList.remove('active'));
    
    // Adicionar classe active na thumbnail clicada
    if (clickedThumb) {
        clickedThumb.classList.add('active');
    }
    
    // Efeito de transição
    mainImage.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    mainImage.style.opacity = '0.3';
    mainImage.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        mainImage.src = newSrc;
        mainImage.onload = function() {
            mainImage.style.opacity = '1';
            mainImage.style.transform = 'scale(1)';
        };
    }, 150);
}

// ===============================================
// FORMULÁRIO DE COMPRA
// ===============================================
function initializePurchaseForm() {
    // Controles de quantidade
    window.increaseQuantity = function() {
        const quantityInput = document.getElementById('quantity');
        if (quantityInput) {
            const currentValue = parseInt(quantityInput.value) || 1;
            const maxValue = parseInt(quantityInput.max) || 10;
            
            if (currentValue < maxValue) {
                quantityInput.value = currentValue + 1;
                updatePrice();
            }
        }
    };
    
    window.decreaseQuantity = function() {
        const quantityInput = document.getElementById('quantity');
        if (quantityInput) {
            const currentValue = parseInt(quantityInput.value) || 1;
            const minValue = parseInt(quantityInput.min) || 1;
            
            if (currentValue > minValue) {
                quantityInput.value = currentValue - 1;
                updatePrice();
            }
        }
    };
    
    // Validação de quantidade
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('input', function() {
            const value = parseInt(this.value);
            const min = parseInt(this.min) || 1;
            const max = parseInt(this.max) || 10;
            
            if (value < min) this.value = min;
            if (value > max) this.value = max;
            
            updatePrice();
        });
    }
    
    // Animação do botão de adicionar ao carrinho
    const addToCartForm = document.querySelector('.purchase-form');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('.add-to-cart-btn');
            if (submitBtn) {
                submitBtn.style.pointerEvents = 'none';
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adicionando...';
                
                // Simular delay para mostrar o loading
                setTimeout(() => {
                    // O form será submetido normalmente
                }, 500);
            }
        });
    }
}

function updatePrice() {
    // Esta função pode ser expandida para calcular preços dinâmicos
    // Por enquanto, apenas atualiza visualmente se necessário
    const quantity = parseInt(document.getElementById('quantity')?.value) || 1;
    console.log('Quantidade atualizada:', quantity);
}

// ===============================================
// MODAIS E OVERLAYS
// ===============================================
function initializeModals() {
    // Modal de imagem
    window.openImageModal = function() {
        const mainImage = document.querySelector('.main-image img');
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        
        if (mainImage && modal && modalImage) {
            modalImage.src = mainImage.src;
            modal.style.display = 'flex';
            
            setTimeout(() => {
                modal.style.opacity = '1';
            }, 10);
        }
    };
    
    window.closeImageModal = function() {
        const modal = document.getElementById('imageModal');
        if (modal) {
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    };
    
    // Fechar modal com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
}

// ===============================================
// FUNÇÕES UTILITÁRIAS
// ===============================================
window.shareBook = function() {
    if (navigator.share) {
        const bookTitle = document.querySelector('.book-title')?.textContent || 'Livro';
        const bookUrl = window.location.href;
        
        navigator.share({
            title: bookTitle,
            text: `Confira este livro: ${bookTitle}`,
            url: bookUrl
        }).catch(err => console.log('Erro ao compartilhar:', err));
    } else {
        // Fallback para navegadores sem suporte ao Web Share API
        const bookUrl = window.location.href;
        navigator.clipboard.writeText(bookUrl).then(() => {
            showNotification('Link copiado para a área de transferência!', 'success');
        }).catch(() => {
            showNotification('Não foi possível copiar o link', 'error');
        });
    }
};

window.addToWishlist = function() {
    // Implementar funcionalidade de lista de desejos
    showNotification('Funcionalidade em desenvolvimento', 'info');
};

// ===============================================
// ANIMAÇÕES E EFEITOS
// ===============================================
function initializeAnimations() {
    // Intersection Observer para animações de entrada
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                
                // Remove o observer após a animação
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Elementos para animar
    const animatedElements = document.querySelectorAll([
        '.book-header',
        '.book-description', 
        '.book-specs',
        '.purchase-card'
    ].join(','));
    
    animatedElements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(el);
    });
    
    // Efeito parallax sutil
    let ticking = false;
    
    function updateParallax() {
        const scrolled = window.pageYOffset;
        const mainImage = document.querySelector('.main-image');
        
        if (mainImage) {
            const rate = scrolled * -0.05;
            mainImage.style.transform = `translateY(${rate}px)`;
        }
        
        ticking = false;
    }
    
    function requestTick() {
        if (!ticking) {
            requestAnimationFrame(updateParallax);
            ticking = true;
        }
    }
    
    window.addEventListener('scroll', requestTick, { passive: true });
}

// ===============================================
// SISTEMA DE NOTIFICAÇÕES
// ===============================================
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const typeIcons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        info: 'fas fa-info-circle',
        warning: 'fas fa-exclamation-triangle'
    };
    
    const typeColors = {
        success: '#10b981',
        error: '#ef4444',
        info: '#3b82f6',
        warning: '#f59e0b'
    };
    
    notification.innerHTML = `
        <i class="${typeIcons[type] || typeIcons.info}"></i>
        <span>${message}</span>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 2rem;
        right: 2rem;
        background: ${typeColors[type] || typeColors.info};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        z-index: 10001;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        max-width: 300px;
        min-width: 250px;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

// ===============================================
// NAVEGAÇÃO POR TECLADO
// ===============================================
document.addEventListener('keydown', function(e) {
    const thumbnails = document.querySelectorAll('.thumbnail');
    
    if (thumbnails.length > 1) {
        const currentActive = document.querySelector('.thumbnail.active');
        if (currentActive) {
            const currentIndex = Array.from(thumbnails).indexOf(currentActive);
            
            if (e.key === 'ArrowLeft' && currentIndex > 0) {
                e.preventDefault();
                thumbnails[currentIndex - 1].click();
            } else if (e.key === 'ArrowRight' && currentIndex < thumbnails.length - 1) {
                e.preventDefault();
                thumbnails[currentIndex + 1].click();
            }
        }
    }
});

// ===============================================
// LAZY LOADING DE IMAGENS
// ===============================================
function initializeLazyLoading() {
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
}