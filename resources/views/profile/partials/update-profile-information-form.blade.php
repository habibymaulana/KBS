<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="role" :value="__('Peran')" />
            <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="siswa" @selected(old('role', $user->role) === 'siswa')>Siswa</option>
                <option value="guru" @selected(old('role', $user->role) === 'guru')>Guru</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('role')" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="grade_level" :value="__('Kelas / Tingkat')" />
                <x-text-input id="grade_level" name="grade_level" type="text" class="mt-1 block w-full" :value="old('grade_level', $user->grade_level)" placeholder="mis. X IPA" />
                <x-input-error class="mt-2" :messages="$errors->get('grade_level')" />
            </div>

            <div>
                <x-input-label for="subject_focus" :value="__('Fokus Mata Pelajaran')" />
                <x-text-input id="subject_focus" name="subject_focus" type="text" class="mt-1 block w-full" :value="old('subject_focus', $user->subject_focus)" placeholder="mis. Matematika" />
                <x-input-error class="mt-2" :messages="$errors->get('subject_focus')" />
            </div>
        </div>

        <div>
            <x-input-label for="bio" :value="__('Bio singkat')" />
            <textarea id="bio" name="bio" rows="3" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ceritakan tujuan belajar atau mengajar">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="flex items-center justify-between gap-4">
            <div class="text-sm text-gray-600">
                <span class="font-semibold">Peran:</span>
                <span class="inline-flex items-center rounded-full bg-indigo-50 text-indigo-700 px-3 py-1 text-xs uppercase tracking-wide">
                    {{ strtoupper($user->role) }}
                </span>
            </div>

            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
