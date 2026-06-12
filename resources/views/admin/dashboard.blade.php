<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Paneli</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl p-10 shadow-2xl text-center space-y-6">
        <h1 class="text-3xl font-extrabold tracking-tight text-indigo-400">Admin Paneline Hoş Geldiniz!</h1>
        
        <p class="text-slate-400 text-base">
            Giriş yapan yetkili: <strong class="text-slate-100 font-semibold">{{ auth()->user()->name }}</strong>
        </p>

        <div class="flex items-center justify-center gap-4 pt-4">
            <a href="{{ route('home') }}" class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium rounded-xl transition">Ana Sayfaya Dön</a>
            
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-500 text-white font-medium rounded-xl transition">Çıkış Yap</button>
            </form>
        </div>
    </div>
</body>
</html>
