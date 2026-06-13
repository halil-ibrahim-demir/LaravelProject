<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        if (!$admin->roles()->where('name', 'admin')->exists()) {
            $admin->roles()->attach($adminRole);
        }

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Standard User',
                'password' => Hash::make('password'),
            ]
        );

        if (!$user->roles()->where('name', 'user')->exists()) {
            $user->roles()->attach($userRole);
        }

        $elektronik = Category::firstOrCreate(['title' => 'Elektronik'], ['status' => true]);
        $giyim = Category::firstOrCreate(['title' => 'Giyim'], ['status' => true]);
        $evYasam = Category::firstOrCreate(['title' => 'Ev & Yaşam'], ['status' => true]);
        $kitap = Category::firstOrCreate(['title' => 'Kitap'], ['status' => true]);
        $kozmetik = Category::firstOrCreate(['title' => 'Kozmetik'], ['status' => true]);
        $sporOutdoor = Category::firstOrCreate(['title' => 'Spor & Outdoor'], ['status' => true]);

        Product::firstOrCreate(
            ['title' => 'Akıllı Telefon'],
            [
                'category_id' => $elektronik->id,
                'user_id' => $admin->id,
                'price' => 24999.00,
                'stock' => 20,
                'description' => 'En son teknoloji yüksek çözünürlüklü ekran ve güçlü işlemci.',
                'image' => '/images/telefon.png',
                'status' => true
            ]
        );

        Product::firstOrCreate(
            ['title' => 'Dizüstü Bilgisayar'],
            [
                'category_id' => $elektronik->id,
                'user_id' => $admin->id,
                'price' => 38999.00,
                'stock' => 15,
                'description' => 'Hızlı performans, uzun pil ömrü ve şık metalik kasa.',
                'image' => '/images/laptop.png',
                'status' => true
            ]
        );

        Product::firstOrCreate(
            ['title' => 'Deri Ceket'],
            [
                'category_id' => $giyim->id,
                'user_id' => $admin->id,
                'price' => 1899.00,
                'stock' => 30,
                'description' => '%100 hakiki deriden üretilmiş, rüzgar geçirmez modern tasarım.',
                'image' => '/images/ceket.png',
                'status' => true
            ]
        );

        Product::firstOrCreate(
            ['title' => 'Spor Ayakkabı'],
            [
                'category_id' => $giyim->id,
                'user_id' => $admin->id,
                'price' => 1450.00,
                'stock' => 45,
                'description' => 'Ortopedik taban ve nefes alabilir kumaş ile günlük yürüyüş rahatlığı.',
                'image' => '/images/ayakkabi.png',
                'status' => true
            ]
        );

        Product::firstOrCreate(
            ['title' => 'Filtre Kahve Makinesi'],
            [
                'category_id' => $evYasam->id,
                'user_id' => $admin->id,
                'price' => 2200.00,
                'stock' => 10,
                'description' => 'Zaman ayarlı, 1.25 litre kapasiteli, sıcak tutma özellikli kahve makinesi.',
                'image' => '/images/kahve.png',
                'status' => true
            ]
        );

        Product::firstOrCreate(
            ['title' => 'Çalışma Masası'],
            [
                'category_id' => $evYasam->id,
                'user_id' => $admin->id,
                'price' => 1100.00,
                'stock' => 25,
                'description' => 'Geniş çalışma yüzeyli, çekmeceli ve ergonomik ahşap masa.',
                'image' => '/images/masa.png',
                'status' => true
            ]
        );

        Product::firstOrCreate(
            ['title' => 'Nutuk'],
            [
                'category_id' => $kitap->id,
                'user_id' => $admin->id,
                'price' => 95.00,
                'stock' => 100,
                'description' => 'Mustafa Kemal Atatürk\'ün tarihi başyapıtı ve Nutuk eseri.',
                'image' => '/images/nutuk.png',
                'status' => true
            ]
        );

        Product::firstOrCreate(
            ['title' => 'Simyacı'],
            [
                'category_id' => $kitap->id,
                'user_id' => $admin->id,
                'price' => 75.00,
                'stock' => 80,
                'description' => 'Paulo Coelho\'nun dünyaca ünlü, ilham verici felsefi romanı.',
                'image' => '/images/simyaci.png',
                'status' => true
            ]
        );

        Product::firstOrCreate(
            ['title' => 'Erkek Parfümü'],
            [
                'category_id' => $kozmetik->id,
                'user_id' => $admin->id,
                'price' => 1250.00,
                'stock' => 40,
                'description' => 'Odunsu ve baharatlı notalarıyla kalıcı ve etkileyici koku.',
                'image' => '/images/parfum.png',
                'status' => true
            ]
        );

        Product::firstOrCreate(
            ['title' => 'Kablosuz Kulaklık'],
            [
                'category_id' => $elektronik->id,
                'user_id' => $admin->id,
                'price' => 3200.00,
                'stock' => 50,
                'description' => 'Aktif gürültü engelleme özellikli, yüksek ses kaliteli kulaklık.',
                'image' => '/images/telefon.png',
                'status' => true
            ]
        );

        Product::firstOrCreate(
            ['title' => 'Nemlendirici Krem'],
            [
                'category_id' => $kozmetik->id,
                'user_id' => $admin->id,
                'price' => 380.00,
                'stock' => 60,
                'description' => 'Tüm cilt tipleri için 24 saat etkili yoğun nemlendirici bakım kremi.',
                'image' => '/images/krem.png',
                'status' => true
            ]
        );

        Product::firstOrCreate(
            ['title' => 'Kamp Çadırı'],
            [
                'category_id' => $sporOutdoor->id,
                'user_id' => $admin->id,
                'price' => 4500.00,
                'stock' => 12,
                'description' => '3 kişilik, su geçirmez ve kolay kurulumlu pratik kamp çadırı.',
                'image' => '/images/cadir.png',
                'status' => true
            ]
        );
    }
}
