<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();

        return response()->json($posts);
    }

    public function getPostsByDiscipline($discipline)
    {
        $posts = Post::where('discipline', $discipline)->get();

        return response()->json($posts);
    }

    public function getPostBySlug($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        return response()->json($post);
    }

    public function getPostIsFavoris()
    {
        $posts = Post::where('favoris', true)->get();

        return response()->json($posts);
    }

    public function getPostByDecade($year)
    {
        $startDecade = floor($year / 10) * 10;
        $endDecade = $startDecade + 9;
        $posts = Post::whereBetween('year', [$startDecade, $endDecade])->orderBy('year', 'ASC')->get();

        return response()->json([
            'decade' => "$startDecade-$endDecade",
            'posts' => $posts
        ]);
    }

    public function getPostByYear($year)
    {
        $posts = Post::where('year', $year)->orderBy('year', 'ASC')->get();

        return response()->json([
            'year' => $year,
            'posts' => $posts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Afficher le post par son id
        $post = Post::findOrFail($id);

        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // Update the post with the request data
        $post->update($request->all());

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(null, 204);
    }
}
