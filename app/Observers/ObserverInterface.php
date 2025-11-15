<?php

namespace App\Observers;

/**
 * Interface Observer - Padrão Observer
 * Define o contrato para todos os observadores
 */
interface ObserverInterface
{
    /**
     * Método chamado quando o subject notifica uma mudança
     * 
     * @param string $event Nome do evento
     * @param mixed $data Dados do evento
     */
    public function update(string $event, $data): void;
}
