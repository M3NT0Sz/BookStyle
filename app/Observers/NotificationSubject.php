<?php

namespace App\Observers;

/**
 * Subject - Padrão Observer
 * Gerencia os observadores e notifica sobre mudanças
 */
class NotificationSubject
{
    /**
     * Lista de observadores registrados
     * @var ObserverInterface[]
     */
    private array $observers = [];

    /**
     * Registrar um observador
     */
    public function attach(ObserverInterface $observer): void
    {
        $this->observers[] = $observer;
    }

    /**
     * Remover um observador
     */
    public function detach(ObserverInterface $observer): void
    {
        $key = array_search($observer, $this->observers, true);
        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }

    /**
     * Notificar todos os observadores
     */
    public function notify(string $event, $data): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($event, $data);
        }
    }
}
