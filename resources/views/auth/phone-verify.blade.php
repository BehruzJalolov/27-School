<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2 text-center">Kodni tasdiqlash</h2>
    <p class="text-sm text-gray-500 text-center mb-6">
        {{ $phone }} raqamiga yuborilgan 6 xonali kodni kiriting
    </p>

    @if (app()->environment('local'))
        <div class="mb-4 p-3 bg-yellow-50 text-yellow-800 text-sm rounded-md">
            Dev rejim: test kodi <strong>123456</strong>
        </div>
    @endif

    <form method="POST" action="{{ $submitRoute }}">
        @csrf
        <input type="hidden" name="phone" value="{{ $phone }}">

        <div>
            <x-input-label for="code" value="Tasdiqlash kodi" />
            <x-text-input
                id="code"
                class="block mt-1 w-full text-center text-2xl tracking-widest"
                type="text"
                name="code"
                inputmode="numeric"
                maxlength="6"
                pattern="[0-9]{6}"
                required
                autofocus
            />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900"
               href="{{ $purpose === 'login' ? route('login') : route('register') }}">
                Orqaga
            </a>

            <x-primary-button>
                Tasdiqlash
            </x-primary-button>
        </div>
    </form>

    @if ($purpose === 'login')
        <form method="POST" action="{{ $resendRoute }}" class="mt-4 text-center">
            @csrf
            <input type="hidden" name="phone" value="{{ $phone }}">
            <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-800 underline">
                Kodni qayta yuborish
            </button>
        </form>
    @endif
</x-guest-layout>
