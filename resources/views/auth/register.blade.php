<x-guest-layout>

    <form
        method="POST"
        action="{{ route('register') }}"
        enctype="multipart/form-data">

        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />

            <x-text-input
                id="name"
                class="block mt-1 w-full"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name" />

            <x-input-error
                :messages="$errors->get('name')"
                class="mt-2" />
        </div>


        <!-- NIM -->
        <div class="mt-4">

            <x-input-label
                for="nim"
                value="NIM" />

            <x-text-input
                id="nim"
                class="block mt-1 w-full"
                type="text"
                name="nim"
                :value="old('nim')"
                required />

            <x-input-error
                :messages="$errors->get('nim')"
                class="mt-2" />

        </div>


        <!-- Departemen / Prodi -->
        <div class="mt-4">

            <label
                for="departemen"
                class="block font-medium text-sm text-gray-700">

                Prodi

            </label>

            <select
                id="departemen"
                name="departemen"
                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                required>

                <option value="">
                    -- Pilih Departemen --
                </option>

                <option
                    value="TI"
                    {{ old('departemen') == 'TI' ? 'selected' : '' }}>

                    TI

                </option>

                <option
                    value="AKUNTANSI"
                    {{ old('departemen') == 'AKUNTANSI' ? 'selected' : '' }}>

                    AKUNTANSI

                </option>

                <option
                    value="K3"
                    {{ old('departemen') == 'K3' ? 'selected' : '' }}>

                    K3

                </option>

                <option
                    value="REKAYASA_PANGAN"
                    {{ old('departemen') == 'REKAYASA_PANGAN' ? 'selected' : '' }}>

                    REKAYASA PANGAN

                </option>

                <option
                    value="TI&AI"
                    {{ old('departemen') == 'TI&AI' ? 'selected' : '' }}>

                    TI & AI

                </option>

            </select>

            <x-input-error
                :messages="$errors->get('departemen')"
                class="mt-2" />

        </div>


        <!-- Nomor WhatsApp -->
        <div class="mt-4">

            <x-input-label
                for="no_whatsapp"
                value="Nomor WhatsApp" />

            <x-text-input
                id="no_whatsapp"
                class="block mt-1 w-full"
                type="text"
                name="no_whatsapp"
                :value="old('no_whatsapp')"
                placeholder="08xxxxxxxxxx"
                required />

            <x-input-error
                :messages="$errors->get('no_whatsapp')"
                class="mt-2" />

        </div>


        <!-- Upload KTM -->
        <div class="mt-4">

            <x-input-label
                for="ktm"
                value="Upload KTM" />

            <input
                id="ktm"
                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                type="file"
                name="ktm"
                accept=".jpg,.jpeg,.png,.pdf"
                required>

            <p class="text-sm text-gray-500 mt-1">

                Format yang diperbolehkan:
                JPG, JPEG, PNG, PDF.
                Maksimal 4 MB.

            </p>

            <x-input-error
                :messages="$errors->get('ktm')"
                class="mt-2" />

        </div>


        <!-- Email -->
        <div class="mt-4">

            <x-input-label
                for="email"
                :value="__('Email')" />

            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username" />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2" />

        </div>


        <!-- Password -->
        <div class="mt-4">

            <x-input-label
                for="password"
                :value="__('Password')" />

            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="new-password" />

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2" />

        </div>


        <!-- Confirm Password -->
        <div class="mt-4">

            <x-input-label
                for="password_confirmation"
                :value="__('Confirm Password')" />

            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password" />

            <x-input-error
                :messages="$errors->get('password_confirmation')"
                class="mt-2" />

        </div>


        <!-- Tombol -->
        <div class="flex items-center justify-end mt-4">

            <a
                class="underline text-sm text-gray-600
                dark:text-gray-400
                hover:text-gray-900
                dark:hover:text-gray-100
                rounded-md
                focus:outline-none
                focus:ring-2
                focus:ring-offset-2
                focus:ring-indigo-500
                dark:focus:ring-offset-gray-800"

                href="{{ route('login') }}">

                {{ __('Already registered?') }}

            </a>


            <x-primary-button class="ms-4">

                {{ __('Register') }}

            </x-primary-button>

        </div>

    </form>

</x-guest-layout>