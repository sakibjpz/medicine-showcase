<header x-data="{
    megaOpen: false,
    activeColumn: 'products',
    mobileOpen: false,
    mobileSearchOpen: false,
    openMega(col) {
        this.megaOpen = true;
        this.activeColumn = col;
    },
    closeMega() {
        this.megaOpen = false;
        this.activeColumn = 'products';
    },
    toggleMobile() { this.mobileOpen = !this.mobileOpen; },
    toggleMobileSearch() { this.mobileSearchOpen = !this.mobileSearchOpen; }
}"
    @keydown.escape.window="closeMega(); mobileOpen = false; mobileSearchOpen = false"
    class="sticky top-0 z-50 bg-white shadow-header">

    {{-- Pre-header --}}
    <div class="bg-med-600 text-white text-xs">
        <div class="container-site py-2 flex items-center justify-between">
            <div class="hidden sm:flex items-center gap-4">
                <span class="font-medium">Verified sourcing</span>
                <span class="text-med-100">|</span>
                <span>Quality-first information</span>
                <span class="text-med-100">|</span>
                <span>Professional enquiries</span>
            </div>
            <div class="flex items-center flex-wrap gap-2 sm:gap-4 sm:ml-auto text-[10px] sm:text-xs">
                <span class="hidden sm:inline">Country: Global</span>
                <span class="hidden sm:inline">Language: EN</span>
                <a href="{{ route('faq') }}" class="hover:text-med-100 hover:underline">Help</a>
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="font-semibold hover:text-med-100 hover:underline">Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-med-100 hover:underline">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-med-100 hover:underline">Log in</a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Brand / search / CTA row --}}
    <div class="container-site py-4">
        <div class="flex items-center justify-between gap-4 lg:gap-8">
            {{-- Brand --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0" aria-label="MedSource Home">
                <div class="w-10 h-10 rounded-full bg-med-600 flex items-center justify-center text-white font-bold text-lg">M</div>
                <div>
                    <span class="block text-xl font-bold text-navy-900 leading-tight">MedSource</span>
                    <span class="hidden sm:block text-[11px] text-slate-500 tracking-wide">Global Medical Platforms and Centers</span>
                </div>
            </a>

            {{-- Search --}}
            <div class="hidden md:flex flex-1 max-w-2xl relative"
                 x-data="{
                    activeIndex: -1,
                    query: {{ json_encode(request('q')) }},
                    suggestions: [],
                    open: false,
                    loading: false,
                    abortController: null,
                    async fetchSuggestions() {
                        if (this.query.length < 2) { this.suggestions = []; this.open = false; return; }
                        this.loading = true;
                        if (this.abortController) this.abortController.abort();
                        this.abortController = new AbortController();
                        try {
                            const res = await fetch('{{ route('search.suggest') }}?q=' + encodeURIComponent(this.query), { signal: this.abortController.signal });
                            this.suggestions = await res.json();
                            this.open = this.suggestions.length > 0;
                            this.activeIndex = -1;
                        } catch (e) { if (e.name !== 'AbortError') console.error(e); }
                        this.loading = false;
                    },
                    go(url) { window.location.href = url; },
                    select() {
                        if (this.activeIndex >= 0 && this.suggestions[this.activeIndex]) {
                            this.go(this.suggestions[this.activeIndex].url);
                        } else {
                            this.$refs.searchForm.submit();
                        }
                    },
                    next() {
                        if (! this.suggestions.length) return;
                        this.open = true;
                        this.activeIndex = (this.activeIndex + 1) % this.suggestions.length;
                    },
                    prev() {
                        if (! this.suggestions.length) return;
                        this.open = true;
                        this.activeIndex = (this.activeIndex - 1 + this.suggestions.length) % this.suggestions.length;
                    },
                    highlight(text) {
                        if (! this.query) return text;
                        const q = this.query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                        return text.replace(new RegExp('(' + q + ')', 'ig'), '<mark class="bg-yellow-100 text-inherit rounded px-0.5">$1</mark>');
                    }
                 }"
                 @keydown.escape.window="open = false">
                <form action="{{ route('search') }}" method="GET" class="w-full relative" role="search" x-ref="searchForm" @submit="open = false">
                    <label for="global-search" class="sr-only">Search products, pages, manufacturers and more</label>
                    <div class="relative">
                        <input id="global-search" name="q" type="search" x-model="query"
                               @input.debounce.300ms="fetchSuggestions" @focus="if (query.length >= 2) fetchSuggestions()"
                               class="w-full pl-11 pr-4 py-2.5 rounded-full border border-slate-300 bg-slate-50 text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-med-500 focus:border-med-500 transition"
                               placeholder="Search products, pages, manufacturers and more"
                               autocomplete="off"
                       @keydown.arrow-down.prevent="next"
                       @keydown.arrow-up.prevent="prev"
                       @keydown.enter.prevent="select"
                       @keydown.escape="open = false; activeIndex = -1;">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <div x-show="open" x-transition class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg border border-slate-200 shadow-lg z-50 overflow-hidden" @click.away="open = false" role="listbox" aria-label="Search suggestions">
                        <ul class="max-h-72 overflow-y-auto divide-y divide-slate-100">
                            <template x-for="(item, index) in suggestions" :key="item.url + item.title">
                                <li>
                                    <button type="button"
                                            @click="go(item.url)"
                                            @mouseenter="activeIndex = index"
                                            x-bind:class="{ 'bg-med-50': activeIndex === index }"
                                            class="w-full text-left px-4 py-3 hover:bg-med-50 focus:bg-med-50 focus:outline-none">
                                        <span class="text-[10px] font-semibold uppercase tracking-wider text-med-600" x-text="item.type"></span>
                                        <span class="block text-sm font-semibold text-slate-900" x-html="highlight(item.title)"></span>
                                        <span class="block text-xs text-slate-500 truncate" x-html="highlight(item.summary)"></span>
                                    </button>
                                </li>
                            </template>
                        </ul>
                        <a :href="'{{ route('search') }}?q=' + encodeURIComponent(query)" class="block w-full text-center px-4 py-2 text-sm text-med-700 bg-slate-50 hover:bg-med-50 border-t border-slate-100">See all results</a>
                    </div>
                </form>
            </div>

            {{-- CTA --}}
            <a href="{{ route('professional-enquiry') }}" class="hidden md:inline-flex btn-primary shrink-0">
                Professional Enquiry
            </a>

            {{-- Mobile controls --}}
            <div class="flex items-center gap-2 md:hidden">
                <button @click="toggleMobileSearch" class="p-2 rounded-md text-slate-600 hover:bg-slate-100" aria-label="Toggle search" :aria-expanded="mobileSearchOpen">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                <button @click="toggleMobile" class="p-2 rounded-md text-slate-600 hover:bg-slate-100" :aria-expanded="mobileOpen" aria-controls="mobile-menu" aria-label="Toggle menu">
                    <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile search --}}
        <div x-show="mobileSearchOpen" class="md:hidden mt-3 relative" style="display: none;"
             x-data="{
                activeIndex: -1,
                    query: {{ json_encode(request('q')) }},
                suggestions: [],
                open: false,
                loading: false,
                abortController: null,
                async fetchSuggestions() {
                    if (this.query.length < 2) { this.suggestions = []; this.open = false; return; }
                    this.loading = true;
                    if (this.abortController) this.abortController.abort();
                    this.abortController = new AbortController();
                    try {
                        const res = await fetch('{{ route('search.suggest') }}?q=' + encodeURIComponent(this.query), { signal: this.abortController.signal });
                        this.suggestions = await res.json();
                        this.open = this.suggestions.length > 0;
                            this.activeIndex = -1;
                    } catch (e) { if (e.name !== 'AbortError') console.error(e); }
                    this.loading = false;
                },
                go(url) { window.location.href = url; },
                    select() {
                        if (this.activeIndex >= 0 && this.suggestions[this.activeIndex]) {
                            this.go(this.suggestions[this.activeIndex].url);
                        } else {
                            this.$refs.searchForm.submit();
                        }
                    },
                    next() {
                        if (! this.suggestions.length) return;
                        this.open = true;
                        this.activeIndex = (this.activeIndex + 1) % this.suggestions.length;
                    },
                    prev() {
                        if (! this.suggestions.length) return;
                        this.open = true;
                        this.activeIndex = (this.activeIndex - 1 + this.suggestions.length) % this.suggestions.length;
                    },
                    highlight(text) {
                        if (! this.query) return text;
                        const q = this.query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                        return text.replace(new RegExp('(' + q + ')', 'ig'), '<mark class="bg-yellow-100 text-inherit rounded px-0.5">$1</mark>');
                    }
             }"
             @keydown.escape.window="open = false">
            <form action="{{ route('search') }}" method="GET" role="search" class="relative" x-ref="searchForm" @submit="open = false">
                <label for="mobile-search" class="sr-only">Search products, pages, manufacturers and more</label>
                <input id="mobile-search" name="q" type="search" x-model="query"
                       @input.debounce.300ms="fetchSuggestions" @focus="if (query.length >= 2) fetchSuggestions()"
                       class="w-full px-4 py-2 rounded-full border border-slate-300 bg-slate-50"
                       placeholder="Search products, pages, manufacturers and more"
                       autocomplete="off"
                       @keydown.arrow-down.prevent="next"
                       @keydown.arrow-up.prevent="prev"
                       @keydown.enter.prevent="select"
                       @keydown.escape="open = false; activeIndex = -1;">

                <div x-show="open" x-transition class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg border border-slate-200 shadow-lg z-50 overflow-hidden" @click.away="open = false" role="listbox" aria-label="Search suggestions">
                    <ul class="max-h-72 overflow-y-auto divide-y divide-slate-100">
                        <template x-for="(item, index) in suggestions" :key="item.url + item.title">
                            <li>
                                <button type="button"
                                        @click="go(item.url)"
                                        @mouseenter="activeIndex = index"
                                        x-bind:class="{ 'bg-med-50': activeIndex === index }"
                                        class="w-full text-left px-4 py-3 hover:bg-med-50 focus:bg-med-50 focus:outline-none">
                                    <span class="text-[10px] font-semibold uppercase tracking-wider text-med-600" x-text="item.type"></span>
                                    <span class="block text-sm font-semibold text-slate-900" x-html="highlight(item.title)"></span>
                                    <span class="block text-xs text-slate-500 truncate" x-html="highlight(item.summary)"></span>
                                </button>
                            </li>
                        </template>
                    </ul>
                    <a :href="'{{ route('search') }}?q=' + encodeURIComponent(query)" class="block w-full text-center px-4 py-2 text-sm text-med-700 bg-slate-50 hover:bg-med-50 border-t border-slate-100">See all results</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Main navigation --}}
    <nav class="hidden md:block bg-navy-900 relative" aria-label="Main navigation" @mouseleave="closeMega()">
        <div class="container-site">
            <div class="flex items-stretch -mx-2">
                <a href="{{ route('home') }}" class="nav-pill {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>

                <button type="button"
                        class="nav-pill {{ request()->routeIs('products', 'products.*') ? 'active' : '' }}"
                        @mouseenter="openMega('products')"
                        @click="openMega('products')"
                        :aria-expanded="megaOpen && activeColumn === 'products'"
                        aria-haspopup="true">
                    Products
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <a href="{{ route('therapeutic-areas') }}" class="nav-pill {{ request()->routeIs('therapeutic-areas') ? 'active' : '' }}">Therapeutic Areas</a>

                <a href="{{ route('manufacturers') }}" class="nav-pill {{ request()->routeIs('manufacturers') ? 'active' : '' }}">Manufacturers</a>

                <a href="{{ route('how-it-works') }}" class="nav-pill {{ request()->routeIs('how-it-works') ? 'active' : '' }}">How It Works</a>

                <a href="{{ route('quality-compliance') }}" class="nav-pill {{ request()->routeIs('quality-compliance') ? 'active' : '' }}">Quality</a>

                <button type="button"
                        class="nav-pill {{ request()->routeIs('articles-updates', 'faq', 'professional-enquiry', 'navigation-overview') ? 'active' : '' }}"
                        @mouseenter="openMega('resources')"
                        @click="openMega('resources')"
                        :aria-expanded="megaOpen && activeColumn === 'resources'"
                        aria-haspopup="true">
                    Resources
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <a href="{{ route('about') }}" class="nav-pill {{ request()->routeIs('about') ? 'active' : '' }}">About</a>

                <a href="{{ route('contact-hub') }}" class="nav-pill {{ request()->routeIs('contact-hub') ? 'active' : '' }}">Contact</a>
            </div>
        </div>

        {{-- Mega menu --}}
        <div x-show="megaOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="absolute top-full left-0 w-full bg-white border-t border-slate-200 shadow-mega z-40"
             style="display: none;"
             role="region"
             aria-label="Mega menu">
            <div class="container-site py-8">
                <h2 class="text-2xl font-bold text-navy-900 mb-2">Navigation overview</h2>
                <p class="text-sm text-slate-500 mb-6">Explore MedSource by category.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                    {{-- Product Information --}}
                    <div class="p-5 rounded-lg bg-med-50 border border-med-100 transition"
                         :class="{ 'ring-2 ring-med-500 bg-med-100': activeColumn === 'products' }"
                         @mouseenter="activeColumn = 'products'">
                        <h3 class="text-med-700 font-bold uppercase text-xs tracking-wider mb-5">Product Information</h3>
                        <ul class="space-y-4 text-sm">
                            <li class="flex gap-3">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                                <div>
                                    <a href="{{ route('products') }}" class="font-medium text-slate-800 hover:text-med-700">All Products</a>
                                    <p class="text-xs text-slate-500">Clear destination and content label</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                                <div>
                                    <a href="{{ route('products.search') }}" class="font-medium text-slate-800 hover:text-med-700">Product Search</a>
                                    <p class="text-xs text-slate-500">Clear destination and content label</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                                <div>
                                    <a href="{{ route('products.documents') }}" class="font-medium text-slate-800 hover:text-med-700">Product Documents</a>
                                    <p class="text-xs text-slate-500">Clear destination and content label</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                                <div>
                                    <a href="{{ route('products.country-status') }}" class="font-medium text-slate-800 hover:text-med-700">Country Status</a>
                                    <p class="text-xs text-slate-500">Clear destination and content label</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    {{-- Explore --}}
                    <div class="p-5 rounded-lg bg-med-50 border border-med-100 transition"
                         :class="{ 'ring-2 ring-med-500 bg-med-100': activeColumn === 'explore' }"
                         @mouseenter="activeColumn = 'explore'">
                        <h3 class="text-med-700 font-bold uppercase text-xs tracking-wider mb-5">Explore</h3>
                        <ul class="space-y-4 text-sm">
                            <li class="flex gap-3">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                                <div>
                                    <a href="{{ route('therapeutic-areas') }}" class="font-medium text-slate-800 hover:text-med-700">Therapeutic Areas</a>
                                    <p class="text-xs text-slate-500">Clear destination and content label</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                                <div>
                                    <a href="{{ route('manufacturers') }}" class="font-medium text-slate-800 hover:text-med-700">Manufacturers</a>
                                    <p class="text-xs text-slate-500">Clear destination and content label</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                                <div>
                                    <a href="{{ route('countries-languages') }}" class="font-medium text-slate-800 hover:text-med-700">Countries & Languages</a>
                                    <p class="text-xs text-slate-500">Clear destination and content label</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                                <div>
                                    <a href="{{ route('how-it-works') }}" class="font-medium text-slate-800 hover:text-med-700">How It Works</a>
                                    <p class="text-xs text-slate-500">Clear destination and content label</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    {{-- Trust & Quality --}}
                    <div class="p-5 rounded-lg bg-med-50 border border-med-100 transition"
                         :class="{ 'ring-2 ring-med-500 bg-med-100': activeColumn === 'trust' }"
                         @mouseenter="activeColumn = 'trust'">
                        <h3 class="text-med-700 font-bold uppercase text-xs tracking-wider mb-5">Trust & Quality</h3>
                        <ul class="space-y-4 text-sm">
                            <li class="flex gap-3">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                                <div>
                                    <a href="{{ route('quality-compliance') }}" class="font-medium text-slate-800 hover:text-med-700">Quality & Compliance</a>
                                    <p class="text-xs text-slate-500">Clear destination and content label</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                                <div>
                                    <a href="{{ route('safety-notices') }}" class="font-medium text-slate-800 hover:text-med-700">Safety Notices</a>
                                    <p class="text-xs text-slate-500">Clear destination and content label</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                                <div>
                                    <a href="{{ route('official-sources') }}" class="font-medium text-slate-800 hover:text-med-700">Official Sources</a>
                                    <p class="text-xs text-slate-500">Clear destination and content label</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                                <div>
                                    <a href="{{ route('accessibility') }}" class="font-medium text-slate-800 hover:text-med-700">Accessibility</a>
                                    <p class="text-xs text-slate-500">Clear destination and content label</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    {{-- Resources & Help --}}
                    <div class="p-5 rounded-lg bg-med-50 border border-med-100 transition"
                         :class="{ 'ring-2 ring-med-500 bg-med-100': activeColumn === 'resources' }"
                         @mouseenter="activeColumn = 'resources'">
                        <h3 class="text-med-700 font-bold uppercase text-xs tracking-wider mb-5">Resources & Help</h3>
                        <ul class="space-y-4 text-sm">
                            <li class="flex gap-3">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                                <div>
                                    <a href="{{ route('articles-updates') }}" class="font-medium text-slate-800 hover:text-med-700">Articles & Updates</a>
                                    <p class="text-xs text-slate-500">Clear destination and content label</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                                <div>
                                    <a href="{{ route('faq') }}" class="font-medium text-slate-800 hover:text-med-700">FAQ</a>
                                    <p class="text-xs text-slate-500">Clear destination and content label</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                                <div>
                                    <a href="{{ route('contact-hub') }}" class="font-medium text-slate-800 hover:text-med-700">Contact Hub</a>
                                    <p class="text-xs text-slate-500">Clear destination and content label</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                                <div>
                                    <a href="{{ route('professional-enquiry') }}" class="font-medium text-slate-800 hover:text-med-700">Professional Enquiry</a>
                                    <p class="text-xs text-slate-500">Clear destination and content label</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </nav>

    {{-- Therapeutic categories bar --}}
    <div class="hidden md:block bg-med-50 border-b border-med-100">
        <div class="container-site py-2.5">
            <div class="flex items-center flex-wrap gap-x-6 gap-y-1 text-sm">
                <span class="font-semibold text-navy-900">Therapeutic categories</span>
                <a href="{{ route('therapeutic-areas') }}" class="text-med-700 hover:text-med-900 hover:underline">Oncology</a>
                <a href="{{ route('therapeutic-areas') }}" class="text-med-700 hover:text-med-900 hover:underline">Hepatology</a>
                <a href="{{ route('therapeutic-areas') }}" class="text-med-700 hover:text-med-900 hover:underline">Respiratory</a>
                <a href="{{ route('therapeutic-areas') }}" class="text-med-700 hover:text-med-900 hover:underline">Renal</a>
                <a href="{{ route('therapeutic-areas') }}" class="text-med-700 hover:text-med-900 hover:underline">Cardiovascular</a>
                <a href="{{ route('therapeutic-areas') }}" class="text-med-700 hover:text-med-900 hover:underline">Dermatology</a>
                <a href="{{ route('therapeutic-areas') }}" class="text-med-700 hover:text-med-900 hover:underline">Diabetes</a>
                <a href="{{ route('therapeutic-areas') }}" class="text-med-700 hover:text-med-900 hover:underline">Other</a>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="-translate-y-4 opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="-translate-y-4 opacity-0"
         class="absolute inset-x-0 top-full z-50 md:hidden bg-white border-b border-slate-200 shadow-xl max-h-[calc(100vh-8rem)] overflow-y-auto"
         style="display: none;"
         id="mobile-menu"
         role="dialog"
         aria-modal="true"
         aria-label="Mobile navigation">
        <div class="p-4 space-y-2">

            <a href="{{ route('home') }}" class="block w-full text-left px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('home') ? 'bg-med-600 text-white' : 'bg-navy-900 text-white hover:bg-navy-800' }}">Home</a>
            <a href="{{ route('products') }}" class="block w-full text-left px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('products', 'products.*') ? 'bg-med-600 text-white' : 'bg-navy-900 text-white hover:bg-navy-800' }}">Products</a>
            <a href="{{ route('therapeutic-areas') }}" class="block w-full text-left px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('therapeutic-areas') ? 'bg-med-600 text-white' : 'bg-navy-900 text-white hover:bg-navy-800' }}">Therapeutic Areas</a>
            <a href="{{ route('manufacturers') }}" class="block w-full text-left px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('manufacturers') ? 'bg-med-600 text-white' : 'bg-navy-900 text-white hover:bg-navy-800' }}">Manufacturers</a>
            <a href="{{ route('how-it-works') }}" class="block w-full text-left px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('how-it-works') ? 'bg-med-600 text-white' : 'bg-navy-900 text-white hover:bg-navy-800' }}">How It Works</a>
            <a href="{{ route('quality-compliance') }}" class="block w-full text-left px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('quality-compliance') ? 'bg-med-600 text-white' : 'bg-navy-900 text-white hover:bg-navy-800' }}">Quality</a>
            <a href="{{ route('resources') }}" class="block w-full text-left px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('resources', 'articles-updates', 'faq', 'professional-enquiry', 'navigation-overview') ? 'bg-med-600 text-white' : 'bg-navy-900 text-white hover:bg-navy-800' }}">Resources</a>
            <a href="{{ route('about') }}" class="block w-full text-left px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('about') ? 'bg-med-600 text-white' : 'bg-navy-900 text-white hover:bg-navy-800' }}">About</a>
            <a href="{{ route('contact-hub') }}" class="block w-full text-left px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('contact-hub') ? 'bg-med-600 text-white' : 'bg-navy-900 text-white hover:bg-navy-800' }}">Contact</a>

            <div class="pt-4 border-t border-slate-200">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Therapeutic categories</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('therapeutic-areas') }}" class="px-3 py-1.5 text-xs font-medium rounded-full border border-med-600 text-med-700 bg-white hover:bg-med-50">Oncology</a>
                    <a href="{{ route('therapeutic-areas') }}" class="px-3 py-1.5 text-xs font-medium rounded-full border border-med-600 text-med-700 bg-white hover:bg-med-50">Hepatology</a>
                    <a href="{{ route('therapeutic-areas') }}" class="px-3 py-1.5 text-xs font-medium rounded-full border border-med-600 text-med-700 bg-white hover:bg-med-50">Respiratory</a>
                    <a href="{{ route('therapeutic-areas') }}" class="px-3 py-1.5 text-xs font-medium rounded-full border border-med-600 text-med-700 bg-white hover:bg-med-50">Renal</a>
                    <a href="{{ route('therapeutic-areas') }}" class="px-3 py-1.5 text-xs font-medium rounded-full border border-med-600 text-med-700 bg-white hover:bg-med-50">Cardiovascular</a>
                    <a href="{{ route('therapeutic-areas') }}" class="px-3 py-1.5 text-xs font-medium rounded-full border border-med-600 text-med-700 bg-white hover:bg-med-50">Dermatology</a>
                    <a href="{{ route('therapeutic-areas') }}" class="px-3 py-1.5 text-xs font-medium rounded-full border border-med-600 text-med-700 bg-white hover:bg-med-50">Diabetes</a>
                    <a href="{{ route('therapeutic-areas') }}" class="px-3 py-1.5 text-xs font-medium rounded-full border border-med-600 text-med-700 bg-white hover:bg-med-50">Other</a>
                </div>
            </div>

            <a href="{{ route('professional-enquiry') }}" class="block w-full text-center btn-primary mt-2">Professional Enquiry</a>
        </div>
    </div>
</header>
