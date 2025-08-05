<?php
namespace App\Services;

use App\Models\Movie;

class MovieService
{
    public function getAll()
    {
        return Movie::all();
    }

    public function store(array $data)
    {
        return Movie::create($data);
    }

    public function find($id)
    {
        return Movie::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $movie = $this->find($id);
        $movie->update($data);
        return $movie;
    }

    public function destroy($id)
    {
        return Movie::destroy($id);
    }
}
