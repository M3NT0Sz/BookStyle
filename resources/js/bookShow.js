// bookShow.js - Versão Corrigida para Galeria

document.addEventListener('DOMContentLoaded', function() {
    console.log('=== INICIANDO GALERIA DE IMAGENS ===');
    
    // Animação ao adicionar ao carrinho (corrigida: agora só anima no submit, não no click)
    const form = document.querySelector('.add-cart-form');
    if (form) {
        form.addEventListener('submit', function() {
            const addCartBtn = form.querySelector('button[type="submit"]');
            if (addCartBtn) {
                addCartBtn.style.background = 'linear-gradient(135deg, #059669 0%, #10b981 100%)';
                addCartBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Adicionando...';
                addCartBtn.disabled = true;
            }
        });
    }
    // Removido: evento de click do botão de adicionar ao carrinho

    // GALERIA DE IMAGENS - VERSÃO CORRIGIDA
    const mainImg = document.getElementById('mainBookImage');
    const thumbs = document.querySelectorAll('.book-gallery-thumb');
    
    console.log('Imagem principal encontrada:', !!mainImg);
    console.log('Número de miniaturas encontradas:', thumbs.length);
    
    if (mainImg && thumbs.length > 0) {
        // Debug das URLs das imagens
        console.log('URL da imagem principal:', mainImg.src);
        thumbs.forEach(function(thumb, index) {
            const imgUrl = thumb.getAttribute('data-img');
            console.log(`Miniatura ${index + 1} URL:`, imgUrl);
        });

        // Precarregamento das imagens
        const preloadImages = [];
        thumbs.forEach(function(thumb) {
            const imgUrl = thumb.getAttribute('data-img');
            if (imgUrl) {
                const preloadImg = new Image();
                preloadImg.src = imgUrl;
                preloadImages.push(preloadImg);
            }
        });

        // Função para normalizar URL (remove diferenças de formatação)
        function normalizeUrl(url) {
            return url.replace(/\\/g, '/').toLowerCase().trim();
        }

        // Adicionar eventos de clique nas miniaturas
        thumbs.forEach(function(thumb, index) {
            thumb.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                console.log(`=== CLIQUE NA MINIATURA ${index + 1} ===`);
                
                const newSrc = this.getAttribute('data-img');
                console.log('Nova URL:', newSrc);
                console.log('URL atual da imagem principal:', mainImg.src);
                
                if (!newSrc) {
                    console.log('Erro: data-img não encontrado');
                    return;
                }
                
                // Remove seleção de todas as miniaturas
                thumbs.forEach(function(t) { 
                    t.classList.remove('selected'); 
                });
                
                // Adiciona seleção na miniatura clicada
                this.classList.add('selected');
                console.log('Miniatura selecionada:', index + 1);
                
                // Sempre troca a imagem, mesmo que seja a mesma (para garantir funcionamento)
                console.log('Iniciando troca da imagem...');
                
                // Efeito de transição
                mainImg.style.transition = 'all 0.3s ease';
                mainImg.style.opacity = '0.3';
                mainImg.style.transform = 'scale(0.95)';
                
                // Aguarda a transição e troca a imagem
                setTimeout(() => {
                    mainImg.src = newSrc;
                    mainImg.alt = `Imagem do livro ${index + 1}`;
                    
                    // Verifica se a imagem carregou
                    mainImg.onload = function() {
                        console.log('Imagem carregada com sucesso:', newSrc);
                        mainImg.style.opacity = '1';
                        mainImg.style.transform = 'scale(1)';
                    };
                    
                    mainImg.onerror = function() {
                        console.log('Erro ao carregar imagem:', newSrc);
                        // Volta para opacity normal mesmo com erro
                        mainImg.style.opacity = '1';
                        mainImg.style.transform = 'scale(1)';
                    };
                    
                    // Fallback caso onload não funcione
                    setTimeout(() => {
                        if (mainImg.style.opacity !== '1') {
                            mainImg.style.opacity = '1';
                            mainImg.style.transform = 'scale(1)';
                        }
                    }, 500);
                    
                }, 150);
                
                console.log('=== FIM DO CLIQUE ===');
            });

            // Efeito hover nas miniaturas
            thumb.addEventListener('mouseenter', function() {
                if (!this.classList.contains('selected')) {
                    this.style.transition = 'all 0.3s ease';
                    this.style.transform = 'translateY(-0.125em) scale(1.05)';
                    this.style.zIndex = '10';
                }
            });

            thumb.addEventListener('mouseleave', function() {
                if (!this.classList.contains('selected')) {
                    this.style.transform = '';
                    this.style.zIndex = '';
                }
            });
        });

        // Navegação por teclado
        document.addEventListener('keydown', function(e) {
            if (thumbs.length > 1) {
                const currentSelected = document.querySelector('.book-gallery-thumb.selected');
                if (currentSelected) {
                    let currentIndex = Array.from(thumbs).indexOf(currentSelected);
                    
                    if (e.key === 'ArrowLeft' && currentIndex > 0) {
                        e.preventDefault();
                        thumbs[currentIndex - 1].click();
                    } else if (e.key === 'ArrowRight' && currentIndex < thumbs.length - 1) {
                        e.preventDefault();
                        thumbs[currentIndex + 1].click();
                    }
                }
            }
        });

        // Garantir que a primeira miniatura esteja selecionada
        if (thumbs.length > 0) {
            const firstSelected = document.querySelector('.book-gallery-thumb.selected');
            if (!firstSelected) {
                console.log('Selecionando primeira miniatura...');
                thumbs[0].classList.add('selected');
            }
        }
        
        console.log('Galeria inicializada com sucesso!');
    } else {
        console.log('Galeria não encontrada ou sem miniaturas');
    }

    // Efeito parallax sutil na imagem principal
    if (mainImg) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.1;
            if (Math.abs(rate) < 100) { // Limita o movimento
                mainImg.style.transform = `translateY(${rate}px)`;
            }
        });
    }

    // Animação de entrada dos elementos
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observa elementos para animação
    const animatedElements = document.querySelectorAll('.book-meta > span, .book-desc, .book-purchase-row');
    animatedElements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(1em)';
        el.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(el);
    });

    // Efeito de zoom na imagem principal
    if (mainImg) {
        mainImg.addEventListener('click', function() {
            const overlay = document.createElement('div');
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.8);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                cursor: zoom-out;
                opacity: 0;
                transition: opacity 0.3s ease;
            `;
            
            const zoomedImg = document.createElement('img');
            zoomedImg.src = this.src;
            zoomedImg.style.cssText = `
                max-width: 90%;
                max-height: 90%;
                object-fit: contain;
                border-radius: 0.5em;
                box-shadow: 0 1em 3em rgba(0,0,0,0.5);
                transform: scale(0.8);
                transition: transform 0.3s ease;
            `;
            
            overlay.appendChild(zoomedImg);
            document.body.appendChild(overlay);
            
            setTimeout(() => {
                overlay.style.opacity = '1';
                zoomedImg.style.transform = 'scale(1)';
            }, 10);
            
            overlay.addEventListener('click', function() {
                overlay.style.opacity = '0';
                zoomedImg.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    if (document.body.contains(overlay)) {
                        document.body.removeChild(overlay);
                    }
                }, 300);
            });
        });
        
        mainImg.style.cursor = 'zoom-in';
    }

    // Sistema de notificações
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 2em;
            right: 2em;
            background: ${type === 'error' ? '#ef4444' : '#22c55e'};
            color: white;
            padding: 1em 1.5em;
            border-radius: 0.5em;
            box-shadow: 0 0.25em 1em rgba(0,0,0,0.2);
            z-index: 1001;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            font-weight: 500;
        `;
        notification.textContent = message;
        
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
        }, 3000);
    }

    console.log('=== SCRIPT CARREGADO COMPLETAMENTE ===');
});