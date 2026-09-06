<?php

namespace App\Services\Chat;

use App\Services\BaseService;

/**
 * ChatService — Day 10
 *
 * Business logic:
 *   - sendMessage(string $userMessage, array $history): string
 *       → Gọi Gemini API với context
 *       → Trả về response text
 *   - buildPrompt(string $userMessage, array $history): array
 *       → Xây dựng conversation history cho API call
 *   - Giới hạn lịch sử chat (chỉ giữ N tin gần nhất)
 */
class ChatService extends BaseService
{
    // TODO: Day 10
}
