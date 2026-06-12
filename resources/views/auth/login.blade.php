<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-slate-800 border border-slate-700 rounded-2xl p-8 shadow-xl">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-indigo-400 mb-2">E-Ticaret</h1>
            <p class="text-slate-400 text-sm font-medium">Lütfen giriş yapın</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl p-4 mb-6 text-sm">
                Giriş denemesi başarısız oldu. Bilgilerinizi kontrol edin.
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">E-Posta Adresi</label>
                <input type="email" name="email" id="email" class="w-full bg-slate-950 border border-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-3 text-sm text-slate-100 placeholder-slate-600 outline-none transition" placeholder="ornek@domain.com" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Şifre</label>
                <input type="password" name="password" id="password" class="w-full bg-slate-950 border border-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-3 text-sm text-slate-100 placeholder-slate-600 outline-none transition" placeholder="••••••••" required>
                @error('password')
                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-slate-700 bg-slate-950 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-900">
                <label for="remember" class="ml-2 text-sm text-slate-400 select-none cursor-pointer">Beni Hatırla</label>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-violet-600 hover:brightness-110 active:scale-[0.98] text-white font-semibold py-3 rounded-xl shadow-lg shadow-indigo-500/20 transition-all">Giriş Yap</button>
        </form>
    </div>
</body>
</html>
