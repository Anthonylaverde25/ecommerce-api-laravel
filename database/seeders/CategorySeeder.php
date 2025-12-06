<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar tabla (opcional - comentar si no quieres borrar)
        // Category::truncate();

        // Categorías principales (sin padre)
        $electronics = $this->createCategory(
            name: 'Electronics',
            description: 'Electronic devices and accessories'
        );

        $fashion = $this->createCategory(
            name: 'Fashion',
            description: 'Clothing, shoes and accessories'
        );

        $home = $this->createCategory(
            name: 'Home & Garden',
            description: 'Furniture, decor and garden supplies'
        );

        $sports = $this->createCategory(
            name: 'Sports & Outdoors',
            description: 'Sports equipment and outdoor gear'
        );

        $books = $this->createCategory(
            name: 'Books',
            description: 'Physical and digital books'
        );

        $toys = $this->createCategory(
            name: 'Toys & Games',
            description: 'Toys, games and hobbies'
        );

        // Subcategorías de Electronics
        $this->createCategory(
            name: 'Smartphones',
            description: 'Mobile phones and accessories',
            parentId: $electronics->id
        );

        $this->createCategory(
            name: 'Laptops & Computers',
            description: 'Computers, laptops and peripherals',
            parentId: $electronics->id
        );

        $this->createCategory(
            name: 'Cameras & Photography',
            description: 'Cameras, lenses and photography equipment',
            parentId: $electronics->id
        );

        $this->createCategory(
            name: 'Audio & Headphones',
            description: 'Speakers, headphones and audio equipment',
            parentId: $electronics->id
        );

        $this->createCategory(
            name: 'Smart Home',
            description: 'Smart home devices and IoT products',
            parentId: $electronics->id
        );

        // Subcategorías de Fashion
        $mensClothing = $this->createCategory(
            name: "Men's Clothing",
            description: "Men's apparel and accessories",
            parentId: $fashion->id
        );

        $womensClothing = $this->createCategory(
            name: "Women's Clothing",
            description: "Women's apparel and accessories",
            parentId: $fashion->id
        );

        $this->createCategory(
            name: 'Shoes',
            description: 'Footwear for men, women and children',
            parentId: $fashion->id
        );

        $this->createCategory(
            name: 'Bags & Accessories',
            description: 'Handbags, backpacks and fashion accessories',
            parentId: $fashion->id
        );

        // Sub-subcategorías de Men's Clothing
        $this->createCategory(
            name: 'Shirts & T-Shirts',
            description: "Men's shirts and t-shirts",
            parentId: $mensClothing->id
        );

        $this->createCategory(
            name: 'Pants & Jeans',
            description: "Men's pants and jeans",
            parentId: $mensClothing->id
        );

        // Subcategorías de Home & Garden
        $this->createCategory(
            name: 'Furniture',
            description: 'Indoor and outdoor furniture',
            parentId: $home->id
        );

        $this->createCategory(
            name: 'Kitchen & Dining',
            description: 'Kitchenware and dining essentials',
            parentId: $home->id
        );

        $this->createCategory(
            name: 'Bedding & Bath',
            description: 'Bedroom and bathroom essentials',
            parentId: $home->id
        );

        $this->createCategory(
            name: 'Garden & Outdoor',
            description: 'Garden tools and outdoor decoration',
            parentId: $home->id
        );

        // Subcategorías de Sports & Outdoors
        $this->createCategory(
            name: 'Fitness Equipment',
            description: 'Gym and fitness equipment',
            parentId: $sports->id
        );

        $this->createCategory(
            name: 'Camping & Hiking',
            description: 'Camping gear and hiking equipment',
            parentId: $sports->id
        );

        $this->createCategory(
            name: 'Team Sports',
            description: 'Soccer, basketball, baseball equipment',
            parentId: $sports->id
        );

        // Subcategorías de Books
        $this->createCategory(
            name: 'Fiction',
            description: 'Novels and fiction literature',
            parentId: $books->id
        );

        $this->createCategory(
            name: 'Non-Fiction',
            description: 'Educational and reference books',
            parentId: $books->id
        );

        $this->createCategory(
            name: 'Children\'s Books',
            description: 'Books for kids and young adults',
            parentId: $books->id
        );

        // Subcategorías de Toys & Games
        $this->createCategory(
            name: 'Action Figures & Dolls',
            description: 'Action figures, dolls and collectibles',
            parentId: $toys->id
        );

        $this->createCategory(
            name: 'Board Games & Puzzles',
            description: 'Board games, card games and puzzles',
            parentId: $toys->id
        );

        $this->createCategory(
            name: 'Educational Toys',
            description: 'Learning and educational toys',
            parentId: $toys->id
        );

        $this->info('✅ Categories seeded successfully!');
        $this->info('📊 Created:');
        $this->info('   - 6 main categories');
        $this->info('   - ' . (Category::count() - 6) . ' subcategories');
        $this->info('   - Total: ' . Category::count() . ' categories');
    }

    /**
     * Helper para crear categoría
     */
    private function createCategory(
        string $name,
        string $description,
        ?int $parentId = null,
        bool $isActive = true
    ): Category {
        return Category::create([
            'name' => $name,
            'description' => $description,
            'parent_id' => $parentId,
            'is_active' => $isActive,
        ]);
    }

    /**
     * Helper para mostrar mensajes en consola
     */
    private function info(string $message): void
    {
        if ($this->command) {
            $this->command->info($message);
        }
    }
}
