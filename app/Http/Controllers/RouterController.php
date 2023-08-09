<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RouterController extends Controller
{

    public function router()
    {
        if(request()->page) {
            return view('/templates/' . request()->page->template->blade_template);
        }

        // This the root  page for the domain shouldn't get here this is just a fallback, a redirect should be setup to redirect to the proper website funnel page
        switch(request()->getHost()){
            case('interactr.io') :
                return (request()->path() === '/') ? view('/templates/interactr-fe') : redirect('/');
            default :
                abort(404);
        }
    }
}
