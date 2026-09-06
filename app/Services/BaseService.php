<?php

namespace App\Services;

/**
 * BaseService
 *
 * Abstract base class cho tất cả Services trong project.
 * Không chứa business logic — chỉ cung cấp helper methods chung.
 */
abstract class BaseService
{
    //
    // Sẽ bổ sung helper methods khi có nhu cầu thực tế:
    //   - formatCurrency(int $amount): string
    //   - generateCode(string $prefix): string
    //   - v.v.
    //
}
