<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('users.index') }}" class="text-lime-brand hover:text-lime-300 font-medium flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Users
        </a>
    </div>

    <div class="bg-[#1E1E1E] rounded-xl shadow-sm border border-gray-800 overflow-hidden max-w-2xl mx-auto">
        <div class="p-6 border-b border-gray-800">
            <h2 class="text-xl font-bold text-white">Edit User</h2>
            <p class="text-gray-400 text-sm">Update user information</p>
        </div>

        <form method="POST" action="{{ route('users.update', $user) }}" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <x-input-label for="name" value="Name" class="text-gray-300" />
                    <x-text-input id="name"
                        class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                        type="text" name="name" :value="old('name', $user->name)" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" value="Email" class="text-gray-300" />
                    <x-text-input id="email"
                        class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                        type="email" name="email" :value="old('email', $user->email)" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="role" value="Role" class="text-gray-300" />
                    <select id="role" name="role"
                        class="bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand rounded-md shadow-sm block mt-1 w-full">
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ (old('role') ?? $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="password" value="Password (Leave blank to keep current)"
                            class="text-gray-300" />
                        <x-text-input id="password"
                            class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                            type="password" name="password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" value="Confirm Password" class="text-gray-300" />
                        <x-text-input id="password_confirmation"
                            class="block mt-1 w-full bg-black border-gray-700 text-white focus:border-lime-brand focus:ring-lime-brand"
                            type="password" name="password_confirmation" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('users.index') }}"
                    class="px-4 py-2 border border-gray-700 rounded-md text-gray-400 hover:bg-gray-800 font-medium">Cancel</a>
                <button type="submit"
                    class="px-4 py-2 bg-lime-brand hover:bg-lime-400 text-black rounded-md font-bold transition-colors">Update
                    User</button>
            </div>
        </form>
    </div>
</x-app-layout>