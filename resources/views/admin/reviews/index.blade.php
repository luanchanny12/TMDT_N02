<x-layouts.admin :title="'Quản lý đánh giá'" :header="'Đánh giá'">

    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 flex-1">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Tìm nội dung, sản phẩm hoặc khách..."
                       class="w-full pl-10 pr-4 py-2.5 border border-[#efe8e3] rounded-lg bg-white focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent transition-all text-sm">
                <svg class="absolute left-3 top-3 h-4 w-4 text-[#9a9490]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <button type="submit"
                    class="bg-[#b8847e] text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-[#a6736d] transition-colors">
                Tìm
            </button>
        </form>
    </div>

    {{-- Status Filter Tabs --}}
    @php
        $statuses = [
            '' => 'Tất cả',
            'pending' => 'Chờ duyệt',
            'approved' => 'Đã duyệt',
            'rejected' => 'Từ chối',
        ];
    @endphp
    <div class="flex flex-wrap gap-2 mb-6">
        @foreach($statuses as $value => $label)
            <a href="{{ route('admin.reviews.index', array_merge(request()->except('status', 'page'), $value ? ['status' => $value] : [])) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ request('status') === $value || (!request('status') && $value === '')
                          ? 'bg-[#b8847e] text-white'
                          : 'bg-white border border-[#efe8e3] text-[#3d3d3d] hover:bg-[#faf7f4]' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Reviews Table --}}
    @if($reviews->count())
        <div class="bg-white rounded-xl shadow-sm border border-[#efe8e3] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-[#faf7f4]">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-[#9a9490] uppercase tracking-wide">Sản phẩm</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-[#9a9490] uppercase tracking-wide">Khách</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-[#9a9490] uppercase tracking-wide">Sao</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-[#9a9490] uppercase tracking-wide">Nội dung</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-[#9a9490] uppercase tracking-wide">Trạng thái</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-[#9a9490] uppercase tracking-wide">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#efe8e3]">
                        @foreach($reviews as $review)
                            <tr class="hover:bg-[#faf7f4] transition-colors">
                                <td class="px-5 py-4">
                                    <p class="font-medium text-[#3d3d3d]">{{ $review->product?->name ?? '—' }}</p>
                                    <p class="text-xs text-[#9a9490]">{{ $review->created_at->format('d/m/Y H:i') }}</p>
                                </td>
                                <td class="px-5 py-4 text-[#3d3d3d]">{{ $review->user?->name ?? '—' }}</td>
                                <td class="px-5 py-4 text-[#c9a9a6]">
                                    @for($i = 1; $i <= 5; $i++)
                                        {{ $i <= $review->rating ? '★' : '☆' }}
                                    @endfor
                                </td>
                                <td class="px-5 py-4 max-w-[280px]">
                                    <p class="text-[#6b6560] text-sm line-clamp-2">{{ $review->comment ?: '—' }}</p>
                                    @if($review->image)
                                        <img src="{{ asset('storage/' . $review->image) }}" alt="Ảnh đánh giá"
                                             class="mt-2 w-14 h-14 object-cover rounded-lg border border-[#efe8e3]">
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Chờ duyệt',
                                            'approved' => 'Đã duyệt',
                                            'rejected' => 'Từ chối',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$review->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$review->status] ?? $review->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($review->status !== 'approved')
                                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 text-green-600 hover:text-green-800 font-medium text-sm transition-colors">
                                                    Duyệt
                                                </button>
                                            </form>
                                        @endif
                                        @if($review->status !== 'rejected')
                                            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 text-orange-500 hover:text-orange-700 font-medium text-sm transition-colors">
                                                    Từ chối
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                              onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 text-red-500 hover:text-red-700 font-medium text-sm transition-colors">
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($reviews->hasPages())
                <div class="px-5 py-4 border-t border-[#efe8e3]">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-20 animate-fade-in-up">
            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-[#f5f0ec] to-[#efe8e3] rounded-full flex items-center justify-center">
                <svg class="h-12 w-12 text-[#c9a9a6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <h2 class="font-serif text-xl font-semibold text-[#3d3d3d] mb-2">Không tìm thấy đánh giá</h2>
            <p class="text-[#9a9490] text-sm">
                @if(request('search') || request('status'))
                    Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.
                @else
                    Chưa có đánh giá nào trong hệ thống.
                @endif
            </p>
        </div>
    @endif

</x-layouts.admin>
