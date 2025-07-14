<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\SearchRequest;
use Illuminate\Support\Facades\Redis;

class ArticlesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SearchRequest $request)
    {
        $history = [];
        // Формирование поискового запроса
        $searchKey = "*" . $request['search'] . "* OR " . $request['search'] . "~";
        $results = Article::search($searchKey)->get();
        if (!Redis::exists('search_history')) {
            $history[] = $request['search'];
        }

        return response()->json([
            "cash count" => Redis::get('search_history'),
            "results" => $results
        ]);
    }
}
