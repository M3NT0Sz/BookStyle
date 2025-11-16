// Checkout Transparente com Mercado Pago
let mp = null;
let cardForm = null;

// Inicializar Mercado Pago
function initMercadoPago(publicKey) {
    console.log('Inicializando Mercado Pago...');
    mp = new MercadoPago(publicKey, {
        locale: 'pt-BR'
    });
    
    console.log('Mercado Pago inicializado');
}

// Processar pagamento
async function processPayment(formData) {
    try {
        const paymentMethod = formData.get('payment_method');
        
        // Validações básicas
        if (!paymentMethod) {
            throw new Error('Selecione um método de pagamento');
        }
        
        // Preparar dados do pagamento
        const paymentData = {
            payment_method_id: paymentMethod,
            payer: {
                email: document.getElementById('payer_email')?.value || '{{ Auth::user()->email }}',
                identification: {
                    type: 'CPF',
                    number: document.getElementById('payer_document')?.value || ''
                }
            },
            billing_address: {
                street: formData.get('billing_address[street]'),
                number: formData.get('billing_address[number]'),
                complement: formData.get('billing_address[complement]') || '',
                neighborhood: formData.get('billing_address[neighborhood]'),
                city: formData.get('billing_address[city]'),
                state: formData.get('billing_address[state]'),
                postal_code: formData.get('billing_address[postal_code]')
            },
            shipping_address: formData.get('same_address') ? 
                {
                    street: formData.get('billing_address[street]'),
                    number: formData.get('billing_address[number]'),
                    complement: formData.get('billing_address[complement]') || '',
                    neighborhood: formData.get('billing_address[neighborhood]'),
                    city: formData.get('billing_address[city]'),
                    state: formData.get('billing_address[state]'),
                    postal_code: formData.get('billing_address[postal_code]')
                } :
                {
                    street: formData.get('shipping_address[street]'),
                    number: formData.get('shipping_address[number]'),
                    complement: formData.get('shipping_address[complement]') || '',
                    neighborhood: formData.get('shipping_address[neighborhood]'),
                    city: formData.get('shipping_address[city]'),
                    state: formData.get('shipping_address[state]'),
                    postal_code: formData.get('shipping_address[postal_code]')
                }
        };
        
        // Se for pagamento com cartão, criar token
        if (paymentMethod === 'credit_card' || paymentMethod === 'debit_card') {
            const cardData = {
                cardNumber: formData.get('card_number')?.replace(/\s/g, ''),
                cardholderName: formData.get('card_name'),
                cardExpirationMonth: formData.get('card_expiry')?.split('/')[0],
                cardExpirationYear: '20' + formData.get('card_expiry')?.split('/')[1],
                securityCode: formData.get('card_cvv'),
                identificationType: 'CPF',
                identificationNumber: paymentData.payer.identification.number
            };
            
            console.log('Criando token do cartão...');
            
            // Criar token usando SDK do Mercado Pago
            const token = await mp.fields.createCardToken(cardData);
            
            if (token.error) {
                throw new Error(token.error.message || 'Erro ao processar dados do cartão');
            }
            
            paymentData.token = token.id;
            paymentData.installments = 1; // Pode ser ajustável
        }
        
        console.log('Enviando pagamento para o servidor...');
        
        // Enviar para o backend
        const response = await fetch('/checkout/process', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(paymentData)
        });
        
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || 'Erro ao processar pagamento');
        }
        
        console.log('Pagamento processado:', result);
        
        // Redirecionar baseado no resultado
        if (result.redirect_url) {
            window.location.href = result.redirect_url;
        } else {
            // Fallback
            window.location.href = '/orders/' + result.order_id;
        }
        
    } catch (error) {
        console.error('Erro no processamento:', error);
        throw error;
    }
}

// Exportar funções
window.initMercadoPago = initMercadoPago;
window.processPayment = processPayment;
