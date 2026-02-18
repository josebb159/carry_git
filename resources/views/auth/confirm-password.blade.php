<x-guest-layout>
    <style>
        /* Animación del fondo radial */
        @keyframes radialMove {
            0% { background-position: 0% 50%; scale: 1; opacity: 0.8; }
            50% { background-position: 100% 50%; scale: 1.1; opacity: 0.6; }
            100% { background-position: 0% 50%; scale: 1; opacity: 0.8; }
        }

        .bg-animated {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: radial-gradient(circle at 50% 50%, #1a1a1a 0%, #111827 50%, #000000 100%);
            background-size: 200% 200%;
            animation: radialMove 15s ease-in-out infinite;
        }

        /* Ocultar elementos por defecto del layout guest */
        body > div > div:nth-child(1) {
            display: none !important;
        }
        
        body > div > div:nth-child(2) {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: none !important;
        }

        /* Glassmorphism Card */
        .glass-card {
            background: rgba(0, 0, 0, 0.65) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.7);
        }

        /* Inputs Personalizados */
        .custom-input {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: white !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .custom-input:focus {
            background: rgba(255, 255, 255, 0.06) !important;
            border-color: #cff700 !important; /* lime-brand */
            box-shadow: 0 0 15px rgba(207, 247, 0, 0.1) !important;
            outline: none !important;
            --tw-ring-color: transparent !important;
        }

        /* Botón Metálico */
        .metallic-button {
            background: linear-gradient(135deg, #374151 0%, #000000 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5), inset 0 1px 1px rgba(255, 255, 255, 0.1);
        }

        .metallic-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(207, 247, 0, 0.2);
            border-color: rgba(207, 247, 0, 0.4);
        }

        .min-h-screen {
            background: transparent !important;
        }
    </style>

    <div class="bg-animated"></div>

    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="glass-card w-full max-w-md rounded-2xl p-8 sm:p-12 relative overflow-hidden text-center">
            
            <div class="relative z-10 mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-black border border-white/10 shadow-lg mb-6">
                    <svg class="w-8 h-8 text-lime-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-white text-2xl font-extrabold tracking-tight">Área Segura</h2>
                <p class="text-gray-500 mt-4 text-sm font-medium leading-relaxed px-4">
                    Esta es una sección protegida. Por favor, confirma tu contraseña para continuar.
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <!-- Password -->
                <div class="mb-8">
                    <label for="password" class="block text-[10px] font-bold text-gray-400 uppercase text-left tracking-widest mb-2 pl-1">Tu Contraseña</label>
                    <input id="password" 
                           class="custom-input block w-full rounded-xl px-4 py-4 text-sm text-center" 
                           type="password"
                           name="password"
                           placeholder="••••••••••••"
                           required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                </div>

                <div class="space-y-4">
                    <button type="submit" class="metallic-button w-full py-4 rounded-xl text-white text-sm font-bold tracking-[0.2em] uppercase">
                        Confirmar Acceso
                    </button>
                </div>
            </form>
            
            <div class="mt-10 text-center">
                <p class="text-[10px] text-gray-600 font-medium uppercase tracking-[0.2em]">&copy; 2026 Carri Logistics S.A.</p>
            </div>
        </div>
    </div>
</x-guest-layout>
