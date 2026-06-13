<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Paneli - Admin Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">

    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col justify-between p-6">
            <div>
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center font-bold text-lg text-white shadow-lg shadow-indigo-500/30">A</div>
                    <div>
                        <h2 class="font-bold text-lg">Yönetim Paneli</h2>
                        <span class="text-xs text-slate-400">Admin Paneline Hoş Geldiniz!</span>
                    </div>
                </div>
                
                <nav class="space-y-2">
                    <button onclick="switchTab('overview')" id="btn-overview" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-600 text-white font-medium transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                        <span>Genel Bakış</span>
                    </button>
                    <button onclick="switchTab('orders')" id="btn-orders" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-slate-200 font-medium transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>Siparişler</span>
                    </button>
                    <button onclick="switchTab('products')" id="btn-products" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-slate-200 font-medium transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Ürünler</span>
                    </button>
                    <button onclick="switchTab('categories')" id="btn-categories" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-slate-200 font-medium transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <span>Kategoriler</span>
                    </button>
                </nav>
            </div>

            <div>
                <div class="p-4 bg-slate-950 border border-slate-800 rounded-2xl mb-4">
                    <p class="text-xs text-slate-400">Aktif Yönetici</p>
                    <h4 class="font-semibold text-sm truncate">{{ auth()->user()->name }}</h4>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('home') }}" class="flex-1 text-center py-2.5 bg-slate-800 hover:bg-slate-700 text-xs font-semibold rounded-xl transition">Mağaza</a>
                    <form action="{{ route('logout') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-2.5 bg-red-900/60 hover:bg-red-800 text-red-200 text-xs font-semibold rounded-xl transition">Çıkış Yap</button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto p-8 relative">
            <div id="toast" class="fixed top-8 right-8 z-50 transform translate-y-[-100px] opacity-0 transition-all duration-300 px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3">
                <span id="toast-message"></span>
            </div>

            <div id="tab-overview" class="space-y-8 block">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight">Genel Bakış</h1>
                        <p class="text-slate-400 text-sm mt-1">Mağaza stok durumları ve temel göstergeler.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 backdrop-blur-md flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-xs uppercase tracking-wider font-semibold">Toplam Ürün Çeşidi</p>
                            <h3 id="stat-products-count" class="text-3xl font-extrabold mt-2 text-indigo-400">-</h3>
                        </div>
                        <div class="p-3 bg-indigo-500/10 text-indigo-400 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                    </div>

                    <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 backdrop-blur-md flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-xs uppercase tracking-wider font-semibold">Gelen Siparişler</p>
                            <h3 id="stat-orders-count" class="text-3xl font-extrabold mt-2 text-emerald-400">-</h3>
                        </div>
                        <div class="p-3 bg-emerald-500/10 text-emerald-400 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                    </div>

                    <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 backdrop-blur-md flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-xs uppercase tracking-wider font-semibold">Düşük Stoklu Ürünler</p>
                            <h3 id="stat-low-stock-count" class="text-3xl font-extrabold mt-2 text-amber-400">-</h3>
                        </div>
                        <div class="p-3 bg-amber-500/10 text-amber-400 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden backdrop-blur-md">
                    <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                        <h2 class="font-bold text-lg">Stok Durum Tablosu</h2>
                        <span class="text-xs text-slate-400">Tüm ürünler ve kalan stok adetleri</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-950/40 border-b border-slate-800 text-slate-400 text-xs uppercase font-semibold">
                                    <th class="p-4">Ürün</th>
                                    <th class="p-4">Kategori</th>
                                    <th class="p-4">Fiyat</th>
                                    <th class="p-4">Kalan Stok</th>
                                    <th class="p-4">Durum</th>
                                </tr>
                            </thead>
                            <tbody id="overview-stock-table" class="divide-y divide-slate-800/40">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="tab-orders" class="space-y-8 hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight">Sipariş Yönetimi</h1>
                        <p class="text-slate-400 text-sm mt-1">Gelen siparişlerin durumlarını yönetin ve kargo takibi ekleyin.</p>
                    </div>
                </div>

                <div class="bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden backdrop-blur-md">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-950/40 border-b border-slate-800 text-slate-400 text-xs uppercase font-semibold">
                                    <th class="p-4">Sipariş No</th>
                                    <th class="p-4">Müşteri</th>
                                    <th class="p-4">Toplam Tutar</th>
                                    <th class="p-4">Ödeme Yöntemi</th>
                                    <th class="p-4">Tarih</th>
                                    <th class="p-4">Durum</th>
                                    <th class="p-4 text-right">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody id="orders-table" class="divide-y divide-slate-800/40">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="tab-products" class="space-y-8 hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight">Ürün Yönetimi</h1>
                        <p class="text-slate-400 text-sm mt-1">Yeni ürün ekleyin, güncelleyin veya kaldırın.</p>
                    </div>
                    <button onclick="openProductModal()" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/25 transition">
                        + Yeni Ürün Ekle
                    </button>
                </div>

                <div class="bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden backdrop-blur-md">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-950/40 border-b border-slate-800 text-slate-400 text-xs uppercase font-semibold">
                                    <th class="p-4">Görsel</th>
                                    <th class="p-4">Başlık</th>
                                    <th class="p-4">Kategori</th>
                                    <th class="p-4">Fiyat</th>
                                    <th class="p-4">Stok</th>
                                    <th class="p-4">Durum</th>
                                    <th class="p-4 text-right">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody id="products-table" class="divide-y divide-slate-800/40">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="tab-categories" class="space-y-8 hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight">Kategori Yönetimi</h1>
                        <p class="text-slate-400 text-sm mt-1">Yeni kategori ekleyin veya mevcut kategorileri yönetin.</p>
                    </div>
                    <button onclick="openCategoryModal()" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/25 transition">
                        + Yeni Kategori Ekle
                    </button>
                </div>

                <div class="bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden backdrop-blur-md">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-950/40 border-b border-slate-800 text-slate-400 text-xs uppercase font-semibold">
                                    <th class="p-4">Kategori Adı</th>
                                    <th class="p-4">Üst Kategori</th>
                                    <th class="p-4">Durum</th>
                                    <th class="p-4 text-right">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody id="categories-table" class="divide-y divide-slate-800/40">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="order-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-8 space-y-6">
            <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                <h3 id="modal-order-title" class="text-xl font-bold text-indigo-400">Sipariş Detayları</h3>
                <button onclick="closeOrderModal()" class="text-slate-400 hover:text-slate-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm" id="modal-order-details">
            </div>

            <div class="border-t border-b border-slate-800 py-4">
                <h4 class="font-bold text-sm mb-3">Sipariş Edilen Ürünler</h4>
                <div class="space-y-3" id="modal-order-items">
                </div>
            </div>

            <form id="order-update-form" class="space-y-4">
                <input type="hidden" id="update-order-id">
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-400 mb-2">Sipariş Durumu</label>
                        <select id="update-order-status" class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500">
                            <option value="pending">Beklemede (Pending)</option>
                            <option value="accepted">Kabul Edildi (Accepted)</option>
                            <option value="shipping">Kargoda (Shipping)</option>
                            <option value="completed">Tamamlandı (Completed)</option>
                            <option value="canceled">İptal Edildi (Canceled)</option>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-400 mb-2">Kargo Takip No</label>
                        <input type="text" id="update-order-tracking" class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500" placeholder="Kargo Takip Numarası">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeOrderModal()" class="px-5 py-3 bg-slate-800 hover:bg-slate-700 font-semibold rounded-xl transition">Kapat</button>
                    <button type="submit" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl transition">Güncelle ve Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <div id="product-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-xl max-h-[90vh] overflow-y-auto p-8 space-y-6">
            <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                <h3 id="modal-product-title" class="text-xl font-bold text-indigo-400">Ürün Ekle / Düzenle</h3>
                <button onclick="closeProductModal()" class="text-slate-400 hover:text-slate-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="product-form" class="space-y-4">
                <input type="hidden" id="product-id">
                
                <div class="flex flex-col">
                    <label class="text-xs font-semibold text-slate-400 mb-2">Kategori</label>
                    <select id="product-category-id" class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500" required>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-xs font-semibold text-slate-400 mb-2">Ürün Başlığı</label>
                    <input type="text" id="product-title" class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-400 mb-2">Fiyat (TL)</label>
                        <input type="number" step="0.01" min="0" id="product-price" class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500" required>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-400 mb-2">Stok Miktarı</label>
                        <input type="number" min="0" id="product-stock" class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500" required>
                    </div>
                </div>

                <div class="flex flex-col">
                    <label class="text-xs font-semibold text-slate-400 mb-2">Kısa Açıklama</label>
                    <textarea id="product-description" class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500" rows="3"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-400 mb-2">Görsel URL (İsteğe Bağlı)</label>
                        <input type="text" id="product-image" class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500">
                        <div class="mt-2 w-full h-24 rounded-xl bg-slate-950 border border-slate-800 flex items-center justify-center overflow-hidden">
                            <img id="product-image-preview" class="w-full h-full object-contain hidden">
                            <span id="product-image-preview-placeholder" class="text-xs text-slate-500">Önizleme Yok</span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-400 mb-2">Durum</label>
                        <select id="product-status" class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500">
                            <option value="1">Aktif</option>
                            <option value="0">Pasif</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeProductModal()" class="px-5 py-3 bg-slate-800 hover:bg-slate-700 font-semibold rounded-xl transition">Kapat</button>
                    <button type="submit" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl transition">Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <div id="category-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-xl max-h-[90vh] overflow-y-auto p-8 space-y-6">
            <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                <h3 class="text-xl font-bold text-indigo-400">Yeni Kategori Ekle</h3>
                <button onclick="closeCategoryModal()" class="text-slate-400 hover:text-slate-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="category-form" class="space-y-4">
                <div class="flex flex-col">
                    <label class="text-xs font-semibold text-slate-400 mb-2">Kategori Adı</label>
                    <input type="text" id="category-title" class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500" required>
                </div>

                <div class="flex flex-col">
                    <label class="text-xs font-semibold text-slate-400 mb-2">Üst Kategori (İsteğe Bağlı)</label>
                    <select id="category-parent-id" class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500">
                        <option value="" class="bg-slate-900 text-slate-100">Yok</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-xs font-semibold text-slate-400 mb-2">Durum</label>
                    <select id="category-status" class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500">
                        <option value="1">Aktif</option>
                        <option value="0">Pasif</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeCategoryModal()" class="px-5 py-3 bg-slate-800 hover:bg-slate-700 font-semibold rounded-xl transition">Kapat</button>
                    <button type="submit" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl transition">Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        let categoriesList = [];

        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toast-message');
            toastMsg.innerText = message;
            if (isError) {
                toast.className = 'fixed top-8 right-8 z-50 transform transition-all duration-300 px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 bg-red-900 border border-red-700 text-red-100 opacity-100 translate-y-0';
            } else {
                toast.className = 'fixed top-8 right-8 z-50 transform transition-all duration-300 px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 bg-emerald-900 border border-emerald-700 text-emerald-100 opacity-100 translate-y-0';
            }
            setTimeout(() => {
                toast.className = 'fixed top-8 right-8 z-50 transform translate-y-[-100px] opacity-0 transition-all duration-300 px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3';
            }, 3000);
        }

        function switchTab(tabId) {
            document.querySelectorAll('main > div[id^="tab-"]').forEach(el => el.classList.add('hidden'));
            document.getElementById(`tab-${tabId}`).classList.remove('hidden');

            document.querySelectorAll('aside nav button').forEach(el => {
                el.classList.remove('bg-indigo-600', 'text-white');
                el.classList.add('text-slate-400', 'hover:bg-slate-800', 'hover:text-slate-200');
            });
            const activeBtn = document.getElementById(`btn-${tabId}`);
            activeBtn.classList.remove('text-slate-400', 'hover:bg-slate-800', 'hover:text-slate-200');
            activeBtn.classList.add('bg-indigo-600', 'text-white');

            if (tabId === 'overview') loadOverview();
            if (tabId === 'orders') loadOrders();
            if (tabId === 'products') loadProducts();
            if (tabId === 'categories') loadCategories();
        }

        async function loadOverview() {
            try {
                const prodRes = await fetch('/admin/products', { headers });
                const products = await prodRes.json();

                const ordRes = await fetch('/admin/orders', { headers });
                const orders = await ordRes.json();

                document.getElementById('stat-products-count').innerText = products.length;
                document.getElementById('stat-orders-count').innerText = orders.length;

                let lowStockCount = 0;
                const stockTable = document.getElementById('overview-stock-table');
                stockTable.innerHTML = '';

                products.forEach(p => {
                    if (p.stock < 10) lowStockCount++;

                    let stockBadge = '';
                    if (p.stock === 0) {
                        stockBadge = '<span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-red-950 text-red-400 border border-red-900">Tükendi</span>';
                    } else if (p.stock < 10) {
                        stockBadge = '<span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-amber-950 text-amber-400 border border-amber-900">Kritik</span>';
                    } else {
                        stockBadge = '<span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-950 text-emerald-400 border border-emerald-900">Yeterli</span>';
                    }

                    const row = `
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="p-4 font-semibold text-sm">${p.title}</td>
                            <td class="p-4 text-slate-400 text-sm">${p.category ? p.category.title : '-'}</td>
                            <td class="p-4 text-sm font-semibold">${parseFloat(p.price).toFixed(2)} TL</td>
                            <td class="p-4 text-sm font-bold">${p.stock}</td>
                            <td class="p-4">${stockBadge}</td>
                        </tr>
                    `;
                    stockTable.insertAdjacentHTML('beforeend', row);
                });

                document.getElementById('stat-low-stock-count').innerText = lowStockCount;
            } catch (err) {
                showToast('İstatistikler yüklenirken hata oluştu', true);
            }
        }

        async function loadOrders() {
            try {
                const res = await fetch('/admin/orders', { headers });
                const orders = await res.json();
                const table = document.getElementById('orders-table');
                table.innerHTML = '';

                orders.forEach(o => {
                    let statusBadge = '';
                    if (o.status === 'pending') statusBadge = '<span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-amber-950 text-amber-400 border border-amber-900">Beklemede</span>';
                    else if (o.status === 'accepted') statusBadge = '<span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-blue-950 text-blue-400 border border-blue-900">Kabul Edildi</span>';
                    else if (o.status === 'shipping') statusBadge = '<span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-indigo-950 text-indigo-400 border border-indigo-900">Kargoda</span>';
                    else if (o.status === 'completed') statusBadge = '<span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-950 text-emerald-400 border border-emerald-900">Tamamlandı</span>';
                    else statusBadge = '<span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-red-950 text-red-400 border border-red-900">İptal Edildi</span>';

                    const row = `
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="p-4 text-sm font-semibold text-indigo-400">#${o.id}</td>
                            <td class="p-4 text-sm font-medium">${o.name}</td>
                            <td class="p-4 text-sm font-bold">${parseFloat(o.total_amount).toFixed(2)} TL</td>
                            <td class="p-4 text-slate-400 text-sm">${o.payment_method === 'Credit Card' ? 'Kredi Kartı' : 'Kapıda Ödeme'}</td>
                            <td class="p-4 text-slate-400 text-sm">${new Date(o.created_at).toLocaleDateString('tr-TR')}</td>
                            <td class="p-4">${statusBadge}</td>
                            <td class="p-4 text-right">
                                <button onclick="viewOrderDetails(${o.id})" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-lg transition">Yönet</button>
                            </td>
                        </tr>
                    `;
                    table.insertAdjacentHTML('beforeend', row);
                });
            } catch (err) {
                showToast('Siparişler yüklenirken hata oluştu', true);
            }
        }

        async function viewOrderDetails(id) {
            try {
                const res = await fetch(`/admin/orders/${id}`, { headers });
                const order = await res.json();

                document.getElementById('modal-order-title').innerText = `Sipariş Yönetimi #${order.id}`;
                document.getElementById('update-order-id').value = order.id;
                document.getElementById('update-order-status').value = order.status;
                document.getElementById('update-order-tracking').value = order.tracking_no || '';

                const detailsDiv = document.getElementById('modal-order-details');
                detailsDiv.innerHTML = `
                    <div>
                        <span class="text-slate-400 block text-xs">Müşteri</span>
                        <strong>${order.name}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs">Telefon</span>
                        <strong>${order.phone}</strong>
                    </div>
                    <div class="col-span-2">
                        <span class="text-slate-400 block text-xs">Adres</span>
                        <strong>${order.address1} ${order.address2 || ''} ${order.city} / ${order.country}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs">Toplam Tutar</span>
                        <strong class="text-emerald-400">${parseFloat(order.total_amount).toFixed(2)} TL</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs">Ödeme</span>
                        <strong>${order.payment_method}</strong>
                    </div>
                `;

                const itemsDiv = document.getElementById('modal-order-items');
                itemsDiv.innerHTML = '';
                order.items.forEach(item => {
                    const row = `
                        <div class="flex items-center justify-between bg-slate-950 p-3 rounded-xl border border-slate-850">
                            <div>
                                <span class="font-medium text-sm block">${item.product ? item.product.title : 'Silinmiş Ürün'}</span>
                                <span class="text-xs text-slate-400">${item.quantity} Adet</span>
                            </div>
                            <span class="text-sm font-semibold">${parseFloat(item.price).toFixed(2)} TL</span>
                        </div>
                    `;
                    itemsDiv.insertAdjacentHTML('beforeend', row);
                });

                document.getElementById('order-modal').classList.remove('hidden');
                document.getElementById('order-modal').classList.add('flex');
            } catch (err) {
                showToast('Sipariş detayları yüklenemedi', true);
            }
        }

        function closeOrderModal() {
            document.getElementById('order-modal').classList.remove('flex');
            document.getElementById('order-modal').classList.add('hidden');
        }

        document.getElementById('order-update-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('update-order-id').value;
            const status = document.getElementById('update-order-status').value;
            const tracking_no = document.getElementById('update-order-tracking').value;

            try {
                const res = await fetch(`/admin/orders/${id}`, {
                    method: 'PUT',
                    headers,
                    body: JSON.stringify({ status, tracking_no })
                });

                if (res.ok) {
                    showToast('Sipariş başarıyla güncellendi');
                    closeOrderModal();
                    loadOrders();
                } else {
                    const err = await res.json();
                    showToast(err.message || 'Güncelleme hatası', true);
                }
            } catch (err) {
                showToast('Bağlantı hatası', true);
            }
        });

        async function loadProducts() {
            try {
                const res = await fetch('/admin/products', { headers });
                const products = await res.json();
                const table = document.getElementById('products-table');
                table.innerHTML = '';

                products.forEach(p => {
                    const row = `
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="p-4">
                                <div class="w-10 h-10 rounded-lg bg-slate-800 flex items-center justify-center overflow-hidden text-xs text-slate-400">
                                    ${p.image ? `<img src="${p.image}" class="w-full h-full object-cover">` : 'Görsel'}
                                </div>
                            </td>
                            <td class="p-4 text-sm font-semibold">${p.title}</td>
                            <td class="p-4 text-slate-400 text-sm">${p.category ? p.category.title : '-'}</td>
                            <td class="p-4 text-sm font-semibold text-indigo-400">${parseFloat(p.price).toFixed(2)} TL</td>
                            <td class="p-4 text-sm font-bold">${p.stock}</td>
                            <td class="p-4">
                                ${p.status ? '<span class="px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-950 text-emerald-400 border border-emerald-900">Aktif</span>' : '<span class="px-2 py-0.5 text-[10px] font-bold rounded bg-red-950 text-red-400 border border-red-900">Pasif</span>'}
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <button onclick="editProduct(${p.id})" class="px-2.5 py-1.5 bg-indigo-900/60 hover:bg-indigo-800 text-indigo-200 text-xs font-semibold rounded-lg transition">Düzenle</button>
                                <button onclick="deleteProduct(${p.id})" class="px-2.5 py-1.5 bg-red-900/60 hover:bg-red-800 text-red-200 text-xs font-semibold rounded-lg transition">Sil</button>
                            </td>
                        </tr>
                    `;
                    table.insertAdjacentHTML('beforeend', row);
                });
            } catch (err) {
                showToast('Ürünler yüklenirken hata oluştu', true);
            }
        }

        async function openProductModal(isEdit = false) {
            try {
                const res = await fetch('/admin/products/create', { headers });
                categoriesList = await res.json();
                
                const select = document.getElementById('product-category-id');
                select.innerHTML = '';
                categoriesList.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id;
                    opt.innerText = c.title;
                    opt.className = 'bg-slate-900 text-slate-100';
                    select.appendChild(opt);
                });

                if (!isEdit) {
                    document.getElementById('modal-product-title').innerText = 'Yeni Ürün Ekle';
                    document.getElementById('product-id').value = '';
                    document.getElementById('product-title').value = '';
                    document.getElementById('product-price').value = '';
                    document.getElementById('product-stock').value = '';
                    document.getElementById('product-description').value = '';
                    document.getElementById('product-image').value = '';
                    document.getElementById('product-status').value = '1';
                    
                    const preview = document.getElementById('product-image-preview');
                    const placeholder = document.getElementById('product-image-preview-placeholder');
                    preview.classList.add('hidden');
                    placeholder.classList.remove('hidden');

                    document.getElementById('product-modal').classList.remove('hidden');
                    document.getElementById('product-modal').classList.add('flex');
                }
            } catch (err) {
                showToast('Kategoriler yüklenemedi', true);
            }
        }

        function closeProductModal() {
            document.getElementById('product-modal').classList.remove('flex');
            document.getElementById('product-modal').classList.add('hidden');
        }

        async function editProduct(id) {
            try {
                await openProductModal(true);
                const res = await fetch(`/admin/products/${id}/edit`, { headers });
                const data = await res.json();
                const p = data.product;

                document.getElementById('modal-product-title').innerText = 'Ürünü Düzenle';
                document.getElementById('product-id').value = p.id;
                document.getElementById('product-category-id').value = p.category_id;
                document.getElementById('product-title').value = p.title;
                document.getElementById('product-price').value = p.price;
                document.getElementById('product-stock').value = p.stock;
                document.getElementById('product-description').value = p.description || '';
                document.getElementById('product-image').value = p.image || '';
                document.getElementById('product-status').value = p.status ? '1' : '0';

                const preview = document.getElementById('product-image-preview');
                const placeholder = document.getElementById('product-image-preview-placeholder');
                if (p.image) {
                    preview.src = p.image;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                } else {
                    preview.classList.add('hidden');
                    placeholder.classList.remove('hidden');
                }

                document.getElementById('product-modal').classList.remove('hidden');
                document.getElementById('product-modal').classList.add('flex');
            } catch (err) {
                showToast('Ürün bilgileri yüklenemedi', true);
            }
        }

        document.getElementById('product-image').addEventListener('input', (e) => {
            const url = e.target.value;
            const preview = document.getElementById('product-image-preview');
            const placeholder = document.getElementById('product-image-preview-placeholder');
            if (url) {
                preview.src = url;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        });

        document.getElementById('product-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('product-id').value;
            const category_id = document.getElementById('product-category-id').value;
            const title = document.getElementById('product-title').value;
            const price = document.getElementById('product-price').value;
            const stock = document.getElementById('product-stock').value;
            const description = document.getElementById('product-description').value;
            const image = document.getElementById('product-image').value;
            const status = document.getElementById('product-status').value === '1';

            const payload = { category_id, title, price, stock, description, image, status };

            const url = id ? `/admin/products/${id}` : '/admin/products';
            const method = id ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method,
                    headers,
                    body: JSON.stringify(payload)
                });

                if (res.ok) {
                    showToast(id ? 'Ürün başarıyla güncellendi' : 'Ürün başarıyla eklendi');
                    closeProductModal();
                    loadProducts();
                } else {
                    const err = await res.json();
                    showToast(err.message || 'İşlem başarısız oldu', true);
                }
            } catch (err) {
                showToast('Bağlantı hatası', true);
            }
        });

        async function deleteProduct(id) {
            if (!confirm('Bu ürünü silmek istediğinize emin misiniz?')) return;

            try {
                const res = await fetch(`/admin/products/${id}`, {
                    method: 'DELETE',
                    headers
                });

                if (res.ok) {
                    showToast('Ürün silindi');
                    loadProducts();
                } else {
                    showToast('Ürün silinirken hata oluştu', true);
                }
            } catch (err) {
                showToast('Bağlantı hatası', true);
            }
        }

        async function loadCategories() {
            try {
                const res = await fetch('/admin/products/create', { headers });
                const categories = await res.json();
                const table = document.getElementById('categories-table');
                table.innerHTML = '';

                categories.forEach(c => {
                    const row = `
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="p-4 text-sm font-semibold">${c.title}</td>
                            <td class="p-4 text-slate-400 text-sm">${c.parent ? c.parent.title : '-'}</td>
                            <td class="p-4">
                                ${c.status ? '<span class="px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-950 text-emerald-400 border border-emerald-900">Aktif</span>' : '<span class="px-2 py-0.5 text-[10px] font-bold rounded bg-red-950 text-red-400 border border-red-900">Pasif</span>'}
                            </td>
                            <td class="p-4 text-right">
                                <button onclick="deleteCategory(${c.id})" class="px-2.5 py-1.5 bg-red-900/60 hover:bg-red-800 text-red-200 text-xs font-semibold rounded-lg transition">Sil</button>
                            </td>
                        </tr>
                    `;
                    table.insertAdjacentHTML('beforeend', row);
                });
            } catch (err) {
                showToast('Kategoriler yüklenirken hata oluştu', true);
            }
        }

        async function openCategoryModal() {
            try {
                const res = await fetch('/admin/products/create', { headers });
                const categories = await res.json();
                const select = document.getElementById('category-parent-id');
                select.innerHTML = '<option value="" class="bg-slate-900 text-slate-100">Yok</option>';
                categories.forEach(c => {
                    if (!c.parent_id) {
                        const opt = document.createElement('option');
                        opt.value = c.id;
                        opt.innerText = c.title;
                        opt.className = 'bg-slate-900 text-slate-100';
                        select.appendChild(opt);
                    }
                });

                document.getElementById('category-title').value = '';
                document.getElementById('category-parent-id').value = '';
                document.getElementById('category-status').value = '1';

                document.getElementById('category-modal').classList.remove('hidden');
                document.getElementById('category-modal').classList.add('flex');
            } catch (err) {
                showToast('Kategoriler yüklenemedi', true);
            }
        }

        function closeCategoryModal() {
            document.getElementById('category-modal').classList.remove('flex');
            document.getElementById('category-modal').classList.add('hidden');
        }

        document.getElementById('category-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const title = document.getElementById('category-title').value;
            const parent_id = document.getElementById('category-parent-id').value || null;
            const status = document.getElementById('category-status').value === '1';

            try {
                const res = await fetch('/admin/categories', {
                    method: 'POST',
                    headers,
                    body: JSON.stringify({ title, parent_id, status })
                });

                if (res.ok) {
                    showToast('Kategori başarıyla eklendi');
                    closeCategoryModal();
                    loadCategories();
                } else {
                    const err = await res.json();
                    showToast(err.message || 'Kategori eklenemedi', true);
                }
            } catch (err) {
                showToast('Bağlantı hatası', true);
            }
        });

        async function deleteCategory(id) {
            if (!confirm('Bu kategoriyi silmek istediğinize emin misiniz?')) return;

            try {
                const res = await fetch(`/admin/categories/${id}`, {
                    method: 'DELETE',
                    headers
                });

                if (res.ok) {
                    showToast('Kategori silindi');
                    loadCategories();
                } else {
                    showToast('Kategori silinirken hata oluştu', true);
                }
            } catch (err) {
                showToast('Bağlantı hatası', true);
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            loadOverview();
        });
    </script>
</body>
</html>
