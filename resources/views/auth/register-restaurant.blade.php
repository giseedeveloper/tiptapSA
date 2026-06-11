<x-guest-layout title="TIPTAP | Register Restaurant" :hero-background="true" :wide="true">
    <div class="max-w-2xl mx-auto">
        <!-- Progress Bar -->
        <div class="mb-8 bg-[#F5F3FF] h-2 rounded-full overflow-hidden border border-[#DDD7FE]">
            <div id="progress-bar" class="bg-gradient-to-r from-[#8C71F6] to-[#6D52E8] h-full transition-all duration-500" style="width: 15%"></div>
        </div>

        <div id="chat-container" class="flex flex-col min-h-[400px]">
            <!-- Chat Header -->
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 flex items-center justify-center overflow-hidden rounded-full ring-2 ring-[#DDD7FE]">
                    <img src="{{ public_asset('images/logo.png') }}" alt="TIPTAP Logo" class="w-full h-full object-contain bg-white">
                </div>
                <div>
                    <h2 class="text-xl font-black text-[#12141C]">TIPTAP Assistant</h2>
                    <p class="text-xs text-emerald-600 font-bold uppercase tracking-widest flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        Online • Ready to help
                    </p>
                </div>
            </div>

            <form id="registration-form" method="POST" action="{{ route('restaurant.register.store') }}" class="flex flex-col flex-1">
                @csrf

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-medium space-y-1" role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @php
                    $inputClass = 'block w-full text-base sm:text-lg p-4 bg-[#F5F3FF] border border-[#DDD7FE] rounded-xl font-medium text-[#12141C] placeholder-[#64708B]/50 focus:ring-2 focus:ring-[#8C71F6] focus:border-transparent transition-all';
                    $bubbleClass = 'chat-bubble-left bg-[#F5F3FF] border border-[#DDD7FE] rounded-2xl rounded-tl-none p-4 text-[#12141C] font-medium mb-4';
                @endphp

                <!-- Step 1: Restaurant Name -->
                <div class="step step-active" data-step="1">
                    <div class="{{ $bubbleClass }}">
                        Hello! Welcome to TIPTAP. To get started, what is your restaurant called?
                    </div>
                    <div class="mt-4">
                        <input id="restaurant_name" type="text" name="restaurant_name" value="{{ old('restaurant_name') }}" required placeholder="e.g. TIPTAP Grill"
                               class="{{ $inputClass }}">
                        <x-input-error :messages="$errors->get('restaurant_name')" class="mt-2" />
                    </div>
                </div>

                <!-- Step 2: Location -->
                <div class="step step-hidden" data-step="2">
                    <div class="{{ $bubbleClass }}">
                        Great! Where is your restaurant located?
                    </div>
                    <div class="mt-4">
                        <input id="location" type="text" name="location" value="{{ old('location') }}" required placeholder="e.g. Sandton, Johannesburg"
                               class="{{ $inputClass }}">
                        <x-input-error :messages="$errors->get('location')" class="mt-2" />
                    </div>
                </div>

                <!-- Step 3: Phone -->
                <div class="step step-hidden" data-step="3">
                    <div class="{{ $bubbleClass }}">
                        Got it. What is the restaurant phone number for contact?
                    </div>
                    <div class="mt-4">
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required placeholder="e.g. 071 234 5678"
                               class="{{ $inputClass }}">
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                </div>

                <!-- Step 4: Manager Name -->
                <div class="step step-hidden" data-step="4">
                    <div class="{{ $bubbleClass }}">
                        Who will be the manager of this restaurant? (Your full name)
                    </div>
                    <div class="mt-4">
                        <input id="manager_name" type="text" name="manager_name" value="{{ old('manager_name') }}" required placeholder="e.g. John Doe"
                               class="{{ $inputClass }}">
                        <x-input-error :messages="$errors->get('manager_name')" class="mt-2" />
                    </div>
                </div>

                <!-- Step 5: Email -->
                <div class="step step-hidden" data-step="5">
                    <div class="{{ $bubbleClass }}">
                        What email should we use for your login?
                    </div>
                    <div class="mt-4">
                        <input id="manager_email" type="email" name="manager_email" value="{{ old('manager_email') }}" required placeholder="e.g. manager@tiptap.com"
                               class="{{ $inputClass }}">
                        <x-input-error :messages="$errors->get('manager_email')" class="mt-2" />
                    </div>
                </div>

                <!-- Step 6: Password -->
                <div class="step step-hidden" data-step="6">
                    <div class="{{ $bubbleClass }}">
                        Finally, set a strong password to secure your account.
                    </div>
                    <div class="mt-4 space-y-4">
                        <input id="manager_password" type="password" name="manager_password" required placeholder="Password"
                               class="{{ $inputClass }}">
                        <input id="manager_password_confirmation" type="password" name="manager_password_confirmation" required placeholder="Confirm Password"
                               class="{{ $inputClass }}">
                        <x-input-error :messages="$errors->get('manager_password')" class="mt-2" />
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="mt-12 flex items-center justify-between">
                    <button type="button" id="prev-btn" class="hidden text-[#64708B] font-bold hover:text-[#12141C] transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m15 18-6-6 6-6"/>
                        </svg>
                        Go back
                    </button>

                    <button type="button" id="next-btn" class="btn-fin text-white px-10 py-4 rounded-xl font-bold text-lg flex items-center gap-2 ml-auto">
                        Continue
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </button>

                    <button type="submit" id="submit-btn" class="hidden bg-gradient-to-r from-emerald-500 to-[#6D52E8] text-white px-10 py-4 rounded-xl font-bold text-lg shadow-xl shadow-emerald-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center gap-2">
                        Complete registration
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Login Link -->
        <div class="text-center mt-8 pt-6 border-t border-[#DDD7FE]">
            <p class="text-[#64708B] font-medium text-sm">Already have an account?</p>
            <a href="{{ route('login') }}" class="text-[#8C71F6] font-bold hover:text-[#6D52E8] transition-colors">
                Sign in here
            </a>
        </div>
    </div>

    <style>
        .step-hidden { display: none; }
        .step-active { display: block; animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const steps = document.querySelectorAll('.step');
            const nextBtn = document.getElementById('next-btn');
            const prevBtn = document.getElementById('prev-btn');
            const submitBtn = document.getElementById('submit-btn');
            const progressBar = document.getElementById('progress-bar');
            let currentStep = 1;

            function updateUI() {
                steps.forEach(step => {
                    if (parseInt(step.dataset.step) === currentStep) {
                        step.classList.remove('step-hidden');
                        step.classList.add('step-active');
                    } else {
                        step.classList.add('step-hidden');
                        step.classList.remove('step-active');
                    }
                });

                const progress = (currentStep / steps.length) * 100;
                progressBar.style.width = `${progress}%`;

                if (currentStep === 1) {
                    prevBtn.classList.add('hidden');
                } else {
                    prevBtn.classList.remove('hidden');
                }

                if (currentStep === steps.length) {
                    nextBtn.classList.add('hidden');
                    submitBtn.classList.remove('hidden');
                } else {
                    nextBtn.classList.remove('hidden');
                    submitBtn.classList.add('hidden');
                }
            }

            nextBtn.addEventListener('click', () => {
                const currentInput = steps[currentStep - 1].querySelector('input');
                if (currentInput && currentInput.value.trim() === '') {
                    currentInput.classList.add('border-rose-500', 'ring-2', 'ring-rose-500/50');
                    currentInput.focus();
                    return;
                }
                currentInput.classList.remove('border-rose-500', 'ring-2', 'ring-rose-500/50');

                if (currentStep < steps.length) {
                    currentStep++;
                    updateUI();
                    const nextInput = steps[currentStep - 1].querySelector('input');
                    if (nextInput) setTimeout(() => nextInput.focus(), 300);
                }
            });

            prevBtn.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    updateUI();
                }
            });

            document.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && currentStep < steps.length) {
                    e.preventDefault();
                    nextBtn.click();
                }
            });
        });
    </script>
</x-guest-layout>
