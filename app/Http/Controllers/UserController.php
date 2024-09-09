<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * login user and take JWT Token
     * @param LoginRequest $request
     * @return array[]
     * @throws \Exception
     */

    public function login(LoginRequest $request)
    {
        $data = $request->only('email', 'password');
        if (!$token = auth()->attempt($data)) {
            throw new \Exception('wrong email or password');
        }
        $user = auth()->user();
        return ["user" => [...$user->toArray(), ...["role" => $user->getRoleNames()->first(),
            "token" => $token]]];
    }


    /**
     *  Display a listing of the resource.
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $users = User::with(['tasksPerformed', 'addedTasks'])->withTrashed()->paginate($request->perPage);
        return $users;
    }


    /**
     *  Store a newly created resource in storage.
     * @param StoreRequest $request
     * @return array
     */

    public function store(StoreRequest $request)
    {
        $data = $request->only(['name', 'email', 'national_id', 'is_male']);
        $user = new User($data);
        $user->password = Hash::make($data['national_id']);
        $user->save();
        $user->assignRole($request->role);
        return [
            'message' => 'added successfully',
            'user' => [...$user->toArray(), ...["role" => $user->getRoleNames()->first()]]
        ];
    }


    /**
     *  Display the specified resource.
     * @param User $user
     * @return User
     */
    public function show(User $user)
    {
        return $user->load(['tasksPerformed', 'addedTasks']);
    }


    /**
     *  Update the specified resource in storage.
     * @param UpdateRequest $request
     * @param User $user
     * @return array
     */
    public function update(UpdateRequest $request, User $user)
    {
        $data = array_filter($request->only(['name', 'email', 'national_id', 'is_male']), function ($value) {
            return !is_null($value);
        });
        $user->update($data);
        return [
            'message' => 'updated successfully',
            'user' => $user
        ];
    }

    /**
     *  Remove the specified resource from storage.
     * @param User $user
     * @return string
     */
    public function destroy(user $user)
    {
        $user->delete();
        return 'deleted successfully';
    }


    /**
     * Log the user out (Invalidate the token)
     * @return string
     */
    public function logout()
    {
        Auth::logout();
        return 'Successfully logged out';
    }

    /**
     * change password for auth user
     * @param ChangePasswordRequest $request
     * @return string
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = User::findOrFail(\auth()->user()->uuid);
        $user->password = $request->password;
        $user->update();
        return "Done";
    }

}
