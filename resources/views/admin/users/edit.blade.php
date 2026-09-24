<x-layouts.admin :title="'Chỉnh sửa người dùng'" :header="'Sửa người dùng: ' . $user->name">
    <div class="max-w-xl">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-[#efe8e3] p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-[#3d3d3d] mb-1.5">Họ tên *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full border border-[#efe8e3] rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent transition-all">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-[#3d3d3d] mb-1.5">Email</label>
                <input type="email" value="{{ $user->email }}" disabled
                       class="w-full border border-[#efe8e3] bg-[#faf7f4] text-[#9a9490] rounded-lg px-4 py-2.5 text-sm cursor-not-allowed">
                <p class="text-xs text-[#9a9490] mt-1">Không thể sửa email ở đây.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#3d3d3d] mb-1.5">Vai trò *</label>
                <select name="role"
                        @if(auth()->id() === $user->id) disabled @endif
                        class="w-full border border-[#efe8e3] rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent transition-all bg-white">
                    <option value="customer" @selected(old('role', $user->role) === 'customer')>Customer</option>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                </select>
                @if(auth()->id() === $user->id)
                    <input type="hidden" name="role" value="{{ $user->role }}">
                    <p class="text-xs text-[#9a9490] mt-1">Không thể tự thay đổi vai trò của chính mình.</p>
                @endif
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-[#3d3d3d] mb-1.5">Trạng thái *</label>
                <select name="is_active"
                        class="w-full border border-[#efe8e3] rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent transition-all bg-white">
                    <option value="1" @selected((int) old('is_active', $user->is_active ? 1 : 0) === 1)>Hoạt động</option>
                    <option value="0" @selected((int) old('is_active', $user->is_active ? 1 : 0) === 0)>Đã khóa</option>
                </select>
                @error('is_active') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.users.index') }}"
                   class="px-4 py-2.5 border border-[#c9a9a6] text-[#b8847e] rounded-lg text-sm font-medium hover:bg-[#e8c4c4] transition-colors">
                    Quay lại
                </a>
                <button type="submit"
                        class="px-4 py-2.5 bg-[#b8847e] text-white rounded-lg text-sm font-medium hover:bg-[#a6736d] transition-colors">
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
