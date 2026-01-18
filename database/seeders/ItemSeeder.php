<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener categorías existentes
        $electronics = Category::where('name', 'Electronics')->first();
        $smartphones = Category::where('name', 'Smartphones')->first();
        $laptops = Category::where('name', 'Laptops & Computers')->first();
        $cameras = Category::where('name', 'Cameras & Photography')->first();
        $audio = Category::where('name', 'Audio & Headphones')->first();

        $fashion = Category::where('name', 'Fashion')->first();
        $mensClothing = Category::where('name', "Men's Clothing")->first();
        $womensClothing = Category::where('name', "Women's Clothing")->first();
        $shoes = Category::where('name', 'Shoes')->first();

        $home = Category::where('name', 'Home & Garden')->first();
        $furniture = Category::where('name', 'Furniture')->first();
        $kitchen = Category::where('name', 'Kitchen & Dining')->first();

        $sports = Category::where('name', 'Sports & Outdoors')->first();
        $fitness = Category::where('name', 'Fitness Equipment')->first();

        // ==================== ELECTRONICS ====================

        // iPhone 15 Pro - Múltiples categorías
        $iphone = Item::create([
            'name' => 'iPhone 15 Pro',
            'description' => 'Latest flagship smartphone with titanium design and A17 Pro chip',
            'price' => 999.99,
            'cost_price' => 750.00,
            'is_active' => true,
        ]);
        $iphone->categories()->attach([$electronics->id, $smartphones->id]);

        // Samsung Galaxy S24
        $samsung = Item::create([
            'name' => 'Samsung Galaxy S24 Ultra',
            'description' => 'Flagship Android smartphone with S Pen and 200MP camera',
            'price' => 1199.99,
            'cost_price' => 900.00,
            'is_active' => true,
        ]);
        $samsung->categories()->attach([$electronics->id, $smartphones->id]);

        // MacBook Pro
        $macbook = Item::create([
            'name' => 'MacBook Pro 16" M3',
            'description' => 'Professional laptop with M3 chip, 16GB RAM, 512GB SSD',
            'price' => 2499.99,
            'cost_price' => 2000.00,
            'is_active' => true,
        ]);
        $macbook->categories()->attach([$electronics->id, $laptops->id]);

        // Dell XPS 15
        $dell = Item::create([
            'name' => 'Dell XPS 15',
            'description' => 'Premium Windows laptop with Intel i7, 32GB RAM',
            'price' => 1899.99,
            'cost_price' => 1500.00,
            'is_active' => true,
        ]);
        $dell->categories()->attach([$electronics->id, $laptops->id]);

        // Canon EOS R5
        $canon = Item::create([
            'name' => 'Canon EOS R5',
            'description' => 'Professional mirrorless camera with 45MP sensor and 8K video',
            'price' => 3899.99,
            'cost_price' => 3200.00,
            'is_active' => true,
        ]);
        $canon->categories()->attach([$electronics->id, $cameras->id]);

        // Sony WH-1000XM5
        $sonyHeadphones = Item::create([
            'name' => 'Sony WH-1000XM5',
            'description' => 'Premium noise-cancelling wireless headphones',
            'price' => 399.99,
            'cost_price' => 280.00,
            'is_active' => true,
        ]);
        $sonyHeadphones->categories()->attach([$electronics->id, $audio->id]);

        // AirPods Pro
        $airpods = Item::create([
            'name' => 'Apple AirPods Pro (2nd Gen)',
            'description' => 'Wireless earbuds with active noise cancellation',
            'price' => 249.99,
            'cost_price' => 180.00,
            'is_active' => true,
        ]);
        $airpods->categories()->attach([$electronics->id, $audio->id]);

        // ==================== FASHION ====================

        // Nike Air Jordan
        $airJordan = Item::create([
            'name' => 'Nike Air Jordan 1 Retro High',
            'description' => 'Iconic basketball sneakers in classic colorway',
            'price' => 179.99,
            'cost_price' => 120.00,
            'is_active' => true,
        ]);
        $airJordan->categories()->attach([$fashion->id, $shoes->id, $sports->id]);

        // Adidas Ultraboost
        $ultraboost = Item::create([
            'name' => 'Adidas Ultraboost 23',
            'description' => 'Premium running shoes with Boost cushioning',
            'price' => 189.99,
            'cost_price' => 130.00,
            'is_active' => true,
        ]);
        $ultraboost->categories()->attach([$fashion->id, $shoes->id, $sports->id]);

        // Levi's 501 Jeans
        $levis = Item::create([
            'name' => "Levi's 501 Original Jeans",
            'description' => 'Classic straight-fit denim jeans',
            'price' => 89.99,
            'cost_price' => 50.00,
            'is_active' => true,
        ]);
        $levis->categories()->attach([$fashion->id, $mensClothing->id]);

        // Ralph Lauren Polo Shirt
        $polo = Item::create([
            'name' => 'Ralph Lauren Classic Polo Shirt',
            'description' => 'Premium cotton polo shirt in multiple colors',
            'price' => 98.50,
            'cost_price' => 60.00,
            'is_active' => true,
        ]);
        $polo->categories()->attach([$fashion->id, $mensClothing->id]);

        // Zara Dress
        $zaraDress = Item::create([
            'name' => 'Zara Floral Summer Dress',
            'description' => 'Elegant floral print midi dress',
            'price' => 69.99,
            'cost_price' => 35.00,
            'is_active' => true,
        ]);
        $zaraDress->categories()->attach([$fashion->id, $womensClothing->id]);

        // ==================== HOME & GARDEN ====================

        // IKEA Sofa
        $sofa = Item::create([
            'name' => 'IKEA KIVIK 3-Seat Sofa',
            'description' => 'Comfortable modular sofa with washable cover',
            'price' => 599.99,
            'cost_price' => 400.00,
            'is_active' => true,
        ]);
        $sofa->categories()->attach([$home->id, $furniture->id]);

        // Dyson Vacuum
        $dyson = Item::create([
            'name' => 'Dyson V15 Detect Cordless Vacuum',
            'description' => 'Advanced cordless vacuum with laser detection',
            'price' => 749.99,
            'cost_price' => 550.00,
            'is_active' => true,
        ]);
        $dyson->categories()->attach([$home->id, $electronics->id]);

        // KitchenAid Mixer
        $kitchenaid = Item::create([
            'name' => 'KitchenAid Artisan Stand Mixer',
            'description' => '5-quart tilt-head stand mixer in multiple colors',
            'price' => 429.99,
            'cost_price' => 300.00,
            'is_active' => true,
        ]);
        $kitchenaid->categories()->attach([$home->id, $kitchen->id]);

        // ==================== SPORTS ====================

        // Peloton Bike
        $peloton = Item::create([
            'name' => 'Peloton Bike+',
            'description' => 'Premium indoor exercise bike with rotating screen',
            'price' => 2495.00,
            'cost_price' => 1800.00,
            'is_active' => true,
        ]);
        $peloton->categories()->attach([$sports->id, $fitness->id]);

        // Yoga Mat
        $yogaMat = Item::create([
            'name' => 'Lululemon The Reversible Mat 5mm',
            'description' => 'Premium yoga mat with dual-sided texture',
            'price' => 88.00,
            'cost_price' => 50.00,
            'is_active' => true,
        ]);
        $yogaMat->categories()->attach([$sports->id, $fitness->id]);

        // Dumbbells
        $dumbbells = Item::create([
            'name' => 'Bowflex SelectTech Adjustable Dumbbells',
            'description' => 'Space-saving adjustable dumbbells 5-52.5 lbs',
            'price' => 349.99,
            'cost_price' => 250.00,
            'is_active' => true,
        ]);
        $dumbbells->categories()->attach([$sports->id, $fitness->id]);

        $this->info('✅ Items seeded successfully!');
        $this->info('📦 Created:');
        $this->info('   - ' . Item::count() . ' items');
        $this->info('   - Items associated to multiple categories');
        $this->info('   - Total stock value: $' . number_format(Item::sum('price'), 2));
    }

    private function info(string $message): void
    {
        if ($this->command) {
            $this->command->info($message);
        }
    }
}
