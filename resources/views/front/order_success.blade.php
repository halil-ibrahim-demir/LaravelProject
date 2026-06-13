<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Başarılı - E-Ticaret</title>
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
            text-align: center;
        }

        .success-icon-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            background: rgba(16, 185, 129, 0.1);
            border: 2px solid var(--success-color);
            border-radius: 50%;
            margin-bottom: 1.5rem;
            color: var(--success-color);
            animation: pulse-glow 2s infinite ease-in-out;
        }

        @keyframes pulse-glow {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 20px 10px rgba(16, 185, 129, 0.2);
            }
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: var(--text-muted);
            margin-bottom: 2.5rem;
            font-size: 1.1rem;
        }

        .section-title {
            text-align: left;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            text-align: left;
            margin-bottom: 2.5rem;
        }

        @media(max-width: 600px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }

        .detail-item {
            background: rgba(15, 23, 42, 0.4);
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .detail-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            font-size: 1rem;
            font-weight: 500;
        }

        .order-items {
            text-align: left;
            margin-bottom: 2.5rem;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: rgba(15, 23, 42, 0.3);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            margin-bottom: 0.75rem;
        }

        .item-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .item-name {
            font-weight: 500;
        }

        .item-qty {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .item-price {
            font-weight: 600;
            color: var(--primary);
        }

        .btn-continue {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary) 0%, #8b5cf6 100%);
            color: white;
            text-decoration: none;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: transform 0.2s, filter 0.2s;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-continue:hover {
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
        <div class="card">
            <div class="success-icon-container">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 40px; height: 40px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>

            <h1>Siparişiniz Başarıyla Alındı!</h1>
            <p class="subtitle">Bizi tercih ettiğiniz için teşekkür ederiz. Sipariş detaylarınız aşağıdadır.</p>

            <h2 class="section-title">Sipariş Bilgileri</h2>
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Alıcı</div>
                    <div class="detail-value">{{ $order->name }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Kargo Takip No</div>
                    <div class="detail-value" style="color: var(--primary); font-weight: 600;">{{ $order->tracking_no }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Telefon</div>
                    <div class="detail-value">{{ $order->phone }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Ödeme Yöntemi</div>
                    <div class="detail-value">{{ $order->payment_method }}</div>
                </div>
                <div class="detail-item" style="grid-column: 1 / -1;">
                    <div class="detail-label">Teslimat Adresi</div>
                    <div class="detail-value">{{ $order->address1 }} {{ $order->address2 }} {{ $order->city }} / {{ $order->country }}</div>
                </div>
            </div>

            <h2 class="section-title">Sipariş Edilen Ürünler</h2>
            <div class="order-items">
                @foreach ($order->items as $item)
                    <div class="order-item">
                        <div class="item-info">
                            <div>
                                <div class="item-name">{{ $item->product->title }}</div>
                                <div class="item-qty">{{ $item->quantity }} Adet</div>
                            </div>
                        </div>
                        <div class="item-price">{{ number_format($item->price, 2) }} TL</div>
                    </div>
                @endforeach
            </div>

            <div style="font-size: 1.25rem; font-weight: 700; display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; padding: 1rem; border-top: 1px dashed var(--border-color); border-bottom: 1px dashed var(--border-color);">
                <span>Toplam Tutar:</span>
                <span style="color: var(--success-color);">{{ number_format($order->total_amount, 2) }} TL</span>
            </div>

            <a href="{{ route('home') }}" class="btn-continue">Alışverişe Devam Et</a>
        </div>
    </div>

</body>
</html>
