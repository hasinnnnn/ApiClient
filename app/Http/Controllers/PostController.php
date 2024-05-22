<?php

namespace App\Http\Controllers;

use App\Models\Post;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Content;


class PostController extends Controller
{
    private function fetchDataFromAPI()
    {
        $client = new Client();
        $url = "http://apihasin.test/api/posts/";
        $response = $client->get($url);
        if ($response->getStatusCode() !== 200) {
        }
        return json_decode($response->getBody()->getContents(), true)['data'];
    }

    private function sendRequest(array $data)
    {
        $client = new Client();
        $url = "http://apihasin.test/api/posts/";
        return $client->post($url, [
            'json' => $data,
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    const API_URL =  "http://apihasin.test/api/posts/";
    public function index()
    {
        $current_url = url()->current();
        $data = $this->fetchDataFromAPI();
        //Using Laravel's built-in collection methods (simpler)
        $data = collect($data);
        $data = $data->jsonserialize();
        foreach ($data['links'] as $key => $value) {
            $parsedUrl = parse_url($value['url']);
            $path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
            $query = isset($parsedUrl['query']) ? $parsedUrl['query'] : '';

            $baseUrl = parse_url($current_url, PHP_URL_SCHEME) . '://' . parse_url($current_url, PHP_URL_HOST);

            $data['links'][$key]['url2'] = $baseUrl . $path . '?' . $query;
        }


        print_r($data['links']);
        exit();
        return view('posts', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'title' => $request->title,
            'author' => $request->author,
            'slug' => $request->slug,
            'body' => $request->body,
        ];

        $response = $this->sendRequest($data);

        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        $data = $contentArray['status'];

        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return back()->withErrors($error)->withInput();
        } else {
            return back()->with('success', 'berhasil menambahkan data');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = new Client();
        $url = "http://apihasin.test/api/posts/$id";
        $response = $client->get($url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);

        if ($contentArray['status'] != true) {
            $error = $contentArray['message'];
            return back()->withErrors($error);
        } else {
            $data = $contentArray['data'];

            return view('posts', ['data' => $data]);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = [
            'title' => $request->title,
            'author' => $request->author,
            'slug' => $request->slug,
            'body' => $request->body,
        ];

        $client = new Client();
        $url = "http://apihasin.test/api/posts/$id";

        $response = $client->put($url, [
            'json' => $data,
        ]);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);

        // Check response status code for success
        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return back()->withErrors($error)->withInput();
        }

        // Success message (adjust as needed)
        return redirect()->to('posts')->with('success', 'Berhasil update data!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, string $id)
    {
        $client = new Client();
        $url = "http://apihasin.test/api/posts/$id";

        $response = $client->delete($url);

        // Check response status code for success
        if ($response->getStatusCode() !== 200) {
            $error = json_decode($response->getBody()->getContents(), true)['message']; // Assuming message key exists in error response
            return back()->withErrors($error)->withInput();
        }

        // Success message (adjust as needed)
        return back()->with('success', 'berhasil delete data!');
    }
}
