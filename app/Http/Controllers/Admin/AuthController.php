<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HandleLoginRequest;
use App\Http\Requests\SendResetLinkRequest;
use App\Http\Resources\AdminLoginResource;
use App\Mail\PasswordResetMail;
use App\Models\Aadmin;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function handlelogin(HandleLoginRequest $request)
    {
        $admin = Aadmin::with('office','warehouse')->where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $admin->createToken('Admin Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'data' => $admin
        ], 200);

        /* return new AdminLoginResource($admin->setAttribute('plainTextToken', $token)); */
    }


    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin) {
            $request->$admin->tokens()->delete(); // Revoke all tokens
        }

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function sendRestLink(SendResetLinkRequest $request)
    {
        $token = Str::random(70);
        $admin = Aadmin::where('email', $request->email)->first();

        if (!$admin) {
            return response()->json(['message' => 'Email address not found'], 404);
        }

        $admin->remember_token = $token;
        $admin->save();

        $resetUrl = config('app.frontend_url') . '/reset-password?token=' . $token . '&email=' . urlencode($request->email);

        /* sending mail */
        Mail::to($request->email)->send(new PasswordResetMail($resetUrl));

        return response()->json([
            'message' => 'A mail has been sent to your email, please check!',
            'token' => $token
        ], 200);
    }

    public function handleResetPassword(Request $request)
    {
        $admin = Aadmin::where('email', $request->email)
            ->where('remember_token', $request->token)
            ->first();

        if (!$admin) {
            return response()->json(['message' => 'Invalid token or email.'], 400);
        }

        $admin->password = bcrypt($request->password);
        $admin->remember_token = null; // Clear the token
        $admin->save();

        return response()->json(['message' => 'Password has been reset successfully.'], 200);
    }
}
