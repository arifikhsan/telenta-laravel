<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Inertia\Response;

use App\Models\User;

use Illuminate\View\View;
use App\Helpers\CommonHelper;

use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class PasswordResetLinkController extends Controller
{
    /**
     * Show the password reset link request page.
     */
    // public function create(Request $request): Response
    // {
    //     return Inertia::render('auth/ForgotPassword', [
    //         'status' => $request->session()->get('status'),
    //     ]);
    // }

    public function create(Request $request)
    {
        return view('forgot_password', ['status' => $request->session()->get('status')]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // public function store(Request $request): RedirectResponse
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //     ]);

    //     Password::sendResetLink(
    //         $request->only('email')
    //     );

    //     return back()->with('status', __('A reset link will be sent if the account exists.'));
    // }

    public function store(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
        ]);


        $code = 200;
        $message = "A reset link will be sent if the account exists.";

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $token = \Str::random(60);
        $resetUrl = url('/reset-password?token=' . $token);

        Mail::to($user->email)->send(new ResetPasswordMail($user->name, $resetUrl));


        $response = CommonHelper::setResponse($code, $message);
        return response()->json($response, $code);
    }


    public function update(Request $request)
    {

        $code = 500;
        $message = "Internal Server Error (EXP)";

        $validated = $request->validate([
            'password' => 'required|string|max:30',
            'newPassword' => 'required|string|max:30',
        ]);


        $password = $validated['password'];
        $newPassword = $validated['newPassword'];
        
        $user = User::findOrFail(auth()->id());

        if (Hash::check($password, $user->password)) {
            $user->update(['password' => Hash::make($newPassword)]);

            if($user) {
                $code = 200;
                $message = "Success";
            } else {
                $code = 502;
                $message = "Failed";
            }
        }

        $response = CommonHelper::setResponse($code, $message);
        return response()->json($response, $code);
    }
}
