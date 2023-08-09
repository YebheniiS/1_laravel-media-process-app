<?php

namespace App\GraphQL\Queries;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class StockVideos
{
    /**
     * @param null $_
     * @param array<string, mixed> $args
     */
    public function __invoke($_, array $args)
    {
        return $this;
    }

    public function giphySearch(array $params): array
    {
        $endpoint = $params['query']
            ? config('services.giphy.search')
            : config('services.giphy.trending');

        $res = Http::get($endpoint, [
          'api_key' => config('services.giphy.key'),
          'q' => $params['query'],
          'page' => $params['page'],
          'per_page' => $params['per_page']
        ]);

        return $res->json();
    }

    public function pexelsSearch(array $params): array
    {
        $endpoint = $params['query']
            ? config('services.pexels.search')
            : config('services.pexels.popular');

        $res = Http::withHeaders(['Authorization' => config('services.pexels.key')])
            ->get($endpoint, [
                'query' => $params['query'],
                'page' => $params['page'],
                'per_page' => $params['per_page']
            ]
        );

        return $res->json();
    }

    public function pixabaySearch(array $params): array
    {
        $res = Http::get(config('services.pixabay.url'), [
            'key' => config('services.pixabay.key'), 
            'q' => $params['query'],
            'page' => $params['page'],
            'per_page' => $params['per_page']
            ]
        );

        return $res->json();
    }
}
