<h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-1">Our categories</h2>
<p class="text-slate-500 mb-10">Lots of new products and product collections</p>

<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6 lg:gap-x-6 lg:gap-y-10">
    @foreach ($categories as $category)
        @php
            $image = $categoryImages[$category->name] ?? null;
            $count = $productCounts[$category->name] ?? 0;
        @endphp
        <a href="{{ route('products') }}?category={{ urlencode($category->name) }}" class="group block text-center">
            <div class="relative aspect-square rounded-full overflow-hidden shadow-md">
                @if ($image)
                    <img src="{{ $image }}" alt="{{ $category->name }}" loading="lazy"
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @else
                    <div class="absolute inset-0 bg-gradient-to-br from-med-600 to-navy-900 flex items-center justify-center text-white text-4xl font-bold group-hover:scale-105 transition duration-300">
                        {{ strtoupper(substr($category->name, 0, 1)) }}
                    </div>
                @endif
                <span class="absolute inset-0 flex items-center justify-center px-3">
                    <span class="bg-white/95 text-slate-800 text-xs sm:text-sm font-medium px-2 sm:px-3 py-1 rounded-full shadow text-center leading-tight max-w-full">{{ $category->name }}</span>
                </span>
            </div>
            <span class="block mt-3 text-xs text-slate-500">{{ $count }} product{{ $count === 1 ? '' : 's' }}</span>
        </a>
    @endforeach
</div>
