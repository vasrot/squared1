<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Carbon;

class PostController extends Controller
{

    public function __construct() {
        $this->middleware('auth:sanctum')->only(['store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->showAll(Post::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'string|required',
            'description' => 'string|required',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->user()->id,
            'publication_date' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        if ($post->save()) {
            return $this->showOne($post, 201);
        }

        return $this->errorResponse('Post not saved', 500);
    }

    /**
     * Import posts from the given URL and save them under 'admin' user.
     */
    public function import() {
        $url = 'https://sq1-api-test.herokuapp.com/posts';

        $client = new Client();
        $response = $client->request('GET', $url);
        if ($response->getStatusCode()) {
            $posts = json_decode($response->getBody());

            // Save every post
            // User admin must be always ID=1, as it's the first user created in the database seeder file.
            foreach ($posts->data as $post) {
                Post::create([
                    'title' => $post->title,
                    'description' => $post->description,
                    'publication_date' => $post->publication_date,
                    'user_id' => 1
                ]);
            }
            return $this->successResponse('Job\'s done', 200);
        }
        return $this->errorResponse('Something went wrong.', 500);
    }
}
