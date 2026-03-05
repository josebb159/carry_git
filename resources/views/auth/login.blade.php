<x-guest-layout>
    <style>
        /* Animación Mesh Mejorada - Movimiento de Nebulosa */
        @keyframes meshMove {
            0% { 
                background-position: 0% 50%, 50% 100%, 100% 50%;
                background-size: 200% 200%;
            }
            33% {
                background-position: 100% 100%, 0% 50%, 50% 0%;
                background-size: 250% 250%;
            }
            66% {
                background-position: 50% 0%, 100% 50%, 0% 100%;
                background-size: 200% 200%;
            }
            100% { 
                background-position: 0% 50%, 50% 100%, 100% 50%;
                background-size: 200% 200%;
            }
        }

        .animate-mesh {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            /* Mezcla de degradados para efecto de profundidad */
            background-image: 
                radial-gradient(circle at 20% 30%, #1a1a1a 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, #121212 0%, transparent 50%),
                radial-gradient(circle at center, #050505 0%, #000000 100%);
            background-color: #050505;
            animation: meshMove 20s ease-in-out infinite;
        }

        /* Eliminación total de fondos blancos del layout base */
        html, body {
            background-color: #050505 !important;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        /* Forzar que el contenedor de Breeze sea invisible */
        body > div {
            background: transparent !important;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: none !important;
        }

        /* Ocultar logo superior del layout default */
        body > div > div:first-child:not(.animate-mesh) {
            display: none !important;
        }

        /* Reset de contenedor de tarjeta de Breeze */
        body > div > div:nth-child(2) {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: none !important;
            display: flex;
            justify-content: center;
        }

        /* Tarjeta Glass Refinada */
        .glass-card {
            background: rgba(26, 26, 26, 0.8) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 1.5rem !important;
            padding: 3rem 2.5rem !important;
            width: 100% !important;
            max-width: 400px !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8) !important;
        }

        /* Inputs Elegantes con Borde Fino */
        .custom-input {
            background: rgba(0, 0, 0, 0.3) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
            padding: 0.9rem 1.25rem !important;
            border-radius: 0.85rem !important;
            font-size: 0.9rem !important;
            transition: all 0.3s ease !important;
            width: 100% !important;
        }

        .custom-input:focus {
            border-color: #cff700 !important; /* lime-brand */
            background: rgba(0, 0, 0, 0.5) !important;
            outline: none !important;
            box-shadow: 0 0 10px rgba(207, 247, 0, 0.1) !important;
        }

        /* Botón Píldora Metálico */
        .pill-button {
            background: linear-gradient(135deg, #2a2a2a 0%, #000000 100%) !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 9999px !important;
            padding: 1rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.2em !important;
            font-size: 0.75rem !important;
            width: 100% !important;
            transition: all 0.3s ease !important;
            cursor: pointer !important;
            margin-top: 1rem;
        }

        .pill-button:hover {
            transform: translateY(-2px);
            border-color: rgba(207, 247, 0, 0.4) !important;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
        }

        /* Labels */
        .label-style {
            display: block !important;
            font-size: 0.7rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            color: #6b7280 !important;
            margin-bottom: 0.5rem !important;
            margin-left: 0.25rem !important;
        }
    </style>

    <div class="animate-mesh"></div>

    <div class="glass-card">
        <div class="text-center mb-10">
            <div class="flex items-center justify-center mb-4">
                <img src="{{ asset('images/Asset1.png') }}" alt="Carri Logistics" class="h-16 w-auto object-contain">
            </div>
            <p class="text-gray-500 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">Acceso Seguro</p>
        </div>

        <x-auth-session-status class="mb-6" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-6">
                <label for="email" class="label-style">Identificación</label>
                <input id="email" 
                       class="custom-input" 
                       type="email" 
                       name="email" 
                       placeholder="usuario@carri.com"
                       :value="old('email')" 
                       required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-500" />
            </div>

            <div class="mb-8">
                <div class="flex justify-between items-center mb-0.5">
                    <label for="password" class="label-style">Contraseña</label>
                </div>
                <input id="password" 
                       class="custom-input"
                       type="password"
                       name="password"
                       placeholder="••••••••••••"
                       required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-500" />
            </div>

            <div class="flex items-center justify-between mb-8 px-1">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded border-white/10 bg-black/40 text-lime-brand focus:ring-0 h-4 w-4 transition-all" name="remember">
                    <span class="ms-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Recordarme</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="text-[10px] font-bold text-gray-500 hover:text-white transition-colors uppercase tracking-widest" href="{{ route('password.request') }}">
                        ¿Problemas?
                    </a>
                @endif
            </div>

            <div>
                <button type="submit" class="pill-button">
                    Acceder
                </button>
            </div>
        </form>

        <div class="mt-10 text-center">
            <p class="text-[8px] text-gray-700 font-bold uppercase tracking-[0.3em]">&copy; 2026 Carri Logistics S.A.</p>
        </div>
    </div>
</x-guest-layout>