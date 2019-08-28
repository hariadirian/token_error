<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'cek_order', 'inquiry', 'payment', 'assign/order', 'get_order_value','payment_status2', 'payment_status','payment_success', 'prepare_order', 'get_order', '/webpush','/webpush/*','api/*', 'gensignature', 'sendinv', 'closeinv', 'updateinvstatus'
    ];
}
