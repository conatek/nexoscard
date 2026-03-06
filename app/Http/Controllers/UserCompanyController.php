<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserEditRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class UserCompanyController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_company_index'), 403);
        $company = auth()->user()->company_id;
        $users = User::where('company_id', $company)->get();
        return view('back.users_company.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_company_create'), 403);
        $company = Company::where('id', auth()->user()->company_id)->first();
        $roles = Role::all()->pluck('name', 'id');
        return view('back.users_company.create', compact('company', 'roles'));
    }

    public function store(UserCreateRequest $request)
    {
        // Las validaciones se realizan en UserCreateRequest

        $user = User::create($request->only('name', 'email', 'company_id')
            + [
                'password' => bcrypt($request->input('password')),
            ]);

        $roles = $request->input('roles', []);
        $user->syncRoles($roles);
        return redirect()->route('users_company.show', $user->id)->with('success', 'Usuario creado exitosamente!');
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_company_show'), 403);
        $user->load('roles');
        return view('back.users_company.show', compact('user'));
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_company_edit'), 403);
        $company = Company::where('id', auth()->user()->company_id)->first();

//        dd($user);

        if($user->company_id != $company->id) {
            return redirect()->route('users_company.index');
        }

        $roles = Role::all()->pluck('name', 'id');
        $user->load('roles');
        return view('back.users_company.edit', compact('user', 'company', 'roles'));
    }

    public function update(UserEditRequest $request, User $user)
    {
        // Las validaciones se realizan en UserEditRequest

        $data = $request->only('name', 'email', 'company_id');
        $password = $request->input('password');
        if($password) {
            $data['password'] = bcrypt($password);
        }

        $user->update($data);
        $roles = $request->input('roles', []);
        $user->syncRoles($roles);
        return redirect()->route('users_company.show', $user->id)->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_company_destroy'), 403);
        if(auth()->user()->id == $user->id) {
            return redirect()->route('users_company.index');
        }

        $user->delete();
        return back()->with('success', 'Usuario eliminado correctamente.');
    }
}
