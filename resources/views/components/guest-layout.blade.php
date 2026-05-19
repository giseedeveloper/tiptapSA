@props([
    'title' => 'TIPTAP |  ',
    'heroBackground' => false,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        @include('partials.brand-icons')

        <!-- Premium Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            }
            
            body {
                background: #0f0a1e;
                min-height: 100vh;
                min-height: 100dvh;
            }

            .login-hero-bg {
                background-image: url("{{ public_asset('images/login-bg.jpg') }}");
                background-size: cover;
                background-position: center 42%;
                background-repeat: no-repeat;
                transform: scale(1.02);
            }

            .login-hero-overlay {
                background:
                    linear-gradient(105deg, rgba(12, 8, 24, 0.94) 0%, rgba(12, 8, 24, 0.78) 38%, rgba(12, 8, 24, 0.45) 62%, rgba(12, 8, 24, 0.35) 100%),
                    linear-gradient(180deg, rgba(12, 8, 24, 0.25) 0%, rgba(12, 8, 24, 0.55) 100%);
            }

            body.has-hero-background .glass-card {
                background: rgba(18, 12, 36, 0.72);
                backdrop-filter: blur(24px);
                -webkit-backdrop-filter: blur(24px);
                border: 1px solid rgba(255, 255, 255, 0.12);
                box-shadow:
                    0 25px 50px -12px rgba(0, 0, 0, 0.55),
                    0 0 0 1px rgba(139, 92, 246, 0.08) inset;
            }

            body.has-hero-background .login-footer {
                text-shadow: 0 1px 12px rgba(0, 0, 0, 0.8);
            }

            /* Gradient Text */
            .gradient-text {
                background: linear-gradient(135deg, #8b5cf6 0%, #06b6d4 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            /* Glassmorphism */
            .glass-card {
                background: rgba(28, 22, 51, 0.6);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.08);
            }

            /* Animations */
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-15px); }
            }
            
            @keyframes pulse-glow {
                0%, 100% { box-shadow: 0 0 30px rgba(139, 92, 246, 0.3); }
                50% { box-shadow: 0 0 60px rgba(139, 92, 246, 0.5); }
            }

            .animate-float { animation: float 6s ease-in-out infinite; }
            .animate-pulse-glow { animation: pulse-glow 3s ease-in-out infinite; }

            /* Mobile Optimizations */
            @media (max-width: 640px) {
                .glass-card {
                    background: rgba(28, 22, 51, 0.8);
                    backdrop-filter: blur(10px);
                    -webkit-backdrop-filter: blur(10px);
                }

                body.has-hero-background .glass-card {
                    background: rgba(18, 12, 36, 0.88);
                    backdrop-filter: blur(16px);
                    -webkit-backdrop-filter: blur(16px);
                }

                body.has-hero-background .login-hero-overlay {
                    background:
                        linear-gradient(180deg, rgba(12, 8, 24, 0.88) 0%, rgba(12, 8, 24, 0.82) 55%, rgba(12, 8, 24, 0.75) 100%);
                }

                body.has-hero-background .login-hero-bg {
                    background-position: center 35%;
                }

                .animate-float {
                    animation: none;
                }
            }

            @media (prefers-reduced-motion: reduce) {
                .login-hero-bg {
                    transform: none;
                }
            }

            /* Touch-friendly tap targets */
            @media (hover: none) {
                button, a {
                    min-height: 44px;
                }
            }
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.02); }
            ::-webkit-scrollbar-thumb {
                background: linear-gradient(180deg, rgba(139, 92, 246, 0.5) 0%, rgba(6, 182, 212, 0.5) 100%);
                border-radius: 10px;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-white {{ $heroBackground ? 'has-hero-background' : '' }}">
        @if ($heroBackground)
            <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden" aria-hidden="true">
                <div class="absolute inset-0 login-hero-bg"></div>
                <div class="absolute inset-0 login-hero-overlay"></div>
            </div>
        @else
            <!-- Background Effects -->
            <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
                <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-violet-600/10 rounded-full blur-[150px] -mr-48 -mt-48"></div>
                <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-cyan-600/10 rounded-full blur-[150px] -ml-48 -mb-48"></div>
            </div>
        @endif

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-4 sm:pt-0 px-3 sm:px-4 relative z-10">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2 sm:gap-3 group mb-6 sm:mb-8">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full flex items-center justify-center shadow-xl shadow-violet-500/30 transform group-hover:rotate-12 transition-all duration-500 animate-pulse-glow overflow-hidden">
                    <img src="{{ public_asset('images/logo.png') }}" alt="TIPTAP Logo" class="w-full h-full object-contain bg-white">
                </div>
                <div>
                    <span class="text-xl sm:text-2xl font-black text-white tracking-tight block leading-none hidden">TIP<span class="gradient-text">TAP</span></span>
                </div>
            </a>

            <!-- Content Card -->
            <div class="w-full sm:max-w-md glass-card rounded-2xl sm:rounded-3xl p-5 sm:p-8 shadow-2xl shadow-black/50 relative overflow-hidden">
                <!-- Decorative elements inside card -->
                <div class="absolute -top-10 -right-10 w-32 h-32 sm:w-40 sm:h-40 bg-violet-500/10 rounded-full blur-2xl sm:blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 sm:w-40 sm:h-40 bg-cyan-500/10 rounded-full blur-2xl sm:blur-3xl"></div>
                
                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer -->
            <p class="login-footer mt-6 sm:mt-8 text-white/50 text-xs font-medium text-center flex items-center justify-center gap-2">
                <img
                    src="{{ public_asset('images/flags/za.svg') }}"
                    alt="South Africa flag"
                    width="24"
                    height="18"
                    class="h-[18px] w-6 shrink-0 rounded-[2px] shadow-sm ring-1 ring-white/25 object-cover"
                    title="South Africa"
                >
                <span>&copy; {{ date('Y') }} TIPTAP. All rights reserved.</span>
            </p>
        </div>
    </body>
</html>
