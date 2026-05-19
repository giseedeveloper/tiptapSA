<x-guest-layout title="TIPTAP | Register Restaurant">
    <div class="max-w-2xl mx-auto">
        <!-- Progress Bar -->
        <div class="mb-8 bg-white/5 h-2 rounded-full overflow-hidden border border-white/10">
            <div id="progress-bar" class="bg-gradient-to-r from-violet-600 to-cyan-600 h-full transition-all duration-500" style="width: 15%"></div>
        </div>

        <div id="chat-container" class="flex flex-col min-h-[400px]">
            <!-- Chat Header -->
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 flex items-center justify-center overflow-hidden rounded-full">
                    <img src="{{ asset('images/logo.png') }}" alt="TIPTAP Logo" class="w-full h-full object-contain bg-white">
                </div>
                <div>
                    <h2 class="text-xl font-black text-white">TIPTAP Assistant</h2>
                    <p class="text-xs text-emerald-400 font-bold uppercase tracking-widest flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                        Online • Ready to help
                    </p>
                </div>
            </div>

            <form id="registration-form" method="POST" action="{{ route('restaurant.register.store') }}" class="flex flex-col flex-1">
                @csrf

                <!-- Step 1: Restaurant Name -->
                <div class="step step-active" data-step="1">
                    <div class="chat-bubble-left bg-white/5 border border-white/10 rounded-2xl rounded-tl-none p-4 text-white/80 font-medium mb-4">
                        Hello! Welcome to TIPTAP. To get started, what is your restaurant called?
                    </div>
                    <div class="mt-4">
                        <input id="restaurant_name" type="text" name="restaurant_name" value="{{ old('restaurant_name') }}" required placeholder="e.g. TIPTAP Grill"
                               class="block w-full text-lg p-4 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all">
                        <x-input-error :messages="$errors->get('restaurant_name')" class="mt-2" />
                    </div>
                </div>

                <!-- Step 2: Location -->
                <div class="step step-hidden" data-step="2">
                    <div class="chat-bubble-left bg-white/5 border border-white/10 rounded-2xl rounded-tl-none p-4 text-white/80 font-medium mb-4">
                        Great! Where is your restaurant located?
                    </div>
                    <div class="mt-4">
                        <input id="location" type="text" name="location" value="{{ old('location') }}" required placeholder="e.g. Sandton, Johannesburg"
                               class="block w-full text-lg p-4 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all">
                        <x-input-error :messages="$errors->get('location')" class="mt-2" />
                    </div>
                </div>

                <!-- Step 3: Phone -->
                <div class="step step-hidden" data-step="3">
                    <div class="chat-bubble-left bg-white/5 border border-white/10 rounded-2xl rounded-tl-none p-4 text-white/80 font-medium mb-4">
                        Got it. What is the restaurant phone number for contact?
                    </div>
                    <div class="mt-4">
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required placeholder="e.g. 071 234 5678"
                               class="block w-full text-lg p-4 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all">
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                </div>

                <!-- Step 4: Manager Name -->
                <div class="step step-hidden" data-step="4">
                    <div class="chat-bubble-left bg-white/5 border border-white/10 rounded-2xl rounded-tl-none p-4 text-white/80 font-medium mb-4">
                        Who will be the manager of this restaurant? (Your full name)
                    </div>
                    <div class="mt-4">
                        <input id="manager_name" type="text" name="manager_name" value="{{ old('manager_name') }}" required placeholder="e.g. John Doe"
                               class="block w-full text-lg p-4 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all">
                        <x-input-error :messages="$errors->get('manager_name')" class="mt-2" />
                    </div>
                </div>

                <!-- Step 5: Email -->
                <div class="step step-hidden" data-step="5">
                    <div class="chat-bubble-left bg-white/5 border border-white/10 rounded-2xl rounded-tl-none p-4 text-white/80 font-medium mb-4">
                        What email should we use for your login?
                    </div>
                    <div class="mt-4">
                        <input id="manager_email" type="email" name="manager_email" value="{{ old('manager_email') }}" required placeholder="e.g. manager@tiptap.com"
                               class="block w-full text-lg p-4 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all">
                        <x-input-error :messages="$errors->get('manager_email')" class="mt-2" />
                    </div>
                </div>

                <!-- Step 6: Password -->
                <div class="step step-hidden" data-step="6">
                    <div class="chat-bubble-left bg-white/5 border border-white/10 rounded-2xl rounded-tl-none p-4 text-white/80 font-medium mb-4">
                        Finally, set a strong password to secure your account.
                    </div>
                    <div class="mt-4 space-y-4">
                        <input id="manager_password" type="password" name="manager_password" required placeholder="Password"
                               class="block w-full text-lg p-4 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all">
                        <input id="manager_password_confirmation" type="password" name="manager_password_confirmation" required placeholder="Confirm Password"
                               class="block w-full text-lg p-4 bg-white/5 border border-white/10 rounded-xl font-medium text-white placeholder-white/30 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all">
                        <x-input-error :messages="$errors->get('manager_password')" class="mt-2" />
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="mt-12 flex items-center justify-between">
                    <button type="button" id="prev-btn" class="hidden text-white/40 font-bold hover:text-white transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m15 18-6-6 6-6"/>
                        </svg>
                        Go back
                    </button>
                    
                    <button type="button" id="next-btn" class="bg-gradient-to-r from-violet-600 to-cyan-600 text-white px-10 py-4 rounded-xl font-bold text-lg shadow-xl shadow-violet-500/25 hover:shadow-violet-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center gap-2 ml-auto">
                        Endelea
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </button>

                    <button type="submit" id="submit-btn" class="hidden bg-gradient-to-r from-emerald-500 to-cyan-500 text-white px-10 py-4 rounded-xl font-bold text-lg shadow-xl shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center gap-2">
                        Complete registration
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Login Link -->
        <div class="text-center mt-8 pt-6 border-t border-white/10">
            <p class="text-white/40 font-medium text-sm">Already have an account?</p>
            <a href="{{ route('login') }}" class="text-violet-400 font-bold hover:text-cyan-400 transition-colors">
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

                // Update Progress Bar
                const progress = (currentStep / steps.length) * 100;
                progressBar.style.width = `${progress}%`;

                // Update Buttons
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
                    // Focus on next input
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

            // Allow "Enter" key to go to next step
            document.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && currentStep < steps.length) {
                    e.preventDefault();
                    nextBtn.click();
                }
            });
        });
    </script>
</x-guest-layout>
