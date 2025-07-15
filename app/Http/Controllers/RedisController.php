<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller
{
    public function saveToRedis(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'value' => 'required|string',
        ]);

        // Exists используется для проверки существования ключа
        $exist = Redis::exists($validated['key']);
        // Сохраняем значение в Redis
        Redis::set($validated['key'], $validated['value']);

        return response()->json([
            'exist' => $exist ? true : false,
            'success' => true,
            'msg' => 'Сохранено'
        ]);
    }

    public function getFromRedis(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string',
        ]);

        // Получаем значение по ключу
        $value = Redis::get($validated['key']);

        if ($value !== null) {
            return response()->json([
                'success' => true,
                'data' => $value
            ]);
        }

        return response()->json([
            'success' => false,
            'msg' => 'Not found'
        ], 404);
    }

    public function deleteFromRedis(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string',
        ]);

        // Удаляем ключ из Redis
        $deleted = Redis::del($validated['key']);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'msg' => 'Удалено'
            ]);
        }

        return response()->json([
            'success' => false,
            'msg' => 'Not found'
        ], 404);
    }

    public function clearRedis()
    {
        // Очищаем базу данных Redis
        Redis::flushdb();

        return response()->json([
            'success' => true,
            'msg' => 'Redis очищен'
        ]);
    }
}
