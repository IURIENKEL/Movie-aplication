<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Services\TMDBService;

class MovieController extends Controller
{
    protected TMDBService $tmdbService;

    public function __construct(TMDBService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function index()
    {
        return Movie::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'overview' => 'nullable|string',
            'poster_path' => 'nullable|string',
            'release_date' => 'nullable|date',
            'tmdb_id' => 'required|string|unique:movies,tmdb_id',
        ]);

        $movie = Movie::create($validated);
        return response()->json($movie, 201);
    }

    public function show($id)
    {
        $movie = $this->tmdbService->getMovieDetails($id);

        return response()->json($movie);
    }

    public function update(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'overview' => 'nullable|string',
            'poster_path' => 'nullable|string',
            'release_date' => 'nullable|date',
            'tmdb_id' => 'sometimes|required|string|unique:movies,tmdb_id,' . $movie->id,
        ]);

        $movie->update($validated);
        return response()->json($movie);
    }

    public function destroy($id)
    {
        Movie::destroy($id);
        return response()->json(null, 204);
    }

    public function addToFavorites(Request $request)
    {
    $request->validate([
        'movie_id' => 'required|integer',
    ]);

    $tmdbMovie = $this->tmdbService->getMovieDetails($request->movie_id);

    if (isset($tmdbMovie['success']) && $tmdbMovie['success'] === false) {
        return response()->json(['message' => 'Filme não encontrado na API.'], 404);
    }

    $favorite = Favorite::firstOrCreate([
        'user_id' => auth()->id(),
        'movie_id' => $tmdbMovie['id'],
    ], [
        'title' => $tmdbMovie['title'],
        'poster_path' => $tmdbMovie['poster_path'],
        'genre_ids' => collect($tmdbMovie['genres'])->pluck('id')->toArray(),
    ]);

    return response()->json([
        'message' => 'Filme favoritado com sucesso.',
        'favorite' => $favorite,
    ]);
    }

    public function listFavorites(Request $request)
    {
        $genre = $request->input('genre');

        $favorites = Favorite::where('user_id', auth()->id())->get();

        if ($genre) {
            $favorites = $favorites->filter(function ($fav) use ($genre) {
                return in_array((int) $genre, $fav->genre_ids ?? []);
            })->values();
        }

        return response()->json($favorites);
    }

    public function removeFromFavorites($movie_id)
    {
        $deleted = Favorite::where('user_id', auth()->id())
            ->where('movie_id', $movie_id)
            ->delete();

        if ($deleted) {
            return response()->json(['message' => 'Removido com sucesso.']);
        }

        return response()->json(['message' => 'Filme não encontrado nos favoritos.'], 404);
    }
}
