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
        $results = Article::search($searchKey)->paginate(10);

        // Сохранение истории поиска в Redis
        if (!Redis::exists('search_history')) {
            $history[] = $request['search'];
            Redis::set("search_history", json_encode($history));
        } else {
            $history = json_decode(Redis::get("search_history"));
            $history[] = $request['search'];
            $history = array_unique($history);

            Redis::set("search_history", json_encode($history));
        }

        return response()->json([
            "history" => json_decode(Redis::get('search_history')),
            "results" => $results
        ]);
    }
}