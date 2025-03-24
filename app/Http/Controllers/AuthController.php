<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(){
        return view('auth.login');
    }
    public function check( Request $request ){
        $request->validate([
            'email'=>'required',
            'password'=>'required',
        ]);
        $credentials = $request->only('email','password');
        if (!Auth::attempt($credentials)) {
            return back()->with('danger','Invalid Credentials !!!');
        }
        if (auth()->user()->role=='admin') {
            return redirect()->route('dashboard');
        }
        return redirect()->route('contract.my-contracts');

    }
    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }

    public function dashboard(){

        $users = User::count();
        $templates = ContractTemplate::count();
        $contracts = Contract::select('id','status')->get();
        return view('dashboard',compact('users','templates','contracts'));
        
    }
    
}
