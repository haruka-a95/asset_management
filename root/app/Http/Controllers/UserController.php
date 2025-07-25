<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\DepartmentService;

class UserController extends Controller
{
    protected $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $departments = $this->departmentService->getAllDepartments();
        return view('users.edit', compact('user', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'ユーザー情報を更新しました。');
    }
}
