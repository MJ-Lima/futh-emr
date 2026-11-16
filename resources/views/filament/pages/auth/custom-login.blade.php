<x-filament-panels::page.simple>
    <div class="futh-login-wrapper">
        {{-- Brand --}}
        <div class="futh-login-brand">
            <div class="futh-login-emblem">
                <svg viewBox="0 0 120 120" width="110" height="110" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <path id="topArcFuthLogin" d="M 20 60 A 40 40 0 0 1 100 60" fill="none"/>
                        <path id="bottomArcFuthLogin" d="M 100 60 A 40 40 0 0 1 20 60" fill="none"/>
                    </defs>
                    <circle cx="60" cy="60" r="50" fill="#FFFFFF" stroke="#22C55E" stroke-width="3"/>
                    <circle cx="60" cy="60" r="44" fill="none" stroke="#22C55E" stroke-width="1"/>
                    <text font-size="9.5" font-weight="600" fill="#374151" letter-spacing="1.5">
                        <textPath href="#topArcFuthLogin" startOffset="50%" text-anchor="middle">HEALTH IS WEALTH</textPath>
                    </text>
                    <text font-size="5" font-weight="500" fill="#6B7280" letter-spacing="0.5">
                        <textPath href="#bottomArcFuthLogin" startOffset="50%" text-anchor="middle">FEDERAL UNIVERSITY TEACHING HOSPITAL</textPath>
                    </text>
                    <g transform="translate(60 60)">
                        <rect x="-5" y="-18" width="10" height="36" rx="2" fill="#22C55E"/>
                        <rect x="-18" y="-5" width="36" height="10" rx="2" fill="#22C55E"/>
                    </g>
                </svg>
            </div>
            <div class="futh-login-brand-name">FUTH EMR</div>
            <div class="futh-login-emblem-text">FEDERAL UNIVERSITY TEACHING HOSPITAL</div>
        </div>

        {{-- Login Card --}}
        <div class="futh-login-card">
            <h1>Welcome Back</h1>
            <p class="subtitle">Sign In</p>

            <form wire:submit="authenticate">
                <div class="futh-form-group">
                    <label class="futh-form-label">Username</label>
                    <input type="email"
                           wire:model="data.email"
                           class="futh-form-control"
                           placeholder="Enter username"
                           autocomplete="username"
                           required autofocus />
                    @error('data.email') <span class="futh-form-error">{{ $message }}</span> @enderror
                </div>

                <div class="futh-form-group">
                    <label class="futh-form-label">Password</label>
                    <div class="futh-input-with-icon">
                        <input type="password"
                               wire:model="data.password"
                               id="password"
                               class="futh-form-control"
                               placeholder="Enter password"
                               autocomplete="current-password"
                               required />
                        <button type="button"
                                class="futh-eye-toggle"
                                onclick="const el=document.getElementById('password'); el.type = el.type==='password' ? 'text' : 'password'; this.innerHTML = el.type==='password' ? '<svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\' width=\'18\' height=\'18\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M2.036 12.322a1 1 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178a1 1 0 010 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z\'/><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\'/></svg>' : '<svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\' width=\'18\' height=\'18\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88\' /></svg>';">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1 1 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178a1 1 0 010 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                    @error('data.password') <span class="futh-form-error">{{ $message }}</span> @enderror
                </div>

                <div class="futh-form-row">
                    <label class="futh-checkbox">
                        <input type="checkbox" wire:model="data.remember" />
                        <span class="futh-checkmark"></span>
                        Remember me
                    </label>
                    <a href="#" class="futh-link">Forgot Password</a>
                </div>

                <button type="submit" class="futh-btn-login">
                    {{ __('Login') }}
                </button>
            </form>
        </div>
    </div>

    <style>
        .futh-login-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px 16px;
            background: #FFFFFF;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        .futh-login-brand {
            display: flex; flex-direction: column; align-items: center;
            margin-bottom: 28px; text-align: center;
        }
        .futh-login-emblem { width: 110px; height: 110px; margin-bottom: 4px; }
        .futh-login-brand-name {
            font-size: 28px; font-weight: 700; color: #22C55E;
            margin-top: 14px; letter-spacing: 0.5px;
        }
        .futh-login-emblem-text {
            font-size: 10px; font-weight: 600; color: #6B7280;
            text-transform: uppercase; letter-spacing: 1.2px;
            line-height: 1.4; margin-top: 6px;
        }
        .futh-login-card {
            background: #FFFFFF; border: 1px solid #E5E7EB; border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            padding: 40px 44px; width: 100%; max-width: 480px;
        }
        .futh-login-card h1 {
            font-size: 24px; font-weight: 600; color: #111827;
            text-align: center; margin: 0 0 4px;
        }
        .futh-login-card .subtitle {
            text-align: center; color: #374151; font-size: 15px; margin-bottom: 26px;
        }
        .futh-form-group { margin-bottom: 18px; }
        .futh-form-label {
            display: block; font-size: 13px; font-weight: 500;
            color: #374151; margin-bottom: 6px;
        }
        .futh-form-control {
            width: 100%; padding: 10px 12px; border: 1px solid #D1D5DB;
            border-radius: 6px; font-size: 14px; color: #111827;
            background: #FFFFFF; transition: border-color 0.15s, box-shadow 0.15s; outline: none;
        }
        .futh-form-control:focus {
            border-color: #22C55E;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15);
        }
        .futh-input-with-icon { position: relative; }
        .futh-input-with-icon .futh-form-control { padding-right: 40px; }
        .futh-eye-toggle {
            position: absolute; right: 10px; top: 50%;
            transform: translateY(-50%); background: transparent;
            border: none; color: #6B7280; cursor: pointer; padding: 4px;
        }
        .futh-eye-toggle:hover { color: #22C55E; }
        .futh-form-error { display: block; color: #DC2626; font-size: 12px; margin-top: 4px; }
        .futh-form-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 18px;
        }
        .futh-checkbox {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 14px; color: #374151; cursor: pointer;
        }
        .futh-checkbox input { width: 16px; height: 16px; accent-color: #22C55E; }
        .futh-link {
            color: #22C55E; font-size: 14px; text-decoration: none; font-weight: 500;
        }
        .futh-link:hover { color: #16A34A; text-decoration: underline; }
        .futh-btn-login {
            display: block; width: 100%; padding: 12px 16px; background: #22C55E;
            color: #FFFFFF; font-size: 15px; font-weight: 600; border: none;
            border-radius: 6px; cursor: pointer; transition: background 0.15s;
        }
        .futh-btn-login:hover { background: #16A34A; }
        .futh-btn-login:disabled { opacity: 0.7; cursor: not-allowed; }
    </style>
</x-filament-panels::page.simple>
