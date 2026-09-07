@php
$items = $breadcrumbs ?? [];
@endphp

<nav aria-label="Breadcrumb" class="bg-slate-50 border-b border-slate-200">
    <div class="container-site py-3">
        <ol class="flex flex-wrap items-center text-sm text-slate-500" itemscope itemtype="https://schema.org/BreadcrumbList">
            <li class="flex items-center" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="{{ route('home') }}" class="hover:text-med-700" itemprop="item">
                    <span itemprop="name">Home</span>
                </a>
                <meta itemprop="position" content="1" />
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </li>
            @foreach ($items as $index => $item)
                <li class="flex items-center" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    @if (isset($item['url']))
                        <a href="{{ $item['url'] }}" class="hover:text-med-700" itemprop="item"><span itemprop="name">{{ $item['label'] }}</span></a>
                    @else
                        <span class="text-med-900 font-medium" itemprop="name">{{ $item['label'] }}</span>
                    @endif
                    <meta itemprop="position" content="{{ $index + 2 }}" />
                    @if (!$loop->last)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</nav>
