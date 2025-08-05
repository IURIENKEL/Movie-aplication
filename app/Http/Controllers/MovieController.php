<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TMDBService;
use App\Services\MovieService;
use App\Services\FavoriteService;

class MovieController extends Controller
{
    protected TMDBService $tmdbService;
    protected MovieService $movieService;
    protected FavoriteService $favoriteService;

    public function __construct(
        TMDBService $tmdbService,
        MovieService $movieService,
        FavoriteService $favoriteService
    ) {
        $this->tmdbService = $tmdbService;
        $this->movieService = $movieService;
        $this->favoriteService = $favoriteService;
    }

    public function index()
    {
        return $this->movieService->getAll();
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

        $movie = $this->movieService->store($validated);
        return response()->json($movie, 201);
    }

    public function show($id)
    {
        return response()->json($this->tmdbService->getMovieDetails($id));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'overview' => 'nullable|string',
            'poster_path' => 'nullable|string',
            'release_date' => 'nullable|date',
            'tmdb_id' => 'sometimes|required|string|unique:movies,tmdb_id,' . $id,
        ]);

        $movie = $this->movieService->update($id, $validated);
        return response()->json($movie);
    }

    public function destroy($id)
    {
        $this->movieService->destroy($id);
        return response()->json(null, 204);
    }

    public function addToFavorites(Request $request)
    {
        $request->validate(['movie_id' => 'required|integer']);

        $result = $this->favoriteService->addToFavorites($request->movie_id);

        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], 404);
        }

        return response()->json($result);
    }

    public function listFavorites(Request $request)
    {
        $genre = $request->input('genre');
        $favorites = $this->favoriteService->listFavorites($genre);

        return response()->json($favorites);
    }

    public function removeFromFavorites($movie_id)
    {
        $deleted = $this->favoriteService->removeFromFavorites($movie_id);

        if ($deleted) {
            return response()->json(['message' => 'Removido com sucesso.']);
        }

        return response()->json(['message' => 'Filme não encontrado nos favoritos.'], 404);
    }
}
