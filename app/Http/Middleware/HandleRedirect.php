<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;

class HandleRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check for redirects
        $redirect = Redirect::where('from', $request->url())->first();

        if($redirect) {
            $redirect->increment('visits');

            $params = '?';
            foreach(request()->all() as $param => $value){
                $params .= $param . '=' . $value . "&";
            }

            return redirect()->away($redirect->to . $params  );
        }

        return $next($request);
    }
}
