<?php

namespace App\Http\Controllers\Api;


use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Services\ApiResponseService;
use App\Http\Requests\UserRequest\StoreUserRequest;
use App\Http\Requests\UserRequest\UpdateUserRequest;


class UserController extends Controller
{
    /**
    * @var UserService
    */
    protected $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService The service that handles user operations.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of all users.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the list of all users.
     */
    public function index()
    {
        $users = $this->userService->getAllUsers();

        return response()->json($users);
    }

    /**
     * Display the specified user by ID.
     *
     * @param int $id The ID of the user to retrieve.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the user data.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the user is not found.
     */
    public function show($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return ApiResponseService::error('User not found', 404);
        }
        return response()->json($user);
    }

    /**
     * Store a newly created user in the database.
     *
     * @param StoreUserRequest $request The request instance containing user data.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the created user data.
     * @throws \Illuminate\Validation\ValidationException If the provided data does not meet the validation rules.
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        return response()->json($user, 201);
    }

    /**
     * Update the specified user by ID.
     *
     * @param UpdateUserRequest $request The request instance containing updated user data.
     * @param int $id The ID of the user to update.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the updated user data.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the user is not found.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->userService->updateUser($id, $request->validated());
        // if (!$user) {
        //     return ApiResponseService::error('User not found', 404);
        // }
        return response()->json($user);
    }

    /**
     * Remove the specified user by ID from the database.
     *
     * @param int $id The ID of the user to delete.
     * @return \Illuminate\Http\JsonResponse A JSON response confirming the deletion.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the user is not found.
     */
    public function destroy($id)
    {
        $this->userService->deleteUser($id);
        // if (!$user) {
        //     return ApiResponseService::error('User not found', 404);
        // }
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
