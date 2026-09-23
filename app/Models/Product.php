<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'internal_product_id',
        'brand_name',
        'generic_inn_name',
        'other_name',
        'therapeutic_category',
        'subcategory',
        'dosage_form',
        'strength',
        'pack_size_spec',
        'sort_order',
        'route_admin',
        'manufacturer_id',
        'country_of_origin',
        'legal_status',
        'active_ingredients',
        'short_description',
        'full_description',
        'approved_indication',
        'product_images',
        'product_image_labels',
        'banner_image',
        'image_alt_text',
        'dosage_admin_text',
        'safety_info',
        'drug_interactions',
        'precautions',
        'storage_conditions',
        'availability_status',
        'country_market',
        'page_language',
        'enquiry_contact_link',
        'url_slug',
        'seo_title',
        'meta_description',
        'official_source_url',
        'last_verified_date',
        'content_status',
        'reviewer_approver',
        'information_disclaimer',
        'has_known_interactions',
        'has_precautions',
    ];

    protected function casts(): array
    {
        return [
            'product_images' => 'array',
            'product_image_labels' => 'array',
            'country_market' => 'array',
            'has_known_interactions' => 'boolean',
            'has_precautions' => 'boolean',
            'last_verified_date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public static function nextInternalId(string $category): string
    {
        $letters = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $category), 0, 3));
        $prefix = $letters ?: 'GEN';
        $latest = static::where('internal_product_id', 'like', $prefix . '-%')
            ->orderByDesc('internal_product_id')
            ->value('internal_product_id');

        $number = 1;
        if ($latest) {
            $suffix = (int) substr($latest, strlen($prefix) + 1);
            $number = $suffix + 1;
        }

        return $prefix . '-' . str_pad((string) $number, 3, '0', STR_PAD_LEFT);
    }

    public static function slugFrom(string $brand, string $strength): string
    {
        $base = \Illuminate\Support\Str::slug($brand . '-' . $strength);

        return $base ?: 'product';
    }

    public static function uniqueSlug(string $base): string
    {
        $slug = $base;
        $counter = 1;

        while (static::where('url_slug', $slug)->exists()) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

    public function scopePublished($query)
    {
        return $query->whereIn('content_status', ['Approved', 'Published']);
    }
}
