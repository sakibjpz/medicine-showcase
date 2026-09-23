<h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-navy-900 mb-1">Our categories</h2>
<p class="text-slate-500 text-sm sm:text-base mb-6 sm:mb-10">Lots of new products and product collections</p>

<div class="grid grid-cols-5 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-8 gap-x-2 gap-y-5 sm:gap-6 lg:gap-y-10">
    @foreach ($categories as $category)
        @php
            $image = $categoryImages[$category->name] ?? null;
            $count = $productCounts[$category->name] ?? 0;
        @endphp
        <a href="{{ route('products') }}?category={{ urlencode($category->name) }}" class="group flex flex-col items-center text-center">
            <div class="relative w-14 h-14 sm:w-20 sm:h-20 lg:w-24 lg:h-24 rounded-full overflow-hidden shadow-md">
                @if ($image)
                    <img src="{{ $image }}" alt="{{ $category->name }}" loading="lazy"
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @else
                    <div class="absolute inset-0 bg-gradient-to-br from-med-600 to-navy-900 flex items-center justify-center text-white text-2xl sm:text-4xl font-bold group-hover:scale-105 transition duration-300">
                        {{ strtoupper(substr($category->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <span class="block mt-1.5 sm:mt-2 text-[10px] sm:text-sm font-medium text-slate-700 leading-tight">{{ $category->name }}</span>
            <span class="block text-[9px] sm:text-xs text-slate-400">{{ $count }} product{{ $count === 1 ? '' : 's' }}</span>
        </a>
    @endforeach
</div>
