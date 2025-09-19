// Home Page Interactive Features
document.addEventListener('DOMContentLoaded', function() {
    
    // Mobile Menu Toggle
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');
    
    if (hamburger && navLinks) {
        hamburger.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
    }

    // Global function for mobile menu (for backward compatibility)
    window.toggleMobileMenu = function() {
        const navLinks = document.querySelector('.nav-links');
        const hamburger = document.querySelector('.hamburger');
        if (navLinks && hamburger) {
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');
        }
    };

    // Navbar scroll effect
    const navContainer = document.querySelector('.nav-container');
    if (navContainer) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navContainer.style.background = 'rgba(255, 255, 255, 0.98)';
                navContainer.style.backdropFilter = 'blur(15px)';
            } else {
                navContainer.style.background = 'rgba(255, 255, 255, 0.95)';
                navContainer.style.backdropFilter = 'blur(10px)';
            }
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Books Carousel
    function initBooksCarousel() {
        const carousels = document.querySelectorAll('.books-carousel');
        
        carousels.forEach(carousel => {
            const container = carousel.querySelector('.books-container');
            const prevBtn = carousel.querySelector('.carousel-prev');
            const nextBtn = carousel.querySelector('.carousel-next');
            
            if (!container || !prevBtn || !nextBtn) return;

            const scrollAmount = 300;
            
            prevBtn.addEventListener('click', () => {
                container.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            });
            
            nextBtn.addEventListener('click', () => {
                container.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });

            // Update button states based on scroll position
            function updateButtonStates() {
                const scrollLeft = container.scrollLeft;
                const maxScroll = container.scrollWidth - container.clientWidth;
                
                prevBtn.disabled = scrollLeft <= 0;
                nextBtn.disabled = scrollLeft >= maxScroll - 10;
            }

            container.addEventListener('scroll', updateButtonStates);
            updateButtonStates(); // Initial state
        });
    }

    // Initialize carousel after DOM is ready
    initBooksCarousel();

    // Search functionality
    const searchForm = document.querySelector('.search-form');
    const searchInput = document.querySelector('.search-input');
    
    if (searchForm && searchInput) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = searchInput.value.trim();
            if (query) {
                // Redirect to books page with search query
                window.location.href = `/books?search=${encodeURIComponent(query)}`;
            }
        });

        // Search suggestions (placeholder for future implementation)
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length >= 2) {
                // Here you could implement search suggestions
                console.log('Search query:', query);
            }
        });
    }

    // Newsletter form
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = this.querySelector('.newsletter-input');
            const email = emailInput.value.trim();
            
            if (validateEmail(email)) {
                // Simulate newsletter subscription
                showNotification('Obrigado! Você foi inscrito em nossa newsletter.', 'success');
                emailInput.value = '';
            } else {
                showNotification('Por favor, insira um email válido.', 'error');
            }
        });
    }

    // Email validation helper
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Notification system
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-icon">
                    ${type === 'success' ? '✓' : type === 'error' ? '⚠' : 'ℹ'}
                </span>
                <span class="notification-text">${message}</span>
            </div>
        `;

        // Add notification styles if not already present
        if (!document.querySelector('#notification-styles')) {
            const styles = document.createElement('style');
            styles.id = 'notification-styles';
            styles.textContent = `
                .notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: white;
                    padding: 1rem 1.5rem;
                    border-radius: 10px;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                    z-index: 10000;
                    opacity: 0;
                    transform: translateX(100%);
                    transition: all 0.3s ease;
                    max-width: 300px;
                }
                .notification.show {
                    opacity: 1;
                    transform: translateX(0);
                }
                .notification-content {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }
                .notification-icon {
                    font-weight: bold;
                    font-size: 1.2rem;
                }
                .notification-success {
                    border-left: 4px solid #1abc9c;
                }
                .notification-success .notification-icon {
                    color: #1abc9c;
                }
                .notification-error {
                    border-left: 4px solid #e74c3c;
                }
                .notification-error .notification-icon {
                    color: #e74c3c;
                }
                .notification-info {
                    border-left: 4px solid #3498db;
                }
                .notification-info .notification-icon {
                    color: #3498db;
                }
            `;
            document.head.appendChild(styles);
        }

        document.body.appendChild(notification);

        // Show notification
        setTimeout(() => notification.classList.add('show'), 100);

        // Hide and remove notification
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                
                // Add stagger animation for cards
                if (entry.target.classList.contains('category-card') || 
                    entry.target.classList.contains('book-card') ||
                    entry.target.classList.contains('testimonial-card')) {
                    const cards = entry.target.parentNode.children;
                    Array.from(cards).forEach((card, index) => {
                        if (card === entry.target) return;
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, index * 100);
                    });
                }
            }
        });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('.category-card, .book-card, .testimonial-card, .feature-item').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

    // Counter animation for hero stats
    function animateCounters() {
        const counters = document.querySelectorAll('.stat-number');
        counters.forEach(counter => {
            const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
            const increment = target / 100;
            let current = 0;
            
            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    counter.textContent = Math.ceil(current).toLocaleString() + '+';
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target.toLocaleString() + '+';
                }
            };
            
            updateCounter();
        });
    }

    // Trigger counter animation when hero section is visible
    const heroObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                heroObserver.unobserve(entry.target);
            }
        });
    });

    const heroStats = document.querySelector('.hero-stats');
    if (heroStats) {
        heroObserver.observe(heroStats);
    }

    // Book rating interaction
    document.querySelectorAll('.book-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            const stars = this.querySelectorAll('.star');
            stars.forEach((star, index) => {
                setTimeout(() => {
                    star.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        star.style.transform = 'scale(1)';
                    }, 150);
                }, index * 50);
            });
        });
    });

    // Add to cart functionality (placeholder)
    document.querySelectorAll('.btn-primary').forEach(btn => {
        if (btn.textContent.includes('Carrinho') || btn.textContent.includes('Cart')) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const bookCard = this.closest('.book-card');
                const bookTitle = bookCard.querySelector('.book-title')?.textContent;
                showNotification(`"${bookTitle}" foi adicionado ao carrinho!`, 'success');
            });
        }
    });

    // Carousel functionality for books sections
    window.moveCarousel = function(carouselId, direction) {
        const carousel = document.getElementById(carouselId);
        if (!carousel) return;
        
        const booksGrid = carousel.querySelector('.books-grid');
        if (!booksGrid) return;
        
        const cardWidth = 300; // Card width + gap
        const visibleCards = Math.floor(booksGrid.parentElement.offsetWidth / cardWidth);
        const scrollAmount = cardWidth * visibleCards * direction;
        
        booksGrid.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
        
        // Update button states after scroll
        setTimeout(() => {
            updateCarouselButtons(carouselId);
        }, 300);
    };

    // Update carousel button states
    function updateCarouselButtons(carouselId) {
        const carousel = document.getElementById(carouselId);
        if (!carousel) return;
        
        const booksGrid = carousel.querySelector('.books-grid');
        const prevBtn = carousel.querySelector('.carousel-btn.prev');
        const nextBtn = carousel.querySelector('.carousel-btn.next');
        
        if (!booksGrid || !prevBtn || !nextBtn) return;
        
        const scrollLeft = booksGrid.scrollLeft;
        const maxScroll = booksGrid.scrollWidth - booksGrid.clientWidth;
        
        // Update button states
        prevBtn.disabled = scrollLeft <= 10;
        nextBtn.disabled = scrollLeft >= maxScroll - 10;
        
        // Update button opacity
        prevBtn.style.opacity = prevBtn.disabled ? '0.5' : '1';
        nextBtn.style.opacity = nextBtn.disabled ? '0.5' : '1';
    }

    // Initialize carousel buttons and scroll listeners
    function initializeCarousels() {
        const carousels = ['newBooksCarousel', 'usedBooksCarousel'];
        
        carousels.forEach(carouselId => {
            const carousel = document.getElementById(carouselId);
            if (!carousel) return;
            
            const booksGrid = carousel.querySelector('.books-grid');
            if (!booksGrid) return;
            
            // Add scroll event listener
            booksGrid.addEventListener('scroll', () => {
                updateCarouselButtons(carouselId);
            });
            
            // Initial button state
            updateCarouselButtons(carouselId);
            
            // Add touch/swipe support for mobile
            let isDown = false;
            let startX;
            let scrollLeftStart;
            
            booksGrid.addEventListener('mousedown', (e) => {
                isDown = true;
                startX = e.pageX - booksGrid.offsetLeft;
                scrollLeftStart = booksGrid.scrollLeft;
                booksGrid.style.cursor = 'grabbing';
            });
            
            booksGrid.addEventListener('mouseleave', () => {
                isDown = false;
                booksGrid.style.cursor = 'grab';
            });
            
            booksGrid.addEventListener('mouseup', () => {
                isDown = false;
                booksGrid.style.cursor = 'grab';
            });
            
            booksGrid.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - booksGrid.offsetLeft;
                const walk = (x - startX) * 2;
                booksGrid.scrollLeft = scrollLeftStart - walk;
            });
        });
    }

    // Initialize carousels after DOM is ready
    initializeCarousels();

    // Quick view functionality
    window.quickView = function(bookId) {
        showNotification('Visualização rápida em desenvolvimento', 'info');
        // Here you could implement a modal or redirect to book details
    };

    // Add to cart from overlay
    window.addToCart = function(bookId) {
        showNotification('Livro adicionado ao carrinho!', 'success');
        // Here you could implement actual cart functionality
    };

    console.log('BookStyle Home - JavaScript loaded successfully!');
});
