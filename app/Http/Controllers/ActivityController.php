<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityCreateRequest;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function create(ActivityCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();

        $activity = new Activity($data);
        $activity->user_id = $user->id;
        $activity->email = $user->username;
        $activity->save();

        return (new ActivityResource($activity))->response()->setStatusCode(201);
    }
}
