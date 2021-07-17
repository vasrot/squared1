<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

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
            'user' => auth()->user()->id,
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
            foreach ($posts as $post) {
                Post::create([
                    'title' => $post->title,
                    'description' => $post->description,

                ]);
            }
        }

        // $data = json_decode($countryJson, true);
        // foreach ($data['countries'] as $obj) {
        //     Country::create(array(
        //       'country_name' => $obj['name'], 'iso_code' => $obj['sortname']
        //       ));
        // }
    }
}
