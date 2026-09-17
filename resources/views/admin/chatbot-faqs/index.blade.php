<x-layouts.admin title="Chatbot FAQ" header="Quản lý Chatbot FAQ">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-[#efe8e3] p-4">
            <p class="text-[#9a9490] text-sm">Tổng câu hỏi</p>
            <p class="text-2xl font-bold text-[#3d3d3d]">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-[#efe8e3] p-4">
            <p class="text-[#9a9490] text-sm">Đang hoạt động</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-[#efe8e3] p-4">
            <p class="text-[#9a9490] text-sm">Tạm tắt</p>
            <p class="text-2xl font-bold text-red-500">{{ $stats['total'] - $stats['active'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-[#efe8e3]">
        <div class="p-4 border-b border-[#efe8e3] flex flex-col sm:flex-row gap-3 justify-between items-start sm:items-center">
            <form method="GET" class="flex gap-2 flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm câu hỏi..."
                       class="flex-1 border border-[#efe8e3] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent">
                <select name="status" class="border border-[#efe8e3] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#c9a9a6]">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tạm tắt</option>
                </select>
                <button type="submit" class="bg-[#b8847e] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#a6736d]">Tìm</button>
            </form>
            <a href="{{ route('admin.chatbot-faqs.create') }}"
               class="bg-[#b8847e] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#a6736d] flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Thêm câu hỏi
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#faf7f4] text-[#9a9490] text-left">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Câu hỏi</th>
                        <th class="px-4 py-3">Trả lời</th>
                        <th class="px-4 py-3">Từ khóa</th>
                        <th class="px-4 py-3">Trạng thái</th>
                        <th class="px-4 py-3">Thứ tự</th>
                        <th class="px-4 py-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#efe8e3]">
                    @forelse($faqs as $faq)
                        <tr class="hover:bg-[#faf7f4]">
                            <td class="px-4 py-3 text-[#9a9490]">{{ $faq->id }}</td>
                            <td class="px-4 py-3 font-medium text-[#3d3d3d] max-w-[200px] truncate">{{ $faq->question }}</td>
                            <td class="px-4 py-3 text-[#9a9490] max-w-[300px] truncate">{{ $faq->answer }}</td>
                            <td class="px-4 py-3 text-[#9a9490] text-xs">{{ $faq->keywords ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if($faq->status === 'active')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Hoạt động</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Tạm tắt</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-[#9a9490]">{{ $faq->sort_order }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('admin.chatbot-faqs.edit', $faq) }}"
                                       class="text-sm px-3 py-1.5 rounded-lg bg-[#faf7f4] hover:bg-[#e8c4c4] text-[#3d3d3d] transition-colors flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span class="hidden xl:inline">Sửa</span>
                                    </a>
                                    <form action="{{ route('admin.chatbot-faqs.toggle', $faq) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="text-sm px-3 py-1.5 rounded-lg bg-[#faf7f4] hover:bg-[#e8c4c4] text-[#3d3d3d] transition-colors flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                            <span class="hidden xl:inline">{{ $faq->status === 'active' ? 'Tắt' : 'Bật' }}</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.chatbot-faqs.destroy', $faq) }}" method="POST"
                                          onsubmit="return confirm('Xóa câu hỏi này?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="text-sm px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span class="hidden xl:inline">Xóa</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-[#9a9490]">Chưa có câu hỏi FAQ nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $faqs->links() }}
        </div>
    </div>
</x-layouts.admin>
