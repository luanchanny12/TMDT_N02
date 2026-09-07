<?php

namespace App\Livewire;

use Livewire\Component;

class ChatbotWidget extends Component
{
    public $isOpen = false;
    public $message = '';
    public $messages = [];
    public $isLoading = false;

    protected $listeners = ['toggleChatbot' => 'toggle'];

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function sendMessage()
    {
        if (empty(trim($this->message))) {
            return;
        }

        $userMessage = $this->message;
        $this->messages[] = [
            'type' => 'user',
            'content' => $userMessage,
        ];
        $this->message = '';
        $this->isLoading = true;

        $this->dispatch('getBotResponse', message: $userMessage);

        $this->isLoading = false;
    }

    public function receiveBotResponse($response)
    {
        $this->messages[] = [
            'type' => 'bot',
            'content' => $response,
        ];
    }

    public function render()
    {
        return view('livewire.chatbot-widget');
    }
}
