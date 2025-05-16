<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;

class ArticlesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SearchRequest $request)
    {
        $searchKey = $request['search'];
        $results = Article::search($searchKey)->get();

        return response()->json($results);
    }
}
