<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserEditRequest;
use App\Models\Company;
use App\Models\User;
// use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;

class UserController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_index'), 403);
        $companies = Company::select('id', 'company_name')->get();
        $users = User::with(['company', 'roles'])->get();
        $roles = Role::all();

        return view('back.users.index', compact('users', 'roles', 'companies'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), 403);
        $companies = Company::all()->pluck('name', 'id');
        $roles = Role::all()->pluck('name', 'id');
        return view('back.users.create', compact('companies', 'roles'));
    }

    public function store(UserCreateRequest $request)
    {
        // Las validaciones se realizan en UserCreateRequest

        if($request->hasFile('image')) {
            $file = request()->file('image');
            $cloudinary_object = Cloudinary::upload($file->getRealPath(), ['folder' =>  'mh/' . env("APP_ENV", "local") . '/' . $request['company_id'] . '/users']); // => mh/local/1/users/qxrcxytjrwufqjij9ix3
            $image_public_id = $cloudinary_object->getPublicId();
            $image_url = $cloudinary_object->getSecurePath();
        } else {
            $image_public_id = null;
            $image_url = null;
        }

        $user = User::create($request->only('name', 'email', 'company_id')
            + [
                'image_public_id' => $image_public_id,
                'image_url' => $image_url,
                'password' => bcrypt($request->input('password')),
            ]);

        $roles = $request->input('roles', []);
        $user->syncRoles($roles);
        // return redirect()->route('users.show', $user->id)->with('success', 'Usuario creado exitosamente!');

        return response()->json([
            'message'=>'Usuario creado exitosamente',
            'user'=>$user
        ]);

    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), 403);
        $user->load('roles');
        return view('back.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), 403);
        // $companies = Company::all()->pluck('name', 'id');
        // $roles = Role::all()->pluck('name', 'id');
        // $user->load('roles');
        // return view('back.users.edit', compact('user', 'companies', 'roles'));

        $result = [];

        $company = Company::where('id', $user->company_id)->pluck('company_name', 'id');
        // $roles = Role::all()->pluck('name', 'id');
        $user->load('roles');

        $result['company'] = $company;
        // $result['roles'] = $roles;
        $result['user'] = $user;

        return $result;
    }

    public function update(UserEditRequest $request, User $user)
    {
        // Las validaciones se realizan en UserEditRequest

        $data = $request->only('name', 'email', 'company_id');
        $password = $request->input('password');

        if($password) {
            $data['password'] = bcrypt($password);
        }

        $url = isset($user['image_url']) ? $user['image_url'] : null;
        $public_id = isset($user['image_public_id']) ? $user['image_public_id'] : null;
        if($request->hasFile('image')) {
            if($public_id != null) {
                Cloudinary::destroy($public_id);
            }
            $file = request()->file('image');
            $cloudinary_object = Cloudinary::upload($file->getRealPath(), ['folder' =>  'mh/' . env("APP_ENV", "local") . '/' . $request['company_id'] . '/users']); // => mh/local/1/users/qxrcxytjrwufqjij9ix3
            $image_public_id = $cloudinary_object->getPublicId();
            $image_url = $cloudinary_object->getSecurePath();

            $data['image_public_id'] = $image_public_id;
            $data['image_url'] = $image_url;
        } else {
            $data['image_public_id'] = $public_id;
            $data['image_url'] = $url;
        }

        $user->update($data);
        $roles = $request->input('roles', []);
        $user->syncRoles($roles);
        // return redirect()->route('users.show', $user->id)->with('success', 'Usuario actualizado correctamente.');

        return response()->json([
            'message'=>'Usuario actualizado exitosamente!',
            'user'=>$user
        ]);
    }

    public function destroy($user_id)
    {
        // abort_if(Gate::denies('user_destroy'), 403);

        try {
            $user = User::where('id', $user_id)->first();
            if(isset($user['image_public_id'])) {
                $public_id = $user['image_public_id'];
                Cloudinary::destroy($public_id);
            }

            $user->delete();

            return response()->json([
                'message'=>'Usuario eliminado exitosamente!',
                'user'=>$user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
