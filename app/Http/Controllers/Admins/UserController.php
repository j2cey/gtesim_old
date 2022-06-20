<?php

namespace App\Http\Controllers\Admins;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware(['role:super-admin|admin|moderator']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        return Inertia::render('Admins/Users/Index', [
            'users' => User::where('is_admin', 0)->latest()->paginate(5),
            'roles' => Role::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request) {
        if (auth()->user()->hasAnyRole(['super-admin', 'admin'])) {
            $this->validate($request, [
                'name' => ['required', 'max:50'],
                'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            ]);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'is_admin' => 0,
                'password' => Hash::make('password')
            ]);
            $role = Role::where('id', 5)->first();
            $user->syncRoles($role);
            return back();
        }
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return void
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return void
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, User $user) {
        if (auth()->user()->hasAnyRole(['super-admin', 'admin'])) {
            $this->validate($request, [
                'name' => ['required', 'max:50'],
                'email' => ['required', 'string', 'email', 'max:50'],
            ]);
            if ($request->roles[0] === null) {
                return back()->withErrors(['roles' => 'The role field is required']);
            }
            if ($request->roles[0]['id'] != 5) {
                $adminRole = Role::where('id', $request->roles[0]['id'])->first();
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'is_admin' => 1,
                ]);
                $user->syncRoles($adminRole);
                return back();
            } else {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);
            }
            return back();
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse|\Illuminate\Http\Response
     */
    public function destroy(User $user) {
        if (auth()->user()->hasAnyRole(['super-admin', 'admin'])) {
            $user->delete();
            return back();
        }
        return back();
    }
}
