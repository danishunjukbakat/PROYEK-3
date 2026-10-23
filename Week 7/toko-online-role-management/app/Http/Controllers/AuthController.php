<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function registration() { return view('register'); }
    public function register(\App\Http\Requests\RegisterRequest $request)
    {
        $data = $request->safe()->except('role');
        $data['id_user'] = 'U'.bin2hex(random_bytes(7));
        $user = \App\Models\User::create($data);
        Auth::login($user);
        $request->session()->regenerate();
        if ($request->expectsJson()) return response()->json(['id_user'=>$user->getKey(),'role'=>$user->role], 201);
        return redirect()->route('barang.index')->with('success','Akun customer berhasil dibuat.');
    }
    public function create() { return view('login'); }
    public function store(Request $request)
    {
        $credentials=$request->validate([
            'username'=>['required','string','max:50'],
            'password'=>['required','string','max:200'],
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('barang.index');
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
