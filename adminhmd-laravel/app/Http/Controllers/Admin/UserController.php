<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // index page searching by username and email
    public function index(Request $request){
        
        $search = $request->get('search');
        $users = User::when($search, function ($q) use ($search) {
        $q->where(function ($query) use ($search) {
            $query->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
        });
    })->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact( 'users','search'));

    }

    //create user

    public function store(Request $request){

        $request->validate([
            'username'=>'required|max:50',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:8'
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'city' => $request->city,
            'designation' => $request->designation,
            'is_admin' => $request->boolean('is_admin'),
            'created_by' => auth()->id(),
        ]);

        return back()->with('success','User added Successfully!');
    }

    //update user
    public function update(Request $request, User $user){

        $request->validate([
            'username' => 'required|max:50',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'phone_number' => $request->phone_number,
            'city' => $request->city,
            'designation' => $request->designation,
            'is_admin' => $request->boolean('is_admin'),
            'updated_by' => auth()->id(),

        ];

        if($request->filled('password')){
            $request->validate(['password' => 'min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success','User updated successfully!');

    }

    //delete user
    public function destroy(User $user){

        if($user->id === auth()->id()){
            return back()->with('error','You cannot delete your own account!');
        }

        $user->delete();
        return back()->with('success','User deleted  successfully');
    }

}
