<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Daftar sebagai')" />
            <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="siswa" @selected(old('role') === 'siswa')>Siswa</option>
                <option value="guru" @selected(old('role') === 'guru')>Guru</option>
            </select>
            <p class="mt-2 text-sm text-gray-500">Pilih peran untuk menampilkan dashboard yang sesuai.</p>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Kelas (untuk siswa) -->
        <div class="mt-4">
            <x-input-label for="grade_level" :value="__('Kelas / Tingkat (opsional)')" />
            <x-text-input id="grade_level" class="block mt-1 w-full" type="text" name="grade_level" :value="old('grade_level')" placeholder="mis. X IPA" />
            <x-input-error :messages="$errors->get('grade_level')" class="mt-2" />
        </div>

        <!-- Fokus Mata Pelajaran (untuk guru) -->
        <div class="mt-4">
            <x-input-label for="subject_focus" :value="__('Fokus Mata Pelajaran (opsional)')" />
            <x-text-input id="subject_focus" class="block mt-1 w-full" type="text" name="subject_focus" :value="old('subject_focus')" placeholder="mis. Matematika" />
            <x-input-error :messages="$errors->get('subject_focus')" class="mt-2" />
        </div>

        <!-- Bio singkat -->
        <div class="mt-4">
            <x-input-label for="bio" :value="__('Bio singkat (opsional)')" />
            <textarea id="bio" name="bio" rows="3" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ceritakan sedikit tentang dirimu">{{ old('bio') }}</textarea>
            <x-input-error :messages="$errors->get('bio')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
