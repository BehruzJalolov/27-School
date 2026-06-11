<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2 text-center">Ro'yxatdan o'tish</h2>
    <p class="text-sm text-gray-500 text-center mb-6">Telefon raqamingizni tasdiqlang</p>

    <form method="POST" action="{{ route('register.send-code') }}">
        @csrf

        <div>
            <x-input-label for="name" value="Ism familiya" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="phone" value="Telefon raqam" />
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" placeholder="+998 90 123 45 67" required />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="Email (ixtiyoriy)" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <p class="mt-4 text-sm text-gray-500">Ro'yxatdan o'tgach avtomatik <strong>O'quvchi</strong> roli beriladi.</p>

        <div class="mt-4">
            <x-input-label for="password" value="Parol (ixtiyoriy)" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Parolni tasdiqlash" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900" href="{{ route('login') }}">
                Kirish
            </a>

            <x-primary-button>
                Kod yuborish
            </x-primary-button>
        </div>
    </form>

    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white dark:bg-gray-800 text-gray-500">yoki</span>
        </div>
    </div>

    <a href="{{ route('oneid.redirect') }}"
       class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
        OneID orqali ro'yxatdan o'tish
    </a>
</x-guest-layout>
