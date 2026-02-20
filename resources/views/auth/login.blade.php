<x-guest-layout>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Session Message -->
        <x-auth-session-status class="mb-5" :status="session('status')" />

        <!-- Username / Email -->
        <div class="mb-[1.5rem] text-left">
            <label for="email" class="block mb-[0.6rem] font-[600] text-[#001f18] text-[0.9rem]">
                <i class="fas fa-user mr-1"></i> Username
            </label>
            <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="w-full px-[1.25rem] py-[1rem] border border-[rgba(0,0,0,0.12)] rounded-[14px] text-[1rem] transition-colors duration-150 bg-black/5 hover:bg-white focus:outline-none focus:border-[#001f18] focus:bg-white focus:ring-4 focus:ring-[#00312c]/5" 
                placeholder="Masukkan username atau email">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Password -->
        <div class="mb-[1.5rem] text-left">
            <label for="password" class="block mb-[0.6rem] font-[600] text-[#001f18] text-[0.9rem]">
                <i class="fas fa-lock mr-1"></i> Password
            </label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full px-[1.25rem] py-[1rem] border border-[rgba(0,0,0,0.12)] rounded-[14px] text-[1rem] transition-colors duration-150 bg-black/5 hover:bg-white focus:outline-none focus:border-[#001f18] focus:bg-white focus:ring-4 focus:ring-[#00312c]/5"
                placeholder="Masukkan password">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <button type="submit" class="w-full p-[1rem] bg-[#001f18] text-white border-none rounded-[14px] text-[1.1rem] font-[700] cursor-pointer mt-4 transition-all duration-150 shadow-[0_10px_20px_-5px_rgba(0,49,44,0.3)] hover:bg-[#002d23] hover:shadow-[0_12px_24px_-5px_rgba(0,49,44,0.4)] block">
            Login Sekarang
        </button>
    </form>
</x-guest-layout>
