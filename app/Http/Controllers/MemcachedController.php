<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MemcachedController extends Controller
{
    // Установить значение в кэш
    public function set(Request $request)
    {
        $key = $request->input('key', 'default_key');
        $value = $request->input('value', 'default_value');
        $ttl = $request->input('ttl', 60); // минуты

        Cache::put($key, $value, now()->addMinutes($ttl));

        return response()->json(['message' => 'Кэш установлен', 'key' => $key, 'value' => $value]);
    }

    // Получить значение из кэша
    public function get(Request $request)
    {
        $key = $request->input('key', 'default_key');

        $value = Cache::get($key);

        return response()->json(['key' => $key, 'value' => $value]);
    }

    // Удалить ключ из кэша
    public function delete(Request $request)
    {
        $key = $request->input('key', 'default_key');

        $deleted = Cache::forget($key);

        return response()->json(['message' => $deleted ? 'Удалено' : 'Ключ не найден', 'key' => $key]);
    }

    // Проверить наличие ключа
    public function has(Request $request)
    {
        $key = $request->input('key', 'default_key');

        $exists = Cache::has($key);

        return response()->json(['key' => $key, 'exists' => $exists]);
    }
}
