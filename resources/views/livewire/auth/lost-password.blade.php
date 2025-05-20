<div x-data="{
    tokenSent: @entangle('tokenSent'),
    remainingTime: @entangle('remainingTime'),
    showPasswordForm: @entangle('showPasswordForm'),
    init() {
        if (this.tokenSent) {
            this.startTimer();
        }

        window.addEventListener('token-sent', () => {
            this.startTimer();
        });
    },
    startTimer() {
        // Clear any existing timer
        clearInterval(this.timer);

        // Update timer every second
        this.timer = setInterval(() => {
            if (this.remainingTime > 0) {
                this.remainingTime--;
            } else {
                clearInterval(this.timer);
            }
        }, 1000);
    },

    formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
    }
}" class="min-h-screen flex items-center justify-center p-4 bg-gray-50">
    <div class="w-full max-w-md bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Form Header -->
        <div class="bg-blue-600 py-4 px-5">
            <h1 class="text-white text-xl font-bold text-center">
                <template x-if="!tokenSent">
                    <span x-text="'Savior Schools System | Password Recovery'"></span>
                </template>
                <template x-if="tokenSent">
                    <span x-text="'Verification Code'" x-transition></span>
                </template>
            </h1>
        </div>

        <!-- Form Body -->
        <div class="p-6" x-init="if(tokenSent) startTimer()" @token-sent.window="startTimer()">

            @if(!$tokenSent && !$showPasswordForm)
                <!-- Request Token Form -->
                <div x-show="!tokenSent && !showPasswordForm" x-transition>
                    <form wire:submit.prevent="requestResetToken">
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-50 rounded-md">
                                <ul class="list-disc list-inside text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label for="mobile" class="block text-gray-700 text-sm font-medium mb-2">Mobile
                                Number</label>
                            <input
                                wire:model="mobile"
                                type="tel"
                                id="mobile"
                                placeholder="e.g. +989123456789"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('mobile') border-red-500 @enderror"
                            >
                            @error('mobile')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="captcha" class="block text-gray-700 text-sm font-medium mb-2">Security
                                Code</label>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-md flex justify-center items-center p-2">
                                    <img
                                        src="{{ captcha_src() }}"
                                        wire:click="$refresh"
                                        class="cursor-pointer rounded"
                                        alt="Captcha image"
                                    >
                                </div>
                                <button
                                    type="button"
                                    wire:click="$refresh"
                                    class="p-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>
                            </div>
                            <input
                                wire:model="captcha"
                                type="text"
                                id="captcha"
                                placeholder="Enter the code above"
                                class="w-full mt-2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('captcha') border-red-500 @enderror"
                            >
                            @error('captcha')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="w-full flex justify-center items-center gap-2 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-70"
                        >
                        <span wire:loading wire:target="requestResetToken">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                            <span wire:loading.remove wire:target="requestResetToken">Send Verification Code</span>
                            <span wire:loading wire:target="requestResetToken">Sending...</span>
                        </button>
                    </form>
                </div>
            @endif
            @if($tokenSent)

                <!-- Verification Code Form -->
                <div x-show="tokenSent" x-transition>
                    <div class="mb-4 p-4 bg-blue-50 rounded-md text-center">
                        <p class="text-blue-700">Verification code sent to {{ $mobile }}</p>
                    </div>

                    <form wire:submit.prevent="verifyCode">
                        <div class="mb-4">
                            <label for="verification_code" class="block text-gray-700 text-sm font-medium mb-2">Verification
                                Code</label>
                            <input
                                wire:model.live="verification_code"
                                type="text"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                id="verification_code"
                                placeholder="Enter 6-digit code"
                                maxlength="6"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-center font-mono text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('verification_code') border-red-500 @enderror"
                            >
                            @error('verification_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4 text-center text-sm">
                        <span x-show="remainingTime > 0" class="text-gray-600">
                            Code expires in:
                            <span
                                x-text="Math.floor(remainingTime / 60) + ':' + ('0' + (remainingTime % 60)).slice(-2)"></span>
                        </span>
                            <span x-show="remainingTime <= 0" class="text-red-500">
                            Code has expired
                        </span>
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2"
                            :disabled="remainingTime <= 0"
                            :class="{ 'opacity-50 cursor-not-allowed': remainingTime <= 0 }"
                        >
                            Verify Code
                        </button>

                        <button
                            type="button"
                            @click="tokenSent = false"
                            class="w-full text-blue-600 py-2 px-4 rounded-md hover:bg-blue-50"
                        >
                            Change Mobile Number
                        </button>
                    </form>
                </div>
            @endif

            @if($showPasswordForm)
                <!-- New Password Form -->
                <div x-show="showPasswordForm" x-transition>
                    <form wire:submit.prevent="resetPassword">
                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 text-sm font-medium mb-2">New
                                Password</label>
                            <input
                                wire:model="password"
                                type="password"
                                id="password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                            >
                            @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-2">Confirm
                                Password</label>
                            <input
                                wire:model="password_confirmation"
                                type="password"
                                id="password_confirmation"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password_confirmation') border-red-500 @enderror"
                            >
                            @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="w-full flex justify-center items-center gap-2 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-70"
                        >
                        <span wire:loading wire:target="resetPassword">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                            <span wire:loading.remove wire:target="resetPassword">Reset Password</span>
                            <span wire:loading wire:target="resetPassword">Resetting password... Please wait!</span>
                        </button>
                    </form>
                </div>
            @endif
            <!-- Back to Login -->
            <div class="mt-4 text-center text-sm">
                <a wire:navigate href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    Back to login page
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <p class="text-xs text-gray-500 text-center">© {{ date('Y') }} Savior Schools Management System</p>
            <p class="text-xs text-gray-500 text-center">All rights reserved.</p>
        </div>
    </div>
</div>
