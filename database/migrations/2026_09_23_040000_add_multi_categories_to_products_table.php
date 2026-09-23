<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('categories')->nullable()->after('therapeutic_category');
            $table->json('subcategories')->nullable()->after('subcategory');
        });

        DB::table('products')
            ->whereNotNull('therapeutic_category')
            ->where('therapeutic_category', '!=', '')
            ->update(['categories' => DB::raw('JSON_ARRAY(therapeutic_category)')]);

        DB::table('products')
            ->whereNotNull('subcategory')
            ->where('subcategory', '!=', '')
            ->update(['subcategories' => DB::raw("JSON_ARRAY(CONCAT(therapeutic_category, '|', subcategory))")]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['categories', 'subcategories']);
        });
    }
};
