<?php

namespace App\Livewire;

use App\Services\Chat\ChatService;
use Livewire\Component;

class ChatbotWidget extends Component
{
    public $isOpen = false;
    public $message = '';
    public $messages = [];
    public $isLoading = false;

    // Khởi tạo ChatService
    private ChatService $chatService;

    public function boot(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function mount()
    {
        // Khôi phục history từ Session nếu có
        $this->messages = session('chatbot_history', []);
    }

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
    }

    // Dùng cho quick action buttons (Livewire v4 không cho chain $set + method)
    public function sendQuick(string $text): void
    {
        $this->message = $text;
        $this->sendMessage();
    }

    public function sendMessage()
    {
        if (empty(trim($this->message))) {
            return;
        }

        $userMessage = $this->message;
        $this->message = '';

        // Lấy history TRƯỚC khi thêm message mới để tránh truyền 2 lần cho API
        $historyForApi = $this->messages;

        // Thêm user message vào UI
        $this->messages[] = [
            'type' => 'user',
            'content' => $userMessage,
        ];

        session(['chatbot_history' => $this->messages]);
        $this->dispatch('scrollToBottom');

        // Gọi Gemini API với history CŨ (không gồm message vừa thêm)
        $response = $this->chatService->sendMessage($userMessage, $historyForApi);

        $this->messages[] = [
            'type' => 'bot',
            'content' => $response,
        ];

        session(['chatbot_history' => $this->messages]);
        $this->dispatch('scrollToBottom');
    }

    public function render()
    {
        return view('livewire.chatbot-widget');
    }
}
