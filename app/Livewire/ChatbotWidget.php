<?php

namespace App\Livewire;

use App\Services\ChatbotService;
use Livewire\Component;

class ChatbotWidget extends Component
{
    public $isOpen = false;
    public $message = '';
    public $messages = [];
    public $isLoading = false;

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function sendMessage(ChatbotService $chatbot)
    {
        if (empty(trim($this->message))) {
            return;
        }

        $userMessage = $this->message;
        $this->message = '';

        $this->messages[] = [
            'type'    => 'user',
            'content' => $userMessage,
        ];

        $history = array_map(fn ($m) => [
            'type'    => $m['type'],
            'content' => $m['content'],
        ], $this->messages);

        $response = $chatbot->sendMessage($userMessage, $history);

        $this->messages[] = [
            'type'    => 'bot',
            'content' => $response,
        ];

        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.chatbot-widget');
    }
}