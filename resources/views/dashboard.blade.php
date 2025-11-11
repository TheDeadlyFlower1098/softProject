<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    This is the code to ensure the form is submitted correctly, which I don't want to actually put in the code yet so it's just commented for later

    {{-- <form method="POST" action="{{ route('email.submit') }}">
        @csrf
        <label for="email" class="block text-sm font-medium">Email</label>
        <input id="email" name="email" type="email" required
            class="mt-1 block w-full rounded-md border p-2 text-gray-800">
        @error('email')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror

        <button type="submit" class="mt-3 rounded-md px-4 py-2 bg-blue-600 text-white">
            Save
        </button>
    </form>

    @if (session('success'))
        <p class="mt-2 text-green-500">{{ session('success') }}</p>
    @endif --}}

</x-app-layout>
