<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Brand;
use App\Models\Category;
use App\Models\HomepageSection;
use App\Models\Lead;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSpecification;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedRolesAndPermissions();
        $this->seedAdminUser();
        $this->seedSettings();
        $this->seedBrands();
        $this->seedCategories();
        $this->seedProducts();
        $this->seedPages();
        $this->seedBlogs();
        $this->seedBanners();
        $this->seedTestimonials();
        $this->seedHomepageSections();
        $this->seedSampleLeads();
    }

    protected function seedRolesAndPermissions(): void
    {
        $roles = ['super-admin', 'admin', 'content-manager', 'seo-executive', 'sales-team', 'support-team', 'dealer', 'customer'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $permissions = [
            'manage-products', 'manage-brands', 'manage-categories', 'manage-pages',
            'manage-blogs', 'manage-banners', 'manage-leads', 'manage-dealers',
            'manage-settings', 'manage-users', 'manage-seo', 'view-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        Role::findByName('super-admin')->givePermissionTo(Permission::all());
        Role::findByName('admin')->givePermissionTo(Permission::all());
    }

    protected function seedAdminUser(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@cuttingplotter.in'],
            ['name' => 'Super Admin', 'password' => 'password', 'is_active' => true]
        );
        $admin->assignRole('super-admin');
    }

    protected function seedSettings(): void
    {
        $settings = [
            'site_name' => 'Cutting Plotter India',
            'site_tagline' => 'Premium Plotters, Printers & Industrial Printing Equipment',
            'company_name' => 'Skycut India',
            'company_address' => 'Mumbai, Maharashtra, India',
            'company_phone' => '+91 98765 43210',
            'company_whatsapp' => '+919876543210',
            'company_email' => 'info@cuttingplotter.in',
            'company_gst' => '27AAAAA0000A1Z5',
            'google_analytics' => '',
            'google_tag_manager' => '',
            'meta_pixel' => '',
            'facebook_url' => 'https://facebook.com/skycutindia',
            'instagram_url' => 'https://instagram.com/skycutindia',
            'youtube_url' => 'https://youtube.com/skycutindia',
            'linkedin_url' => 'https://linkedin.com/company/skycutindia',
            'maintenance_mode' => '0',
            'default_meta_title' => 'Cutting Plotter India | Plotters, Printers & Printheads',
            'default_meta_description' => 'Leading supplier of cutting plotters, UV printers, printheads, inks, and spare parts in India. Multi-brand industrial printing equipment.',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value, 'general');
        }
    }

    protected function seedBrands(): void
    {
        $brands = [
            ['name' => 'Skycut', 'description' => 'Premium vinyl cutting plotters and digital cutting solutions.'],
            ['name' => 'Roland', 'description' => 'World-class wide-format printers and cutting machines.'],
            ['name' => 'Mimaki', 'description' => 'Industrial inkjet printers and cutting plotters.'],
            ['name' => 'Epson', 'description' => 'High-precision printheads and eco-solvent printers.'],
            ['name' => 'GCC', 'description' => 'Professional cutting plotters for signage industry.'],
            ['name' => 'Xaar', 'description' => 'Industrial printhead technology leader.'],
        ];

        foreach ($brands as $i => $brand) {
            Brand::firstOrCreate(
                ['slug' => Str::slug($brand['name'])],
                array_merge($brand, ['is_featured' => $i < 4, 'sort_order' => $i])
            );
        }
    }

    protected function seedCategories(): void
    {
        $categories = [
            'Cutting Plotters' => ['Vinyl Cutters', 'Digital Cutters', 'Flatbed Cutters'],
            'Printers' => ['UV Printers', 'Eco-Solvent Printers', 'DTF Printers', 'Sublimation Printers'],
            'Printheads' => ['Epson Printheads', 'Ricoh Printheads', 'Xaar Printheads'],
            'Inks' => ['Eco-Solvent Ink', 'UV Ink', 'Sublimation Ink', 'DTF Ink'],
            'Spare Parts' => ['Motors', 'Belts', 'Boards', 'Sensors'],
            'Accessories' => ['Blades', 'Cutting Mats', 'Media', 'Software'],
        ];

        $order = 0;
        foreach ($categories as $parent => $children) {
            $parentCat = Category::firstOrCreate(
                ['slug' => Str::slug($parent)],
                ['name' => $parent, 'is_featured' => true, 'sort_order' => $order++]
            );

            foreach ($children as $j => $child) {
                Category::firstOrCreate(
                    ['slug' => Str::slug($child)],
                    ['name' => $child, 'parent_id' => $parentCat->id, 'sort_order' => $j]
                );
            }
        }
    }

    protected function seedProducts(): void
    {
        $skycut = Brand::where('slug', 'skycut')->first();
        $plotters = Category::where('slug', 'cutting-plotters')->first();
        $vinyl = Category::where('slug', 'vinyl-cutters')->first();

        $products = [
            [
                'name' => 'Skycut V-Series Vinyl Cutter 24"',
                'sku' => 'SKY-V24-001',
                'short_description' => 'Professional 24-inch vinyl cutting plotter with servo motor.',
                'description' => '<p>The Skycut V-Series is a high-performance vinyl cutting plotter designed for sign makers, decal producers, and garment decorators.</p>',
                'price' => 85000, 'offer_price' => 79999, 'stock' => 25,
                'features' => ['Servo Motor', 'USB & LAN', 'Auto Registration', 'Contour Cutting'],
                'is_featured' => true, 'is_new_arrival' => true,
            ],
            [
                'name' => 'Skycut V-Series Vinyl Cutter 48"',
                'sku' => 'SKY-V48-001',
                'short_description' => 'Large format 48-inch vinyl cutting plotter for industrial use.',
                'description' => '<p>Industrial-grade 48-inch vinyl cutter with advanced servo motor technology.</p>',
                'price' => 145000, 'offer_price' => 135000, 'stock' => 15,
                'features' => ['48" Cutting Width', 'Servo Motor', 'Heavy Duty Frame'],
                'is_featured' => true, 'is_best_seller' => true,
            ],
            [
                'name' => 'Roland GR2-640 Vinyl Cutter',
                'sku' => 'ROL-GR2-640',
                'short_description' => 'Roland GR2 series 64-inch professional vinyl cutter.',
                'description' => '<p>Roland GR2-640 delivers exceptional cutting precision for large format applications.</p>',
                'price' => 320000, 'stock' => 8,
                'features' => ['64" Width', 'Overlap Cutting', 'Roland CutStudio'],
                'is_trending' => true,
            ],
            [
                'name' => 'Mimaki CG-60AR Cutting Plotter',
                'sku' => 'MIM-CG60AR',
                'short_description' => 'Mimaki 24-inch cutting plotter with AR registration.',
                'description' => '<p>Mimaki CG-60AR offers automatic registration for precise contour cutting.</p>',
                'price' => 175000, 'offer_price' => 165000, 'stock' => 12,
                'is_featured' => true,
            ],
            [
                'name' => 'Epson I3200 Printhead',
                'sku' => 'EPS-I3200',
                'short_description' => 'Genuine Epson I3200-A1 industrial printhead.',
                'description' => '<p>Original Epson I3200 printhead for UV and eco-solvent printers.</p>',
                'price' => 45000, 'stock' => 50,
                'is_best_seller' => true,
            ],
            [
                'name' => 'Xaar 2002 Printhead',
                'sku' => 'XAR-2002',
                'short_description' => 'Xaar 2002+ industrial printhead for UV printing.',
                'description' => '<p>High-performance Xaar 2002 printhead for industrial UV applications.</p>',
                'price' => 85000, 'stock' => 20,
            ],
        ];

        foreach ($products as $i => $data) {
            $product = Product::firstOrCreate(
                ['sku' => $data['sku']],
                array_merge($data, [
                    'slug' => Str::slug($data['name']),
                    'brand_id' => $skycut?->id,
                    'category_id' => $plotters?->id,
                    'subcategory_id' => $vinyl?->id,
                    'hsn' => '8443',
                    'gst_rate' => 18,
                    'sort_order' => $i,
                    'meta_title' => $data['name'].' | Cutting Plotter India',
                ])
            );

            ProductSpecification::firstOrCreate(
                ['product_id' => $product->id, 'label' => 'Cutting Width'],
                ['value' => '24 inches', 'group' => 'Technical', 'sort_order' => 0]
            );
            ProductSpecification::firstOrCreate(
                ['product_id' => $product->id, 'label' => 'Max Speed'],
                ['value' => '800 mm/s', 'group' => 'Technical', 'sort_order' => 1]
            );
        }
    }

    protected function seedPages(): void
    {
        Page::firstOrCreate(
            ['slug' => 'about-us'],
            [
                'title' => 'About Us',
                'content' => '<p>Cutting Plotter India is a leading supplier of industrial printing and cutting equipment across India.</p>',
                'meta_title' => 'About Us | Cutting Plotter India',
                'is_active' => true,
            ]
        );

        Page::firstOrCreate(
            ['slug' => 'contact'],
            [
                'title' => 'Contact Us',
                'content' => '<p>Get in touch with our sales team for quotes and product inquiries.</p>',
                'meta_title' => 'Contact Us | Cutting Plotter India',
                'is_active' => true,
            ]
        );
    }

    protected function seedBlogs(): void
    {
        $category = BlogCategory::firstOrCreate(
            ['slug' => 'industry-news'],
            ['name' => 'Industry News', 'description' => 'Latest news from the printing industry']
        );

        $admin = User::first();

        Blog::firstOrCreate(
            ['slug' => 'how-to-choose-vinyl-cutter'],
            [
                'blog_category_id' => $category->id,
                'author_id' => $admin?->id,
                'title' => 'How to Choose the Right Vinyl Cutter for Your Business',
                'excerpt' => 'A comprehensive guide to selecting the perfect vinyl cutting plotter.',
                'content' => '<p>Choosing the right vinyl cutter depends on your production volume, material types, and budget...</p>',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'meta_title' => 'How to Choose a Vinyl Cutter | Cutting Plotter India',
            ]
        );
    }

    protected function seedBanners(): void
    {
        Banner::firstOrCreate(
            ['title' => 'Hero Banner 1'],
            [
                'location' => 'homepage_hero',
                'description' => 'Premium Cutting Plotters & Industrial Printing Equipment',
                'link' => '/products',
                'is_active' => true,
                'sort_order' => 0,
            ]
        );
    }

    protected function seedTestimonials(): void
    {
        Testimonial::firstOrCreate(
            ['name' => 'Rajesh Kumar'],
            [
                'designation' => 'Owner',
                'company' => 'SignMax Solutions, Delhi',
                'content' => 'Excellent service and genuine products. Our Skycut plotter has been running flawlessly for 2 years.',
                'rating' => 5,
                'is_featured' => true,
                'sort_order' => 0,
            ]
        );

        Testimonial::firstOrCreate(
            ['name' => 'Priya Sharma'],
            [
                'designation' => 'Production Manager',
                'company' => 'PrintCraft Mumbai',
                'content' => 'Best place to buy printheads and inks in India. Fast delivery and competitive pricing.',
                'rating' => 5,
                'is_featured' => true,
                'sort_order' => 1,
            ]
        );
    }

    protected function seedHomepageSections(): void
    {
        $sections = [
            ['section_key' => 'hero', 'title' => 'Hero Section', 'sort_order' => 0],
            ['section_key' => 'featured_products', 'title' => 'Featured Products', 'sort_order' => 1],
            ['section_key' => 'categories', 'title' => 'Product Categories', 'sort_order' => 2],
            ['section_key' => 'brands', 'title' => 'Our Brands', 'sort_order' => 3],
            ['section_key' => 'testimonials', 'title' => 'Testimonials', 'sort_order' => 4],
            ['section_key' => 'latest_blogs', 'title' => 'Latest Blogs', 'sort_order' => 5],
            ['section_key' => 'cta', 'title' => 'Call to Action', 'sort_order' => 6],
        ];

        foreach ($sections as $section) {
            HomepageSection::firstOrCreate(['section_key' => $section['section_key']], $section);
        }
    }

    protected function seedSampleLeads(): void
    {
        Lead::firstOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name' => 'Demo Lead',
                'phone' => '+91 99999 88888',
                'company' => 'Demo Company',
                'source' => 'website',
                'type' => 'quote',
                'message' => 'Interested in Skycut V-Series 48" cutter.',
                'status' => 'new',
            ]
        );
    }
}
