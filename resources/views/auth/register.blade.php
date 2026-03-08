<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - TechInventory</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        .font-display { font-family: 'Lilita One', cursive; letter-spacing: 1px; }
        .font-sans { font-family: 'Nunito', sans-serif; }
        body { background-color: #f4f4f9; }
        .bg-purple-card { background-color: #635bff; }
        .btn-pink { background-color: #ffd6f4; }
    </style>
</head>
<body class="font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <div class="bg-purple-card w-full max-w-md p-8 rounded-[2rem] shadow-2xl text-white border-4 border-[#635bff]">
        
        <div class="text-center mb-6 border-b border-indigo-400 pb-4">
            <h2 class="text-4xl font-display text-white mb-2 tracking-wide">Daftar Akun</h2>
            <p class="text-sm opacity-90">Buat akun untuk meminjam alat lab.</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-bold mb-1 ml-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="w-full px-4 py-2.5 rounded-2xl text-black border-none focus:ring-4 focus:ring-pink-300 shadow-inner">
                <x-input-error :messages="$errors->get('name')" class="mt-1 text-pink-200 font-bold" />
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold mb-1 ml-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-2.5 rounded-2xl text-black border-none focus:ring-4 focus:ring-pink-300 shadow-inner">
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-pink-200 font-bold" />
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold mb-1 ml-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2.5 rounded-2xl text-black border-none focus:ring-4 focus:ring-pink-300 shadow-inner">
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-pink-200 font-bold" />
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold mb-1 ml-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-4 py-2.5 rounded-2xl text-black border-none focus:ring-4 focus:ring-pink-300 shadow-inner">
            </div>

            <div class="flex items-center justify-between mt-4">
                <a href="{{ route('login') }}" class="text-sm hover:underline opacity-80 hover:opacity-100">
                    Sudah punya akun?
                </a>
                
                <button type="submit" class="font-display btn-pink text-black px-6 py-2 rounded-xl text-lg hover:brightness-95 transition shadow-md">
                    Daftar Sekarang
                </button>
            </div>
        </form>
    </div>

</body>
</html>