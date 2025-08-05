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
        $validated = $request->validated();
        // Формирование поискового запроса
        $searchKey = "*" . $validated['search'] . "* OR " . $validated['search'] . "~";
        $results = Article::search($searchKey)->paginate(10);

        // Для того чтобы выполнить несколько операций одной пачкой используем pipeline
        Redis::pipeline(function ($pipe) use ($validated) {
            // Удаляем дубли внутри в массива
            $pipe->lrem('search_history',  $validated['search'], 0);
            // rpush добавляет элемент в конец списка
            // lpush добавляет элемент в конец списка
            $pipe->lpush('search_history', $validated['search']);
            // Обрезает размер списка внутри redis
            $pipe->ltrim('search_history', 0, 4);
        });

        // Получаем текущий список
        $history = Redis::lrange('search_history', 0, -1);

        return response()->json([
            "history" => $history,
            "results" => $results
        ])->header('Cache-Control', 'public, max-age=60');;
    }
}
