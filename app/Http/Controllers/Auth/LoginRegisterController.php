<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginRegisterController extends Controller
{

    public function register()
    {
        return view('auth.register');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);
      
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usertype' => 'admin' 
        ]);

        
        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();

        
        if ($request->user()->usertype === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'You have successfully registered & logged in!');
        }

       
        return redirect()->intended(route('dashboard'));
    }

    
    public function login()
    {
        return view('auth.login');
    }

   
    public function authenticate(Request $request)
    {
        
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        
        if (Auth::attempt($credentials)) {            
            $request->session()->regenerate();           
            if ($request->user()->usertype === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'You have successfully logged in!');
            }

           
            return redirect()->intended(route('dashboard'));
        }

       
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    
    public function logout(Request $request)
    {        
        Auth::logout();        
        $request->session()->invalidate();
        $request->session()->regenerateToken();       
        return redirect()->route('login')
        ->with('success', 'You have logged out successfully!');
    }
}
