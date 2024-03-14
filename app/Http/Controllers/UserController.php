<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

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
}
