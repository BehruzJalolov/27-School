<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-6 text-center">Kirish</h2>

    @if ($errors->has('oneid'))
        <div class="mb-4 text-sm text-red-600">{{ $errors->first('oneid') }}</div>
    @endif

    <form method="POST" action="{{ route('login.send-code') }}">
        @csrf

        <div>
            <x-input-label for="phone" value="Telefon raqam" />
            <x-text-input
                id="phone"
                class="block mt-1 w-full"
                type="tel"
                name="phone"
                :value="old('phone')"
                placeholder="+998 90 123 45 67"
                required
                autofocus
            />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            <p class="mt-2 text-xs text-gray-500">Telefon raqamingizga tasdiqlash kodi yuboriladi.</p>
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900" href="{{ route('register') }}">
                Ro'yxatdan o'tish
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
       class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
        OneID orqali kirish
    </a>
</x-guest-layout>
