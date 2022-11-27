<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Todo;

class TodoController extends Controller
{
    /**
     * タスク一覧を取得する
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $params = [
            'title'    => $request->input('title', null),
            'start_at' => $request->input('start_at', null),
            'end_at'   => $request->input('end_at', null),
        ];

        try {
            $query = Todo::query();
            if (isset($params['title'])) {
                $query->Where('title', 'LIKE', "%{$params['title']}%");
            }
            if (isset($params['start_at'])) {
                $query->Where('start_at', $params['start_at']);
            }
            if (isset($params['end_at'])) {
                $query->Where('end_at', $params['end_at']);
            }
            $todos = $query->get(['id', 'title']);

            if ($todos->isEmpty()) {
                $todos = '該当のタスクは見つかりませんでした。';
            }

            $result = [
                'status' => Response::HTTP_OK,
                'list' => $todos
            ];
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error($e);
            $result = [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'エラーが発生しました。'
            ];
            return response()->json($result);
        }
    }
}
