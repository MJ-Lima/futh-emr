<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetLink;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

/**
 * Handles the "I forgot my password" flow for BOTH staff (web guard) and
 * patient portal (patient_portal guard) users. The guard/broker is chosen
 * based on the route prefix or an explicit guard parameter.
 */
class PasswordResetController extends Controller
{
    /**
     * Display the "request reset link" form.
     * GET /password/forgot          (staff)
     * GET /portal/password/forgot   (patient)
     */
    public function showRequestForm(Request $request, string $guard = 'web')
    {
        $guard = $this->resolveGuard($request, $guard);

        return view('auth.passwords.request', [
            'guard'    => $guard,
            'userType' => $guard === 'patient_portal' ? 'patient' : 'staff',
            'routeBase' => $guard === 'patient_portal' ? 'portal.password' : 'password',
        ]);
    }

    /**
     * Send the reset link email.
     */
    public function sendResetLink(Request $request, string $guard = 'web')
    {
        $guard = $this->resolveGuard($request, $guard);
        $broker = $guard === 'patient_portal' ? 'patient_portal_users' : 'users';
        $userType = $guard === 'patient_portal' ? 'patient' : 'staff';

        $validated = $request->validate(['email' => ['required', 'email']]);

        // We send the email ourselves so we can route the URL through the
        // correct reset endpoint. The broker still stores the token.
        $user = Password::broker($broker)->getUser($validated);

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => [__('We can\'t find a user with that email address.')],
            ]);
        }

        $token = Password::broker($broker)->createToken($user);
        $resetUrl = $this->resetUrl($token, $user->getEmailForPasswordReset(), $guard);

        try {
            Mail::to($user->getEmailForPasswordReset())
                ->send(new PasswordResetLink(
                    $user->getEmailForPasswordReset(),
                    $token,
                    $resetUrl,
                    $userType,
                ));
        } catch (\Throwable $e) {
            logger()->warning('Failed to send password reset email', [
                'email' => $user->getEmailForPasswordReset(),
                'error' => $e->getMessage(),
            ]);
            throw ValidationException::withMessages([
                'email' => ['Unable to send reset email right now. Please try again later or contact support.'],
            ]);
        }

        return back()->with('status', 'We have emailed your password reset link.');
    }

    /**
     * Display the "set new password" form.
     */
    public function showResetForm(Request $request, string $token = null, string $guard = 'web')
    {
        $guard = $this->resolveGuard($request, $guard);

        return view('auth.passwords.reset', [
            'token'    => $token,
            'email'    => $request->input('email'),
            'guard'    => $guard,
            'userType' => $guard === 'patient_portal' ? 'patient' : 'staff',
            'routeBase' => $guard === 'patient_portal' ? 'portal.password' : 'password',
        ]);
    }

    /**
     * Update the password and redirect to the appropriate login.
     */
    public function update(Request $request, string $guard = 'web')
    {
        $guard = $this->resolveGuard($request, $guard);
        $broker = $guard === 'patient_portal' ? 'patient_portal_users' : 'users';
        $loginRoute = $guard === 'patient_portal' ? 'portal.login' : 'filament.admin.auth.login';

        $validated = $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::broker($broker)->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($validated) {
                $user->forceFill([
                    'password'       => Hash::make($validated['password']),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            },
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route($loginRoute)
                ->with('status', __($status))
                ->with('success', 'Your password has been reset. You can now sign in.');
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }

    protected function resolveGuard(Request $request, string $default = 'web'): string
    {
        $explicit = $request->route()?->parameter('guard');
        if ($explicit && in_array($explicit, ['web', 'patient_portal'], true)) {
            return $explicit;
        }
        if ($request->query('guard') === 'patient_portal') {
            return 'patient_portal';
        }
        return $default;
    }

    protected function resetUrl(string $token, string $email, string $guard): string
    {
        if ($guard === 'patient_portal') {
            return url(route('portal.password.reset', ['token' => $token, 'email' => $email]));
        }
        return url(route('password.reset', ['token' => $token, 'email' => $email]));
    }
}
