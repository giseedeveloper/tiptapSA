@props([
    'title' => 'TIPTAP |  ',
    'heroBackground' => false,
    'wide' => false,
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
        @include('partials.portal-theme')

        <style>
            * {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            }
            
            body {
                background: #FAFBFC;
                min-height: 100vh;
                min-height: 100dvh;
            }

            body:not(.has-hero-background) {
                background: #12101c;
            }

            .login-hero-bg {
                background: linear-gradient(165deg, #DDD7FE 0%, #F5F3FF 35%, #FFFFFF 72%);
            }

            .login-hero-blob {
                position: absolute;
                border-radius: 50%;
                filter: blur(80px);
                pointer-events: none;
            }

            .login-hero-blob-1 {
                width: 420px;
                height: 420px;
                background: rgba(140, 113, 246, 0.35);
                top: -120px;
                right: -80px;
            }

            .login-hero-blob-2 {
                width: 320px;
                height: 320px;
                background: rgba(198, 189, 250, 0.5);
                bottom: 0;
                left: -100px;
            }

            .login-hero-blob-3 {
                width: 200px;
                height: 200px;
                background: rgba(37, 211, 102, 0.12);
                top: 40%;
                left: 30%;
            }

            body.has-hero-background {
                color: #12141C;
            }

            body.has-hero-background .glass-card {
                background: rgba(255, 255, 255, 0.92);
                backdrop-filter: blur(20px) saturate(180%);
                -webkit-backdrop-filter: blur(20px) saturate(180%);
                border: 1px solid rgba(140, 113, 246, 0.15);
                box-shadow:
                    0 8px 32px rgba(18, 20, 28, 0.06),
                    0 0 0 1px rgba(140, 113, 246, 0.06);
            }

            body.has-hero-background .login-footer {
                color: #64708B;
                text-shadow: none;
            }

            /* Gradient Text */
            .gradient-text {
                background: linear-gradient(135deg, #6D52E8 0%, #8C71F6 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            body.has-hero-background .btn-fin {
                background: linear-gradient(135deg, #8C71F6 0%, #6D52E8 100%);
                box-shadow: 0 4px 20px rgba(109, 82, 232, 0.35);
            }

            body.has-hero-background .btn-fin:hover {
                box-shadow: 0 8px 28px rgba(109, 82, 232, 0.45);
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
                0%, 100% { box-shadow: 0 0 30px rgba(140, 113, 246, 0.3); }
                50% { box-shadow: 0 0 60px rgba(140, 113, 246, 0.5); }
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
                    background: rgba(255, 255, 255, 0.95);
                }

                .animate-float {
                    animation: none;
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
                background: linear-gradient(180deg, rgba(140, 113, 246, 0.5) 0%, rgba(109, 82, 232, 0.5) 100%);
                border-radius: 10px;
            }
        </style>
    </head>
    <body class="font-sans antialiased {{ $heroBackground ? 'has-hero-background' : 'text-white' }}">
        @if ($heroBackground)
            <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden" aria-hidden="true">
                <div class="absolute inset-0 login-hero-bg"></div>
                <div class="login-hero-blob login-hero-blob-1"></div>
                <div class="login-hero-blob login-hero-blob-2"></div>
                <div class="login-hero-blob login-hero-blob-3"></div>
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
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full flex items-center justify-center {{ $heroBackground ? 'shadow-xl shadow-[#8C71F6]/25' : 'shadow-xl shadow-violet-500/30' }} transform group-hover:rotate-12 transition-all duration-500 animate-pulse-glow overflow-hidden">
                    <img src="{{ public_asset('images/logo.png') }}" alt="TIPTAP Logo" class="w-full h-full object-contain bg-white">
                </div>
                <div>
                    <span class="text-xl sm:text-2xl font-black text-white tracking-tight block leading-none hidden">TIP<span class="gradient-text">TAP</span></span>
                </div>
            </a>

            <!-- Content Card -->
            <div @class([
                'w-full glass-card rounded-2xl sm:rounded-3xl p-5 sm:p-8 relative overflow-hidden',
                'shadow-xl shadow-[#6D52E8]/10' => $heroBackground,
                'shadow-2xl shadow-black/50' => ! $heroBackground,
                'sm:max-w-2xl' => $wide,
                'sm:max-w-md' => ! $wide,
            ])>
                <!-- Decorative elements inside card -->
                <div class="absolute -top-10 -right-10 w-32 h-32 sm:w-40 sm:h-40 {{ $heroBackground ? 'bg-[#8C71F6]/10' : 'bg-violet-500/10' }} rounded-full blur-2xl sm:blur-3xl"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 sm:w-40 sm:h-40 {{ $heroBackground ? 'bg-[#DDD7FE]/60' : 'bg-cyan-500/10' }} rounded-full blur-2xl sm:blur-3xl"></div>
                
                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer -->
            <p class="login-footer mt-6 sm:mt-8 {{ $heroBackground ? 'text-[#64708B]' : 'text-white/50' }} text-xs font-medium text-center flex items-center justify-center gap-2">
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
