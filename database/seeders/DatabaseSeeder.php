<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Slider;
use App\Models\Page;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\TrainingProgram;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Categories (idempotent)
        $equipment = Category::updateOrCreate(['slug' => 'equipment'], ['name' => 'Equipment', 'description' => 'Elite fitness equipment', 'type' => 'equipment', 'is_featured' => true, 'sort_order' => 1]);
        $apparel = Category::updateOrCreate(['slug' => 'apparel'], ['name' => 'Apparel', 'description' => 'Training apparel for men and women', 'type' => 'apparel', 'is_featured' => true, 'sort_order' => 2]);
        $accessories = Category::updateOrCreate(['slug' => 'accessories'], ['name' => 'Accessories', 'description' => 'Essential training accessories', 'type' => 'accessories', 'is_featured' => true, 'sort_order' => 3]);
        $mens = Category::updateOrCreate(['slug' => 'men'], ['name' => 'Men', 'description' => 'Men\'s collection', 'type' => 'apparel', 'is_featured' => true, 'sort_order' => 4]);
        $women = Category::updateOrCreate(['slug' => 'women'], ['name' => 'Women', 'description' => 'Women\'s collection', 'type' => 'apparel', 'is_featured' => true, 'sort_order' => 5]);

        if (Product::count() === 0) {
            // Equipment Products
            $products = [
                ['category_id' => $equipment->id, 'name' => 'Yeabneh Weight Vest - 40LB | 18.14KG', 'price' => 205, 'is_featured' => true, 'is_new' => true],
                ['category_id' => $equipment->id, 'name' => 'Yeabneh Weight Vest - 30LB | 13.61KG', 'price' => 160, 'is_featured' => true, 'is_new' => true],
                ['category_id' => $equipment->id, 'name' => 'Yeabneh Weight Vest - 20LB | 9KG', 'price' => 125, 'is_featured' => true, 'is_new' => true],
                ['category_id' => $equipment->id, 'name' => 'Yeabneh Metal Parallettes', 'price' => 105, 'is_featured' => true, 'is_new' => true],
                ['category_id' => $equipment->id, 'name' => 'Yeabneh Parallettes V2', 'price' => 84, 'is_featured' => true],
                ['category_id' => $equipment->id, 'name' => 'Yeabneh Travel Size Parallettes V2', 'price' => 45, 'is_featured' => true],
                ['category_id' => $equipment->id, 'name' => 'Yeabneh Half Bar', 'price' => 165, 'is_featured' => true],
                ['category_id' => $equipment->id, 'name' => 'Yeabneh Dip Bars', 'price' => 165, 'is_featured' => true],
                ['category_id' => $equipment->id, 'name' => 'Yeabneh Dip Bars [Wooden Bars]', 'price' => 164, 'is_new' => true],
                ['category_id' => $equipment->id, 'name' => 'Yeabneh Weight Belt V2', 'price' => 50, 'is_featured' => true],
                ['category_id' => $equipment->id, 'name' => 'Gymnastics Rings V2', 'price' => 49.99, 'is_featured' => true],
                ['category_id' => $equipment->id, 'name' => 'Yeabneh Ankle Weights', 'price' => 25, 'compare_at_price' => 30, 'is_clearance' => true],
                ['category_id' => $equipment->id, 'name' => 'Complete Resistance Band Set', 'price' => 78.26, 'compare_at_price' => 86.96, 'is_featured' => true],
                ['category_id' => $equipment->id, 'name' => 'Resistance Band V2 | 10-35LB', 'price' => 17],
                ['category_id' => $equipment->id, 'name' => 'Resistance Band V2 | 30-60LB', 'price' => 23],
                ['category_id' => $equipment->id, 'name' => 'Resistance Band V2 | 40-80LB', 'price' => 29],
                ['category_id' => $equipment->id, 'name' => 'Resistance Band V2 | 50-125LB', 'price' => 34],
                ['category_id' => $equipment->id, 'name' => 'Green Resistance Band | 50-125LB', 'price' => 15, 'compare_at_price' => 29.99, 'is_clearance' => true],
                ['category_id' => $equipment->id, 'name' => 'Purple Resistance Band | 40-80LB', 'price' => 10, 'compare_at_price' => 24.99, 'is_clearance' => true],
                ['category_id' => $equipment->id, 'name' => 'Yeabneh Weight Belt', 'price' => 20, 'compare_at_price' => 45, 'is_clearance' => true],
                ['category_id' => $apparel->id, 'name' => 'Yeabneh Joggers - Black', 'price' => 74, 'is_featured' => true, 'is_new' => true],
                ['category_id' => $apparel->id, 'name' => 'Yeabneh Joggers - Solid Grey', 'price' => 74, 'is_new' => true],
                ['category_id' => $apparel->id, 'name' => 'Yeabneh Training Tee - Black', 'price' => 45, 'is_featured' => true],
                ['category_id' => $apparel->id, 'name' => 'Yeabneh Compression Tee - Black', 'price' => 45],
                ['category_id' => $apparel->id, 'name' => 'Yeabneh Long Sleeve Compression Tee - Black', 'price' => 60],
                ['category_id' => $apparel->id, 'name' => 'Yeabneh Cut Off Tank - Black', 'price' => 45],
                ['category_id' => $apparel->id, 'name' => 'Yeabneh Hoodie - Black', 'price' => 65, 'is_featured' => true],
                ['category_id' => $apparel->id, 'name' => 'Yeabneh Shorts V2 - Black', 'price' => 50],
                ['category_id' => $apparel->id, 'name' => 'Yeabneh Shorts - Grey', 'price' => 27, 'compare_at_price' => 45, 'is_clearance' => true],
                ['category_id' => $accessories->id, 'name' => 'Strength Wrist Wraps V2', 'price' => 18],
                ['category_id' => $accessories->id, 'name' => 'Strength Wrist Wraps', 'price' => 8, 'compare_at_price' => 14.99, 'is_clearance' => true],
                ['category_id' => $accessories->id, 'name' => 'Yeabneh Jump Rope', 'price' => 7.99],
            ];

            foreach ($products as $i => $data) {
                $data['slug'] = Str::slug($data['name']);
                $data['description'] = '<p>Premium quality fitness gear designed for peak performance. Built to last with the highest quality materials.</p>';
                $data['stock'] = rand(10, 100);
                $data['sort_order'] = $i;
                $product = Product::create($data);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'https://placehold.co/600x600/111/fff?text=' . urlencode(Str::limit($product->name, 20)),
                    'sort_order' => 0,
                ]);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'https://placehold.co/600x600/333/fff?text=' . urlencode(Str::limit($product->name, 20)) . '+2',
                    'sort_order' => 1,
                ]);
            }
        }

        // Sliders (idempotent)
        Slider::updateOrCreate(['sort_order' => 1], ['title' => 'New Look. More power.', 'subtitle' => 'All in your hands.', 'button_text' => 'Shop Now', 'link' => '/shop', 'is_active' => true]);
        Slider::updateOrCreate(['sort_order' => 2], ['title' => 'Elite Equipment', 'subtitle' => 'For ultimate performance', 'button_text' => 'Shop Equipment', 'link' => '/shop?category=equipment', 'is_active' => true]);

        // Pages (idempotent)
        $pages = [
            ['slug' => 'faq', 'title' => 'FAQ', 'content' => '<h2>Frequently Asked Questions</h2><p>Welcome to our FAQ page. Here you can find answers to the most common questions about our products and services.</p><h3>Shipping</h3><p>We offer free shipping on orders over $100. Standard shipping takes 3-7 business days.</p><h3>Returns</h3><p>We accept returns within 30 days of purchase. Items must be unused and in original packaging.</p>'],
            ['slug' => 'contact-us', 'title' => 'Contact Us', 'content' => '<h2>Contact Us</h2><p>Email: support@yeabnehsstore.com</p><p>We typically respond within 24 hours.</p>'],
            ['slug' => 'return-policy', 'title' => 'Return Policy', 'content' => '<h2>Return Policy</h2><p>We offer a 30-day return policy on all products. Items must be unused and in original packaging to be eligible for a return.</p>'],
            ['slug' => 'privacy-policy', 'title' => 'Privacy Policy', 'content' => '<h2>Privacy Policy</h2><p>Your privacy is important to us. This policy outlines how we collect, use, and protect your personal information.</p>'],
            ['slug' => 'terms-and-conditions', 'title' => 'Terms and Conditions', 'content' => '<h2>Terms and Conditions</h2><p>By using this website, you agree to these terms and conditions.</p>'],
        ];
        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page);
        }

        // Courses (idempotent)
        if (Course::count() === 0) {
            $coursesData = [
                [
                    'title' => 'Fitness Fundamentals', 'description' => 'Master the basics of fitness training. Learn proper form for push-ups, pull-ups, dips, and squats. Perfect for beginners.',
                    'long_description' => '<p>This comprehensive beginner course covers everything you need to start your fitness journey.</p>',
                    'level' => 'beginner', 'duration_weeks' => 4, 'lessons_count' => 12, 'price' => 49.99, 'is_featured' => true,
                    'lessons' => [
                        ['title' => 'Introduction to Fitness Training', 'description' => 'What is fitness training and why it works', 'duration_minutes' => 15],
                        ['title' => 'Perfect Push-Up Form', 'description' => 'Master the fundamental push-up', 'duration_minutes' => 20],
                        ['title' => 'Pull-Up Progressions', 'description' => 'From dead hangs to full pull-ups', 'duration_minutes' => 25],
                    ],
                ],
                [
                    'title' => 'Handstand Mastery', 'description' => 'Progress from wall handstands to freestanding.',
                    'long_description' => '<p>The handstand is one of the most iconic fitness skills.</p>',
                    'level' => 'intermediate', 'duration_weeks' => 6, 'lessons_count' => 18, 'price' => 79.99, 'compare_at_price' => 99.99, 'is_featured' => true,
                    'lessons' => [
                        ['title' => 'Handstand Anatomy', 'description' => 'Understanding the mechanics', 'duration_minutes' => 15],
                        ['title' => 'Wrist Preparation', 'description' => 'Essential warm-up for handstands', 'duration_minutes' => 10],
                    ],
                ],
                [
                    'title' => 'Muscle-Up Blueprint', 'description' => 'The complete guide to achieving your first muscle-up.',
                    'long_description' => '<p>The muscle-up is the ultimate upper body skill.</p>',
                    'level' => 'advanced', 'duration_weeks' => 8, 'lessons_count' => 24, 'price' => 99.99, 'is_featured' => true,
                    'lessons' => [
                        ['title' => 'Muscle-Up Prerequisites', 'description' => 'Strength requirements', 'duration_minutes' => 12],
                        ['title' => 'Explosive Pull-Up Training', 'description' => 'Building pulling power', 'duration_minutes' => 25],
                    ],
                ],
                [
                    'title' => 'Strength & Planche', 'description' => 'Build incredible pushing strength with progressive planche training.',
                    'level' => 'advanced', 'duration_weeks' => 12, 'lessons_count' => 30, 'price' => 129.99,
                    'lessons' => [
                        ['title' => 'Planche Fundamentals', 'description' => 'Scapular strength and protraction', 'duration_minutes' => 20],
                    ],
                ],
            ];

            foreach ($coursesData as $i => $data) {
                $lessons = $data['lessons'] ?? [];
                unset($data['lessons']);
                $data['slug'] = Str::slug($data['title']);
                $data['image'] = 'https://placehold.co/800x500/0a0a0a/c8ff00?text=' . urlencode(Str::limit($data['title'], 15));
                $data['sort_order'] = $i;
                $course = Course::create($data);
                foreach ($lessons as $j => $lesson) {
                    $lesson['sort_order'] = $j;
                    $course->lessons()->create($lesson);
                }
            }
        }

        // Training Programs (idempotent)
        if (TrainingProgram::count() === 0) {
            $trainingData = [
                ['title' => '1-on-1 Elite Coaching', 'description' => 'Fully personalized fitness coaching.', 'type' => '1-on-1', 'duration' => '60 min', 'max_participants' => 1, 'price' => 89.99, 'is_featured' => true],
                ['title' => 'Small Group Training', 'description' => 'Train with a motivated group.', 'type' => 'group', 'duration' => '75 min', 'max_participants' => 6, 'price' => 45.00, 'compare_at_price' => 59.99, 'is_featured' => true],
                ['title' => 'Online Coaching Program', 'description' => 'Train from anywhere.', 'type' => 'online', 'duration' => '4 weeks', 'max_participants' => 0, 'price' => 149.99, 'is_featured' => true],
                ['title' => 'Beginner Foundations', 'description' => 'New to fitness training?', 'type' => 'group', 'duration' => '60 min', 'max_participants' => 8, 'price' => 35.00],
                ['title' => 'Competition Prep', 'description' => 'Preparing for a fitness competition?', 'type' => '1-on-1', 'duration' => '90 min', 'max_participants' => 1, 'price' => 120.00],
            ];

            foreach ($trainingData as $i => $data) {
                $data['slug'] = Str::slug($data['title']);
                $data['image'] = 'https://placehold.co/800x500/0a0a0a/c8ff00?text=' . urlencode(Str::limit($data['title'], 15));
                $data['is_active'] = true;
                $data['sort_order'] = $i;
                $data['features'] = json_encode(['Professional coaching', 'Progress tracking']);
                TrainingProgram::create($data);
            }
        }

        // Admin User (idempotent)
        User::updateOrCreate(
            ['email' => 'admin@yeabnehsstore.com'],
            ['name' => 'Admin', 'password' => 'password', 'role' => 'super_admin', 'is_active' => true, 'email_verified_at' => now()]
        );
    }
}
