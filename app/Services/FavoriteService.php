<?php
namespace App\Services;

use App\Models\Favorite;
use App\Services\TMDBService;
use Illuminate\Support\Facades\Auth;

class FavoriteService
{
    protected TMDBService $tmdbService;

    public function __construct(TMDBService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function addToFavorites(int $movieId): array
    {
        $user = Auth::user();
        $tmdbMovie = $this->tmdbService->getMovieDetails($movieId);

        if (isset($tmdbMovie['success']) && $tmdbMovie['success'] === false) {
            return ['error' => 'Filme não encontrado na API.'];
        }

        $favorite = Favorite::firstOrCreate([
            'user_id' => $user->id,
            'movie_id' => $tmdbMovie['id'],
        ], [
            'title' => $tmdbMovie['title'],
            'poster_path' => $tmdbMovie['poster_path'],
            'genre_ids' => collect($tmdbMovie['genres'])->pluck('id')->toArray(),
        ]);

        return ['message' => 'Filme favoritado com sucesso.', 'favorite' => $favorite];
    }

    public function listFavorites(?int $genre = null)
    {
        $favorites = Favorite::where('user_id', Auth::id())->get();

        if ($genre) {
            $favorites = $favorites->filter(function ($fav) use ($genre) {
                return in_array((int) $genre, $fav->genre_ids ?? []);
            })->values();
        }

        return $favorites;
    }

    public function removeFromFavorites(int $movieId): bool
    {
        return Favorite::where('user_id', Auth::id())
            ->where('movie_id', $movieId)
            ->delete() > 0;
    }
}
