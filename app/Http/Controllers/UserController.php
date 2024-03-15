<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
   public function index(){     
      return User::orderBy('id','desc')->get();       
   }

   public function store(Request $request){  

      $request->validate([
         'name' => 'required',
         'email' => 'required|unique:users',
         'password' => 'required|min:8',
     ]);
     
      return User::create($request->all());
   }

   public function update(User $user, Request $request,$id){  

      $request->validate([
         'name' => 'required',
         'email' => [
            'required',
            'email',
            Rule::unique('users')->ignore($id),
         ],
         'password' => 'nullable|min:8',
     ]);
      
      $update = User::findOrFail($id);

      User::findOrFail($id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $update->password,
      ]);
   
      $new = User::findOrFail($id);
      return $new;
      
   }

   public function destroy($id){
      User::findOrFail($id)->delete();

      return response()->noContent();
   }
}
