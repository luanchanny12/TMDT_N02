<x-layouts.app :title="'Tài khoản'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Tài khoản</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Profile Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Thông tin cá nhân</h2>

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
                                <input type="text" name="name" value="{{ $user->name }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" value="{{ $user->email }}" disabled
                                       class="w-full border border-gray-200 rounded-lg px-4 py-2 bg-gray-50 text-gray-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                                <input type="text" name="phone" value="{{ $user->phone }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
                                <input type="text" name="address" value="{{ $user->address }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            </div>
                        </div>

                        <button type="submit" class="mt-6 bg-[#b8847e] text-white px-6 py-2 rounded-lg hover:bg-[#a6736d] transition">
                            Cập nhật
                        </button>
                    </form>
                </div>
            </div>

            {{-- Referral Code --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Mã giới thiệu</h2>
                    <p class="text-sm text-gray-600 mb-4">Chia sẻ mã này với bạn bè để nhận thưởng khi họ mua hàng thành công.</p>

                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <p class="text-2xl font-bold text-[#b8847e]">{{ $referralCode }}</p>
                    </div>

                    <button onclick="copyReferralCode()"
                            class="mt-4 w-full bg-[#b8847e] text-white py-2 rounded-lg hover:bg-[#a6736d] transition">
                        Copy mã giới thiệu
                    </button>

                    <div class="mt-4">
                        <p class="text-sm text-gray-600 mb-2">Chia sẻ qua:</p>
                        <div class="flex gap-3">
                            <button onclick="shareReferralToFacebook()" class="flex-1 bg-blue-600 text-white py-2 rounded-lg text-sm hover:bg-blue-700">
                                Facebook
                            </button>
                            <button onclick="shareReferralToZalo()" class="flex-1 bg-blue-500 text-white py-2 rounded-lg text-sm hover:bg-blue-600">
                                Zalo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyReferralCode() {
            navigator.clipboard.writeText('{{ $referralCode }}').then(() => {
                alert('Đã copy mã giới thiệu: {{ $referralCode }}');
            });
        }

        function shareReferralToFacebook() {
            const text = encodeURIComponent('Hãy sử dụng mã giới thiệu {{ $referralCode }} khi mua hàng tại SocialShop để nhận ưu đãi!');
            window.open(`https://www.facebook.com/sharer/sharer.php?quote=${text}`, '_blank');
        }

        function shareReferralToZalo() {
            const text = encodeURIComponent('Hãy sử dụng mã giới thiệu {{ $referralCode }} khi mua hàng tại SocialShop để nhận ưu đãi!');
            window.open(`https://zalo.me/share/?msg=${text}`, '_blank');
        }
    </script>
    @endpush
</x-layouts.app>
