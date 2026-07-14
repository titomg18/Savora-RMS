<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Savora RMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-[#fdf2ee]">

    <div class="min-h-screen flex flex-col lg:flex-row">

        {{-- LEFT: hero image panel --}}
        <div class="relative hidden lg:flex lg:w-1/2 min-h-[320px] lg:min-h-screen overflow-hidden">
            <img
                src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1600&auto=format&fit=crop"
                alt="Interior restoran"
                class="absolute inset-0 h-full w-full object-cover"
            >
            {{-- warm overlay to match the mockup's washed-out tone --}}
            <div class="absolute inset-0 bg-[#fdf2ee]/70"></div>

            {{-- logo --}}
            <div class="relative z-10 p-10">
                <a href="/" class="inline-flex items-center gap-2 text-[#d9603b] font-extrabold text-xl">
                    <span aria-hidden="true">🍴</span>
                    <span>Savora RMS</span>
                </a>
            </div>

            {{-- headline --}}
            <div class="relative z-10 mt-auto p-10 max-w-md">
                <h1 class="text-4xl font-extrabold leading-tight text-neutral-900">
                    Streamline your restaurant operations.
                </h1>
                <p class="mt-4 text-neutral-700">
                    The modern management suite designed for high-performance hospitality teams.
                </p>
            </div>
        </div>

        {{-- RIGHT: form panel --}}
        <div class="flex flex-1 items-center justify-center px-6 py-12 lg:py-0">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-xl shadow-orange-950/5 border border-neutral-100 p-10">

                {{-- mobile-only logo --}}
                <a href="/" class="lg:hidden inline-flex items-center gap-2 text-[#d9603b] font-extrabold text-lg mb-8">
                    <span aria-hidden="true">🍴</span>
                    <span>Savora RMS</span>
                </a>

                <h2 class="text-3xl font-extrabold text-neutral-900">Welcome back</h2>
                <p class="mt-2 text-sm text-neutral-500">Please enter your details to sign in.</p>

                @if ($errors->any())
                    <div class="mt-6 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.authenticate') }}" method="POST" class="mt-8 space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-neutral-700 mb-1.5">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-neutral-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                                    <path d="m3 7 9 6 9-6"/>
                                </svg>
                            </span>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="manager@restaurant.com"
                                required
                                autofocus
                                class="w-full rounded-lg border border-neutral-300 pl-10 pr-4 py-2.5 text-sm text-neutral-900 placeholder:text-neutral-400 focus:outline-none focus:ring-2 focus:ring-[#d9603b]/40 focus:border-[#d9603b]"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-neutral-700 mb-1.5">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-neutral-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="4" y="10" width="16" height="10" rx="2"/>
                                    <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                                </svg>
                            </span>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="••••••••"
                                required
                                class="w-full rounded-lg border border-neutral-300 pl-10 pr-4 py-2.5 text-sm text-neutral-900 placeholder:text-neutral-400 focus:outline-none focus:ring-2 focus:ring-[#d9603b]/40 focus:border-[#d9603b]"
                            >
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember me + forgot password --}}
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 text-sm text-neutral-700 cursor-pointer">
                            <input
                                type="checkbox"
                                name="remember"
                                class="h-4 w-4 rounded border-neutral-300 text-[#d9603b] focus:ring-[#d9603b]/40"
                            >
                            Remember me
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-[#d9603b] hover:text-[#c1502f]">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] text-white font-semibold py-3 text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-[#dd6b4a]/50 focus:ring-offset-2"
                    >
                        Sign in
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-neutral-600">
                    Need an account?
                    <a href="#" class="font-medium text-[#d9603b] hover:text-[#c1502f]">Contact Support</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>