<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function __construct(){
        $this->middleware(['guest']);
    }

    public function index(){
        return view('auth.login');
    }

    public function store(Request $request){

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if(!Auth::attempt($credentials, $request->remember)){
            //an error occured and we are passing an error msg
            return redirect()->back()->with('error', 'Invalid login credentials');
        }

        $query = DB::table('users')->where('email', $request->email)->get();
        
        if($query[0]->role === 'administrator'){
            return redirect()->route('dashboard');
        }else if($query[0]->role === 'teacher'){
            return redirect()->route('portal');
        }else{
            return redirect()->route('academic');
        }
    }

}
