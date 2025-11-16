# Integração com Mercado Pago

## Configuração

### 1. Obter Credenciais

1. Acesse o [Painel de Desenvolvedores do Mercado Pago](https://www.mercadopago.com.br/developers/panel/credentials)
2. Faça login com sua conta
3. Vá em "Suas credenciais" → "Credenciais de produção" ou "Credenciais de teste"
4. Copie o **Access Token** e a **Public Key**

### 2. Configurar .env

Adicione as credenciais no arquivo `.env`:

```env
MERCADOPAGO_ACCESS_TOKEN=seu_access_token_aqui
MERCADOPAGO_PUBLIC_KEY=sua_public_key_aqui
```

**Importante:**
- Para testes, use as credenciais de TESTE
- Para produção, use as credenciais de PRODUÇÃO
- Nunca commite o arquivo `.env` com credenciais reais

### 3. Webhook (Notificações)

O webhook está configurado em: `https://seu-dominio.com/api/mercadopago/webhook`

**Para desenvolvimento local:**
1. Use ferramentas como ngrok para expor sua aplicação local
2. Configure a URL do webhook no painel do Mercado Pago
3. Em Produção, configure a URL real da aplicação

## Fluxo de Pagamento

### Checkout Transparente (Cartão)

1. **Cliente finaliza pedido** no checkout
2. **Token do cartão** é gerado no frontend via SDK JS
3. **Pagamento processado** diretamente na API do Mercado Pago
4. **Aprovação instantânea** (ambiente de teste)
5. **Redirecionamento** para página de sucesso

### Pagamento PIX

1. **Cliente escolhe PIX** no checkout
2. **QR Code gerado** via API do Mercado Pago
3. **Exibição** do QR Code + Código Copia e Cola
4. **Cliente paga** no app do banco
5. **Webhook atualiza** status automaticamente
6. **Redirecionamento** para página de sucesso

### Pagamento Boleto

1. **Cliente escolhe Boleto** no checkout
2. **Boleto gerado** via API (disponível apenas em produção)
3. **Link para download/impressão**
4. **Pagamento** em agência/app bancário
5. **Webhook atualiza** após compensação (1-2 dias úteis)

## Rotas Disponíveis

### Cliente (Autenticado)
```php
GET  /payment/pix/{order}         - Exibir QR Code PIX
GET  /payment/boleto/{order}      - Exibir Boleto
GET  /payment/success             - Página de sucesso
GET  /payment/failure             - Página de falha
GET  /payment/pending             - Página de pendente
GET  /payment/status/{orderId}    - Consultar status (API)
```

### Webhook (Pública)
```php
POST /api/mercadopago/webhook     - Receber notificações
```

## Exemplos de Uso

### No Controller do Carrinho

```php
public function checkout(Request $request)
{
    // Criar pedido a partir do carrinho
    $order = $this->createOrderFromCart();
    
    // Redirecionar para pagamento
    return redirect()->route('payment.checkout', $order->id);
}
```

### Na View de Pedidos

```blade
@if($order->payment_status === 'pending')
    <a href="{{ route('payment.checkout', $order->id) }}" class="btn btn-primary">
        Realizar Pagamento
    </a>
@endif

@if($order->payment_status === 'paid')
    <span class="badge badge-success">Pago</span>
@endif
```

## Status de Pagamento

### payment_status (na tabela orders)
- `pending` - Aguardando pagamento
- `paid` - Pago
- `failed` - Falha no pagamento
- `refunded` - Reembolsado

### Status do Mercado Pago
- `approved` - Aprovado → atualiza para `paid`
- `pending` - Pendente → mantém `pending`
- `rejected` - Rejeitado → atualiza para `failed`
- `refunded` - Reembolsado → atualiza para `refunded`

## Testes

### Cartões de Teste Brasil

**✅ APROVADO INSTANTANEAMENTE:**
- **Mastercard:** `5031 4332 1540 6351`
- **Visa:** `4235 6477 2802 5682`
- **Elo:** `5067 2686 5051 7446`
- CVV: `123`
- Validade: Qualquer data futura (ex: 12/30)
- Nome: APRO (importante!)
- CPF: `12345678909`

**⏳ PENDENTE (Aprovado após alguns segundos):**
- Número: `5031 7557 3453 0604`
- Nome: OTHE
- CVV: `123`

**❌ REJEITADO:**
- Número: `5031 4332 1540 6351`
- Nome: CALL (gera rejeição por call for authorize)
- CVV: `123`

**Documentos de teste:**
- CPF: `12345678909`
- Email: `test_user_123456@testuser.com`

[Mais cartões de teste](https://www.mercadopago.com.br/developers/pt/docs/testing/test-cards)

### Forçar Aprovação de Pagamento Pendente (Teste)

Se um pagamento ficou pendente em ambiente de teste:

```bash
php artisan tinker
```

```php
$order = App\Models\Order::find(ID_DO_PEDIDO);
$order->payment_status = 'paid';
$order->status = 'processing';
$order->paid_at = now();
$order->save();
```

## Segurança

1. **Nunca** exponha seu Access Token no frontend
2. Use HTTPS em produção
3. Valide webhooks (verificar origem)
4. Log todas as transações
5. Mantenha as credenciais em `.env`

## Logs

Logs estão em `storage/logs/laravel.log`:
- Criação de preferências
- Webhooks recebidos
- Atualizações de status
- Erros de processamento

## Troubleshooting

### Erro: "Invalid access token"
- Verifique se copiou o Access Token correto
- Confirme que está usando credenciais do ambiente correto (teste/produção)

### Webhook não está funcionando
- Verifique se a URL está acessível publicamente
- Confirme que a rota está registrada
- Cheque os logs do Laravel

### Pagamento não atualiza
- Verifique o webhook
- Consulte manualmente: `GET /payment/status/{orderId}`
- Veja os logs do Mercado Pago no painel

## Suporte

- [Documentação Oficial](https://www.mercadopago.com.br/developers/pt/docs)
- [FAQ](https://www.mercadopago.com.br/developers/pt/support)
- [Comunidade](https://www.mercadopago.com.br/developers/pt/community)
