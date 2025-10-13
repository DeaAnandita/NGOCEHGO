<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    public function index() {
        $users = User::with('role')->get();
        return view('users.index', compact('users'));
    }
    public function create() {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }
    public function store(Request $request) {
        $data = $request->validate([
            'name'=>'required|string|max:100',
            'username'=>'required|string|max:50|unique:users,username',
            'email'=>'nullable|email|unique:users,email',
            'password'=>'required|string|min:6',
            'role_id'=>'required|exists:roles,id'
        ]);
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return redirect()->route('users.index')->with('success','User berhasil dibuat');
    }
    public function show($id) {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }
    public function edit($id) {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('users.edit', compact('user','roles'));
    }
    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'name'=>'sometimes|string|max:100',
            'username'=>'sometimes|string|max:50|unique:users,username,'.$id,
            'email'=>'nullable|email|unique:users,email,'.$id,
            'password'=>'nullable|string|min:6',
            'role_id'=>'required|exists:roles,id'
        ]);
        if(!empty($data['password'])) $data['password'] = Hash::make($data['password']);
        else unset($data['password']);
        $user->update($data);
        return redirect()->route('users.index')->with('success','User diperbarui');
    }
    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success','User dihapus');
    }
}
