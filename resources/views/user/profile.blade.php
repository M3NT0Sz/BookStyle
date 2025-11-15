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

/* Seção de pedidos */
.orders-list {
    display: flex;
    flex-direction: column;
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
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border-color: #667eea;
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.order-info {
    flex: 1;
}

.order-number {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.order-date {
    color: #64748b;
    font-size: 0.9rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-processing {
    background: #dbeafe;
    color: #1e40af;
}

.status-shipped {
    background: #e0e7ff;
    color: #3730a3;
}

.status-delivered {
    background: #d1fae5;
    color: #065f46;
}

.status-cancelled {
    background: #fee2e2;
    color: #991b1b;
}

.order-items {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.order-item-mini {
    display: flex;
    gap: 1rem;
    align-items: center;
    padding: 0.75rem;
    background: white;
    border-radius: 10px;
}

.order-item-mini img {
    width: 60px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.item-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.item-name {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.item-qty {
    color: #64748b;
    font-size: 0.85rem;
}

.btn-review-mini {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.4rem 0.8rem;
    background: linear-gradient(45deg, #f59e0b, #f97316);
    color: white;
    border: none;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    margin-top: 0.25rem;
}

.btn-review-mini:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
}

.btn-review-mini i {
    font-size: 0.75rem;
}

.reviewed-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.4rem 0.8rem;
    background: #d1fae5;
    color: #065f46;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-top: 0.25rem;
}

.reviewed-badge i {
    font-size: 0.75rem;
}

.more-items {
    color: #667eea;
    font-weight: 600;
    font-size: 0.9rem;
    text-align: center;
    margin-top: 0.5rem;
}

.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
    gap: 1rem;
    flex-wrap: wrap;
}

.order-total {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.order-total span {
    color: #64748b;
    font-size: 0.85rem;
}

.order-total strong {
    color: #059669;
    font-size: 1.5rem;
}

.order-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    white-space: nowrap;
}

.btn-warning {
    background: linear-gradient(45deg, #f59e0b, #f97316);
    color: white;
    box-shadow: 0 5px 15px rgba(245, 158, 11, 0.3);
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
}

.logout-form {
    margin-top: 1.5rem;
}

.btn-logout {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 2rem;
    background: linear-gradient(45deg, #ef4444, #dc2626);
    color: white;
    border: none;
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
}

.btn-logout:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
    background: linear-gradient(45deg, #dc2626, #b91c1c);
}

.btn-logout i {
    font-size: 1.1rem;
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

/* Notificações */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 1rem 1.5rem;
    border-radius: 10px;
    background: white;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
    z-index: 9999;
    transform: translateX(400px);
    opacity: 0;
    transition: all 0.3s ease;
}

.notification.show {
    transform: translateX(0);
    opacity: 1;
}

.notification-success {
    color: #065f46;
    background: #d1fae5;
    border-left: 4px solid #10b981;
}

.notification-error {
    color: #991b1b;
    background: #fee2e2;
    border-left: 4px solid #ef4444;
}

.notification i {
    font-size: 1.25rem;
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
    
    .order-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .order-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .order-actions {
        width: 100%;
    }
    
    .order-actions .btn {
        flex: 1;
        justify-content: center;
    }
    
    .order-total strong {
        font-size: 1.25rem;
    }
}

@media (max-width: 480px) {
    .profile-stats {
        grid-template-columns: 1fr;
    }
    
    .book-actions {
        flex-direction: column;
    }
    
    .order-card {
        padding: 1rem;
    }
    
    .order-item-mini {
        flex-direction: column;
        text-align: center;
    }
    
    .order-item-mini img {
        width: 80px;
        height: 100px;
    }
}

/* Estilos para seção de cupons */
.coupon-apply-section {
    margin-bottom: 2rem;
}

.coupon-apply-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    border: 2px solid #667eea;
}

.coupon-apply-card h3 {
    color: #667eea;
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.coupon-apply-description {
    color: #6c757d;
    margin-bottom: 1.5rem;
}

.coupon-form {
    margin-bottom: 1rem;
}

.coupon-input-group {
    display: flex;
    gap: 0.5rem;
}

.coupon-input-group input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.coupon-input-group input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.coupon-input-group button {
    padding: 0.75rem 1.5rem;
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.coupon-input-group button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.subsection-title {
    font-size: 1.5rem;
    color: #2d3748;
    margin-bottom: 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.coupons-section {
    margin-bottom: 3rem;
}

.coupons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
}

.coupon-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: 2px solid #e2e8f0;
}

.coupon-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.coupon-card.recommended {
    border-color: #667eea;
    position: relative;
}

.coupon-card.recommended::before {
    content: 'Recomendado';
    position: absolute;
    top: 10px;
    right: 10px;
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    z-index: 1;
}

.coupon-header {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.coupon-badge {
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.3rem;
    opacity: 0.95;
}

.coupon-discount {
    font-size: 1.75rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.coupon-body {
    padding: 1.5rem;
}

.coupon-code {
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    padding: 1rem;
    text-align: center;
    font-family: 'Courier New', monospace;
    font-weight: 700;
    font-size: 1.25rem;
    color: #495057;
    margin-bottom: 1rem;
    letter-spacing: 1px;
}

.coupon-message,
.coupon-description {
    color: #6c757d;
    font-size: 0.95rem;
    line-height: 1.5;
    margin-bottom: 1rem;
}

.coupon-expires,
.coupon-min-purchase {
    font-size: 0.85rem;
    color: #dc3545;
    display: flex;
    align-items: center;
    gap: 0.3rem;
    margin-bottom: 0.5rem;
}

.coupon-min-purchase {
    color: #28a745;
}

.coupon-genre-restriction {
    background: #e3f2fd;
    border-left: 3px solid #2196f3;
    padding: 0.75rem;
    margin-bottom: 1rem;
    border-radius: 5px;
    font-size: 0.9rem;
    color: #1565c0;
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
}

.coupon-genre-restriction i {
    margin-top: 0.2rem;
}

.coupon-genre-restriction strong {
    font-weight: 600;
}

.coupon-actions {
    padding: 0 1.5rem 1.5rem;
}

.btn-apply-coupon {
    width: 100%;
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    border: none;
    padding: 0.75rem;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-apply-coupon:hover {
    transform: scale(1.02);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.alert {
    padding: 1rem;
    border-radius: 10px;
    margin-top: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
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
                        <span class="stat-value">{{ isset($orders) ? $orders->count() : 0 }}</span>
                        <span class="stat-label">Pedidos</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ isset($user->created_at) ? (is_string($user->created_at) ? date('Y', strtotime($user->created_at)) : $user->created_at->format('Y')) : date('Y') }}</span>
                        <span class="stat-label">Membro desde</span>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i>
                        Sair da Conta
                    </button>
                </form>
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
                <button class="nav-tab" data-section="cupons">
                    <i class="fas fa-tag"></i>
                    Seus Cupons
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
                        <span class="info-value">{{ isset($orders) ? $orders->count() : 0 }}</span>
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

        <!-- Seção: Meus Pedidos -->
        <div id="pedidos" class="profile-section animate-fade-in">
            <h2 class="section-title">
                <i class="fas fa-shopping-bag"></i>
                Meus Pedidos
            </h2>
            
            @if($orders && $orders->count() > 0)
                <div class="orders-list">
                    @foreach($orders as $order)
                        <div class="order-card">
                            <div class="order-header">
                                <div class="order-info">
                                    <h3 class="order-number">Pedido #{{ $order->id }}</h3>
                                    <p class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="order-status">
                                    <span class="status-badge status-{{ $order->status }}">
                                        @if($order->status === 'pending')
                                            <i class="fas fa-clock"></i> Pendente
                                        @elseif($order->status === 'processing')
                                            <i class="fas fa-cog fa-spin"></i> Processando
                                        @elseif($order->status === 'shipped')
                                            <i class="fas fa-shipping-fast"></i> Enviado
                                        @elseif($order->status === 'delivered')
                                            <i class="fas fa-check-circle"></i> Entregue
                                        @elseif($order->status === 'cancelled')
                                            <i class="fas fa-times-circle"></i> Cancelado
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <div class="order-items">
                                @foreach($order->orderItems->take(3) as $item)
                                    <div class="order-item-mini">
                                        @php
                                            $bookImages = [];
                                            if (isset($item->book->images)) {
                                                if (is_string($item->book->images)) {
                                                    $decoded = json_decode($item->book->images, true);
                                                    $bookImages = is_array($decoded) ? $decoded : [];
                                                } elseif (is_array($item->book->images)) {
                                                    $bookImages = $item->book->images;
                                                }
                                            }
                                            $firstImage = !empty($bookImages) ? $bookImages[0] : null;
                                            
                                            if ($firstImage) {
                                                $firstImage = preg_replace('#^storage/#', '', trim($firstImage));
                                                $imageUrl = asset('storage/' . $firstImage);
                                            } else {
                                                $imageUrl = 'https://via.placeholder.com/60x80?text=Livro';
                                            }
                                        @endphp
                                        <img src="{{ $imageUrl }}" 
                                             alt="{{ $item->book->name }}"
                                             onerror="this.onerror=null; this.src='https://via.placeholder.com/60x80?text=Livro';">
                                        <div class="item-info">
                                            <p class="item-name">{{ Str::limit($item->book->name, 40) }}</p>
                                            <p class="item-qty">Qtd: {{ $item->quantity }}</p>
                                        </div>
                                    </div>
                                @endforeach
                                
                                @if($order->orderItems->count() > 3)
                                    <p class="more-items">+ {{ $order->orderItems->count() - 3 }} item(ns)</p>
                                @endif
                            </div>
                            
                            <div class="order-footer">
                                <div class="order-total">
                                    <span>Total:</span>
                                    <strong>R$ {{ number_format($order->total_amount, 2, ',', '.') }}</strong>
                                </div>
                                <div class="order-actions">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                        Ver Detalhes
                                    </a>
                                    
                                    @if($order->status === 'delivered')
                                        @php
                                            $hasAnyUnreviewed = false;
                                            foreach($order->orderItems as $item) {
                                                $reviewed = \App\Models\Review::where('user_id', auth()->id())
                                                    ->where('book_id', $item->book_id)
                                                    ->where('order_id', $order->id)
                                                    ->exists();
                                                if (!$reviewed) {
                                                    $hasAnyUnreviewed = true;
                                                    $firstUnreviewedBook = $item->book_id;
                                                    break;
                                                }
                                            }
                                        @endphp
                                        
                                        @if($hasAnyUnreviewed)
                                            <a href="{{ route('reviews.create', ['order' => $order->id, 'book' => $firstUnreviewedBook]) }}" 
                                               class="btn btn-warning btn-sm">
                                                <i class="fas fa-star"></i>
                                                Avaliar Produtos
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-shopping-bag"></i>
                    <h3>Nenhum pedido ainda</h3>
                    <p>Você ainda não realizou nenhum pedido.</p>
                    <a href="{{ route('books.index') }}" class="btn btn-primary">
                        <i class="fas fa-book"></i>
                        Explorar Livros
                    </a>
                </div>
            @endif
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

        <!-- Seção: Cupons -->
        <div id="cupons" class="profile-section">
            <h2 class="section-title">
                <i class="fas fa-tag"></i>
                Meus Cupons de Desconto
            </h2>
            
            @auth
                @php
                    $cartTotal = 0;
                    $cartBooks = session('cart', []);
                    foreach ($cartBooks as $item) {
                        if (isset($item['price']) && isset($item['quantity'])) {
                            $cartTotal += $item['price'] * $item['quantity'];
                        }
                    }
                    
                    // Pegar cupons sugeridos usando o mesmo método do carrinho
                    $suggestedCoupons = \App\Models\Coupon::getSuggestedCoupons(auth()->user(), $cartTotal);
                    $allCoupons = \App\Models\Coupon::getActiveCoupons();
                @endphp
                
                <!-- Seção de aplicar cupom no carrinho -->
                <div class="coupon-apply-section">
                    <div class="coupon-apply-card">
                        <h3><i class="fas fa-ticket-alt"></i> Aplicar Cupom no Carrinho</h3>
                        <p class="coupon-apply-description">Digite o código do cupom abaixo para aplicá-lo no seu carrinho</p>
                        <form class="coupon-form" action="{{ route('cart.applyCoupon') }}" method="POST">
                            @csrf
                            <div class="coupon-input-group">
                                <input type="text" name="coupon_code" placeholder="Digite o código do cupom" required>
                                <button type="submit">
                                    <i class="fas fa-check"></i>
                                    Aplicar
                                </button>
                            </div>
                        </form>
                        
                        @if(session('coupon_error'))
                            <div class="alert alert-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ session('coupon_error') }}
                            </div>
                        @endif
                        
                        @if(session('coupon_success'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                {{ session('coupon_success') }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Cupons Recomendados -->
                @if(count($suggestedCoupons) > 0)
                    <div class="coupons-section">
                        <h3 class="subsection-title"><i class="fas fa-magic"></i> Cupons Recomendados para Você</h3>
                        <div class="coupons-grid">
                            @foreach($suggestedCoupons as $suggestion)
                                @php $coupon = $suggestion['coupon']; @endphp
                                <div class="coupon-card recommended">
                                    <div class="coupon-header">
                                        <span class="coupon-badge">
                                            @switch($coupon['trigger_type'])
                                                @case('first_purchase')
                                                    <i class="fas fa-star"></i> Boas-vindas
                                                    @break
                                                @case('birthday')
                                                    <i class="fas fa-birthday-cake"></i> Aniversário
                                                    @break
                                                @case('loyalty')
                                                    <i class="fas fa-trophy"></i> Fidelidade
                                                    @break
                                                @case('high_value_cart')
                                                    <i class="fas fa-gem"></i> VIP
                                                    @break
                                                @case('genre_based')
                                                    <i class="fas fa-book"></i> Gênero
                                                    @break
                                                @default
                                                    <i class="fas fa-gift"></i> Especial
                                            @endswitch
                                        </span>
                                        <div class="coupon-discount">
                                            {{ $coupon['type'] == 'percent' ? $coupon['discount'] . '% OFF' : 'R$ ' . number_format($coupon['discount'], 2, ',', '.') . ' OFF' }}
                                        </div>
                                    </div>
                                    <div class="coupon-body">
                                        <div class="coupon-code">{{ $coupon['code'] }}</div>
                                        
                                        @php
                                            // Extrair apenas a mensagem sem a parte de gêneros
                                            $cleanMessage = $suggestion['message'];
                                            if (strpos($cleanMessage, '(válido para:') !== false) {
                                                $cleanMessage = trim(substr($cleanMessage, 0, strpos($cleanMessage, '(válido para:')));
                                            }
                                        @endphp
                                        <p class="coupon-message">{{ $cleanMessage }}</p>
                                        
                                        @if(isset($coupon['applicable_genres']) && !empty($coupon['applicable_genres']))
                                            @php
                                                $rawGenres = $coupon['applicable_genres'];
                                                $formattedGenres = [];
                                                
                                                // Tentar múltiplas decodificações se necessário
                                                while (is_string($rawGenres) && (strpos($rawGenres, '[') !== false || strpos($rawGenres, '{') !== false)) {
                                                    $temp = json_decode($rawGenres, true);
                                                    if (json_last_error() === JSON_ERROR_NONE) {
                                                        $rawGenres = $temp;
                                                    } else {
                                                        break;
                                                    }
                                                }
                                                
                                                // Se ainda for string, extrair manualmente
                                                if (is_string($rawGenres)) {
                                                    $rawGenres = preg_replace('/[\[\]"\'{}]/', '', $rawGenres);
                                                    $rawGenres = array_map('trim', explode(',', $rawGenres));
                                                }
                                                
                                                // Garantir que é array e formatar
                                                if (is_array($rawGenres)) {
                                                    foreach ($rawGenres as $genre) {
                                                        $genre = trim($genre);
                                                        if (!empty($genre)) {
                                                            $formattedGenres[] = ucwords(str_replace(['-', '_'], ' ', $genre));
                                                        }
                                                    }
                                                }
                                            @endphp
                                            @if(!empty($formattedGenres))
                                                <div class="coupon-genre-restriction">
                                                    <i class="fas fa-book-open"></i>
                                                    <strong>Gêneros válidos:</strong> {{ implode(', ', $formattedGenres) }}
                                                </div>
                                            @endif
                                        @endif
                                        
                                        @if($coupon['expires_at'])
                                            <div class="coupon-expires">
                                                <i class="fas fa-clock"></i>
                                                Válido até {{ date('d/m/Y', strtotime($coupon['expires_at'])) }}
                                            </div>
                                        @endif
                                        @if(isset($coupon['minimum_cart_value']) && $coupon['minimum_cart_value'])
                                            <div class="coupon-min-purchase">
                                                <i class="fas fa-shopping-cart"></i>
                                                Mínimo: R$ {{ number_format($coupon['minimum_cart_value'], 2, ',', '.') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="coupon-actions">
                                        <button class="btn-apply-coupon" onclick="copyCouponCode('{{ $coupon['code'] }}')">
                                            <i class="fas fa-copy"></i>
                                            Copiar Código
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Todos os Cupons Disponíveis -->
                @if(count($allCoupons) > 0)
                    <div class="coupons-section">
                        <h3 class="subsection-title"><i class="fas fa-tags"></i> Todos os Cupons Disponíveis</h3>
                        <div class="coupons-grid">
                            @foreach($allCoupons as $coupon)
                                <div class="coupon-card">
                                    <div class="coupon-header">
                                        <span class="coupon-badge">
                                            <i class="fas fa-tag"></i> {{ ucfirst($coupon['type'] == 'percent' ? 'Percentual' : 'Fixo') }}
                                        </span>
                                        <div class="coupon-discount">
                                            {{ $coupon['type'] == 'percent' ? $coupon['discount'] . '% OFF' : 'R$ ' . number_format($coupon['discount'], 2, ',', '.') . ' OFF' }}
                                        </div>
                                    </div>
                                    <div class="coupon-body">
                                        <div class="coupon-code">{{ $coupon['code'] }}</div>
                                        @if(isset($coupon['description']) && $coupon['description'])
                                            <p class="coupon-description">{{ $coupon['description'] }}</p>
                                        @endif
                                        
                                        @if(isset($coupon['applicable_genres']) && !empty($coupon['applicable_genres']))
                                            @php
                                                $rawGenres = $coupon['applicable_genres'];
                                                $formattedGenres = [];
                                                
                                                // Tentar múltiplas decodificações se necessário
                                                while (is_string($rawGenres) && (strpos($rawGenres, '[') !== false || strpos($rawGenres, '{') !== false)) {
                                                    $temp = json_decode($rawGenres, true);
                                                    if (json_last_error() === JSON_ERROR_NONE) {
                                                        $rawGenres = $temp;
                                                    } else {
                                                        break;
                                                    }
                                                }
                                                
                                                // Se ainda for string, extrair manualmente
                                                if (is_string($rawGenres)) {
                                                    $rawGenres = preg_replace('/[\[\]"\'{}]/', '', $rawGenres);
                                                    $rawGenres = array_map('trim', explode(',', $rawGenres));
                                                }
                                                
                                                // Garantir que é array e formatar
                                                if (is_array($rawGenres)) {
                                                    foreach ($rawGenres as $genre) {
                                                        $genre = trim($genre);
                                                        if (!empty($genre)) {
                                                            $formattedGenres[] = ucwords(str_replace(['-', '_'], ' ', $genre));
                                                        }
                                                    }
                                                }
                                            @endphp
                                            @if(!empty($formattedGenres))
                                                <div class="coupon-genre-restriction">
                                                    <i class="fas fa-book-open"></i>
                                                    <strong>Gêneros válidos:</strong> {{ implode(', ', $formattedGenres) }}
                                                </div>
                                            @endif
                                        @endif
                                        
                                        @if(isset($coupon['expires_at']) && $coupon['expires_at'])
                                            <div class="coupon-expires">
                                                <i class="fas fa-clock"></i>
                                                Válido até {{ date('d/m/Y', strtotime($coupon['expires_at'])) }}
                                            </div>
                                        @endif
                                        @if(isset($coupon['minimum_cart_value']) && $coupon['minimum_cart_value'])
                                            <div class="coupon-min-purchase">
                                                <i class="fas fa-shopping-cart"></i>
                                                Mínimo: R$ {{ number_format($coupon['minimum_cart_value'], 2, ',', '.') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="coupon-actions">
                                        <button class="btn-apply-coupon" onclick="copyCouponCode('{{ $coupon['code'] }}')">
                                            <i class="fas fa-copy"></i>
                                            Copiar Código
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if(count($allCoupons) == 0 && count($suggestedCoupons) == 0)
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-tag"></i>
                        </div>
                        <h3 class="empty-title">Nenhum cupom disponível no momento</h3>
                        <p class="empty-description">Fique atento! Novos cupons são adicionados regularmente.</p>
                    </div>
                @endif
            @endauth
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

    // Preview e upload automático da imagem do avatar
    const avatarInput = document.getElementById('avatar-input');
    const avatarPreview = document.getElementById('avatar-preview');
    const fileInput = document.getElementById('image');
    const fileDisplay = document.querySelector('.file-input-display');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Preview imediato
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
                
                // Upload automático
                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', 'PUT');
                
                // Mostrar loading
                avatarPreview.style.opacity = '0.5';
                
                fetch('{{ route("user.update", $user->id) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    avatarPreview.style.opacity = '1';
                    
                    if (!response.ok) {
                        throw new Error('Erro na requisição');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Atualizar src da imagem se retornou URL
                        if (data.image_url) {
                            avatarPreview.src = data.image_url + '?t=' + new Date().getTime();
                        }
                        showNotification('Foto atualizada com sucesso!', 'success');
                    } else {
                        showNotification(data.message || 'Erro ao atualizar foto.', 'error');
                    }
                })
                .catch(error => {
                    avatarPreview.style.opacity = '1';
                    console.error('Error:', error);
                    showNotification('Erro ao atualizar foto. Tente novamente.', 'error');
                });
            }
        });
    }
    
    // Função para mostrar notificações
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            ${message}
        `;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
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

    // Função para copiar código do cupom
    window.copyCouponCode = function(code) {
        // Criar elemento temporário
        const tempInput = document.createElement('input');
        tempInput.value = code;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        
        // Mostrar notificação
        showNotification(`Cupom ${code} copiado! Cole no carrinho para usar.`, 'success');
    };

    // Função de notificação (se não existir)
    if (typeof showNotification === 'undefined') {
        window.showNotification = function(message, type) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                background: ${type === 'success' ? '#28a745' : '#dc3545'};
                color: white;
                border-radius: 10px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.2);
                z-index: 10000;
                animation: slideIn 0.3s ease-out;
            `;
            notification.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        };
    }
});
</script>

<style>
@keyframes slideIn {
    from {
        transform: translateX(400px);
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
        transform: translateX(400px);
        opacity: 0;
    }
}
</style>

@endsection