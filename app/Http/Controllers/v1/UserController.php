<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\v1\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userRepository;
    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     *
     * @return [json]
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->index($request->all());
        return $users;
    }

    /**
     * @param Request $request
     *
     * @return [json]
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'email|unique:users',
            'phone' => 'required|numeric|unique:users',
            'vehicleno' => 'required|alpha_num|unique:users',
            'password' => 'required|min:6',
            'license' => 'required|mimes:pdf|max:2048',
            'start_time' => 'required|date_format:Y-m-d H:i',
            'end_time' => 'required|date_format:Y-m-d H:i|after:start_time',
        ]);
        $users = $this->userRepository->create($request->all(), $request->file('license'));
        return $users;
    }

}
