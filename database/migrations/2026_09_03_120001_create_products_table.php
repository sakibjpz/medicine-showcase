<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Section 1: Basic Product Information (P01-P11)
            $table->string('internal_product_id', 50)->unique();
            $table->string('brand_name', 255);
            $table->string('generic_inn_name', 255);
            $table->string('other_name', 255)->nullable();
            $table->string('therapeutic_category', 50);
            $table->string('dosage_form', 50);
            $table->string('strength', 50);
            $table->string('pack_size_spec', 255);
            $table->string('route_admin', 50)->nullable();
            $table->foreignId('manufacturer_id')->constrained('manufacturers')->cascadeOnDelete();
            $table->string('country_of_origin', 100);
            $table->string('legal_status', 50);

            // Section 2: Product Description and Media (P12-P17)
            $table->text('active_ingredients');
            $table->text('short_description');
            $table->longText('full_description');
            $table->longText('approved_indication');
            $table->json('product_images');
            $table->string('image_alt_text', 255);

            // Section 3: Use and Safety Information (P18-P22)
            $table->longText('dosage_admin_text')->nullable();
            $table->longText('safety_info');
            $table->longText('drug_interactions')->nullable();
            $table->boolean('has_known_interactions')->default(false);
            $table->longText('precautions')->nullable();
            $table->boolean('has_precautions')->default(false);
            $table->text('storage_conditions');

            // Section 4: Website and Market Information (P23-P29)
            $table->string('availability_status', 50);
            $table->json('country_market');
            $table->string('page_language', 50);
            $table->string('enquiry_contact_link', 255);
            $table->string('url_slug', 255)->unique();
            $table->string('seo_title', 255);
            $table->text('meta_description');

            // Section 5: Source, Review and Publication (P30-P34)
            $table->string('official_source_url', 255)->nullable();
            $table->date('last_verified_date')->nullable();
            $table->string('content_status', 50)->default('Draft');
            $table->string('reviewer_approver', 255);
            $table->longText('information_disclaimer');

            $table->timestamps();

            $table->index('therapeutic_category');
            $table->index('manufacturer_id');
            $table->index('content_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
