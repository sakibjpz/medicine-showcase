<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DemoProductsSeeder extends Seeder
{
    public function run(): void
    {
        set_time_limit(0);
        ini_set('default_socket_timeout', 120);

        $manufacturerIds = Manufacturer::pluck('id')->toArray();
        if (empty($manufacturerIds)) {
            $manufacturerIds = [1];
        }

        $categoryMeta = [
            'Cardiovascular' => ['prefix' => 'CARD', 'sub' => 'Dyslipidaemia', 'colors' => ['#1e3a8a', '#3b82f6']],
            'Diabetes' => ['prefix' => 'DIA', 'sub' => 'Type 2 diabetes', 'colors' => ['#14532d', '#22c55e']],
            'Hepatology' => ['prefix' => 'HEP', 'sub' => 'Hepatitis C', 'colors' => ['#065f46', '#14b8a6']],
            'Oncology' => ['prefix' => 'ONC', 'sub' => 'Solid tumours', 'colors' => ['#4c1d95', '#a855f7']],
            'Respiratory' => ['prefix' => 'RESP', 'sub' => 'Asthma', 'colors' => ['#0e7490', '#06b6d4']],
            'Infectious Diseases' => ['prefix' => 'INF', 'sub' => 'Antibiotics', 'colors' => ['#7f1d1d', '#ef4444']],
            'Dermatology' => ['prefix' => 'DERM', 'sub' => 'Eczema', 'colors' => ['#7c2d12', '#f97316']],
            'Neurology' => ['prefix' => 'NEUR', 'sub' => 'Epilepsy', 'colors' => ['#312e81', '#8b5cf6']],
            'Gastroenterology' => ['prefix' => 'GAST', 'sub' => 'GERD', 'colors' => ['#713f12', '#eab308']],
            'Other' => ['prefix' => 'OTH', 'sub' => 'Other', 'colors' => ['#334155', '#64748b']],
        ];

        $productSpecs = [
            'Cardiovascular' => [
                ['Crestor', 'rosuvastatin', '10 mg', 'Tablet'],
                ['Zocor', 'simvastatin', '20 mg', 'Tablet'],
                ['Plavix', 'clopidogrel', '75 mg', 'Tablet'],
                ['Norvasc', 'amlodipine', '5 mg', 'Tablet'],
                ['Metoprolol', 'metoprolol tartrate', '50 mg', 'Tablet'],
                ['Aldactone', 'spironolactone', '25 mg', 'Tablet'],
            ],
            'Diabetes' => [
                ['Glucophage', 'metformin', '500 mg', 'Tablet'],
                ['Amaryl', 'glimepiride', '2 mg', 'Tablet'],
                ['Onglyza', 'saxagliptin', '5 mg', 'Tablet'],
                ['Trajenta', 'linagliptin', '5 mg', 'Tablet'],
                ['Victoza', 'liraglutide', '6 mg/ml', 'Injection'],
                ['Lantus', 'insulin glargine', '100 units/ml', 'Injection'],
            ],
            'Hepatology' => [
                ['Vemlidy', 'tenofovir alafenamide', '25 mg', 'Tablet'],
                ['Epivir', 'lamivudine', '100 mg', 'Tablet'],
                ['Pegasys', 'peginterferon alfa-2a', '180 mcg', 'Injection'],
                ['Sovaldi', 'sofosbuvir', '400 mg', 'Tablet'],
                ['Harvoni', 'ledipasvir/sofosbuvir', '90/400 mg', 'Tablet'],
                ['Daklinza', 'daclatasvir', '60 mg', 'Tablet'],
            ],
            'Oncology' => [
                ['Keytruda', 'pembrolizumab', '100 mg', 'Injection'],
                ['Opdivo', 'nivolumab', '240 mg', 'Injection'],
                ['Avastin', 'bevacizumab', '400 mg', 'Injection'],
                ['Herceptin', 'trastuzumab', '440 mg', 'Injection'],
                ['Imbruvica', 'ibrutinib', '420 mg', 'Capsule'],
                ['Xeloda', 'capecitabine', '500 mg', 'Tablet'],
            ],
            'Respiratory' => [
                ['Advair', 'fluticasone/salmeterol', '250/50 mcg', 'Inhalation'],
                ['Spiriva', 'tiotropium', '18 mcg', 'Inhalation'],
                ['Ventolin', 'salbutamol', '100 mcg', 'Inhalation'],
                ['Seretide', 'fluticasone/salmeterol', '125/25 mcg', 'Inhalation'],
                ['Nasonex', 'mometasone', '50 mcg', 'Nasal spray'],
                ['Singulair', 'montelukast', '10 mg', 'Tablet'],
            ],
            'Infectious Diseases' => [
                ['Zithromax', 'azithromycin', '500 mg', 'Tablet'],
                ['Cipro', 'ciprofloxacin', '500 mg', 'Tablet'],
                ['Diflucan', 'fluconazole', '150 mg', 'Capsule'],
                ['Valtrex', 'valacyclovir', '500 mg', 'Tablet'],
                ['Flagyl', 'metronidazole', '400 mg', 'Tablet'],
                ['Tamiflu', 'oseltamivir', '75 mg', 'Capsule'],
            ],
            'Dermatology' => [
                ['Elidel', 'pimecrolimus', '1% cream', 'Cream'],
                ['Dovonex', 'calcipotriol', '50 mcg/g', 'Ointment'],
                ['Differin', 'adapalene', '0.1% gel', 'Gel'],
                ['Lamisil', 'terbinafine', '250 mg', 'Tablet'],
                ['Accutane', 'isotretinoin', '20 mg', 'Capsule'],
                ['Elocon', 'mometasone furoate', '0.1% cream', 'Cream'],
            ],
            'Neurology' => [
                ['Keppra', 'levetiracetam', '500 mg', 'Tablet'],
                ['Lamictal', 'lamotrigine', '100 mg', 'Tablet'],
                ['Topamax', 'topiramate', '50 mg', 'Tablet'],
                ['Neurontin', 'gabapentin', '300 mg', 'Capsule'],
                ['Imitrex', 'sumatriptan', '50 mg', 'Tablet'],
                ['Tegretol', 'carbamazepine', '200 mg', 'Tablet'],
            ],
            'Gastroenterology' => [
                ['Pentasa', 'mesalazine', '500 mg', 'Tablet'],
                ['Motilium', 'domperidone', '10 mg', 'Tablet'],
                ['Zofran', 'ondansetron', '4 mg', 'Tablet'],
                ['Creon', 'pancrelipase', '25000 units', 'Capsule'],
                ['Prevacid', 'lansoprazole', '30 mg', 'Capsule'],
                ['Imodium', 'loperamide', '2 mg', 'Capsule'],
            ],
            'Other' => [
                ['Panadol', 'paracetamol', '500 mg', 'Tablet'],
                ['Brufen', 'ibuprofen', '400 mg', 'Tablet'],
                ['Zyrtec', 'cetirizine', '10 mg', 'Tablet'],
                ['Augmentin', 'amoxicillin/clavulanate', '625 mg', 'Tablet'],
                ['Nexium', 'esomeprazole', '40 mg', 'Capsule'],
                ['Lyrica', 'pregabalin', '75 mg', 'Capsule'],
            ],
        ];

        $downloadImages = filter_var(env('SEEDER_DOWNLOAD_IMAGES', false), FILTER_VALIDATE_BOOLEAN);

        $index = 0;
        foreach ($productSpecs as $category => $items) {
            $meta = $categoryMeta[$category];
            foreach ($items as $i => $item) {
                $index++;
                [$brand, $generic, $strength, $form] = $item;

                $internalId = 'DEMO-' . $meta['prefix'] . '-' . str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
                $existing = Product::where('internal_product_id', $internalId)->first();
                $baseSlug = Str::slug($brand . '-' . $strength);
                $slug = $existing?->url_slug ?: Product::uniqueSlug($baseSlug);

                $product = Product::updateOrCreate(
                    ['internal_product_id' => $internalId],
                    [
                        'brand_name' => $brand,
                        'generic_inn_name' => $generic,
                        'other_name' => null,
                        'therapeutic_category' => $category,
                        'subcategory' => $meta['sub'],
                        'dosage_form' => $form,
                        'strength' => $strength,
                        'pack_size_spec' => '30 units per pack',
                        'route_admin' => $form === 'Inhalation' ? 'Inhalation' : 'Oral',
                        'manufacturer_id' => $manufacturerIds[$index % count($manufacturerIds)],
                        'country_of_origin' => 'United States',
                        'legal_status' => 'Prescription only',
                        'active_ingredients' => $generic . ' ' . $strength,
                        'short_description' => "$brand is a demo product used to illustrate the MedSource catalogue layout.",
                        'full_description' => "$brand ($generic) $strength is a demonstration entry for the $category category.",
                        'approved_indication' => 'Indicated for demonstration and layout preview purposes.',
                        'product_images' => ["images/products/{$slug}.jpg"],
                        'image_alt_text' => "$brand packaging",
                        'dosage_admin_text' => 'As directed by a healthcare professional.',
                        'safety_info' => 'Demo product. No actual safety data.',
                        'drug_interactions' => 'No demo interactions.',
                        'precautions' => 'Demo product.',
                        'storage_conditions' => 'Store in a cool dry place.',
                        'availability_status' => 'Information available',
                        'country_market' => ['Global'],
                        'page_language' => 'English',
                        'enquiry_contact_link' => '/professional-enquiry',
                        'url_slug' => $slug,
                        'seo_title' => "$brand $strength",
                        'meta_description' => "Demo page for $brand $strength.",
                        'official_source_url' => 'https://example.com',
                        'last_verified_date' => now()->subDays(rand(1, 30))->format('Y-m-d'),
                        'content_status' => 'Published',
                        'reviewer_approver' => 'Admin User',
                        'has_known_interactions' => false,
                        'has_precautions' => false,
                        'information_disclaimer' => 'This is demo product information.',
                    ]
                );

                if ($downloadImages) {
                    $this->writeProductImage($product);
                }
            }
        }
    }

    private function writeProductImage(Product $product): void
    {
        $slug = $product->url_slug;
        $path = public_path("images/products/{$slug}.jpg");

        if (file_exists($path)) {
            return;
        }

        $brand = $product->brand_name;
        $strength = $product->strength;
        $form = $product->dosage_form;

        $prompt = "Professional product photo of {$brand} {$strength} {$form} medication, pharmaceutical packaging, clean white background, studio lighting, high resolution, no text, no watermark";
        $url = 'https://image.pollinations.ai/prompt/' . urlencode($prompt) . "?width=600&height=800&nologo=true&seed={$slug}&model=flux";

        File::ensureDirectoryExists(public_path('images/products'));

        $image = @file_get_contents($url);
        if ($image !== false) {
            File::put($path, $image);
        }
    }
}
