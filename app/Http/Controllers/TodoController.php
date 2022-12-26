<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Todo;
use App\Http\Requests\todo\DetailRequest;
use App\Http\Requests\todo\CreateRequest;
use App\Http\Requests\todo\UpdateRequest;
use App\Http\Requests\todo\DeleteRequest;
use App\Exceptions\ApiNoDataExistException;
use App\Http\Resources\TodoCollection;
use App\Http\Resources\TodoResource;

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
        $todos = Todo::when($request->input('title'), function ($query, $title) {
            $query->where('title', 'LIKE', "%{$title}%");
        })
            ->when($request->input('start_at'), function ($query, $startAt) {
                $query->where('start_at', $startAt);
            })
            ->when($request->input('end_at'), function ($query, $endAt) {
                $query->where('end_at', $endAt);
            })
            ->get();

        if ($todos->isEmpty()) {
            throw new ApiNoDataExistException();
            // abort(404, '該当のタスクは見つかりませんでした。');
        }
        return new TodoCollection($todos);
    }

    /**
     * 指定したタスクを取得
     *
     * @param DetailRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function detail(DetailRequest $request, $id)
    {
        $todo = Todo::where('id', $id)->whereNull('deleted_at')->firstOrFail();
        return new TodoResource($todo);
    }

    /**
     * タスクを新規登録する
     *
     * @param CreateRequest $request
     * @return JsonResponse
     */
    public function create(CreateRequest $request)
    {
        $todo = Todo::create($request->all());
        return new TodoResource($todo);
    }

    /**
     * タスクを更新する
     *
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, $id)
    {
        if (!Todo::where('id', $id)->whereNull('deleted_at')->exists()) {
            throw new ApiNoDataExistException();
        }
        Todo::where('id', $id)->update($request->all());
        $todo = Todo::where('id', $id)->first();
        return new TodoResource($todo);
    }

    /**
     * タスクを削除する
     *
     * @param DeleteRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function delete(DeleteRequest $request, $id)
    {
        if (!Todo::where('id', $id)->whereNull('deleted_at')->exists()) {
            throw new ApiNoDataExistException();
        }

        Todo::where('id', $id)->delete();
    }
}
