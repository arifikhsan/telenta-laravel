<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
// use Inertia\Inertia;
// use Inertia\Response;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): View
    {
        return view('login');
        // return Inertia::render('auth/Login', [
        //     'canResetPassword' => Route::has('password.request'),
        //     'status' => $request->session()->get('status'),
        // ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(route('dashboard', absolute: false));
    // }

    public function store(Request $request) 
    {

        $code = 500;
        $message = "Internal Server Error (EXP)";

        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);


        $email = $request->input('email');
        $password = $request->input('password');

        /* get data user */
        $user = User::getUserByEmail($email);

        if ($user) {
            if (Hash::check($password, $user->password) && Auth::attempt($validated)) {
                $code = 200;
                $message = "Login Success";
                $request->session()->regenerate();
                session(['role' => $user->role->name]);
                
            } else {
                $code = 401;
                $message = "Invalid Password";
            }
        } else {
            $code = 404;
            $message = "Account Not Found";
        }

        $response = $this->setResponse($code, $message);
        return response()->json($response, $code);
    }

    /**
     * Destroy an authenticated session.
     */
    // public function destroy(Request $request): RedirectResponse
    // {
    //     Auth::guard('web')->logout();

    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return redirect('/');
    // }


    public function destroy()
    {
        Auth::guard('web')->logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    }


    public function setResponse($code, $message){
        /* this function used for set response */
        $data = array(
            'code' => $code,
            'message' => $message
        );

        return $data;
    }

}
