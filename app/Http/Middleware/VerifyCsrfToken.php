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
        '/api/integration/pks/ksufghksrghksafgewghyud',
        'projects/list',
        '/428q7trcq3478ty8cnq347tnc9q247t9nc2q4m9/stream-conversion-update',
        '/qencode/*',
        'api/qencode/*',
        'temp-routes/*'
    ];
}
