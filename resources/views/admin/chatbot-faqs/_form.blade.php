@php $isEdit = isset($faq); @endphp

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-[#3d3d3d] mb-1">Câu hỏi <span class="text-red-500">*</span></label>
        <input type="text" name="question" value="{{ old('question', $faq->question ?? '') }}" required
               class="w-full border border-[#efe8e3] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent"
               placeholder="Ví dụ: Shop có miễn phí ship không?">
        @error('question') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-[#3d3d3d] mb-1">Trả lời <span class="text-red-500">*</span></label>
        <textarea name="answer" required rows="4"
                  class="w-full border border-[#efe8e3] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent"
                  placeholder="Trả lời của chatbot khi khách hỏi câu này...">{{ old('answer', $faq->answer ?? '') }}</textarea>
        @error('answer') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-[#3d3d3d] mb-1">Từ khóa gợi ý</label>
        <input type="text" name="keywords" value="{{ old('keywords', $faq->keywords ?? '') }}"
               class="w-full border border-[#efe8e3] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent"
               placeholder="Ví dụ: ship, giao hàng, freeship (phân cách bằng dấu phẩy)">
        <p class="text-xs text-[#9a9490] mt-1">Từ khóa giúp chatbot nhận diện câu hỏi nhanh hơn</p>
        @error('keywords') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-[#3d3d3d] mb-1">Trạng thái <span class="text-red-500">*</span></label>
            <select name="status" required
                    class="w-full border border-[#efe8e3] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent">
                <option value="active" {{ old('status', $faq->status ?? 'active') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                <option value="inactive" {{ old('status', $faq->status ?? '') === 'inactive' ? 'selected' : '' }}>Tạm tắt</option>
            </select>
            @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-[#3d3d3d] mb-1">Thứ tự ưu tiên</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $faq->sort_order ?? 0) }}" min="0"
                   class="w-full border border-[#efe8e3] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent">
            @error('sort_order') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

<div class="mt-6 flex gap-3">
    <button type="submit"
            class="bg-[#b8847e] text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-[#a6736d] transition-colors">
        {{ $isEdit ? 'Cập nhật' : 'Tạo mới' }}
    </button>
    <a href="{{ route('admin.chatbot-faqs.index') }}"
       class="bg-[#faf7f4] text-[#3d3d3d] px-5 py-2 rounded-lg text-sm font-medium border border-[#efe8e3] hover:bg-[#e8c4c4] transition-colors">
        Hủy
    </a>
</div>
