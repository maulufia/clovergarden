<?php

namespace clovergarden\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/agspay/AGSMobile_approve',
        '/agspay/AGSMobile_cancel'
    ];
}
