<x-layouts.admin :title="'Quản lý khách hàng'" :header="'Khách hàng'">

    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 flex-1">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Tìm theo tên hoặc email..."
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
        <p class="text-sm text-[#9a9490] whitespace-nowrap">Tổng: {{ $users->total() }} khách hàng</p>
    </div>

    {{-- Users Table --}}
    @if($users->count())
        <div class="bg-white rounded-xl shadow-sm border border-[#efe8e3] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-[#faf7f4]">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-[#9a9490] uppercase tracking-wide">Khách hàng</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-[#9a9490] uppercase tracking-wide">Vai trò</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-[#9a9490] uppercase tracking-wide">Ngày tham gia</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-[#9a9490] uppercase tracking-wide">Đơn hàng</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#efe8e3]">
                        @foreach($users as $user)
                            <tr class="hover:bg-[#faf7f4] transition-colors">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                                                 class="w-10 h-10 rounded-full object-cover flex-shrink-0 border border-[#efe8e3]">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-[#b8847e] flex items-center justify-center flex-shrink-0">
                                                <span class="text-white text-sm font-semibold">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-semibold text-[#3d3d3d] truncate">
                                                {{ $user->name }}
                                            </p>
                                            <p class="text-xs text-[#9a9490] truncate">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    @if($user->isAdmin())
                                        <span class="inline-flex items-center bg-[#faf7f4] text-[#b8847e] text-[10px] font-bold px-1.5 py-0.5 rounded">Admin</span>
                                    @else
                                        <span class="text-xs text-[#9a9490]">Khách hàng</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-[#9a9490]">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="font-medium text-[#3d3d3d]">{{ $user->orders_count }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="px-5 py-4 border-t border-[#efe8e3]">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-20 animate-fade-in-up">
            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-[#f5f0ec] to-[#efe8e3] rounded-full flex items-center justify-center">
                <svg class="h-12 w-12 text-[#c9a9a6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h2 class="font-serif text-xl font-semibold text-[#3d3d3d] mb-2">Không tìm thấy khách hàng</h2>
            <p class="text-[#9a9490] text-sm">
                @if(request('search'))
                    Thử thay đổi từ khóa tìm kiếm.
                @else
                    Chưa có khách hàng nào trong hệ thống.
                @endif
            </p>
        </div>
    @endif

</x-layouts.admin>
