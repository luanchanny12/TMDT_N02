<?php

namespace App\Http\Controllers;

use App\Exceptions\CartException;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Services\Cart\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService
    ) {}

    /**
     * Trang giỏ hàng.
     *
     * @group Cart
     * @authenticated
     */
    public function index(): View
    {
        $cart = $this->cartService->getCartWithItems(auth()->id());

        return view('cart.index', compact('cart'));
    }

    /**
     * Thêm sản phẩm vào giỏ.
     *
     * Nếu sản phẩm đã có trong giỏ, số lượng sẽ được cộng dồn.
     *
     * @group Cart
     * @authenticated
     *
     * @bodyParam product_id int required ID của sản phẩm. Example: 1
     * @bodyParam quantity int required Số lượng muốn thêm. Example: 2
     *
     * @response 200 {"message": "Đã thêm sản phẩm vào giỏ hàng.", "cart_count": 3}
     * @response 422 {"message": "Sản phẩm \"Áo thun\" chỉ còn 1 trong kho."}
     */
    public function store(AddToCartRequest $request): JsonResponse
    {
        try {
            $this->cartService->addItem(
                auth()->id(),
                $request->integer('product_id'),
                $request->integer('quantity', 1)
            );

            $cartCount = $this->cartService->getItemCount(auth()->id());

            return response()->json([
                'message'    => 'Đã thêm sản phẩm vào giỏ hàng.',
                'cart_count' => $cartCount,
            ]);
        } catch (CartException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ.
     *
     * @group Cart
     * @authenticated
     *
     * @urlParam cartItem int required ID của CartItem. Example: 5
     * @bodyParam quantity int required Số lượng mới. Example: 3
     *
     * @response 200 {"message": "Đã cập nhật giỏ hàng.", "subtotal": 150000, "cart_total": 450000}
     * @response 422 {"message": "Sản phẩm \"Áo thun\" chỉ còn 1 trong kho."}
     */
    public function update(UpdateCartRequest $request, int $cartItem): JsonResponse
    {
        try {
            $item = $this->cartService->updateItem(
                auth()->id(),
                $cartItem,
                $request->integer('quantity')
            );

            $cart = $this->cartService->getCartWithItems(auth()->id());

            return response()->json([
                'message'    => 'Đã cập nhật giỏ hàng.',
                'subtotal'   => $item->subtotal(),
                'cart_total' => $cart->totalPrice(),
                'cart_count' => $cart->totalItems(),
            ]);
        } catch (CartException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Xóa 1 sản phẩm khỏi giỏ hàng.
     *
     * @group Cart
     * @authenticated
     *
     * @urlParam cartItem int required ID của CartItem. Example: 5
     *
     * @response 200 {"message": "Đã xóa sản phẩm khỏi giỏ hàng.", "cart_count": 2}
     */
    public function destroy(int $cartItem): JsonResponse
    {
        $this->cartService->removeItem(auth()->id(), $cartItem);

        $cartCount = $this->cartService->getItemCount(auth()->id());

        return response()->json([
            'message'    => 'Đã xóa sản phẩm khỏi giỏ hàng.',
            'cart_count' => $cartCount,
        ]);
    }
}
