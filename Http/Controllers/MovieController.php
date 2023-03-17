<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MovieFormRequest;


class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $user = Auth::user();
        // $token = $user->createToken('testToken');
        // return  $token; 
        $movies = Movie::get();
        return view('movies.index', compact('movies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $genres = Genre::get();
        return view('movies.create', compact('genres'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MovieFormRequest $request)
    {
        $movie = new Movie();
        $movie->title = $request->title;
        $movie->cost = $request->cost;
        if (!$movie->save()) {
            return  redirect(route('movies.index'))->with('error', 'Something went wrong!');
        }

        $movie->genres()->attach($request->genres);
        return  redirect(route('movies.index'))->with('success', 'New movie was added!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        // dd($movie->genres()->pluck('genre_movie.genre_id'));
        $genres = Genre::get();
        return view('movies.edit', compact('movie', 'genres'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MovieFormRequest $request, Movie $movie)
    {
        $movie->title = $request->title;
        $movie->cost = $request->cost;
        if (!$movie->save()) {
            return  redirect(route('movies.index'))->with('error', 'Something went wrong!');
        }

        $movie->genres()->sync($request->genres);
        return  redirect(route('movies.index'))->with('success', 'Movie was updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {

        if ($movie->delete()) {
            return  response()->json()(['success' => 'Movie was deleted!']);
        }
        return  response()->json()(['error' => 'Something went wrong!']);
    }


    public function rent(Movie $movie)
    {
        if (Auth::user()->credit < $movie->cost) {
            return  redirect(route('movies.index'))->with('error', 'Not enough credit!');
        }

        $movie->user_id = Auth::id();
        if (!$movie->save()) {
            return  redirect(route('movies.index'))->with('error', 'Something went wrong!');
        }

        $user = Auth::user();
        $user->credit = ($user->credit - $movie->cost);
        $user->save(); 
        return  redirect(route('movies.index'))->with('success', 'Movie  rented!');
    }

    public function return(Movie $movie)
    {
        $movie->user_id = null;
        if ($movie->save()) {
            return  redirect(route('movies.index'))->with('success', 'Movie  returned!');
        }
        return  redirect(route('movies.index'))->with('error', 'Something went wrong!');
    }
}
