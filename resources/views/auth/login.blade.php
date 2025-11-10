<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="px-4 py-4 flex justify-center">
        <img src="{{asset('assets/images/header.png')}}" alt="Login Image" className="w-full h-lvh object-cover flex items-center" />
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4 text-center">
            <label for="remember_me" class="inline-flex items-center">
                <span class="ms-2 text-sm text-gray-600"><x-input-error :messages="$errors->get('login')" class="mt-2" /></span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            {{-- @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif --}}

            <x-primary-button class="ms-3 w-full text-center">
                {{ __('Log in') }}
            </x-primary-button>

            
        </div>
    </form>
</x-guest-layout>
