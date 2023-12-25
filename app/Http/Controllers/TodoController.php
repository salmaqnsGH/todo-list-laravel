<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoCreateRequest;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\TodoResource;
use App\Models\Activity;
use App\Models\Todo;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function create(int $activityID, TodoCreateRequest $request): JsonResponse
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

        $data = $request->validated();

        $todo = new Todo($data);
        $todo->activity_id = $activity->id;
        $todo->save();

        return (new TodoResource($todo))->response()->setStatusCode(201);
    }

    public function get(int $activityID, int $todoID): TodoResource
    {
        $user = Auth::user();

        $activity = Activity::where('id', $activityID)->first();
        if(!$activity){
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        $todo = Todo::where('id', $todoID)->first();
        if(!$activity){
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        return new TodoResource($todo);
    }
}
