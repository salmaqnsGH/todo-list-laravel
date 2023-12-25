<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoCreateRequest;
use App\Http\Requests\TodoUpdateRequest;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\TodoResource;
use App\Models\Activity;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    private function getActivity(int $activityID): Activity
    {
        $user = Auth::user();

        $activity = Activity::where('user_id', $user->id)->where('id',  $activityID)->first();
       
        if(!$activity){
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        return $activity;
    }

    private function getTodo(int $todoID): Todo
    {
        $todo = Todo::where('id', $todoID)->first();
        if(!$todo){
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        return $todo;
    }

    public function create(int $activityID, TodoCreateRequest $request): JsonResponse
    {
        $activity = $this->getActivity($activityID);

        $data = $request->validated();

        $todo = new Todo($data);
        $todo->activity_id = $activity->id;
        $todo->save();

        return (new TodoResource($todo))->response()->setStatusCode(201);
    }

    public function get(int $todoID): TodoResource
    {
        $todo = $this->getTodo($todoID);

        return new TodoResource($todo);
    }

    public function update(int $todoID, TodoUpdateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $todo = new Todo($data);
        $todo->fill($data);
        $todo->save();

        return (new TodoResource($todo))->response()->setStatusCode(200);
    }
}
