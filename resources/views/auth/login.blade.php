<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - TechInventory</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        .font-display { font-family: 'Lilita One', cursive; letter-spacing: 1px; }
        .font-sans { font-family: 'Nunito', sans-serif; }
        body { background-color: #f4f4f9; }
        .bg-tema-ungu { background-color: #635bff; }
        .bg-tema-pink { background-color: #ffd6f4; }
    </style>
</head>
<body class="font-sans antialiased min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    @if(session('banned_alert'))
        <div id="bannedNotification" 
             class="fixed top-8 left-1/2 w-max max-w-[90%] bg-[#FF0000] border-4 text-white rounded-full shadow-2xl z-[100] transition-all duration-700 ease-out transform -translate-x-1/2 -translate-y-[250%] flex items-center justify-center gap-4 py-4 px-10">
            <span class="font-display text-2xl tracking-wide text-white drop-shadow-md">
                {{ session('banned_alert') }}
            </span>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const notif = document.getElementById('bannedNotification');
                
                // Animasi Turun (setelah 0.2 detik halaman dimuat)
                setTimeout(() => {
                    notif.classList.remove('-translate-y-[250%]');
                    notif.classList.add('translate-y-0');
                }, 200);

                // Animasi Naik Kembali / Hilang (setelah 4.5 detik)
                setTimeout(() => {
                    notif.classList.remove('translate-y-0');
                    notif.classList.add('-translate-y-[250%]');
                }, 4500);
            });
        </script>
    @endif
    <div class="bg-tema-ungu w-full max-w-md p-8 rounded-[3rem] shadow-2xl text-white border-4 border-[#635bff] relative z-10">
        
        <div class="text-center mb-8">
            <h2 class="text-4xl font-display text-white mb-2 tracking-wide drop-shadow-sm">Login TechInventory</h2>
            <p class="text-sm opacity-90 font-bold">Silakan masuk menggunakan email Anda.</p>
        </div>

        <x-auth-session-status class="mb-4 text-pink-200 font-bold text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-bold mb-2 ml-4 uppercase tracking-wider opacity-90">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-6 py-3 rounded-full text-black border-none focus:ring-4 focus:ring-[#ffd6f4] shadow-inner font-bold">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-pink-200 font-bold ml-4" />
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold mb-2 ml-4 uppercase tracking-wider opacity-90">Password</label>
                <input type="password" name="password" required
                       class="w-full px-6 py-3 rounded-full text-black border-none focus:ring-4 focus:ring-[#ffd6f4] shadow-inner font-bold">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-pink-200 font-bold ml-4" />
            </div>

            <div class="mb-6 flex items-center ml-4">
                <input type="checkbox" name="remember" id="remember" class="rounded border-none text-pink-500 focus:ring-pink-400 w-5 h-5 cursor-pointer shadow-inner">
                <label for="remember" class="ml-2 text-sm cursor-pointer font-bold">Remember me</label>
            </div>

            <div class="flex items-center justify-between mt-4">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs hover:underline opacity-80 hover:opacity-100 font-bold ml-2">
                        Lupa Password?
                    </a>
                @endif
                
                <div class="flex gap-3">
                    <a href="{{ route('register') }}" class="font-display bg-white text-black px-6 py-3 rounded-full text-xl hover:scale-105 transition drop-shadow-sm text-center">
                        Daftar
                    </a>
                    <button type="submit" class="font-display bg-tema-pink text-black px-8 py-3 rounded-full text-xl hover:scale-105 transition drop-shadow-sm">
                        Masuk
                    </button>
                </div>
            </div>
        </form>
    </div>

</body>
</html>