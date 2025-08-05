<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TMDBService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
        $this->baseUrl = 'https://api.themoviedb.org/3';
    }

    public function searchMovies($query)
    {
        $response = Http::get("{$this->baseUrl}/search/movie", [
            'api_key' => $this->apiKey,
            'query' => $query,
            'language' => 'pt-BR',
        ]);

        return $response->json();
    }

    public function getMovieDetails($id)
    {
        $response = Http::get("{$this->baseUrl}/movie/{$id}", [
            'api_key' => $this->apiKey,
            'language' => 'pt-BR',
        ]);

        return $response->json();
    }
}
