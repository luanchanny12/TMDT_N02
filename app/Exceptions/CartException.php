<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Ném ra khi có vi phạm quy tắc nghiệp vụ của giỏ hàng.
 * Ví dụ: thêm quá tồn kho, sản phẩm không active...
 */
class CartException extends RuntimeException {}
