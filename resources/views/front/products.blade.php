<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->title }} - E-Ticaret</title>
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
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 2rem;
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }
        }

        aside {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            height: fit-content;
        }

        aside h3 {
            margin-bottom: 1rem;
            font-size: 1.2rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.5rem;
        }

        .category-list {
            list-style: none;
        }

        .category-list li {
            margin-bottom: 0.75rem;
        }

        .category-list a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.3s;
        }

        .category-list a:hover {
            color: var(--primary);
        }

        .header {
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1rem;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .product-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.15);
        }

        .product-image {
            position: relative;
            height: 200px;
            background: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
        }

        .out-of-stock-overlay {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.85);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ef4444;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            backdrop-filter: blur(2px);
        }

        .product-info {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .product-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-main);
            text-decoration: none;
        }

        .product-price {
            font-size: 1.25rem;
            color: var(--primary);
            font-weight: 700;
            margin-top: auto;
            margin-bottom: 1rem;
        }

        .btn {
            display: inline-block;
            text-align: center;
            background: var(--primary);
            color: white;
            padding: 0.75rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn:hover {
            background: var(--primary-hover);
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
        <aside>
            <h3>Kategoriler</h3>
            <ul class="category-list">
                @foreach ($categories as $cat)
                    <li><a href="{{ route('category.products', $cat->id) }}">{{ $cat->title }}</a></li>
                @endforeach
            </ul>
        </aside>

        <main>
            <div class="header" style="margin-top: 0;">
                <h2>Kategori: {{ $category->title }}</h2>
            </div>
            @if($products->isEmpty())
                <p style="color: var(--text-muted); font-size: 1.1rem;">Bu kategoride henüz ürün bulunmamaktadır.</p>
            @else
                <div class="products-grid">
                    @foreach ($products as $product)
                        <div class="product-card">
                            <div class="product-image">
                                @if ($product->image)
                                    <img src="{{ $product->image }}" alt="{{ $product->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    Görsel Yok
                                @endif
                                @if ($product->stock <= 0)
                                    <div class="out-of-stock-overlay">Tükendi</div>
                                @endif
                            </div>
                            <div class="product-info">
                                <a href="{{ route('product.detail', $product->id) }}" class="product-title">{{ $product->title }}</a>
                                <div class="product-price">{{ number_format($product->price, 2) }} TL</div>
                                <a href="{{ route('product.detail', $product->id) }}" class="btn">Detayı Gör</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>

</body>
</html>
