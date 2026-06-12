<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siparişi Tamamla - E-Ticaret</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: rgba(255, 255, 255, 0.08);
            --error-color: #f43f5e;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
            padding-bottom: 3rem;
        }

        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem 2rem;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        nav a {
            color: var(--text-main);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        nav a:hover {
            color: var(--primary);
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .container {
            max-width: 800px;
            margin: 3rem auto;
            padding: 0 1rem;
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 2.5rem;
            backdrop-filter: blur(20px);
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        @media(max-width: 600px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group-full {
            grid-column: 1 / -1;
        }

        .form-label {
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
        }

        .form-input {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: white;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            border-color: var(--primary);
        }

        .error-message {
            color: var(--error-color);
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        .btn-submit {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, var(--primary) 0%, #8b5cf6 100%);
            border: none;
            border-radius: 12px;
            color: white;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, filter 0.2s;
            margin-top: 1.5rem;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }
    </style>
</head>
<body>

    <nav>
        <a href="{{ route('home') }}" style="font-size: 1.5rem; font-weight: 700;">E-Ticaret</a>
        <div class="nav-links">
            <a href="{{ route('home') }}">Ana Sayfa</a>
            @auth
                <a href="{{ route('cart.index') }}">Sepetim</a>
                @if (auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}">Yönetim Paneli</a>
                @endif
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: var(--text-main); font-weight: 500; cursor: pointer; font-size: 1rem; font-family: inherit;">Çıkış Yap</button>
                </form>
            @else
                <a href="{{ route('login') }}">Giriş Yap</a>
            @endauth
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <h1>Teslimat ve Ödeme Bilgileri</h1>

            <form action="{{ route('checkout') }}" method="POST" class="form-grid">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">Ad Soyad</label>
                    <input type="text" name="name" id="name" class="form-input" placeholder="John Doe" value="{{ old('name') }}" required>
                    @error('name') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Telefon</label>
                    <input type="text" name="phone" id="phone" class="form-input" placeholder="05551234567" value="{{ old('phone') }}" required>
                    @error('phone') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group form-group-full">
                    <label for="address1" class="form-label">Adres Satırı 1</label>
                    <input type="text" name="address1" id="address1" class="form-input" placeholder="Mahalle, Cadde, Sokak No/Daire" value="{{ old('address1') }}" required>
                    @error('address1') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group form-group-full">
                    <label for="address2" class="form-label">Adres Satırı 2 (İsteğe Bağlı)</label>
                    <input type="text" name="address2" id="address2" class="form-input" placeholder="Bina, Blok, Site Bilgisi vb." value="{{ old('address2') }}">
                    @error('address2') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="city" class="form-label">Şehir</label>
                    <input type="text" name="city" id="city" class="form-input" placeholder="İstanbul" value="{{ old('city') }}" required>
                    @error('city') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="state" class="form-label">Bölge / Eyalet (İsteğe Bağlı)</label>
                    <input type="text" name="state" id="state" class="form-input" placeholder="Marmara" value="{{ old('state') }}">
                    @error('state') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="country" class="form-label">Ülke</label>
                    <input type="text" name="country" id="country" class="form-input" placeholder="Türkiye" value="{{ old('country', 'Türkiye') }}" required>
                    @error('country') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="zipcode" class="form-label">Posta Kodu</label>
                    <input type="text" name="zipcode" id="zipcode" class="form-input" placeholder="34000" value="{{ old('zipcode') }}" required>
                    @error('zipcode') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group form-group-full">
                    <label for="payment_method" class="form-label">Ödeme Yöntemi</label>
                    <select name="payment_method" id="payment_method" class="form-input" style="appearance: none; background-image: linear-gradient(45deg, transparent 50%, gray 50%), linear-gradient(135deg, gray 50%, transparent 50%); background-position: calc(100% - 20px) calc(1em + 2px), calc(100% - 15px) calc(1em + 2px); background-size: 5px 5px, 5px 5px; background-repeat: no-repeat; padding-right: 3rem;" required>
                        <option value="Credit Card" class="bg-slate-900">Kredi Kartı</option>
                        <option value="Cash" class="bg-slate-900">Kapıda Ödeme</option>
                    </select>
                    @error('payment_method') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn-submit">Siparişi Tamamla ve Öde</button>
            </form>
        </div>
    </div>

</body>
</html>
