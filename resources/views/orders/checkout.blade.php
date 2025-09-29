@extends('layouts.app')

@section('title', 'Checkout - BookStyle')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Finalizar Pedido</h1>
                <p class="text-gray-600 mt-2">Revise suas informações antes de confirmar o pedido</p>
            </div>

            <form method="POST" action="{{ route('orders.store') }}" id="checkout-form">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Formulário -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Método de Pagamento -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Método de Pagamento</h2>
                            
                            <div class="space-y-3">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="payment_method" value="credit_card" class="mr-3" required onchange="togglePaymentFields('credit_card')">
                                    <div class="flex items-center">
                                        <i class="far fa-credit-card text-blue-600 mr-2"></i>
                                        <span>Cartão de Crédito</span>
                                        <span class="ml-2 text-sm text-green-600 font-medium">(DEMO)</span>
                                    </div>
                                </label>
                                
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="payment_method" value="debit_card" class="mr-3" required onchange="togglePaymentFields('debit_card')">
                                    <div class="flex items-center">
                                        <i class="fas fa-credit-card text-green-600 mr-2"></i>
                                        <span>Cartão de Débito</span>
                                        <span class="ml-2 text-sm text-green-600 font-medium">(DEMO)</span>
                                    </div>
                                </label>
                                
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="payment_method" value="pix" class="mr-3" required onchange="togglePaymentFields('pix')">
                                    <div class="flex items-center">
                                        <i class="fas fa-qrcode text-purple-600 mr-2"></i>
                                        <span>PIX</span>
                                        <span class="ml-2 text-sm text-green-600 font-medium">(DEMO)</span>
                                    </div>
                                </label>
                                
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="payment_method" value="boleto" class="mr-3" required onchange="togglePaymentFields('boleto')">
                                    <div class="flex items-center">
                                        <i class="fas fa-barcode text-orange-600 mr-2"></i>
                                        <span>Boleto Bancário</span>
                                        <span class="ml-2 text-sm text-green-600 font-medium">(DEMO)</span>
                                    </div>
                                </label>
                            </div>
                            
                            @error('payment_method')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <!-- Campos de cartão (visível apenas quando cartão selecionado) -->
                            <div id="card-fields" class="hidden mt-6 p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-md font-semibold text-gray-900 mb-4">Dados do Cartão</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome no Cartão</label>
                                        <input type="text" id="card_name" name="card_name" placeholder="Como está escrito no cartão"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Número do Cartão</label>
                                        <input type="text" id="card_number" name="card_number" placeholder="0000 0000 0000 0000" maxlength="19"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                               oninput="formatCardNumber(this)">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Validade</label>
                                        <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/AA" maxlength="5"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                               oninput="formatExpiry(this)">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                        <input type="text" id="card_cvv" name="card_cvv" placeholder="123" maxlength="4"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                <div class="mt-3 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Este é um sistema de demonstração. Nenhum pagamento real será processado.
                                </div>
                            </div>

                            <!-- Info PIX -->
                            <div id="pix-info" class="hidden mt-6 p-4 bg-purple-50 rounded-lg">
                                <h3 class="text-md font-semibold text-gray-900 mb-2">Pagamento via PIX</h3>
                                <p class="text-sm text-gray-700 mb-3">Após confirmar o pedido, você receberá um QR Code para pagamento.</p>
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Sistema de demonstração - QR Code será simulado.
                                </div>
                            </div>

                            <!-- Info Boleto -->
                            <div id="boleto-info" class="hidden mt-6 p-4 bg-orange-50 rounded-lg">
                                <h3 class="text-md font-semibold text-gray-900 mb-2">Pagamento via Boleto</h3>
                                <p class="text-sm text-gray-700 mb-3">Após confirmar o pedido, você receberá o boleto para pagamento em até 3 dias úteis.</p>
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Sistema de demonstração - Boleto será simulado.
                                </div>
                            </div>
                        </div>

                        <!-- Endereço de Cobrança -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Endereço de Cobrança</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Rua/Logradouro
                                    </label>
                                    <input type="text" name="billing_address[street]" id="billing_street" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" readonly>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Cidade
                                    </label>
                                    <input type="text" name="billing_address[city]" id="billing_city" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" readonly>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Estado
                                    </label>
                                    <select name="billing_address[state]" id="billing_state" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Selecione...</option>
                                        <option value="AC">Acre</option>
                                        <option value="AL">Alagoas</option>
                                        <option value="AP">Amapá</option>
                                        <option value="AM">Amazonas</option>
                                        <option value="BA">Bahia</option>
                                        <option value="CE">Ceará</option>
                                        <option value="DF">Distrito Federal</option>
                                        <option value="ES">Espírito Santo</option>
                                        <option value="GO">Goiás</option>
                                        <option value="MA">Maranhão</option>
                                        <option value="MT">Mato Grosso</option>
                                        <option value="MS">Mato Grosso do Sul</option>
                                        <option value="MG">Minas Gerais</option>
                                        <option value="PA">Pará</option>
                                        <option value="PB">Paraíba</option>
                                        <option value="PR">Paraná</option>
                                        <option value="PE">Pernambuco</option>
                                        <option value="PI">Piauí</option>
                                        <option value="RJ">Rio de Janeiro</option>
                                        <option value="RN">Rio Grande do Norte</option>
                                        <option value="RS">Rio Grande do Sul</option>
                                        <option value="RO">Rondônia</option>
                                        <option value="RR">Roraima</option>
                                        <option value="SC">Santa Catarina</option>
                                        <option value="SP">São Paulo</option>
                                        <option value="SE">Sergipe</option>
                                        <option value="TO">Tocantins</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        CEP
                                    </label>
                                    <input type="text" name="billing_address[postal_code]" id="billing_cep" required
                                           placeholder="00000-000" maxlength="9"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                           onblur="buscarCEP('billing')">
                                    <div id="billing_cep_loading" class="hidden mt-1 text-sm text-blue-600">
                                        <i class="fas fa-spinner fa-spin mr-1"></i>
                                        Buscando endereço...
                                    </div>
                                    <div id="billing_cep_error" class="hidden mt-1 text-sm text-red-600"></div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Número
                                    </label>
                                    <input type="text" name="billing_address[number]" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Complemento
                                    </label>
                                    <input type="text" name="billing_address[complement]" placeholder="Apartamento, bloco, etc. (opcional)"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Bairro
                                    </label>
                                    <input type="text" name="billing_address[neighborhood]" id="billing_neighborhood" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" readonly>
                                </div>
                            </div>
                            
                            @error('billing_address')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Endereço de Entrega -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-semibold text-gray-900">Endereço de Entrega</h2>
                                <label class="flex items-center">
                                    <input type="checkbox" id="same-address" name="same_address" value="1" class="mr-2" onchange="copBillingToShipping()">
                                    <span class="text-sm text-gray-600">Mesmo endereço de cobrança</span>
                                </label>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="shipping-fields">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Rua/Logradouro
                                    </label>
                                    <input type="text" name="shipping_address[street]" id="shipping_street" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" readonly>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Cidade
                                    </label>
                                    <input type="text" name="shipping_address[city]" id="shipping_city" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" readonly>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Estado
                                    </label>
                                    <select name="shipping_address[state]" id="shipping_state" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Selecione...</option>
                                        <option value="AC">Acre</option>
                                        <option value="AL">Alagoas</option>
                                        <option value="AP">Amapá</option>
                                        <option value="AM">Amazonas</option>
                                        <option value="BA">Bahia</option>
                                        <option value="CE">Ceará</option>
                                        <option value="DF">Distrito Federal</option>
                                        <option value="ES">Espírito Santo</option>
                                        <option value="GO">Goiás</option>
                                        <option value="MA">Maranhão</option>
                                        <option value="MT">Mato Grosso</option>
                                        <option value="MS">Mato Grosso do Sul</option>
                                        <option value="MG">Minas Gerais</option>
                                        <option value="PA">Pará</option>
                                        <option value="PB">Paraíba</option>
                                        <option value="PR">Paraná</option>
                                        <option value="PE">Pernambuco</option>
                                        <option value="PI">Piauí</option>
                                        <option value="RJ">Rio de Janeiro</option>
                                        <option value="RN">Rio Grande do Norte</option>
                                        <option value="RS">Rio Grande do Sul</option>
                                        <option value="RO">Rondônia</option>
                                        <option value="RR">Roraima</option>
                                        <option value="SC">Santa Catarina</option>
                                        <option value="SP">São Paulo</option>
                                        <option value="SE">Sergipe</option>
                                        <option value="TO">Tocantins</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        CEP
                                    </label>
                                    <input type="text" name="shipping_address[postal_code]" id="shipping_cep" required
                                           placeholder="00000-000" maxlength="9"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                           onblur="buscarCEP('shipping')">
                                    <div id="shipping_cep_loading" class="hidden mt-1 text-sm text-blue-600">
                                        <i class="fas fa-spinner fa-spin mr-1"></i>
                                        Buscando endereço...
                                    </div>
                                    <div id="shipping_cep_error" class="hidden mt-1 text-sm text-red-600"></div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Número
                                    </label>
                                    <input type="text" name="shipping_address[number]" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Complemento
                                    </label>
                                    <input type="text" name="shipping_address[complement]" placeholder="Apartamento, bloco, etc. (opcional)"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Bairro
                                    </label>
                                    <input type="text" name="shipping_address[neighborhood]" id="shipping_neighborhood" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" readonly>
                                </div>
                            </div>
                            
                            @error('shipping_address')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Observações -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Observações</h2>
                            
                            <textarea name="notes" rows="3" 
                                      placeholder="Observações adicionais sobre seu pedido (opcional)"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                    </div>

                    <!-- Resumo do pedido -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumo do Pedido</h2>
                            
                            <!-- Itens -->
                            <div class="space-y-3 mb-6">
                                @foreach($cartItems as $item)
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-12 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="fas fa-book text-gray-400 text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">
                                            {{ $item->book->title }}
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            Qtd: {{ $item->quantity }} x R$ {{ number_format($item->book->price, 2, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">
                                        R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Totais -->
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span class="text-gray-900">R$ {{ number_format($total, 2, ',', '.') }}</span>
                                </div>
                                <!-- Aqui você pode adicionar frete, desconto, etc. -->
                                <div class="flex justify-between font-semibold text-lg">
                                    <span>Total:</span>
                                    <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <!-- Botão de finalizar -->
                            <button type="submit" 
                                    class="w-full mt-6 bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-credit-card mr-2"></i>
                                Finalizar Pedido
                            </button>
                            
                            <p class="text-xs text-gray-500 mt-3 text-center">
                                Ao finalizar, você concorda com nossos termos de uso
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Função para buscar endereço via CEP usando a API ViaCEP
async function buscarCEP(type) {
    const cepInput = document.getElementById(`${type}_cep`);
    const cep = cepInput.value.replace(/\D/g, '');
    
    if (cep.length !== 8) {
        return;
    }
    
    const loadingDiv = document.getElementById(`${type}_cep_loading`);
    const errorDiv = document.getElementById(`${type}_cep_error`);
    
    // Mostrar loading
    loadingDiv.classList.remove('hidden');
    errorDiv.classList.add('hidden');
    
    try {
        const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        const data = await response.json();
        
        if (data.erro) {
            throw new Error('CEP não encontrado');
        }
        
        // Preencher os campos
        document.getElementById(`${type}_street`).value = data.logradouro;
        document.getElementById(`${type}_city`).value = data.localidade;
        document.getElementById(`${type}_neighborhood`).value = data.bairro;
        
        // Selecionar o estado
        const stateSelect = document.getElementById(`${type}_state`);
        stateSelect.value = data.uf;
        
        // Liberar campos para edição se necessário
        document.getElementById(`${type}_street`).readOnly = false;
        document.getElementById(`${type}_city`).readOnly = false;
        document.getElementById(`${type}_neighborhood`).readOnly = false;
        
        // Focar no campo número
        const numberField = document.querySelector(`input[name="${type}_address[number]"]`);
        if (numberField) {
            numberField.focus();
        }
        
    } catch (error) {
        errorDiv.textContent = 'Erro ao buscar CEP. Verifique o código e tente novamente.';
        errorDiv.classList.remove('hidden');
        
        // Liberar todos os campos para preenchimento manual
        document.getElementById(`${type}_street`).readOnly = false;
        document.getElementById(`${type}_city`).readOnly = false;
        document.getElementById(`${type}_neighborhood`).readOnly = false;
    } finally {
        loadingDiv.classList.add('hidden');
    }
}

// Função para copiar endereço de cobrança para entrega
function copBillingToShipping() {
    const checkbox = document.getElementById('same-address');
    const shippingFields = document.getElementById('shipping-fields');
    
    if (checkbox.checked) {
        // Copiar dados de cobrança para entrega
        const billingStreet = document.getElementById('billing_street').value;
        const billingCity = document.getElementById('billing_city').value;
        const billingState = document.getElementById('billing_state').value;
        const billingCep = document.getElementById('billing_cep').value;
        const billingNeighborhood = document.getElementById('billing_neighborhood').value;
        const billingNumber = document.querySelector('input[name="billing_address[number]"]').value;
        const billingComplement = document.querySelector('input[name="billing_address[complement]"]').value;
        
        document.getElementById('shipping_street').value = billingStreet;
        document.getElementById('shipping_city').value = billingCity;
        document.getElementById('shipping_state').value = billingState;
        document.getElementById('shipping_cep').value = billingCep;
        document.getElementById('shipping_neighborhood').value = billingNeighborhood;
        document.querySelector('input[name="shipping_address[number]"]').value = billingNumber;
        document.querySelector('input[name="shipping_address[complement]"]').value = billingComplement;
        
        // Desabilitar campos de entrega
        shippingFields.style.opacity = '0.6';
        const shippingInputs = shippingFields.querySelectorAll('input, select');
        shippingInputs.forEach(input => {
            input.disabled = true;
            input.removeAttribute('required');
        });
    } else {
        // Habilitar campos de entrega
        shippingFields.style.opacity = '1';
        const shippingInputs = shippingFields.querySelectorAll('input, select');
        shippingInputs.forEach(input => {
            input.disabled = false;
            // Adicionar required apenas nos campos obrigatórios
            if (input.name !== 'shipping_address[complement]') {
                input.setAttribute('required', 'required');
            }
        });
        
        // Limpar campos
        document.getElementById('shipping_street').value = '';
        document.getElementById('shipping_city').value = '';
        document.getElementById('shipping_state').value = '';
        document.getElementById('shipping_cep').value = '';
        document.getElementById('shipping_neighborhood').value = '';
        document.querySelector('input[name="shipping_address[number]"]').value = '';
        document.querySelector('input[name="shipping_address[complement]"]').value = '';
    }
}

// Função para mostrar/ocultar campos de pagamento
function togglePaymentFields(paymentMethod) {
    const cardFields = document.getElementById('card-fields');
    const pixInfo = document.getElementById('pix-info');
    const boletoInfo = document.getElementById('boleto-info');
    
    // Ocultar todos os campos
    cardFields.classList.add('hidden');
    pixInfo.classList.add('hidden');
    boletoInfo.classList.add('hidden');
    
    // SEMPRE remover required dos campos de cartão primeiro
    const cardInputs = cardFields.querySelectorAll('input');
    cardInputs.forEach(input => {
        input.removeAttribute('required');
    });
    
    // Mostrar campos específicos
    if (paymentMethod === 'credit_card' || paymentMethod === 'debit_card') {
        cardFields.classList.remove('hidden');
        
        // Adicionar required APENAS aos campos de cartão quando necessário
        cardInputs.forEach(input => {
            input.setAttribute('required', 'required');
        });
    } else if (paymentMethod === 'pix') {
        pixInfo.classList.remove('hidden');
    } else if (paymentMethod === 'boleto') {
        boletoInfo.classList.remove('hidden');
    }
}

// Função para formatar número do cartão
function formatCardNumber(input) {
    let value = input.value.replace(/\D/g, '');
    value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
    input.value = value;
    
    // Detectar bandeira do cartão (visual apenas)
    const firstDigit = value.charAt(0);
    let cardType = '';
    
    if (firstDigit === '4') cardType = 'Visa';
    else if (firstDigit === '5') cardType = 'Mastercard';
    else if (firstDigit === '3') cardType = 'American Express';
    
    // Você pode adicionar um indicador visual da bandeira aqui
}

// Função para formatar data de validade
function formatExpiry(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    input.value = value;
}

// Máscara para CEP
document.querySelectorAll('input[name*="postal_code"]').forEach(input => {
    input.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 8) {
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        }
    });
});

// Validação do formulário antes do envio
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
    
    if (!paymentMethod) {
        e.preventDefault();
        alert('Por favor, selecione um método de pagamento.');
        return;
    }
    
    // Se for cartão, validar campos básicos - APENAS PARA CARTÕES
    if (paymentMethod.value === 'credit_card' || paymentMethod.value === 'debit_card') {
        const cardName = document.getElementById('card_name').value.trim();
        const cardNumber = document.getElementById('card_number').value.replace(/\D/g, '');
        const cardExpiry = document.getElementById('card_expiry').value.trim();
        const cardCvv = document.getElementById('card_cvv').value.trim();
        
        if (!cardName || !cardNumber || !cardExpiry || !cardCvv) {
            e.preventDefault();
            alert('Por favor, preencha todos os dados do cartão.');
            return;
        }
        
        if (cardNumber.length < 13) {
            e.preventDefault();
            alert('Número do cartão inválido.');
            return;
        }
    }
    
    // Para PIX e Boleto, não validar campos de cartão
    if (paymentMethod.value === 'pix' || paymentMethod.value === 'boleto') {
        // Limpar valores dos campos de cartão para não enviar
        document.getElementById('card_name').value = '';
        document.getElementById('card_number').value = '';
        document.getElementById('card_expiry').value = '';
        document.getElementById('card_cvv').value = '';
    }
    
    // Mostrar loading no botão
    const submitBtn = document.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processando...';
    submitBtn.disabled = true;
});

// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    // Garantir que campos de cartão não sejam obrigatórios por padrão
    const cardInputs = document.querySelectorAll('#card-fields input');
    cardInputs.forEach(input => {
        input.removeAttribute('required');
    });
    
    // Focar no primeiro campo
    const firstInput = document.querySelector('input[name="payment_method"]');
    if (firstInput) {
        firstInput.focus();
    }
});
</script>
@endsection