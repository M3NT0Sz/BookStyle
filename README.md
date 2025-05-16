# BookStyle

BookStyle é uma plataforma web para compra, venda e troca de livros, HQs, ebooks e boxes literários. O sistema foi desenvolvido por estudantes apaixonados por tecnologia e literatura, com o objetivo de democratizar o acesso à leitura e incentivar o compartilhamento de histórias.

## Funcionalidades

- **Catálogo de Livros:** Visualize, pesquise e filtre livros por gênero, autor, preço e condição (novo/usado).
- **Cadastro de Produtos:** Usuários autenticados podem cadastrar livros físicos, ebooks, gibis e boxes, incluindo imagens e detalhes específicos.
- **Carrinho de Compras:** Adicione diferentes produtos ao carrinho, visualize, remova e finalize sua compra.
- **Cupons de Desconto:** CRUD completo de cupons, com aplicação automática de descontos percentuais ou fixos no carrinho.
- **Perfil do Usuário:** Gerencie seus dados, visualize seus livros cadastrados, pedidos e cupons disponíveis.
- **Sistema de Autenticação:** Cadastro, login e gerenciamento de perfil de usuário.

## Tecnologias Utilizadas

- **PHP** (Laravel-like, arquitetura MVC simplificada)
- **Blade** para views
- **CSS customizado** para layout moderno e responsivo
- **Banco de Dados** relacional (MySQL ou SQLite)

## Como Usar

1. Clone o repositório e instale as dependências:
   ```sh
   composer install
   npm install && npm run build
   ```
2. Configure o arquivo `.env` com as credenciais do seu banco de dados.
3. Execute as migrations:
   ```sh
   php artisan migrate
   ```
4. Inicie o servidor:
   ```sh
   php artisan serve
   ```
5. Acesse `http://localhost:8000` no navegador.

## Estrutura de Pastas

- `app/Models` — Modelos de dados (Book, Coupon, Cart, User, etc)
- `app/Http/Controllers` — Lógica das rotas e regras de negócio
- `resources/views` — Templates Blade para todas as páginas
- `database/migrations` — Migrations para criação das tabelas
- `public/` — Assets públicos (imagens, CSS, JS)

## Sobre o Projeto

O BookStyle foi criado para conectar leitores e vendedores em um ambiente seguro, intuitivo e agradável. O sistema permite não só a compra e venda, mas também incentiva a reutilização de livros e a economia colaborativa.

- **Missão:** Democratizar o acesso à leitura.
- **Visão:** Ser referência nacional em troca e venda de livros usados.
- **Valores:** Comunidade, sustentabilidade, acessibilidade e paixão pela leitura.

---

Desenvolvido com carinho por estudantes de ADS. Para dúvidas ou sugestões, entre em contato: contato@bookstyle.com
