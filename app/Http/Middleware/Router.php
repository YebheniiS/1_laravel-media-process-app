<?php

namespace App\Http\Middleware;

use App\Models\Domain;
use App\Models\Funnel;
use App\Models\Page;
use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class Router
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
        // If not a GET we should ignore the router stuff otherwise we create conflicts with nova updated fields in the request
        if (str_contains(request()->path(), "nova")) {
            return $next($request);
        }

        // Get the domain name
        $domain = Domain::where( 'domain_name', $request->getHost() )->first();

        $path = explode('/', request()->path());

        // Ensure we're only getting the pages from a matched domain
        $funnel = ($domain) ? Funnel::where('url', $path[0])->first() : null;

        // Get the page

        // Remove the funnel
        unset($path[0]);

        // Then merge back into the url
        $pageUrl = implode('/', $path);


        $page = ($funnel)
            ?   Page::where('url', $pageUrl)->where('funnel_id', $funnel->id)->with('template')->first()
            :   null;

        View::composer('*', function($view) use($domain, $funnel, $page){
            $view->with('page', $page)->with('funnel', $funnel)->with('domain', $domain);
        });

        $request->merge([
            'page' => $page,
            'funnel' => $funnel,
            'domain' => $domain
        ]);

        return $next($request);
    }
}
