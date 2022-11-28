<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/paytm-callback*','/success/sent_to_success_url*','/fail/sent_to_fail_ur*',
        //
        //'/superadmin/school/create'
        
    ];
}
