<div>
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Form Header -->
            <div class="bg-blue-600 py-4 px-6">
                <h1 class="text-white text-xl font-bold text-center">Savior Schools System | Login</h1>
            </div>
            @session('success')
            <div
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative dark:bg-green-800 dark:border-green-600 dark:text-green-200 flex items-center justify-between"
                role="alert">
                <button type="button" class="mr-2" onclick="this.parentElement.remove()">
                    <svg class="fill-current h-6 w-6 text-green-500 dark:text-green-200" role="button"
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1 1 0 0 1-1.414 0L10 11.414l-2.93 2.93a1 1 0 1 1-1.414-1.414l2.93-2.93-2.93-2.93a1 1 0 1 1 1.414-1.414l2.93 2.93 2.93-2.93a1 1 0 1 1 1.414 1.414l-2.93 2.93 2.93 2.93a1 1 0 0 1 0 1.414z"/>
                    </svg>
                </button>
                <span class="flex-grow">{{ $value }}</span>
            </div>
            @endsession
            <!-- Form Body -->
            <div class="p-6">
                <form wire:submit.prevent="login">
                    <!-- Mobile Field -->
                    <div class="mb-4">
                        <label for="mobile" class="block text-gray-700 text-sm font-medium mb-2">Mobile Number</label>
                        <input
                            type="text"
                            id="mobile"
                            name="mobile"
                            wire:model="mobile"
                            placeholder="Like: +98**********"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <x-input-error :messages="$errors->get('mobile')"/>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            wire:model="password"
                            placeholder="••••••••"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <x-input-error :messages="$errors->get('password')"/>
                    </div>

                    <!-- Forgot Password Link -->
                    <div class="mb-4 flex items-center justify-between">
                        <!-- Remember Me Checkbox -->
                        <div class="flex items-center">
                            <input
                                id="remember_me"
                                name="remember"
                                wire:model="remember"
                                type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            >
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                Remember me
                            </label>
                        </div>

                        <!-- Forgot Password Link -->
                        <div class="text-sm">
                            <a href="{{ route('ForgetPassword') }}" wire:navigate
                               class="font-medium text-blue-600 hover:text-blue-500">
                                Forgot your password?
                            </a>
                        </div>
                    </div>

                    <!-- Captcha -->
                    <div class="mb-6">
                        <label for="captcha" class="block text-gray-700 text-sm font-medium mb-2">Security Code</label>
                        <div class="flex items-center space-x-2">
                            <div
                                class="flex-1 bg-gray-200 rounded-md text-center font-mono text-lg tracking-widest flex justify-center items-center">
                                <img id="captchaImg" src="{{ captcha_src() }}" alt="Captcha" class="rounded mx-auto"
                                     title="Click on image for reload">
                            </div>
                            <button wire:click="$refresh" type="button"
                                    class="px-3 p-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </button>
                        </div>
                        <input
                            type="text"
                            id="captcha"
                            name="captcha"
                            wire:model="captcha"
                            placeholder="Enter the code above"
                            class="w-full mt-2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <x-input-error :messages="$errors->get('captcha')"/>
                    </div>

                    <!-- Login Button -->
                    <button
                        wire:target="login"
                        wire:loading.remove
                        type="submit"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                    >
                        Login
                    </button>
                    <div class="text-center">
                        <p class="font-bold text-blue-600 text-center" wire:loading>Please Wait!</p>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <p class="text-xs text-gray-500 text-center">© 2025 Savior Schools Management System</p>
                <p class="text-xs text-gray-500 text-center">All rights reserved.</p>
            </div>
        </div>
    </div>
</div>
