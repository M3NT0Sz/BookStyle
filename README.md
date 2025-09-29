# 📚 BookStyle - Plataforma Inteligente de Livros

> **Sistema completo de e-commerce literário com IA para cupons inteligentes e experiência personalizada**

BookStyle é uma plataforma web moderna para compra, venda e troca de livros, HQs, ebooks e boxes literários. Desenvolvido por estudantes apaixonados por tecnologia e literatura, combina e-commerce tradicional com **Inteligência Artificial** para oferecer uma experiência única e personalizada.

## ✨ Funcionalidades Principais

### 🛒 **E-commerce Completo**
- **Catálogo Inteligente:** Busca, filtros avançados por gênero, autor, preço e condição
- **Produtos Diversificados:** Livros físicos, ebooks, HQs, boxes e coleções
- **Carrinho Inteligente:** Sistema com sugestões automáticas e validações
- **Checkout Completo:** Integração com ViaCEP, múltiplos métodos de pagamento
- **Histórico de Pedidos:** Acompanhamento completo com status em tempo real

### 🧠 **Sistema de Cupons Inteligentes** *(Novidade!)*
- **Triggers Automáticos:** 6 tipos de cupons gerados por IA baseados em comportamento
- **Sugestões Personalizadas:** Recomendações em tempo real no carrinho
- **Validação Avançada:** Condições inteligentes por usuário, valor e gênero
- **Dashboard Administrativo:** Estatísticas e controle completo
- **Interface Visual:** Cards modernos e notificações automáticas

### 👤 **Experiência do Usuário**
- **Perfil Personalizado:** Gerenciamento completo de dados e preferências
- **Cadastro de Produtos:** Interface intuitiva para venda de livros próprios
- **Sistema de Autenticação:** Login seguro com perfis diferenciados
- **Área Administrativa:** Painel completo para gestão da plataforma

## 🤖 Tipos de Cupons Inteligentes

| Trigger | Desconto | Condições | Ativação |
|---------|----------|-----------|----------|
| 🎉 **Primeiro Pedido** | 15% OFF | R$ 50 mínimo | Após primeira compra |
| 🛒 **Abandono de Carrinho** | 10% OFF | R$ 30 mínimo | 24h após abandono |
| 🎂 **Aniversário** | 20% OFF | Sem mínimo | No dia do aniversário |
| 📖 **Gênero Preferido** | 12% OFF | Baseado no histórico | Mensalmente |
| 🏆 **Fidelidade** | 15-30% OFF | R$ 100 mínimo | Marcos: 5, 10, 20, 50 pedidos |
| 💎 **Carrinho VIP** | R$ 50 OFF | R$ 200 mínimo | Carrinho alto valor |

## 🚀 Tecnologias Utilizadas

### **Backend**
- **PHP 8.2** com arquitetura MVC
- **Laravel Framework** - Eloquent ORM, Migrations, Blade
- **MySQL/SQLite** - Banco de dados relacional
- **Smart Services** - Lógica de negócio para IA de cupons

### **Frontend**
- **Blade Templates** - Engine de template do Laravel
- **CSS3 Moderno** - Flexbox, Grid, Animations
- **JavaScript Vanilla** - Interatividade e AJAX
- **Design Responsivo** - Mobile-first approach

### **Integrações**
- **ViaCEP API** - Busca automática de endereços
- **Sistema de Notificações** - Alertas em tempo real
- **Exportação de Dados** - JSON/CSV para relatórios

## 📦 Instalação e Configuração

### **Pré-requisitos**
```bash
- PHP >= 8.1
- Composer
- Node.js >= 16
- MySQL/SQLite
- Git
```

### **1. Clonagem e Dependências**
```bash
git clone https://github.com/M3NT0Sz/BookStyle.git
cd BookStyle
composer install
npm install && npm run build
```

### **2. Configuração do Ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

### **3. Configuração do Banco**
```bash
# Configure .env com suas credenciais de banco
php artisan migrate --seed
```

### **4. Executar Aplicação**
```bash
# Opção 1: Laravel Herd (Recomendado)
# Acesse: http://bookstyle.test

# Opção 2: Servidor built-in
php artisan serve
# Acesse: http://localhost:8000
```

## 🗂️ Arquitetura do Sistema

```
BookStyle/
├── 📁 app/
│   ├── 📁 Http/Controllers/     # Controllers MVC
│   ├── 📁 Models/              # Models Eloquent
│   ├── 📁 Services/            # Smart Services (IA)
│   └── 📁 Adapters/            # Padrões de Design
├── 📁 database/
│   ├── 📁 migrations/          # Estrutura do BD
│   └── 📁 seeders/             # Dados iniciais
├── 📁 resources/
│   ├── 📁 views/               # Templates Blade
│   ├── 📁 css/                 # Estilos
│   └── 📁 js/                  # JavaScript
└── 📁 routes/                  # Definição de rotas
```

## 🎯 Como Testar os Cupons Inteligentes

### **1. Cupons de Exemplo**
```bash
WELCOME10    - 10% OFF (geral)
FICCAO15     - 15% OFF (livros de ficção)
VIP-COMP50-25 - R$ 50 OFF (carrinho R$ 200+)
```

### **2. Fluxo de Teste**
1. **Faça login** na plataforma
2. **Adicione livros** ao carrinho (valor > R$ 50)
3. **Observe sugestões automáticas** de cupons
4. **Finalize uma compra** para ativar trigger de primeiro pedido
5. **Acesse `/admin/coupons`** como admin para ver estatísticas

### **3. Dashboard Admin**
```bash
URL: /admin/coupons
Usuário: admin@bookstyle.com
Recursos: Estatísticas, gestão, exportação
```

## 📊 Métricas e Analytics

- **Total de Cupons:** Acompanhamento em tempo real
- **Taxa de Conversão:** Impacto dos cupons nas vendas  
- **Triggers Mais Efetivos:** Analytics comportamentais
- **ROI por Tipo:** Retorno de investimento por trigger
- **Exportação:** Relatórios em JSON/CSV

## 🛡️ Recursos de Segurança

- **Validação Server-side** em todos os formulários
- **Sanitização de Dados** contra XSS e SQL Injection
- **Autenticação Segura** com hash de senhas
- **Autorização por Níveis** (User/Admin)
- **Rate Limiting** em endpoints críticos

## 🌟 Destaques Técnicos

### **Padrões de Design**
- **MVC Architecture** - Separação clara de responsabilidades
- **Service Layer** - Lógica de negócio centralizada
- **Adapter Pattern** - Flexibilidade para integrações
- **Observer Pattern** - Triggers automáticos de cupons

### **Performance**
- **Query Optimization** - Consultas otimizadas com PDO
- **Caching Strategy** - Cache de sessões e dados
- **Lazy Loading** - Carregamento sob demanda
- **Database Indexing** - Índices estratégicos

### **UX/UI Moderno**
- **Design System** - Componentes consistentes
- **Micro-interactions** - Animações suaves
- **Progressive Enhancement** - Funciona sem JavaScript
- **Accessibility** - Suporte a screen readers

## 🎨 Sobre o Design

O BookStyle adota uma **identidade visual moderna e acolhedora**:

- **Paleta de Cores:** Tons de azul, branco e gradientes suaves
- **Tipografia:** Inter para textos, Playfair Display para títulos
- **Iconografia:** Font Awesome para consistência
- **Layout:** Grid system responsivo com breakpoints móveis
- **Animações:** Transições suaves com CSS3

## 🌍 Missão e Valores

### **🎯 Missão**
Democratizar o acesso à leitura através de tecnologia inteligente e comunidade colaborativa.

### **👁️ Visão**
Ser a principal plataforma de livros do Brasil, conectando leitores através de IA e experiências personalizadas.

### **💎 Valores**
- **📚 Paixão pela Leitura** - Livros transformam vidas
- **🤝 Comunidade** - Conectamos pessoas através de histórias  
- **🌱 Sustentabilidade** - Reutilização e economia circular
- **🚀 Inovação** - Tecnologia a serviço da educação
- **♿ Acessibilidade** - Livros para todos, sem exceção

## 📈 Roadmap Futuro

### **Versão 2.0**
- [ ] Machine Learning para recomendações
- [ ] API REST completa
- [ ] App Mobile (React Native)
- [ ] Chat em tempo real
- [ ] Sistema de avaliações

### **Versão 2.1**
- [ ] Marketplace de vendedores
- [ ] Programa de afiliados
- [ ] Assinatura premium
- [ ] Realidade aumentada para livros

## 🤝 Contribuição

Contribuições são **muito bem-vindas**! Para contribuir:

1. **Fork** o projeto
2. **Crie** uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. **Commit** suas mudanças (`git commit -m 'Add AmazingFeature'`)
4. **Push** para a branch (`git push origin feature/AmazingFeature`)
5. **Abra** um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 👨‍💻 Desenvolvedores

Desenvolvido com ❤️ por estudantes de **Análise e Desenvolvimento de Sistemas**:

- **Backend & IA:** Sistema de cupons inteligentes
- **Frontend & UX:** Interface moderna e responsiva  
- **Database & DevOps:** Arquitetura escalável

## 📞 Contato

- **Email:** contato@bookstyle.com
- **GitHub:** [@M3NT0Sz](https://github.com/M3NT0Sz)
- **Website:** [bookstyle.test](http://bookstyle.test)

---

<div align="center">
  
**🎯 "Conectando histórias, conectando pessoas"** 

*Made with* ❤️ *and lots of* ☕ *by BookStyle Team*

</div>
