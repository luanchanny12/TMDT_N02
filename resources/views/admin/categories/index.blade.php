<x-layouts.admin :title="'Quản lý danh mục'" :header="'Danh mục'"
    x-data="{
        showModal: false,
        editing: null,
        formName: '',
        formSortOrder: 0,
        formPreview: '',
        formErrors: {},
        openCreate() {
            this.editing = null;
            this.formName = '';
            this.formSortOrder = 0;
            this.formPreview = '';
            this.formErrors = {};
            this.showModal = true;
        },
        openEdit(cat) {
            this.editing = cat;
            this.formName = cat.name;
            this.formSortOrder = cat.sort_order;
            this.formPreview = cat.image ? '{{ asset('storage/') }}/' + cat.image : '';
            this.formErrors = {};
            this.showModal = true;
        },
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => this.formPreview = e.target.result;
                reader.readAsDataURL(file);
            }
        },
        closeModal() {
            this.showModal = false;
            this.editing = null;
        }
    }">

    {{-- Toolbar --}}
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-[#9a9490]">Tổng: {{ $categories->count() }} danh mục</p>
        <button @click="openCreate()"
                class="inline-flex items-center gap-2 bg-[#b8847e] text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-[#a6736d] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Thêm danh mục
        </button>
    </div>

    {{-- Categories Table --}}
    @if($categories->count())
        <div class="bg-white rounded-xl shadow-sm border border-[#efe8e3] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-[#faf7f4]">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-[#9a9490] uppercase tracking-wide">Danh mục</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-[#9a9490] uppercase tracking-wide">Sản phẩm</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-[#9a9490] uppercase tracking-wide">Thứ tự</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-[#9a9490] uppercase tracking-wide">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#efe8e3]">
                        @foreach($categories as $cat)
                            <tr class="hover:bg-[#faf7f4] transition-colors">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($cat->image)
                                            <img src="{{ asset('storage/' . $cat->image) }}"
                                                 alt="{{ $cat->name }}"
                                                 class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#f5f0ec] to-[#efe8e3] flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-[#c9a9a6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-[#3d3d3d]">{{ $cat->name }}</p>
                                            <p class="text-xs text-[#9a9490]">{{ $cat->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        {{ $cat->products_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $cat->products_count }} SP
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-[#9a9490]">{{ $cat->sort_order }}</td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openEdit({{ $cat->toJson() }})"
                                                class="inline-flex items-center gap-1 text-[#b8847e] hover:text-[#a6736d] font-medium text-sm transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Sửa
                                        </button>
                                        @if($cat->products_count > 0)
                                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                                                  onsubmit="return confirm('Danh mục này đang có {{ $cat->products_count }} sản phẩm. Nếu xóa, các sản phẩm sẽ mất liên kết danh mục. Bạn có chắc muốn xóa?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 text-red-500 hover:text-red-700 font-medium text-sm transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Xóa
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 text-red-500 hover:text-red-700 font-medium text-sm transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Xóa
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-20 animate-fade-in-up">
            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-[#f5f0ec] to-[#efe8e3] rounded-full flex items-center justify-center">
                <svg class="h-12 w-12 text-[#c9a9a6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <h2 class="font-serif text-xl font-semibold text-[#3d3d3d] mb-2">Chưa có danh mục nào</h2>
            <p class="text-[#9a9490] mb-6 text-sm">Bắt đầu bằng cách thêm danh mục đầu tiên.</p>
            <button @click="openCreate()"
                    class="inline-flex items-center gap-2 bg-[#b8847e] text-white px-6 py-2.5 rounded-lg font-medium text-sm hover:bg-[#a6736d] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Thêm danh mục
            </button>
        </div>
    @endif

    {{-- Modal Add/Edit --}}
    <div x-show="showModal" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/40" @click="closeModal()"></div>

        {{-- Modal Content --}}
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             @click.away="closeModal()"
             class="relative bg-white rounded-xl shadow-2xl border border-[#efe8e3] w-full max-w-md overflow-hidden">

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-[#efe8e3] flex items-center justify-between">
                <h3 class="font-serif text-lg font-semibold text-[#3d3d3d]"
                    x-text="editing ? 'Sửa danh mục' : 'Thêm danh mục mới'"></h3>
                <button @click="closeModal()" class="text-[#9a9490] hover:text-[#3d3d3d] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form --}}
            <form :action="editing ? '{{ route('admin.categories.index') }}/' + editing.id : '{{ route('admin.categories.store') }}'"
                  method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <template x-if="editing">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-[#3d3d3d] mb-1.5">Tên danh mục *</label>
                    <input type="text" name="name" x-model="formName" required
                           placeholder="Nhập tên danh mục..."
                           class="w-full border border-[#efe8e3] rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent transition-all">
                </div>

                {{-- Image --}}
                <div>
                    <label class="block text-sm font-medium text-[#3d3d3d] mb-1.5">Hình ảnh</label>
                    <div x-show="formPreview" class="mb-3">
                        <img :src="formPreview" alt="Xem trước ảnh" class="w-16 h-16 rounded-full object-cover border border-[#efe8e3]">
                    </div>
                    <input type="file" name="image" accept="image/*"
                           @change="previewImage($event)"
                           class="w-full border border-[#efe8e3] rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent transition-all file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#faf7f4] file:text-[#b8847e] hover:file:bg-[#f5f0ec]">
                    <p class="text-xs text-[#9a9490] mt-1">Ảnh hiển thị dạng tròn, tối đa 1MB.</p>
                </div>

                {{-- Sort Order --}}
                <div>
                    <label class="block text-sm font-medium text-[#3d3d3d] mb-1.5">Thứ tự hiển thị</label>
                    <input type="number" name="sort_order" x-model="formSortOrder" min="0"
                           class="w-full border border-[#efe8e3] rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent transition-all">
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="closeModal()"
                            class="px-4 py-2.5 border border-[#c9a9a6] text-[#b8847e] rounded-lg text-sm font-medium hover:bg-[#e8c4c4] transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                            class="px-4 py-2.5 bg-[#b8847e] text-white rounded-lg text-sm font-medium hover:bg-[#a6736d] transition-colors btn-shine"
                            x-text="editing ? 'Cập nhật' : 'Thêm mới'">
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>
