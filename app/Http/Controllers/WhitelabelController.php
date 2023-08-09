<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Scopes\UserScope;
use Illuminate\Http\Request;

class WhitelabelController extends Controller
{
    //
    public function getWhitelabelForDomain()
    {
        $referrer = request()->headers->get('origin'); // was 'referer', but modified by MagicPalm
        $domain = preg_replace( "#^[^:/.]*[:/]+#i", "", $referrer );
        
        $domain = trim($domain, '/');
        // For security as this route is unauthenticated we need to be careful what we return
        $whitelabel = Agency::where('domain', $domain)->withoutGlobalScope(UserScope::class)->select([
            'name', 'page_title', 'primary_color', 'background_colour', 'secondary_color', 'icon', 'logo'
        ])->first();

        return response()->json($whitelabel);

    }
}
