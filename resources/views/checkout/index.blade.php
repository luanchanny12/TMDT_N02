<x-layouts.app :title="'Thanh toán'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Thanh toán</h1>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Shipping Info --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Thông tin giao hàng</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên *</label>
                                <input type="text" name="shipping_name" value="{{ old('shipping_name', Auth::user()->name) }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 @error('shipping_name') border-red-500 @enderror"
                                       required>
                                @error('shipping_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại *</label>
                                <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 @error('shipping_phone') border-red-500 @enderror"
                                       required>
                                @error('shipping_phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ giao hàng *</label>
                            <textarea name="shipping_address" rows="3"
                                      class="w-full border border-gray-300 rounded-lg px-4 py-2 @error('shipping_address') border-red-500 @enderror"
                                      required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                            <textarea name="shipping_notes" rows="2"
                                      class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                      placeholder="Ghi chú cho đơn hàng (tùy chọn)">{{ old('shipping_notes') }}</textarea>
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="bg-white rounded-lg shadow p-6 mt-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Phương thức thanh toán</h2>

                        <div class="space-y-3">
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:border-[#c9a9a6] {{ old('payment_method') == 'cod' ? 'border-[#c9a9a6] bg-[#faf7f4]' : '' }}">
                                <input type="radio" name="payment_method" value="cod"
                                       {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }}
                                       class="h-4 w-4 text-[#b8847e]">
                                <div class="ml-3">
                                    <span class="font-medium text-gray-900">Thanh toán khi nhận hàng (COD)</span>
                                    <p class="text-sm text-gray-500">Thanh toán bằng tiền mặt khi nhận hàng</p>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:border-[#c9a9a6] {{ old('payment_method') == 'vnpay' ? 'border-[#c9a9a6] bg-[#faf7f4]' : '' }}">
                                <input type="radio" name="payment_method" value="vnpay"
                                       {{ old('payment_method') == 'vnpay' ? 'checked' : '' }}
                                       class="h-4 w-4 text-[#b8847e]">
                                <div class="ml-3">
                                    <span class="font-medium text-gray-900">VNPay</span>
                                    <p class="text-sm text-gray-500">Thanh toán trực tuyến qua VNPay Sandbox</p>
                                </div>
                            </label>
                        </div>
                        @error('payment_method')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-6 sticky top-24">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Đơn hàng của bạn</h2>

                        <div class="space-y-3 mb-4">
                            @foreach(Cart::getContent() as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ $item->name }} x{{ $item->quantity }}</span>
                                    <span class="font-medium">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} ₫</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t pt-3 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tạm tính</span>
                                <span>{{ number_format(Cart::getSubTotal(), 0, ',', '.') }} ₫</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Phí vận chuyển</span>
                                <span class="text-green-600">Miễn phí</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold border-t pt-2">
                                <span>Tổng cộng</span>
                                <span class="text-[#b8847e]">{{ number_format(Cart::getTotal(), 0, ',', '.') }} ₫</span>
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full bg-[#b8847e] text-white py-3 rounded-lg font-semibold hover:bg-[#a6736d] transition mt-6">
                            Đặt hàng
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
