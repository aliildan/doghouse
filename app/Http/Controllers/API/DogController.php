<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\SaveDogJob;
use App\Models\User;
use App\Objects\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DogController extends Controller
{

    /**
     * Create a new dog for the authenticated user.
     * The request is accepted and the dog is saved in the background async.
     * The response is a 202 Accepted with a status of processing.
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'breed' => 'required|string|max:255',
            'age' => 'required|integer',
        ]);
        /** @var User $user */
        $user = $request->user();
        // Dog creation sends to queue
        SaveDogJob::dispatch($user, $request->all());
        $response = new ApiResponse(['dog' => $request->all()], 'Dog creation request accepted!', ApiResponse::STATUS_PROCESSING);
        return response()->json($response->jsonSerialize(), 202);
    }

    /**
     * List dogs for the authenticated user. If name is provided, filter by name.
     * If limit is provided, limit (default 30) the results. If offset is provided, offset (default 0) the results.
     * Cache the response for 1 day.
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $name = $request->get('name', null);
        $limit = $request->get('limit', 30);
        $offset = $request->has('offset', 0);

        $userDogs = $user->dogs();
        if ($request->has('name')) {
            $userDogs->where('name', $name);
        }

        $dogs = $userDogs->skip($offset)->take($limit)->get();
        $response = new ApiResponse(['dogs' => $dogs, "limit" => $limit, "offset" => intval($offset)], $name ? "'$name' Named dogs list" : 'Dogs list!');

        return response()->json($response->jsonSerialize());
    }

}
