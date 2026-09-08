<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with('manufacturer')->where('url_slug', $slug)->firstOrFail();

        return view('products.show', [
            'product' => $product,
            'title' => $product->seo_title,
            'breadcrumbs' => [
                ['label' => 'Products', 'url' => route('products')],
                ['label' => $product->brand_name],
            ],
        ]);
    }
}
