<div
    x-data="{
    remainingTime: @entangle('remainingTime'),
    formName: @entangle('form_name'),
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
                if(this.formName!='register'){
                    this.$dispatch('timer-expired');
                }
            }
        }, 1000);
    },

    formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
    }
}"
    class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full lg:w-[1000px] bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Form Header -->
        <div class="bg-blue-600 py-4 px-6">
            <h1 class="text-white text-xl font-bold text-center lg:text-nowrap">Savior Schools System | Register (Just
                For Parents)</h1>
        </div>
        <x-flash-messages/>

        <!-- Form Body -->
        <div class="p-6">
            @if($form_name=='send_token')
                <form wire:submit.prevent="sendToken">
                    <div class="grid lg:grid-cols-3 gap-3">
                        <!-- Auth Fields -->
                        <div class="mb-4">
                            <label for="mobile" class="block text-gray-700 text-sm font-medium mb-2">Mobile Number
                                (Username)</label>
                            <input type="text" id="mobile" wire:model="mobile" placeholder="e.g: +989123456789"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <x-input-error :messages="$errors->get('mobile')"/>
                        </div>
                        <div class="mb-6">
                            <label for="captcha" class="block text-gray-700 text-sm font-medium mb-2">Security
                                Code</label>
                            <div class="flex items-center space-x-2">
                                <div
                                    class="flex-1 bg-gray-200 rounded-md text-center font-mono text-lg tracking-widest flex justify-center items-center">
                                    <img id="captchaImg" src="{{ captcha_src() }}" alt="Captcha" class="rounded mx-auto"
                                         title="Click on image for reload">
                                </div>
                                <button wire:click="$refresh" type="button"
                                        class="px-3 p-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>
                            </div>
                            <input type="text" id="captcha" wire:model="captcha" placeholder="Enter the code above"
                                   class="w-full mt-2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <x-input-error :messages="$errors->get('captcha')"/>
                        </div>
                    </div>

                    <!-- Register Button -->
                    <button wire:loading.remove type="submit"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Get Registration Code
                    </button>
                    <div class="text-center">
                        <p class="text-blue-600" wire:loading>Sending Code...</p>
                    </div>
                </form>
            @endif
            @if($form_name=='token_sent')
                <form wire:submit.prevent="verify">
                    <div class="grid lg:grid-cols-3 gap-3">
                        <!-- Auth Fields -->
                        <div class="mb-4">
                            <label for="mobile" disabled="" class="block text-gray-700 text-sm font-medium mb-2">Mobile
                                Number
                                (Username)</label>
                            <input type="text" id="mobile" disabled wire:model="mobile" placeholder="+989123456789"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <x-input-error :messages="$errors->get('mobile')"/>
                        </div>
                        <div class="mb-4">
                            <label for="token" class="block text-gray-700 text-sm font-medium mb-2">Enter Code</label>
                            <input type="text" id="token" wire:model="token" placeholder="xxxxx"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >

                            <div>
                                <span x-show="remainingTime > 0" class="text-gray-600">
                                    Code expires in:
                                    <span
                                        x-text="Math.floor(remainingTime / 60) + ':' + ('0' + (remainingTime % 60)).slice(-2)"></span>
                                </span>
                            </div>
                            <x-input-error :messages="$errors->get('token')"/>
                        </div>
                    </div>

                    <!-- Register Button -->
                    <button wire:loading.remove type="submit"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Verify
                    </button>
                    <div class="text-center">
                        <p class="text-blue-600" wire:loading>Verifying...</p>
                    </div>
                </form>
            @endif
            @if($form_name=='register')
                <form wire:submit.prevent="register">
                    <div class="grid lg:grid-cols-4 lg:gap-3">
                        <!-- Personal Information -->
                        <div class="mb-4">
                            <label for="first_name_fa" class="block text-gray-700 text-sm font-medium mb-2">First Name
                                (Farsi)</label>
                            <input type="text" id="first_name_fa" wire:model="first_name_fa" placeholder="مثلا: علی"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <x-input-error :messages="$errors->get('first_name_fa')"/>
                        </div>

                        <div class="mb-4">
                            <label for="last_name_fa" class="block text-gray-700 text-sm font-medium mb-2">Last Name
                                (Farsi)</label>
                            <input type="text" id="last_name_fa" wire:model="last_name_fa" placeholder="مثلا: علوی"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <x-input-error :messages="$errors->get('last_name_fa')"/>
                        </div>
                        <!-- Personal Information -->
                        <div class="mb-4">
                            <label for="first_name_en" class="block text-gray-700 text-sm font-medium mb-2">First
                                Name</label>
                            <input type="text" id="first_name_en" wire:model="first_name_en" placeholder="e.g: John"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <x-input-error :messages="$errors->get('first_name_en')"/>
                        </div>

                        <div class="mb-4">
                            <label for="last_name_en" class="block text-gray-700 text-sm font-medium mb-2">Last
                                Name</label>
                            <input type="text" id="last_name_en" wire:model="last_name_en" placeholder="e.g: Doe"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <x-input-error :messages="$errors->get('last_name_en')"/>
                        </div>

                        <div class="mb-4">
                            <label for="father_name" class="block text-gray-700 text-sm font-medium mb-2">Father's
                                Name</label>
                            <input type="text" id="father_name" wire:model="father_name" placeholder="Father's name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <x-input-error :messages="$errors->get('father_name')"/>
                        </div>

                        <div class="mb-4">
                            <label for="gender" class="block text-gray-700 text-sm font-medium mb-2">Gender</label>
                            <select id="gender" wire:model="gender"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <x-input-error :messages="$errors->get('gender')"/>
                        </div>

                        <div class="mb-4">
                            <label for="birthdate"
                                   class="block text-gray-700 text-sm font-medium mb-2">Birthdate</label>
                            <input type="date" id="birthdate" wire:model="birthdate"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <x-input-error :messages="$errors->get('birthdate')"/>
                        </div>

                        <div class="mb-4">
                            <label for="nationality"
                                   class="block text-gray-700 text-sm font-medium mb-2">Nationality</label>
                            <select id="nationality" wire:model="nationality"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Select Nationality</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->nationality }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('nationality')"/>
                        </div>

                        <div class="mb-4">
                            <label for="birthplace"
                                   class="block text-gray-700 text-sm font-medium mb-2">Birthplace</label>
                            <select id="birthplace" wire:model="birthplace"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Select Birthplace</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->en_short_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('birthplace')"/>
                        </div>

                        <div class="mb-4">
                            <label for="passport_number" class="block text-gray-700 text-sm font-medium mb-2">Passport
                                Number</label>
                            <input type="text" id="passport_number" wire:model="passport_number"
                                   placeholder="If not, enter 0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <x-input-error :messages="$errors->get('passport_number')"/>
                        </div>

                        <div class="mb-4">
                            <label for="faragir_code" class="block text-gray-700 text-sm font-medium mb-2">Faragir
                                Code</label>
                            <input type="text" id="faragir_code" wire:model="faragir_code"
                                   placeholder="Enter Faragir Code"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <x-input-error :messages="$errors->get('faragir_code')"/>
                        </div>

                        <div class="mb-4">
                            <label for="fida_code" class="block text-gray-700 text-sm font-medium mb-2">Faragir
                                Code</label>
                            <input type="text" id="fida_code" wire:model="fida_code" placeholder="Enter Fida Code"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <x-input-error :messages="$errors->get('fida_code')"/>
                        </div>

                        <!-- Contact Information -->

                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email
                                (Optional)</label>
                            <input type="email" id="email" wire:model="email" placeholder="john.doe@example.com"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <x-input-error :messages="$errors->get('email')"/>
                        </div>

                        <!-- Address Information -->
                        <div class="mb-4">
                            <label for="country" class="block text-gray-700 text-sm font-medium mb-2">Residence
                                Country</label>
                            <select id="country" wire:model="country"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->en_short_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('country')"/>
                        </div>

                        <div class="mb-4">
                            <label for="state_city" class="block text-gray-700 text-sm font-medium mb-2">Residence
                                City</label>
                            <input type="text" id="state_city" wire:model="state_city" placeholder="City"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <x-input-error :messages="$errors->get('state_city')"/>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-gray-700 text-sm font-medium mb-2">Residence
                                Address</label>
                            <input type="text" id="address" wire:model="address" placeholder="Full address"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <x-input-error :messages="$errors->get('address')"/>
                        </div>

                        <div class="mb-4">
                            <label for="postal_code" class="block text-gray-700 text-sm font-medium mb-2">Postal
                                Code</label>
                            <input type="text" id="postal_code" wire:model="postal_code" placeholder="1234567890"
                                   pattern="[0-9]{10}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <x-input-error :messages="$errors->get('postal_code')"/>
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block text-gray-700 text-sm font-medium mb-2">Phone Number</label>
                            <input type="text" id="phone" wire:model="phone" placeholder="+989123456789"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <x-input-error :messages="$errors->get('phone')"/>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                            <input type="password" id="password" wire:model="password" placeholder="••••••••"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <x-input-error :messages="$errors->get('password')"/>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-2">Confirm
                                Password</label>
                            <input type="password" id="password_confirmation" wire:model="password_confirmation"
                                   placeholder="••••••••"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>

                        <!-- Captcha -->
                        <div class="mb-6">
                            <label for="captcha" class="block text-gray-700 text-sm font-medium mb-2">Security
                                Code</label>
                            <div class="flex items-center space-x-2">
                                <div
                                    class="flex-1 bg-gray-200 rounded-md text-center font-mono text-lg tracking-widest flex justify-center items-center">
                                    <img id="captchaImg" src="{{ captcha_src() }}" alt="Captcha" class="rounded mx-auto"
                                         title="Click on image for reload">
                                </div>
                                <button wire:click="$refresh" type="button"
                                        class="px-3 p-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>
                            </div>
                            <input type="text" id="captcha" wire:model="captcha" placeholder="Enter the code above"
                                   class="w-full mt-2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <x-input-error :messages="$errors->get('captcha')"/>
                        </div>
                    </div>

                    <!-- Register Button -->
                    <button wire:target="register" wire:loading.remove type="submit"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Register
                    </button>
                    <div class="text-center">
                        <p class="font-bold text-blue-600 text-center" wire:loading>Please Wait!</p>
                    </div>

                    <!-- Login Link -->
                    <div class="mt-4 text-center">
                        <span class="text-sm text-gray-600">Already have an account?</span>
                        <a href="{{ route('login') }}" wire:navigate
                           class="ml-1 text-sm font-medium text-blue-600 hover:text-blue-500">
                            Login here
                        </a>
                    </div>
                </form>
            @endif
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <p class="text-xs text-gray-500 text-center">© 2025 Savior Schools Management System</p>
            <p class="text-xs text-gray-500 text-center">All rights reserved.</p>
        </div>
    </div>
</div>
