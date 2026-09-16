<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    private string $apiKey;
    private string $model;
    private string $systemPrompt;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', '');
        $this->model = config('services.gemini.model', 'gemini-1.5-flash');
        $this->systemPrompt = <<<'PROMPT'
Bạn là trợ lý ảo của SocialShop — cửa hàng trực tuyến bán điện thoại, phụ kiện và đồ công nghệ.
Hãy trả lời ngắn gọn, thân thiện bằng tiếng Việt.
Nếu hỏi về giá, hãy khuyên khách vào xem trang sản phẩm.
Nếu hỏi về giao hàng, miễn phí ship cho đơn từ 500.000đ, giao toàn quốc.
Nếu hỏi về thanh toán, hỗ trợ COD và VNPay.
Nếu hỏi về liên hệ, hotline 1900-xxxx-xxx, email support@socialshop.vn.
Không trả lời các câu hỏi ngoài phạm vi cửa hàng.
PROMPT;
    }

    public function sendMessage(string $userMessage, array $history = []): string
    {
        if (empty($this->apiKey) || $this->apiKey === 'test') {
            return $this->fallbackResponse($userMessage);
        }

        try {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

            $contents = [];

            foreach ($history as $msg) {
                $contents[] = [
                    'role' => $msg['type'] === 'user' ? 'user' : 'model',
                    'parts' => [['text' => $msg['content']]],
                ];
            }

            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => $userMessage]],
            ];

            $payload = [
                'systemInstruction' => [
                    'parts' => [['text' => $this->systemPrompt]],
                ],
                'contents' => $contents,
                'generationConfig' => [
                    'temperature'     => 0.7,
                    'maxOutputTokens' => 256,
                ],
            ];

            $response = Http::timeout(15)->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text']
                    ?? 'Xin lỗi, tôi chưa hiểu yêu cầu của bạn.';
            }

            Log::warning('Gemini API error', ['status' => $response->status(), 'body' => $response->body()]);

            return $this->fallbackResponse($userMessage);
        } catch (\Exception $e) {
            Log::error('Gemini API exception', ['message' => $e->getMessage()]);

            return $this->fallbackResponse($userMessage);
        }
    }

    private function fallbackResponse(string $message): string
    {
        $lower = mb_strtolower($message);

        return match (true) {
            str_contains($lower, 'xin chào') || str_contains($lower, 'hello') || str_contains($lower, 'hi')
                => 'Xin chào! Rất vui được hỗ trợ bạn. Bạn cần tìm sản phẩm nào?',
            str_contains($lower, 'giá') || str_contains($lower, 'price')
                => 'Bạn có thể xem giá sản phẩm trên trang chi tiết sản phẩm. Chúng tôi có nhiều ưu đãi hấp dẫn!',
            str_contains($lower, 'giao hàng') || str_contains($lower, 'ship')
                => 'Chúng tôi giao hàng toàn quốc. Miễn phí ship cho đơn từ 500.000đ!',
            str_contains($lower, 'thanh toán') || str_contains($lower, 'payment')
                => 'Chúng tôi hỗ trợ thanh toán COD và VNPay. Bạn chọn phương thức nào phù hợp nhất!',
            str_contains($lower, 'liên hệ')
                => 'Bạn có thể liên hệ hotline 1900-xxxx-xxx hoặc email support@socialshop.vn.',
            default
                => 'Cảm ơn bạn đã nhắn tin! Hiện tại tôi là trợ lý demo. Vui lòng xem thêm thông tin trên trang web hoặc liên hệ hỗ trợ.',
        };
    }
}
