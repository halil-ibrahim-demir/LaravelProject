<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->title }} - E-Ticaret</title>
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
            --success-color: #10b981;
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
            max-width: 1000px;
            margin: 3rem auto;
            padding: 0 1rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            overflow: hidden;
            backdrop-filter: blur(20px);
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                padding: 1.5rem;
            }
        }

        .gallery {
            background: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            min-height: 400px;
        }

        .details-panel {
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
        }

        .category-badge {
            display: inline-block;
            background: rgba(99, 102, 241, 0.15);
            color: var(--primary);
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            width: fit-content;
            margin-bottom: 1rem;
        }

        h1 {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .description {
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .price-section {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .original-price {
            font-size: 1.25rem;
            text-decoration: line-through;
            color: var(--text-muted);
            font-weight: 400;
        }

        .stock-status {
            font-size: 0.9rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stock-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--success-color);
        }

        .cart-form {
            display: flex;
            gap: 1rem;
            margin-top: auto;
        }

        .quantity-input {
            width: 80px;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: white;
            padding: 0.75rem;
            text-align: center;
            font-size: 1rem;
            outline: none;
        }

        .quantity-input:focus {
            border-color: var(--primary);
        }

        .btn-submit {
            flex-grow: 1;
            background: linear-gradient(135deg, var(--primary) 0%, #8b5cf6 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, filter 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
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
                <a href="{{ route('cart.index') }}">Sepetim{!! auth()->user()->carts()->sum('quantity') > 0 ? '<span style="color: #10b981; font-weight: 600;"> (' . auth()->user()->carts()->sum('quantity') . ')</span>' : '' !!}</a>
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
        <div class="gallery">
            @if ($product->image)
                <img src="{{ $product->image }}" alt="{{ $product->title }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                Görsel Yok
            @endif
        </div>

        <div class="details-panel">
            <span class="category-badge">{{ $product->category->title }}</span>
            <h1>{{ $product->title }}</h1>
            
            <p class="description">
                {{ $product->description ?? 'Bu ürün için detaylı açıklama bulunmamaktadır.' }}
            </p>

            <div class="price-section">
                <span>{{ number_format($product->price, 2) }} TL</span>
            </div>

            @if ($product->stock > 0)
                <div class="stock-status">
                    <span class="stock-dot" style="background: var(--success-color);"></span>
                    <span>Stokta Var ({{ $product->stock }} adet)</span>
                </div>

                <form action="{{ route('cart.store') }}" method="POST" class="cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="quantity-input" required>
                    <button type="submit" class="btn-submit">Sepete Ekle</button>
                </form>
            @else
                <div class="stock-status">
                    <span class="stock-dot" style="background: #ef4444;"></span>
                    <span style="color: #ef4444; font-weight: 600;">Tükendi</span>
                </div>

                <button class="btn-submit" style="background: #1e293b; color: var(--text-muted); cursor: not-allowed;" disabled>Stok Tükendi</button>
            @endif
        </div>
    </div>

</body>
</html>
