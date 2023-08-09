<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AnalyticsController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function generatePixel($projectId, $price, Request $request)
    {
        $data = base64_encode(json_encode([
            'name' => 'conversion',
            'value' => (float) $price,
            'keen' => [
                'addons' => [
                    [
                        'name' => 'keen:ua_parser',
                        'input' => [
                            'ua_string' => 'user_agent'
                        ],
                        'output' => 'parsed_user_agent'
                    ],
                    [
                        'name' => 'keen:ip_to_geo',
                        'input' => [
                            'ip' => 'ip_address'
                        ],
                        'output' => 'ip_geo_info'
                    ]
                ]
            ],
            'user_agent' => '${keen.user_agent}',
            'ip_address' => '${keen.ip}'
        ]));

        return [
            'code' => '<img src="https://api.keen.io/3.0/projects/5a0b6d57c9e77c00010991ac/events/interactr_' . $projectId . '?api_key=0C0A06A953E7793508C231AA47A32803C99BAD34256449FBC431387C1D1933742B2461BE6C454968FD9F1B888924B5E3A4E5D37BBB709D2CDD7036A55343B7916025C0C19954D208D3C049E00950F09F666094BCF5C7733B1BE3E605CF1F016D&data=' . $data . '" />'
        ];
    }
}
