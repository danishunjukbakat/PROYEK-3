<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function create() { return view('login'); }
    public function store(Request $request)
    {
        $credentials=$request->validate([
            'username'=>['required','string','max:50'],
            'password'=>['required','string','max:200'],
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }
        return back()->withErrors(['login'=>'Username atau password salah.'])->onlyInput('username');
    }
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success','Anda sudah logout.');
    }
}
