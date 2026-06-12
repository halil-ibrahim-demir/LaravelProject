<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', function () {
    $user = auth()->user();
    $statusText = $user 
        ? "Giriş yapıldı: <strong>{$user->name}</strong> (" . implode(', ', $user->roles->pluck('name')->toArray()) . ")"
        : "Giriş yapılmadı.";
        
    $logoutButton = $user 
        ? '<form action="'.route('logout').'" method="POST" style="margin-top: 15px;">'.csrf_field().'<button type="submit" style="padding: 8px 16px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer;">Çıkış Yap</button></form>'
        : '<a href="'.route('login').'" style="display: inline-block; margin-top: 15px; padding: 8px 16px; background: #6366f1; color: white; text-decoration: none; border-radius: 6px;">Giriş Yap</a>';

    $adminLink = ($user && $user->hasRole('admin'))
        ? '<br><br><a href="'.route('admin.dashboard').'" style="color: #6366f1; font-weight: bold;">Admin Paneline Git</a>'
        : '';

    return '<div style="font-family: sans-serif; padding: 40px; text-align: center; background: #f8fafc; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <h2>E-Ticaret Ana Sayfası</h2>
                <p>'.$statusText.'</p>
                '.$adminLink.'
                '.$logoutButton.'
            </div>';
})->name('home');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return '<div style="font-family: sans-serif; padding: 40px; text-align: center; background: #0f172a; color: white; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <h1 style="color: #a5b4fc; margin-bottom: 10px;">Admin Paneline Hoş Geldiniz!</h1>
                    <p style="color: #94a3b8;">Giriş yapan yetkili: <strong>' . auth()->user()->name . '</strong></p>
                    <div style="margin-top: 20px; display: flex; gap: 15px;">
                        <a href="'.route('home').'" style="padding: 8px 16px; background: #475569; color: white; text-decoration: none; border-radius: 6px;">Ana Sayfaya Dön</a>
                        <form action="' . route('logout') . '" method="POST">' . csrf_field() . '<button type="submit" style="padding: 8px 16px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer;">Çıkış Yap</button></form>
                    </div>
                </div>';
    })->name('admin.dashboard');
});
