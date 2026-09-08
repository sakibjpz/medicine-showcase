<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Enquiry;
use App\Models\Manufacturer;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public static function seedPages(): array
    {
        return [
        'products' => [
            'heading' => 'All Products',
            'lead'    => 'A comprehensive, sortable and filterable list of pharmaceutical products available through MedSource.',
            'breadcrumbs' => [
                ['label' => 'Product Information'],
                ['label' => 'All Products'],
            ],
            'content' => '<p>Browse our complete product catalogue. Advanced filters by dosage form, active ingredient, market status and therapeutic area will be available in the next iteration.</p>'.
                         '<div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">'.
                         '<div class="p-5 border border-slate-200 rounded-lg"><h3 class="font-semibold text-med-900">Cardiotonix 10 mg</h3><p class="text-sm text-slate-500 mt-1">Cardiology · Tablet · Marketed</p></div>'.
                         '<div class="p-5 border border-slate-200 rounded-lg"><h3 class="font-semibold text-med-900">Oncostat 500 mg</h3><p class="text-sm text-slate-500 mt-1">Oncology · Vial · Marketed</p></div>'.
                         '<div class="p-5 border border-slate-200 rounded-lg"><h3 class="font-semibold text-med-900">Diabetrol 5 mg</h3><p class="text-sm text-slate-500 mt-1">Endocrinology · Tablet · Marketed</p></div>'.
                         '</div>',
        ],
        'products.search' => [
            'heading' => 'Product Search',
            'lead'    => 'Use advanced filters to find products by dosage, form, therapeutic area, manufacturer and more.',
            'breadcrumbs' => [
                ['label' => 'Product Information'],
                ['label' => 'Product Search'],
            ],
            'content' => '<form action="'.'/search'.'" method="GET" class="space-y-4">'.
                         '<div class="grid grid-cols-1 md:grid-cols-3 gap-4">'.
                         '<div><label class="block text-sm font-medium text-slate-700">Keyword</label><input type="text" name="q" class="mt-1 w-full rounded border-slate-300" placeholder="Product name, INN..."></div>'.
                         '<div><label class="block text-sm font-medium text-slate-700">Dosage form</label><select name="form" class="mt-1 w-full rounded border-slate-300"><option value="">Any</option><option>Tablet</option><option>Capsule</option><option>Injection</option></select></div>'.
                         '<div><label class="block text-sm font-medium text-slate-700">Therapeutic area</label><select name="area" class="mt-1 w-full rounded border-slate-300"><option value="">Any</option><option>Cardiology</option><option>Oncology</option><option>Infectious Diseases</option></select></div>'.
                         '</div>'.
                         '<button type="submit" class="btn-primary">Search</button>'.
                         '</form>',
        ],
        'products.documents' => [
            'heading' => 'Product Documents',
            'lead'    => 'Repository of product leaflets, SMPCs, clinical data PDFs and regulatory documentation.',
            'breadcrumbs' => [
                ['label' => 'Product Information'],
                ['label' => 'Product Documents'],
            ],
            'content' => '<ul class="space-y-3">'.
                         '<li><a href="#" class="text-med-700 hover:underline font-medium">Cardiotonix 10 mg – Patient Information Leaflet (PDF)</a></li>'.
                         '<li><a href="#" class="text-med-700 hover:underline font-medium">Oncostat 500 mg – Summary of Product Characteristics (PDF)</a></li>'.
                         '<li><a href="#" class="text-med-700 hover:underline font-medium">Diabetrol 5 mg – Clinical Study Report (PDF)</a></li>'.
                         '<li><a href="#" class="text-med-700 hover:underline font-medium">Safety Update Q2 2026 (PDF)</a></li>'.
                         '</ul>',
        ],
        'products.country-status' => [
            'heading' => 'Country Status',
            'lead'    => 'Global map and table showing product registration and availability by country.',
            'breadcrumbs' => [
                ['label' => 'Product Information'],
                ['label' => 'Country Status'],
            ],
            'content' => '<p class="mb-4">Select a country to see registration status and locally available presentations.</p>'.
                         '<table class="w-full text-left text-sm border border-slate-200 rounded overflow-hidden">'.
                         '<thead class="bg-med-50 text-med-900"><tr><th class="px-4 py-3">Product</th><th class="px-4 py-3">Country</th><th class="px-4 py-3">Status</th></tr></thead>'.
                         '<tbody class="divide-y divide-slate-100">'.
                         '<tr><td class="px-4 py-3">Cardiotonix 10 mg</td><td class="px-4 py-3">United States</td><td class="px-4 py-3 text-green-700 font-medium">Registered</td></tr>'.
                         '<tr><td class="px-4 py-3">Oncostat 500 mg</td><td class="px-4 py-3">Germany</td><td class="px-4 py-3 text-green-700 font-medium">Registered</td></tr>'.
                         '<tr><td class="px-4 py-3">Diabetrol 5 mg</td><td class="px-4 py-3">India</td><td class="px-4 py-3 text-amber-600 font-medium">Pending</td></tr>'.
                         '</tbody></table>',
        ],
        'therapeutic-areas' => [
            'heading' => 'Therapeutic Areas',
            'lead'    => 'Explore medical conditions and the products available for each therapeutic area.',
            'breadcrumbs' => [
                ['label' => 'Explore'],
                ['label' => 'Therapeutic Areas'],
            ],
            'content' => '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">'.
                         '<a href="'.'/products'.'" class="p-5 border border-slate-200 rounded-lg hover:border-med-500 transition"><h3 class="font-semibold text-med-900">Cardiology</h3><p class="text-sm text-slate-500 mt-1">Hypertension, heart failure, anticoagulation</p></a>'.
                         '<a href="'.'/products'.'" class="p-5 border border-slate-200 rounded-lg hover:border-med-500 transition"><h3 class="font-semibold text-med-900">Oncology</h3><p class="text-sm text-slate-500 mt-1">Solid tumours, haematology, supportive care</p></a>'.
                         '<a href="'.'/products'.'" class="p-5 border border-slate-200 rounded-lg hover:border-med-500 transition"><h3 class="font-semibold text-med-900">Infectious Diseases</h3><p class="text-sm text-slate-500 mt-1">Antibiotics, antivirals, vaccines</p></a>'.
                         '<a href="'.'/products'.'" class="p-5 border border-slate-200 rounded-lg hover:border-med-500 transition"><h3 class="font-semibold text-med-900">Endocrinology</h3><p class="text-sm text-slate-500 mt-1">Diabetes, thyroid, metabolism</p></a>'.
                         '<a href="'.'/products'.'" class="p-5 border border-slate-200 rounded-lg hover:border-med-500 transition"><h3 class="font-semibold text-med-900">Neurology</h3><p class="text-sm text-slate-500 mt-1">Epilepsy, migraine, neurodegeneration</p></a>'.
                         '<a href="'.'/products'.'" class="p-5 border border-slate-200 rounded-lg hover:border-med-500 transition"><h3 class="font-semibold text-med-900">Respiratory</h3><p class="text-sm text-slate-500 mt-1">Asthma, COPD, allergy</p></a>'.
                         '</div>',
        ],
        'manufacturers' => [
            'heading' => 'Manufacturers',
            'lead'    => 'Directory of pharmaceutical companies and their product portfolios.',
            'breadcrumbs' => [
                ['label' => 'Explore'],
                ['label' => 'Manufacturers'],
            ],
            'content' => '<ul class="divide-y divide-slate-100 border border-slate-200 rounded-lg">'.
                         '<li class="px-4 py-3"><a href="#" class="font-medium text-med-700 hover:underline">Novartis</a><p class="text-sm text-slate-500">Global portfolio across oncology and cardiology</p></li>'.
                         '<li class="px-4 py-3"><a href="#" class="font-medium text-med-700 hover:underline">Pfizer</a><p class="text-sm text-slate-500">Vaccines, anti-infectives and oncology</p></li>'.
                         '<li class="px-4 py-3"><a href="#" class="font-medium text-med-700 hover:underline">Roche</a><p class="text-sm text-slate-500">Biologics and personalised healthcare</p></li>'.
                         '</ul>',
        ],
        'countries-languages' => [
            'heading' => 'Countries & Languages',
            'lead'    => 'Region-specific landing pages and language switching for MedSource.',
            'breadcrumbs' => [
                ['label' => 'Explore'],
                ['label' => 'Countries & Languages'],
            ],
            'content' => '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">'.
                         '<a href="#" class="p-4 border border-slate-200 rounded-lg hover:border-med-500 transition">United States <span class="text-slate-400">– English</span></a>'.
                         '<a href="#" class="p-4 border border-slate-200 rounded-lg hover:border-med-500 transition">United Kingdom <span class="text-slate-400">– English</span></a>'.
                         '<a href="#" class="p-4 border border-slate-200 rounded-lg hover:border-med-500 transition">Germany <span class="text-slate-400">– Deutsch</span></a>'.
                         '<a href="#" class="p-4 border border-slate-200 rounded-lg hover:border-med-500 transition">France <span class="text-slate-400">– Français</span></a>'.
                         '<a href="#" class="p-4 border border-slate-200 rounded-lg hover:border-med-500 transition">Spain <span class="text-slate-400">– Español</span></a>'.
                         '<a href="#" class="p-4 border border-slate-200 rounded-lg hover:border-med-500 transition">Italy <span class="text-slate-400">– Italiano</span></a>'.
                         '<a href="#" class="p-4 border border-slate-200 rounded-lg hover:border-med-500 transition">India <span class="text-slate-400">– English / हिंदी</span></a>'.
                         '<a href="#" class="p-4 border border-slate-200 rounded-lg hover:border-med-500 transition">Japan <span class="text-slate-400">– 日本語</span></a>'.
                         '</div>',
        ],
        'how-it-works' => [
            'heading' => 'How It Works',
            'lead'    => 'A quick onboarding guide to using the MedSource platform.',
            'breadcrumbs' => [
                ['label' => 'Support'],
                ['label' => 'How It Works'],
            ],
            'content' => '<ol class="list-decimal pl-5 space-y-3 marker:text-med-700 marker:font-bold">'.
                         '<li><strong>Search</strong> for a product, manufacturer or topic using the global search bar.</li>'.
                         '<li><strong>Browse</strong> therapeutic areas, manufacturers or product documents.</li>'.
                         '<li><strong>Compare</strong> country availability and regulatory status.</li>'.
                         '<li><strong>Request</strong> professional information or submit an enquiry to our team.</li>'.
                         '</ol>',
        ],
        'quality-compliance' => [
            'heading' => 'Quality & Compliance',
            'lead'    => 'Certifications, ISO and GMP policies, and quality-first operating principles.',
            'breadcrumbs' => [
                ['label' => 'Trust & Quality'],
                ['label' => 'Quality & Compliance'],
            ],
            'content' => '<p>MedSource is built on a quality-first foundation. We display and verify:</p>'.
                         '<ul class="list-disc pl-5 space-y-2 mt-4">'.
                         '<li>ISO 9001 quality management certification</li>'.
                         '<li>Good Manufacturing Practice (GMP) alignment</li>'.
                         '<li>Good Distribution Practice (GDP) for logistics partners</li>'.
                         '<li>Regular audits and pharmacovigilance commitments</li>'.
                         '</ul>',
        ],
        'safety-notices' => [
            'heading' => 'Safety Notices',
            'lead'    => 'Real-time feed of drug recalls, FDA/EMA alerts and safety warnings.',
            'breadcrumbs' => [
                ['label' => 'Trust & Quality'],
                ['label' => 'Safety Notices'],
            ],
            'content' => '<div class="space-y-4">'.
                         '<div class="p-4 border-l-4 border-amber-500 bg-amber-50 rounded-r"><p class="font-semibold text-amber-900">Recall: Lot #2026-A of Product X</p><p class="text-sm text-amber-800">Updated 1 Sep 2026 · Affected markets: US, Canada</p></div>'.
                         '<div class="p-4 border-l-4 border-red-600 bg-red-50 rounded-r"><p class="font-semibold text-red-900">FDA Alert: New contraindication for Drug Y</p><p class="text-sm text-red-800">Updated 28 Aug 2026 · Healthcare professionals advised</p></div>'.
                         '<div class="p-4 border-l-4 border-blue-600 bg-blue-50 rounded-r"><p class="font-semibold text-blue-900">EMA Update: Labelling revision</p><p class="text-sm text-blue-800">Updated 25 Aug 2026 · European Union</p></div>'.
                         '</div>',
        ],
        'official-sources' => [
            'heading' => 'Official Sources',
            'lead'    => 'Curated links to global regulatory bodies and healthcare organisations.',
            'breadcrumbs' => [
                ['label' => 'Trust & Quality'],
                ['label' => 'Official Sources'],
            ],
            'content' => '<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">'.
                         '<a href="https://www.fda.gov" target="_blank" rel="noopener" class="p-4 border border-slate-200 rounded-lg hover:border-med-500 transition"><strong class="text-med-900">FDA</strong><p class="text-sm text-slate-500">U.S. Food and Drug Administration</p></a>'.
                         '<a href="https://www.ema.europa.eu" target="_blank" rel="noopener" class="p-4 border border-slate-200 rounded-lg hover:border-med-500 transition"><strong class="text-med-900">EMA</strong><p class="text-sm text-slate-500">European Medicines Agency</p></a>'.
                         '<a href="https://www.who.int" target="_blank" rel="noopener" class="p-4 border border-slate-200 rounded-lg hover:border-med-500 transition"><strong class="text-med-900">WHO</strong><p class="text-sm text-slate-500">World Health Organization</p></a>'.
                         '<a href="https://www.ich.org" target="_blank" rel="noopener" class="p-4 border border-slate-200 rounded-lg hover:border-med-500 transition"><strong class="text-med-900">ICH</strong><p class="text-sm text-slate-500">International Council for Harmonisation</p></a>'.
                         '</div>',
        ],
        'accessibility' => [
            'heading' => 'Accessibility',
            'lead'    => 'Our commitment to WCAG 2.1 AA standards and inclusive design.',
            'breadcrumbs' => [
                ['label' => 'Trust & Quality'],
                ['label' => 'Accessibility'],
            ],
            'content' => '<p>MedSource is committed to digital accessibility. We aim to meet WCAG 2.1 Level AA across all 33 page templates.</p>'.
                         '<ul class="list-disc pl-5 space-y-2 mt-4">'.
                         '<li>Keyboard-navigable menus, search and forms</li>'.
                         '<li>Clear focus indicators and logical tab order</li>'.
                         '<li>Text contrast of at least 4.5:1 for body copy</li>'.
                         '<li>Responsive layouts from 375 px to 1920 px viewports</li>'.
                         '<li>Semantic HTML and ARIA labels where appropriate</li>'.
                         '</ul>',
        ],
        'articles-updates' => [
            'heading' => 'Articles & Updates',
            'lead'    => 'News, medical breakthroughs and platform updates from the MedSource team.',
            'breadcrumbs' => [
                ['label' => 'Resources & Help'],
                ['label' => 'Articles & Updates'],
            ],
            'content' => '<div class="space-y-6">'.
                         '<article class="border-b border-slate-100 pb-6"><h3 class="font-semibold text-med-900 text-lg">New biosimilar approvals in Q3 2026</h3><p class="text-sm text-slate-500 mb-2">2 Sep 2026</p><p>A summary of recent biosimilar launches across the EU and US markets with pipeline implications.</p></article>'.
                         '<article class="border-b border-slate-100 pb-6"><h3 class="font-semibold text-med-900 text-lg">MedSource search engine upgrade</h3><p class="text-sm text-slate-500 mb-2">25 Aug 2026</p><p>Improved relevance scoring and sub-500 ms response time for standard queries.</p></article>'.
                         '<article class="border-b border-slate-100 pb-6"><h3 class="font-semibold text-med-900 text-lg">Safety reporting made simpler</h3><p class="text-sm text-slate-500 mb-2">10 Aug 2026</p><p>New one-click reporting links connect healthcare professionals directly to national regulators.</p></article>'.
                         '</div>',
        ],
        'faq' => [
            'heading' => 'Frequently Asked Questions',
            'lead'    => 'Answers to common questions from healthcare professionals, pharmacists and patients.',
            'breadcrumbs' => [
                ['label' => 'Resources & Help'],
                ['label' => 'FAQ'],
            ],
            'content' => '<div class="space-y-4" x-data="{ open: 1 }">'. // placeholder; real accordion via component
                         '<details class="border border-slate-200 rounded-lg p-4 group" open><summary class="font-medium text-med-900 cursor-pointer list-none flex justify-between items-center"><span>Who can use MedSource?</span><span class="text-med-500 group-open:rotate-180 transition">▼</span></summary><p class="mt-3 text-slate-600">MedSource is open to healthcare professionals, pharmacists, regulatory officers and informed patients.</p></details>'.
                         '<details class="border border-slate-200 rounded-lg p-4 group"><summary class="font-medium text-med-900 cursor-pointer list-none flex justify-between items-center"><span>Is the product information free?</span><span class="text-med-500 group-open:rotate-180 transition">▼</span></summary><p class="mt-3 text-slate-600">Yes, public product summaries and regulatory status are provided free of charge.</p></details>'.
                         '<details class="border border-slate-200 rounded-lg p-4 group"><summary class="font-medium text-med-900 cursor-pointer list-none flex justify-between items-center"><span>How do I report a safety issue?</span><span class="text-med-500 group-open:rotate-180 transition">▼</span></summary><p class="mt-3 text-slate-600">Visit the Safety Notices page or use the direct reporting links in the product documents section.</p></details>'.
                         '</div>',
        ],
        'contact-hub' => [
            'heading' => 'Contact Hub',
            'lead'    => 'Unified contact page with forms, email, phone numbers and support tickets.',
            'breadcrumbs' => [
                ['label' => 'Resources & Help'],
                ['label' => 'Contact Hub'],
            ],
            'content' => '<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">'.
                         '<div>'.
                         '<h3 class="font-semibold text-med-900 mb-2">General enquiries</h3>'.
                         '<p class="text-slate-600 mb-1">Email: <a href="mailto:info@medsource.example" class="text-med-700 hover:underline">info@medsource.example</a></p>'.
                         '<p class="text-slate-600 mb-4">Phone: <a href="tel:+1234567890" class="text-med-700 hover:underline">+1 (234) 567-890</a></p>'.
                         '<h3 class="font-semibold text-med-900 mb-2">Support tickets</h3>'.
                         '<p class="text-slate-600">Use the contact form below or submit a professional enquiry for priority handling.</p>'.
                         '</div>'.
                         '<form method="POST" action="'.'/contact-hub'.'" class="space-y-4">'.csrf_field().
                         '<div><label class="block text-sm font-medium text-slate-700">Name</label><input type="text" name="name" required class="mt-1 w-full rounded border-slate-300"></div>'.
                         '<div><label class="block text-sm font-medium text-slate-700">Email</label><input type="email" name="email" required class="mt-1 w-full rounded border-slate-300"></div>'.
                         '<div><label class="block text-sm font-medium text-slate-700">Message</label><textarea name="message" rows="4" required class="mt-1 w-full rounded border-slate-300"></textarea></div>'.
                         '<button type="submit" class="btn-primary">Send Message</button>'.
                         '</form>'.
                         '</div>',
        ],
        'privacy' => [
            'heading' => 'Privacy Policy',
            'lead'    => 'How MedSource collects, uses and protects your personal information.',
            'breadcrumbs' => [
                ['label' => 'Transparency'],
                ['label' => 'Privacy Policy'],
            ],
            'content' => '<p>MedSource respects your privacy. This policy describes the data we collect, how it is used and your rights.</p>'.
                         '<h3 class="font-semibold text-med-900 mt-6 mb-2">1. Information we collect</h3><p>We collect information you provide through forms, enquiries and account registration, plus standard log data.</p>'.
                         '<h3 class="font-semibold text-med-900 mt-6 mb-2">2. Use of information</h3><p>Your data is used to respond to enquiries, improve the platform and comply with legal obligations.</p>'.
                         '<h3 class="font-semibold text-med-900 mt-6 mb-2">3. Data protection</h3><p>All form submissions are transmitted via HTTPS and stored with access controls.</p>',
        ],
        'terms' => [
            'heading' => 'Terms of Service',
            'lead'    => 'Terms and conditions for using the MedSource platform.',
            'breadcrumbs' => [
                ['label' => 'Transparency'],
                ['label' => 'Terms of Service'],
            ],
            'content' => '<p>By accessing MedSource, you agree to these terms. The platform is provided for professional information purposes and is not a substitute for medical advice.</p>'.
                         '<h3 class="font-semibold text-med-900 mt-6 mb-2">1. Use of content</h3><p>Product information is for reference only. Always consult local prescribing information and a qualified healthcare professional.</p>'.
                         '<h3 class="font-semibold text-med-900 mt-6 mb-2">2. Liability</h3><p>MedSource is not liable for clinical decisions made based on the information provided.</p>'.
                         '<h3 class="font-semibold text-med-900 mt-6 mb-2">3. Changes to terms</h3><p>We may update these terms from time to time. Continued use constitutes acceptance.</p>',
        ],
        'about' => [
            'heading' => 'About MedSource',
            'lead'    => 'Global pharmaceutical information, delivered with quality and trust.',
            'breadcrumbs' => [
                ['label' => 'About MedSource'],
            ],
            'content' => '<p>MedSource is a professional-grade medical information portal for healthcare professionals, pharmacists and informed patients. We centralise product data, regulatory information, therapeutic education and corporate transparency.</p>'.
                         '<p>Our mission is to make accurate, globally relevant pharmaceutical information easy to find and understand, while maintaining the highest standards of quality and compliance.</p>',
        ],
        'professional-enquiry' => [
            'heading' => 'Professional Enquiry',
            'lead'    => 'Submit a B2B or professional enquiry. We provide quality assurance, worldwide reach and a professional response.',
            'breadcrumbs' => [
                ['label' => 'Professional Enquiry'],
            ],
            'content' => '<p>Use the form below to reach our team for product information, partnership opportunities or regulatory questions.</p>',
        ],
        'home' => [
            'heading' => 'Global pharmaceutical information. Quality-first professional access.',
            'lead'    => 'MedSource centralises product data, regulatory information, therapeutic education and corporate transparency for healthcare professionals, pharmacists and informed patients.',
            'breadcrumbs' => [],
            'content' => '<p>Welcome to MedSource.</p>',
        ],
        'navigation-overview' => [
            'heading' => 'Navigation overview',
            'lead'    => 'Explore MedSource by category.',
            'breadcrumbs' => [],
            'content' => '',
        ],
        ];
    }

    private static function curatedIndex(): array
    {
        return [
            ['type' => 'Product', 'title' => 'Cardiotonix 10 mg', 'url' => '/products', 'summary' => 'Cardiology tablet for hypertension.'],
            ['type' => 'Product', 'title' => 'Oncostat 500 mg', 'url' => '/products', 'summary' => 'Oncology injection for solid tumours.'],
            ['type' => 'Product', 'title' => 'Diabetrol 5 mg', 'url' => '/products', 'summary' => 'Endocrinology tablet for type 2 diabetes.'],
            ['type' => 'Manufacturer', 'title' => 'Novartis', 'url' => '/manufacturers', 'summary' => 'Global portfolio across oncology and cardiology.'],
            ['type' => 'Manufacturer', 'title' => 'Pfizer', 'url' => '/manufacturers', 'summary' => 'Vaccines, anti-infectives and oncology.'],
            ['type' => 'Manufacturer', 'title' => 'Roche', 'url' => '/manufacturers', 'summary' => 'Biologics and personalised healthcare.'],
            ['type' => 'Therapeutic Area', 'title' => 'Cardiology', 'url' => '/therapeutic-areas', 'summary' => 'Hypertension, heart failure, anticoagulation.'],
            ['type' => 'Therapeutic Area', 'title' => 'Oncology', 'url' => '/therapeutic-areas', 'summary' => 'Solid tumours, haematology, supportive care.'],
            ['type' => 'Therapeutic Area', 'title' => 'Infectious Diseases', 'url' => '/therapeutic-areas', 'summary' => 'Antibiotics, antivirals, vaccines.'],
            ['type' => 'Therapeutic Area', 'title' => 'Endocrinology', 'url' => '/therapeutic-areas', 'summary' => 'Diabetes, thyroid, metabolism.'],
            ['type' => 'Therapeutic Area', 'title' => 'Neurology', 'url' => '/therapeutic-areas', 'summary' => 'Epilepsy, migraine, neurodegeneration.'],
            ['type' => 'Therapeutic Area', 'title' => 'Respiratory', 'url' => '/therapeutic-areas', 'summary' => 'Asthma, COPD, allergy.'],
            ['type' => 'Country', 'title' => 'United States', 'url' => '/countries-languages', 'summary' => 'English'],
            ['type' => 'Country', 'title' => 'United Kingdom', 'url' => '/countries-languages', 'summary' => 'English'],
            ['type' => 'Country', 'title' => 'Germany', 'url' => '/countries-languages', 'summary' => 'Deutsch'],
            ['type' => 'Country', 'title' => 'France', 'url' => '/countries-languages', 'summary' => 'Français'],
            ['type' => 'Country', 'title' => 'Spain', 'url' => '/countries-languages', 'summary' => 'Español'],
            ['type' => 'Country', 'title' => 'Italy', 'url' => '/countries-languages', 'summary' => 'Italiano'],
            ['type' => 'Country', 'title' => 'India', 'url' => '/countries-languages', 'summary' => 'English / हिंदी'],
            ['type' => 'Country', 'title' => 'Japan', 'url' => '/countries-languages', 'summary' => '日本語'],
        ];
    }

    private function pageMatches(string $query)
    {
        return Page::where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('heading', 'like', "%{$query}%")
                  ->orWhere('lead', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('meta_title', 'like', "%{$query}%")
                  ->orWhere('slug', 'like', "%{$query}%");
            })
            ->get();
    }

    private function buildSearchResults(string $query, ?int $limit = null): array
    {
        $q = strtolower($query);

        $pageResults = $this->pageMatches($query)->map(function (Page $page) use ($q) {
            $score = 0;
            if (str_contains(strtolower($page->heading), $q)) $score += 10;
            if (str_contains(strtolower($page->lead ?? ''), $q)) $score += 5;
            if (str_contains(strtolower(strip_tags($page->content ?? '')), $q)) $score += 3;
            if (str_contains(strtolower($page->meta_title ?? ''), $q)) $score += 2;
            if (str_contains(strtolower($page->slug), $q)) $score += 1;

            return [
                'type' => 'Page',
                'title' => $page->heading,
                'url' => $page->slug === 'home' ? route('home') : url('/' . str_replace('.', '/', $page->slug)),
                'summary' => $page->lead ?: Str::limit(strip_tags($page->content ?? ''), 160),
                '_score' => $score,
            ];
        });

        $productResults = Product::with('manufacturer')
            ->published()
            ->where(function ($qb) use ($query) {
                $qb->where('brand_name', 'like', "%{$query}%")
                   ->orWhere('generic_inn_name', 'like', "%{$query}%")
                   ->orWhere('other_name', 'like', "%{$query}%")
                   ->orWhere('therapeutic_category', 'like', "%{$query}%")
                   ->orWhere('subcategory', 'like', "%{$query}%")
                   ->orWhere('strength', 'like', "%{$query}%")
                   ->orWhere('url_slug', 'like', "%{$query}%")
                   ->orWhere('short_description', 'like', "%{$query}%")
                   ->orWhereHas('manufacturer', fn ($m) => $m->where('name', 'like', "%{$query}%"));
            })
            ->get()
            ->map(function (Product $product) use ($q) {
                $score = 0;
                if (str_contains(strtolower($product->brand_name), $q)) $score += 15;
                if (str_contains(strtolower($product->generic_inn_name ?? ''), $q)) $score += 10;
                if (str_contains(strtolower($product->other_name ?? ''), $q)) $score += 7;
                if (str_contains(strtolower($product->therapeutic_category ?? ''), $q)) $score += 5;
                if (str_contains(strtolower($product->subcategory ?? ''), $q)) $score += 4;
                if (str_contains(strtolower($product->manufacturer?->name ?? ''), $q)) $score += 4;
                if (str_contains(strtolower($product->url_slug), $q)) $score += 3;
                if (str_contains(strtolower($product->short_description ?? ''), $q)) $score += 2;
                if (str_contains(strtolower($product->strength ?? ''), $q)) $score += 1;

                $image = $product->product_images[0] ?? null;
                if (empty($image) && file_exists(public_path('images/products/' . $product->url_slug . '.jpg'))) {
                    $image = asset('images/products/' . $product->url_slug . '.jpg');
                } elseif (! empty($image) && ! str_starts_with($image, 'http')) {
                    $image = asset($image);
                }
                if (empty($image)) {
                    $image = asset('images/products/placeholder.svg');
                }

                return [
                    'type' => 'Product',
                    'title' => $product->brand_name . ($product->strength ? ' ' . $product->strength : ''),
                    'url' => route('products.show', $product->url_slug),
                    'summary' => ($product->manufacturer?->name ? $product->manufacturer->name . ' · ' : '') . $product->generic_inn_name . ($product->therapeutic_category ? ' · ' . $product->therapeutic_category : ''),
                    'image' => $image,
                    '_score' => $score,
                ];
            });

        $manufacturerResults = Manufacturer::query()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('country', 'like', "%{$query}%")
            ->orWhere('slug', 'like', "%{$query}%")
            ->get()
            ->map(function (Manufacturer $m) use ($q) {
                $score = 0;
                if (str_contains(strtolower($m->name), $q)) $score += 12;
                if (str_contains(strtolower($m->country ?? ''), $q)) $score += 4;
                if (str_contains(strtolower($m->slug), $q)) $score += 1;

                return [
                    'type' => 'Manufacturer',
                    'title' => $m->name,
                    'url' => route('manufacturers'),
                    'summary' => $m->country ? $m->country . ' · Manufacturer' : 'Manufacturer',
                    '_score' => $score,
                ];
            });

        $categoryResults = Category::active()
            ->where(function ($qb) use ($query) {
                $qb->where('name', 'like', "%{$query}%")
                   ->orWhere('description', 'like', "%{$query}%")
                   ->orWhere('slug', 'like', "%{$query}%");
            })
            ->get()
            ->map(function (Category $c) use ($q) {
                $score = 0;
                if (str_contains(strtolower($c->name), $q)) $score += 11;
                if (str_contains(strtolower($c->description ?? ''), $q)) $score += 4;
                if (str_contains(strtolower($c->slug), $q)) $score += 1;
                if (! empty($c->subcategories) && collect($c->subcategories)->contains(fn ($s) => str_contains(strtolower($s), $q))) $score += 2;

                return [
                    'type' => 'Therapeutic Area',
                    'title' => $c->name,
                    'url' => route('therapeutic-areas'),
                    'summary' => $c->description ?: 'Browse ' . $c->name . ' products',
                    'image' => $c->image,
                    '_score' => $score,
                ];
            });

        $results = $pageResults
            ->merge($productResults)
            ->merge($manufacturerResults)
            ->merge($categoryResults)
            ->filter(fn ($item) => $item['_score'] > 0)
            ->sortByDesc('_score')
            ->values();

        if ($limit) {
            $results = $results->take($limit);
        }

        return $results->map(fn ($item) => collect($item)->except('_score')->all())->all();
    }

    public function suggest(Request $request)
    {
        $query = trim($request->input('q', ''));

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        return response()->json($this->buildSearchResults($query, 8));
    }

    public function index()
    {
        $page = Page::where('slug', 'home')->where('is_published', true)->first()
            ?? (object) ['heading' => 'Global pharmaceutical information. Quality-first professional access.', 'lead' => '', 'content' => ''];

        $categories = Category::active()->orderBy('sort_order')->orderBy('name')->get();

        $productsByCategory = Product::with('manufacturer')
            ->whereIn('content_status', ['Approved', 'Published'])
            ->orderBy('brand_name')
            ->get()
            ->groupBy('therapeutic_category');

        return view('pages.home', [
            'title' => 'MedSource – Global Medical Platforms and Centers',
            'breadcrumbs' => [],
            'heading' => $page->heading,
            'lead' => $page->lead ?? '',
            'page' => $page,
            'categories' => $categories,
            'productCounts' => Product::selectRaw('therapeutic_category, COUNT(*) as count')
                ->groupBy('therapeutic_category')
                ->pluck('count', 'therapeutic_category'),
            'categoryImages' => $this->categoryImages($categories),
            'productsByCategory' => $productsByCategory,
        ]);
    }

    public function show(Request $request, $slug)
    {
        $page = Page::where('slug', $slug)->where('is_published', true)->firstOrFail();

        $viewData = [
            'title' => $page->meta_title ?? $page->heading,
            'heading' => $page->heading,
            'lead' => $page->lead ?? '',
            'breadcrumbs' => $page->breadcrumbs ?? [],
            'content' => $page->content ?? '',
            'page' => $page,
        ];

        if ($page->slug === 'therapeutic-areas') {
            $categories = Category::active()->orderBy('sort_order')->orderBy('name')->get();

            $viewData['categories'] = $categories;
            $viewData['productCounts'] = Product::selectRaw('therapeutic_category, COUNT(*) as count')
                ->groupBy('therapeutic_category')
                ->pluck('count', 'therapeutic_category');
            $viewData['categoryImages'] = $this->categoryImages($categories);

            return view('pages.therapeutic-areas', $viewData);
        }

        if ($page->slug === 'products') {
            $viewData['products'] = Product::published()->orderBy('brand_name')->get();

            return view('pages.products', $viewData);
        }

        return view('pages.content', $viewData);
    }

    private function categoryImages(iterable $categories): array
    {
        $map = [];

        foreach ($categories as $category) {
            $name = $category->name;
            $slug = $category->slug ?: Str::slug($name);

            if ($category->image) {
                $map[$name] = $category->image;
                continue;
            }

            $found = false;
            foreach (['jpg', 'jpeg', 'png', 'webp', 'svg'] as $ext) {
                if (file_exists(public_path("images/categories/{$slug}.{$ext}"))) {
                    $map[$name] = asset("images/categories/{$slug}.{$ext}");
                    $found = true;
                    break;
                }
            }
            if ($found) {
                continue;
            }

            $product = Product::where('therapeutic_category', $name)
                ->whereNotNull('product_images')
                ->first(['product_images']);

            $map[$name] = $product->product_images[0] ?? null;
        }

        return $map;
    }

    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));

        $results = [];

        if ($query !== '') {
            $results = $this->buildSearchResults($query);
        }

        return view('pages.search', [
            'title' => 'Search Results',
            'heading' => 'Search Results',
            'lead' => $query ? 'Results for "'.e($query).'"' : 'Enter a product, manufacturer, therapeutic area, country or page above.',
            'breadcrumbs' => [
                ['label' => 'Search'],
            ],
            'query' => $query,
            'results' => $results,
        ]);
    }

    public function enquiry(Request $request)
    {
        $page = Page::where('slug', 'professional-enquiry')->where('is_published', true)->first()
            ?? (object) ['heading' => 'Professional Enquiry', 'lead' => '', 'content' => ''];

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'institution' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'inquiry_type' => 'required|string|in:Product Information,Partnership,Regulatory,Other',
                'message' => 'nullable|string|max:5000',
            ]);

            Enquiry::create($validated);

            return redirect()->route('professional-enquiry')
                ->with('success', 'Thank you for your enquiry. Our team will respond professionally.');
        }

        return view('pages.enquiry', [
            'title' => $page->meta_title ?? $page->heading,
            'heading' => $page->heading,
            'lead' => $page->lead ?? '',
            'breadcrumbs' => $page->breadcrumbs ?? [['label' => 'Professional Enquiry']],
            'page' => $page,
        ]);
    }

    public function navigationOverview()
    {
        $page = Page::where('slug', 'navigation-overview')->where('is_published', true)->first()
            ?? (object) ['heading' => 'Navigation overview', 'lead' => 'Explore MedSource by category.', 'content' => ''];

        return view('pages.navigation-overview', [
            'title' => $page->heading . ' – MedSource',
            'breadcrumbs' => [],
            'heading' => $page->heading,
            'lead' => $page->lead ?? '',
            'page' => $page,
        ]);
    }

    public function contact(Request $request)
    {
        $page = Page::where('slug', 'contact-hub')->where('is_published', true)->first()
            ?? (object) ['heading' => 'Contact Hub', 'lead' => '', 'content' => ''];

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'subject' => 'nullable|string|max:255',
                'message' => 'required|string|max:5000',
            ]);

            ContactMessage::create($validated);

            return redirect()->route('contact-hub')
                ->with('success', 'Thank you for your message. We will be in touch shortly.');
        }

        return view('pages.contact', [
            'title' => $page->meta_title ?? $page->heading,
            'heading' => $page->heading,
            'lead' => $page->lead ?? '',
            'breadcrumbs' => $page->breadcrumbs ?? [
                ['label' => 'Resources & Help'],
                ['label' => 'Contact Hub'],
            ],
            'page' => $page,
        ]);
    }
}
