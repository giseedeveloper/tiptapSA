<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TIPTAP | The Future of  </title>
    <meta name="description" content="The smartest QR & WhatsApp ordering system for modern restaurants.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @include('partials.brand-icons')

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                    },
                    colors: {
                        background: '#030712', // Very dark blue/gray
                        surface: '#111827',
                        primary: '#6366f1', // Indigo 500
                        secondary: '#a855f7', // Purple 500
                        accent: '#14b8a6', // Teal 500
                        whatsapp: '#25D366',
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                        'hero-glow': 'conic-gradient(from 180deg at 50% 50%, #2a8af6 0deg, #a853ba 180deg, #e92a67 360deg)',
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #030712;
            color: #f8fafc;
            overflow-x: hidden;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #030712;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        /* Glassmorphism Utilities */
        .glass {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .glass-card {
            background: linear-gradient(180deg, rgba(30, 41, 59, 0.3) 0%, rgba(15, 23, 42, 0.3) 100%);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .glass-card:hover {
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 0 40px rgba(99, 102, 241, 0.15);
            transform: translateY(-5px);
            background: linear-gradient(180deg, rgba(30, 41, 59, 0.5) 0%, rgba(15, 23, 42, 0.5) 100%);
        }

        /* Text Gradients */
        .text-gradient {
            background: linear-gradient(135deg, #fff 0%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .text-gradient-primary {
            background: linear-gradient(135deg, #818cf8 0%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Mesh Background */
        .mesh-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
            pointer-events: none;
        }
        
        .mesh-blob {
            position: absolute;
            filter: blur(90px);
            opacity: 0.3;
            animation: blob 10s infinite cubic-bezier(0.4, 0, 0.2, 1);
        }

        .mesh-blob-1 { top: -10%; left: -10%; width: 600px; height: 600px; background: #4f46e5; animation-delay: 0s; }
        .mesh-blob-2 { top: 30%; right: -10%; width: 500px; height: 500px; background: #9333ea; animation-delay: 2s; }
        .mesh-blob-3 { bottom: -10%; left: 20%; width: 700px; height: 700px; background: #0f172a; animation-delay: 4s; }

        /* Grid Pattern */
        .grid-pattern {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
        }
    </style>
</head>
<body class="antialiased selection:bg-primary selection:text-white">

    <!-- Background Elements -->
    <div class="fixed inset-0 z-0">
        <div class="mesh-bg">
            <div class="mesh-blob mesh-blob-1"></div>
            <div class="mesh-blob mesh-blob-2"></div>
            <div class="mesh-blob mesh-blob-3"></div>
        </div>
        <div class="absolute inset-0 grid-pattern z-0"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 flex items-center justify-center overflow-hidden rounded-full group-hover:rotate-12 transition-transform duration-500">
                        <img src="{{ asset('images/logo.png') }}" alt="TIPTAP Logo" class="w-full h-full object-contain bg-white">
                    </div>
                    <span class="text-2xl font-display font-bold text-white tracking-tight">TIP<span class="text-primary">TAP</span></span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center gap-8">
                    <a href="#features" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Features</a>
                    <a href="#how-it-works" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">How it Works</a>
                    <a href="#pricing" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Pricing</a>
                    
                    <div class="h-6 w-px bg-white/10"></div>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-white bg-white/10 px-6 py-2.5 rounded-full hover:bg-white/20 transition-all border border-white/5">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-bold text-slate-300 hover:text-white transition-colors">Log in</a>
                            <div class="relative group/getstarted" id="nav-getstarted-wrap">
                                <button type="button" id="nav-getstarted-btn" class="bg-white text-black px-6 py-2.5 rounded-full font-bold hover:bg-slate-200 transition-all shadow-[0_0_20px_rgba(255,255,255,0.3)] text-sm flex items-center gap-1.5">
                                    Get Started
                                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                </button>
                                <div class="absolute right-0 top-full mt-2 w-56 py-2 bg-[#0f172a] border border-white/10 rounded-xl shadow-xl opacity-0 invisible group-hover/getstarted:opacity-100 group-hover/getstarted:visible transition-all duration-200 z-50" id="nav-getstarted-dropdown" onclick="event.stopPropagation()">
                                    <a href="{{ route('restaurant.register') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition-colors">
                                        <i data-lucide="store" class="w-4 h-4 text-primary shrink-0"></i>
                                        Register Restaurant / Manager
                                    </a>
                                    <a href="{{ route('waiter.register') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition-colors">
                                        <i data-lucide="user" class="w-4 h-4 text-accent shrink-0"></i>
                                        Register as Waiter
                                    </a>
                                </div>
                            </div>
                        @endauth
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <button class="lg:hidden p-2 text-white" id="mobile-menu-btn">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="fixed inset-0 z-[60] bg-[#030712] hidden flex-col p-8 lg:hidden" id="mobile-menu">
        <div class="flex justify-between items-center mb-12">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 flex items-center justify-center overflow-hidden rounded-full">
                    <img src="{{ asset('images/logo.png') }}" alt="TIPTAP Logo" class="w-full h-full object-contain bg-white">
                </div>
                <span class="text-2xl font-display font-bold text-white">TIPTAP</span>
            </div>
            <button id="close-menu-btn" class="text-white"><i data-lucide="x" class="w-8 h-8"></i></button>
        </div>
        <div class="flex flex-col gap-8 text-xl font-medium text-slate-300">
            <a href="#features" class="hover:text-primary">Features</a>
            <a href="#how-it-works" class="hover:text-primary">How it Works</a>
            <a href="#pricing" class="hover:text-primary">Pricing</a>
            <hr class="border-white/10">
            <a href="{{ route('login') }}">Log in</a>
            <p class="text-xs font-bold text-white/50 uppercase tracking-wider pt-2">Get Started</p>
            <a href="{{ route('restaurant.register') }}" class="flex items-center gap-3 py-3 px-4 bg-white/5 rounded-xl border border-white/10 hover:bg-white/10">
                <i data-lucide="store" class="w-5 h-5 text-primary"></i>
                Register Restaurant / Manager
            </a>
            <a href="{{ route('waiter.register') }}" class="flex items-center gap-3 py-3 px-4 bg-white/5 rounded-xl border border-white/10 hover:bg-white/10">
                <i data-lucide="user" class="w-5 h-5 text-accent"></i>
                Register as Waiter
            </a>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden z-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <div data-aos="fade-up" data-aos-duration="1000">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/5 text-primary text-xs font-bold uppercase tracking-widest mb-8 border border-white/10 backdrop-blur-sm">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-3 animate-pulse"></span>
                    The Future of Dining is Here
                </div>
                
                <h1 class="text-5xl lg:text-8xl font-display font-bold text-white tracking-tight mb-8 leading-[1.1]">
                    Dining <span class="text-gradient-primary">Reimagined</span><br>
                    for the Digital Age.
                </h1>
                
                <p class="text-xl text-slate-400 mb-12 max-w-2xl mx-auto leading-relaxed">
                    Empower your restaurant with an intelligent operating system. Seamless QR ordering, WhatsApp integration, and instant payments—all in one platform.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <div class="relative group/herogetstarted w-full sm:w-auto" id="hero-getstarted-wrap">
                        <button type="button" id="hero-getstarted-btn" class="w-full sm:w-auto px-10 py-5 bg-primary text-white rounded-2xl font-bold text-lg shadow-[0_0_40px_rgba(99,102,241,0.4)] hover:shadow-[0_0_60px_rgba(99,102,241,0.6)] hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                            Get Started
                            <i data-lucide="chevron-down" class="w-5 h-5"></i>
                        </button>
                        <div class="absolute left-1/2 -translate-x-1/2 top-full mt-2 w-64 py-2 bg-[#0f172a] border border-white/10 rounded-xl shadow-xl opacity-0 invisible group-hover/herogetstarted:opacity-100 group-hover/herogetstarted:visible transition-all duration-200 z-50" id="hero-getstarted-dropdown" onclick="event.stopPropagation()">
                            <a href="{{ route('restaurant.register') }}" class="flex items-center gap-3 px-4 py-3.5 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition-colors rounded-t-xl">
                                <i data-lucide="store" class="w-5 h-5 text-primary shrink-0"></i>
                                <span><strong class="text-white">Restaurant / Manager</strong><br><span class="text-xs">Start free trial</span></span>
                            </a>
                            <a href="{{ route('waiter.register') }}" class="flex items-center gap-3 px-4 py-3.5 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition-colors rounded-b-xl">
                                <i data-lucide="user" class="w-5 h-5 text-accent shrink-0"></i>
                                <span><strong class="text-white">Waiter</strong><br><span class="text-xs">Register and get your code</span></span>
                            </a>
                        </div>
                    </div>
                    <a href="#demo" class="w-full sm:w-auto px-10 py-5 bg-white/5 text-white border border-white/10 rounded-2xl font-bold text-lg hover:bg-white/10 transition-all flex items-center justify-center gap-3">
                        <i data-lucide="play-circle" class="w-5 h-5"></i>
                        View Demo
                    </a>
                </div>
            </div>

            <!-- Hero Visual -->
            <div class="mt-16 relative max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                <div class="absolute -inset-1 bg-gradient-to-r from-primary via-secondary to-primary rounded-[2rem] blur opacity-20 animate-pulse"></div>
                <div class="relative bg-[#0B0F1A] rounded-[2rem] border border-white/10 shadow-2xl overflow-hidden aspect-video group">
                    <!-- WhatsApp Chat Demo Image -->
                    <img src="{{ asset('images/whatsapp_chat_demo.png') }}" alt="WhatsApp Chat Demo" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0B0F1A]/40 via-transparent to-transparent"></div>
                </div>
            </div>

            <!-- Trusted By -->
            <div class="mt-24 pt-12 border-t border-white/5">
                <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-8">Trusted by Tanzania's Best</p>
                <div class="flex flex-wrap justify-center items-center gap-12 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
                    <span class="text-2xl font-black text-white">SAMAKI<span class="text-primary">SAMAKI</span></span>
                    <span class="text-2xl font-black text-white">AKEMI</span>
                    <span class="text-2xl font-black text-white">CTFM</span>
                    <span class="text-2xl font-black text-white">ELEMENTS</span>
                    <span class="text-2xl font-black text-white">THE PIER</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid -->
    <section id="features" class="py-32 relative z-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="mb-20">
                <h2 class="text-primary font-bold tracking-widest uppercase text-sm mb-4">Features</h2>
                <h3 class="text-4xl md:text-5xl font-display font-bold text-white tracking-tight">Everything you need.<br>Nothing you don't.</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="glass-card p-10 rounded-[2.5rem] md:col-span-2 relative overflow-hidden group">
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-primary/20 rounded-2xl flex items-center justify-center mb-8">
                            <i data-lucide="message-circle" class="w-7 h-7 text-primary"></i>
                        </div>
                        <h4 class="text-2xl font-bold text-white mb-4">WhatsApp Native Ordering</h4>
                        <p class="text-slate-400 leading-relaxed max-w-md">
                            Turn conversations into conversions. Customers can browse your menu, customize orders, and checkout directly within WhatsApp. No app downloads required.
                        </p>
                    </div>
                    <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/4 w-1/2 opacity-50 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-700">
                         <!-- Abstract Phone UI -->
                         <div class="bg-[#0f172a] border border-white/10 rounded-3xl p-4 shadow-2xl rotate-[-10deg]">
                             <div class="space-y-3">
                                 <div class="bg-white/5 h-8 rounded-lg w-3/4"></div>
                                 <div class="bg-primary/20 h-24 rounded-lg w-full"></div>
                                 <div class="bg-white/5 h-8 rounded-lg w-1/2"></div>
                             </div>
                         </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="glass-card p-10 rounded-[2.5rem] relative overflow-hidden group">
                    <div class="w-14 h-14 bg-accent/20 rounded-2xl flex items-center justify-center mb-8">
                        <i data-lucide="zap" class="w-7 h-7 text-accent"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-white mb-4">Instant USSD Pay</h4>
                    <p class="text-slate-400 leading-relaxed">
                        Seamless integration with TigoPesa, M-Pesa, and Airtel Money. Push prompts appear instantly on customer phones.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="glass-card p-10 rounded-[2.5rem] relative overflow-hidden group">
                    <div class="w-14 h-14 bg-secondary/20 rounded-2xl flex items-center justify-center mb-8">
                        <i data-lucide="bar-chart-3" class="w-7 h-7 text-secondary"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-white mb-4">Real-time Analytics</h4>
                    <p class="text-slate-400 leading-relaxed">
                        Track sales, inventory, and staff performance in real-time from any device.
                    </p>
                </div>

                <!-- Card 4 -->
                <div class="glass-card p-10 rounded-[2.5rem] md:col-span-2 relative overflow-hidden group">
                    <div class="flex flex-col md:flex-row items-center gap-12">
                        <div class="flex-1">
                            <div class="w-14 h-14 bg-pink-500/20 rounded-2xl flex items-center justify-center mb-8">
                                <i data-lucide="chef-hat" class="w-7 h-7 text-pink-500"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white mb-4">Kitchen Display System (KDS)</h4>
                            <p class="text-slate-400 leading-relaxed">
                                Ditch the paper tickets. Orders appear instantly on a digital screen in the kitchen, color-coded by wait time.
                            </p>
                        </div>
                        <div class="flex-1 bg-white/5 rounded-2xl p-6 border border-white/10 w-full">
                            <div class="flex justify-between items-center mb-4 border-b border-white/5 pb-4">
                                <span class="text-xs font-bold text-slate-400">ORDER #1024</span>
                                <span class="text-xs font-bold text-green-400">JUST NOW</span>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm text-white">
                                    <span>2x Grilled Chicken</span>
                                    <span class="text-slate-500">Main</span>
                                </div>
                                <div class="flex justify-between text-sm text-white">
                                    <span>1x Chips Masala</span>
                                    <span class="text-slate-500">Side</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="py-32 relative z-10 bg-black/20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-24">
                <h2 class="text-primary font-bold tracking-widest uppercase text-sm mb-4">Workflow</h2>
                <h3 class="text-4xl md:text-5xl font-display font-bold text-white tracking-tight">From Scan to Savor.</h3>
            </div>

            <div class="relative">
                <!-- Connecting Line -->
                <div class="absolute top-1/2 left-0 w-full h-0.5 bg-gradient-to-r from-transparent via-primary/30 to-transparent hidden md:block"></div>

                <div class="grid md:grid-cols-3 gap-12">
                    <div class="relative z-10 text-center group">
                        <div class="w-24 h-24 mx-auto bg-[#0B0F1A] border border-white/10 rounded-full flex items-center justify-center mb-8 group-hover:border-primary transition-colors shadow-xl">
                            <i data-lucide="qr-code" class="w-10 h-10 text-white group-hover:text-primary transition-colors"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white mb-3">1. Scan QR</h4>
                        <p class="text-slate-400 text-sm px-8">Customer scans the code on the table. No app download needed.</p>
                    </div>

                    <div class="relative z-10 text-center group">
                        <div class="w-24 h-24 mx-auto bg-[#0B0F1A] border border-white/10 rounded-full flex items-center justify-center mb-8 group-hover:border-primary transition-colors shadow-xl">
                            <i data-lucide="smartphone" class="w-10 h-10 text-white group-hover:text-primary transition-colors"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white mb-3">2. Order & Pay</h4>
                        <p class="text-slate-400 text-sm px-8">Menu opens in WhatsApp. They order and pay via mobile money.</p>
                    </div>

                    <div class="relative z-10 text-center group">
                        <div class="w-24 h-24 mx-auto bg-[#0B0F1A] border border-white/10 rounded-full flex items-center justify-center mb-8 group-hover:border-primary transition-colors shadow-xl">
                            <i data-lucide="utensils" class="w-10 h-10 text-white group-hover:text-primary transition-colors"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white mb-3">3. Enjoy</h4>
                        <p class="text-slate-400 text-sm px-8">Order is sent to kitchen. Food is served. No waiting.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section id="pricing" class="py-32 relative z-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-24">
                <h2 class="text-primary font-bold tracking-widest uppercase text-sm mb-4">Pricing</h2>
                <h3 class="text-4xl md:text-5xl font-display font-bold text-white tracking-tight">Transparent Value.</h3>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Starter -->
                <div class="glass-card p-8 rounded-[2rem] flex flex-col">
                    <h4 class="text-lg font-bold text-white mb-2">Starter</h4>
                    <div class="text-3xl font-bold text-white mb-6">Free<span class="text-sm text-slate-500 font-normal"> / 14 days</span></div>
                    <ul class="space-y-4 mb-8 flex-1">
                        <li class="flex items-center gap-3 text-sm text-slate-300"><i data-lucide="check" class="w-4 h-4 text-primary"></i> Up to 10 Tables</li>
                        <li class="flex items-center gap-3 text-sm text-slate-300"><i data-lucide="check" class="w-4 h-4 text-primary"></i> Basic Analytics</li>
                        <li class="flex items-center gap-3 text-sm text-slate-300"><i data-lucide="check" class="w-4 h-4 text-primary"></i> Email Support</li>
                    </ul>
                    <a href="{{ route('restaurant.register') }}" class="w-full py-3 bg-white/5 text-white border border-white/10 rounded-xl font-bold text-center hover:bg-white/10 transition-all">Start Trial</a>
                </div>

                <!-- Pro -->
                <div class="glass-card p-8 rounded-[2rem] flex flex-col relative border-primary/50 bg-primary/5">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-primary text-white px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest">Popular</div>
                    <h4 class="text-lg font-bold text-white mb-2">Business</h4>
                    <div class="text-3xl font-bold text-white mb-6">50k<span class="text-sm text-slate-500 font-normal"> TZS / mo</span></div>
                    <ul class="space-y-4 mb-8 flex-1">
                        <li class="flex items-center gap-3 text-sm text-white"><i data-lucide="check" class="w-4 h-4 text-primary"></i> Unlimited Tables</li>
                        <li class="flex items-center gap-3 text-sm text-white"><i data-lucide="check" class="w-4 h-4 text-primary"></i> WhatsApp Bot</li>
                        <li class="flex items-center gap-3 text-sm text-white"><i data-lucide="check" class="w-4 h-4 text-primary"></i> USSD Payments</li>
                        <li class="flex items-center gap-3 text-sm text-white"><i data-lucide="check" class="w-4 h-4 text-primary"></i> Priority Support</li>
                    </ul>
                    <a href="{{ route('restaurant.register') }}" class="w-full py-3 bg-primary text-white rounded-xl font-bold text-center hover:bg-primary/90 transition-all shadow-lg shadow-primary/25">Get Started</a>
                </div>

                <!-- Enterprise -->
                <div class="glass-card p-8 rounded-[2rem] flex flex-col">
                    <h4 class="text-lg font-bold text-white mb-2">Enterprise</h4>
                    <div class="text-3xl font-bold text-white mb-6">Custom</div>
                    <ul class="space-y-4 mb-8 flex-1">
                        <li class="flex items-center gap-3 text-sm text-slate-300"><i data-lucide="check" class="w-4 h-4 text-primary"></i> Multi-branch</li>
                        <li class="flex items-center gap-3 text-sm text-slate-300"><i data-lucide="check" class="w-4 h-4 text-primary"></i> Custom API</li>
                        <li class="flex items-center gap-3 text-sm text-slate-300"><i data-lucide="check" class="w-4 h-4 text-primary"></i> Dedicated Manager</li>
                    </ul>
                    <a href="https://wa.me/255620366103" class="w-full py-3 bg-white/5 text-white border border-white/10 rounded-xl font-bold text-center hover:bg-white/10 transition-all">Contact Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-20 relative z-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-gradient-to-r from-primary to-secondary rounded-[3rem] p-12 md:p-24 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <div class="relative z-10">
                    <h2 class="text-4xl md:text-6xl font-display font-bold text-white mb-8 tracking-tight">Ready to upgrade?</h2>
                    <p class="text-white/80 text-xl mb-12 max-w-2xl mx-auto">Join the digital revolution. Transform your restaurant operations today.</p>
                    <a href="{{ route('restaurant.register') }}" class="inline-block px-12 py-5 bg-white text-primary rounded-full font-bold text-xl shadow-2xl hover:scale-105 transition-transform">
                        Create Free Account
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-white/5 bg-[#020617] pt-20 pb-10 relative z-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-2">
                    <a href="/" class="flex items-center gap-2 mb-6">
                        <div class="w-8 h-8 flex items-center justify-center overflow-hidden rounded-full">
                            <img src="{{ asset('images/logo.png') }}" alt="TIPTAP Logo" class="w-full h-full object-contain bg-white">
                        </div>
                        <span class="text-xl font-display font-bold text-white">TIPTAP</span>
                    </a>
                    <p class="text-slate-500 text-sm max-w-xs">
                        The operating system for modern dining. Built with ❤️ in Tanzania.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6">Product</h4>
                    <ul class="space-y-4 text-sm text-slate-500">
                        <li><a href="#" class="hover:text-primary transition-colors">Features</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">API</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6">Company</h4>
                    <ul class="space-y-4 text-sm text-slate-500">
                        <li><a href="#" class="hover:text-primary transition-colors">About</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Privacy</a></li>
                    </ul>
                </div>
            </div>
            <div class="flex flex-col md:flex-row justify-between items-center pt-8 border-t border-white/5">
                <p class="text-slate-600 text-sm">© {{ date('Y') }} TIPTAP. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="text-slate-600 hover:text-white transition-colors"><i data-lucide="twitter" class="w-5 h-5"></i></a>
                    <a href="#" class="text-slate-600 hover:text-white transition-colors"><i data-lucide="instagram" class="w-5 h-5"></i></a>
                    <a href="#" class="text-slate-600 hover:text-white transition-colors"><i data-lucide="linkedin" class="w-5 h-5"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp -->
    <a href="https://wa.me/255620366103" class="fixed bottom-8 right-8 z-50 group">
        <div class="absolute inset-0 bg-whatsapp rounded-full blur-lg opacity-50 animate-pulse"></div>
        <div class="relative bg-whatsapp text-white p-4 rounded-full shadow-2xl hover:scale-110 transition-transform flex items-center justify-center">
            <i data-lucide="message-circle" class="w-6 h-6"></i>
        </div>
    </a>

    <script>
        AOS.init({
            once: true,
            offset: 50,
            duration: 800,
        });
        lucide.createIcons();

        // Navbar Scroll
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 20) {
                nav.classList.add('bg-[#030712]/80', 'backdrop-blur-xl', 'border-b', 'border-white/5');
                nav.classList.remove('glass');
            } else {
                nav.classList.remove('bg-[#030712]/80', 'backdrop-blur-xl', 'border-b', 'border-white/5');
                nav.classList.add('glass');
            }
        });

        // Mobile Menu
        const menuBtn = document.getElementById('mobile-menu-btn');
        const closeBtn = document.getElementById('close-menu-btn');
        const menu = document.getElementById('mobile-menu');

        menuBtn.addEventListener('click', () => {
            menu.classList.remove('hidden');
            menu.classList.add('flex');
        });

        closeBtn.addEventListener('click', () => {
            menu.classList.add('hidden');
            menu.classList.remove('flex');
        });

        // Get Started dropdowns: toggle on click (for touch/mobile) and close on outside click
        function toggleDropdown(btn, panel) {
            if (!btn || !panel) return;
            const isVisible = panel.classList.contains('!opacity-100');
            document.querySelectorAll('[id$="-getstarted-dropdown"]').forEach(p => {
                p.classList.remove('!opacity-100', '!visible');
            });
            if (!isVisible) {
                panel.classList.add('!opacity-100', '!visible');
            }
        }
        document.getElementById('nav-getstarted-btn')?.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleDropdown(null, document.getElementById('nav-getstarted-dropdown'));
        });
        document.getElementById('hero-getstarted-btn')?.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleDropdown(null, document.getElementById('hero-getstarted-dropdown'));
        });
        document.addEventListener('click', () => {
            document.querySelectorAll('[id$="-getstarted-dropdown"]').forEach(p => {
                p.classList.remove('!opacity-100', '!visible');
            });
        });
    </script>
</body>
</html>
