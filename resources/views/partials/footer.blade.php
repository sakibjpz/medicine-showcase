<footer class="bg-navy-900 text-slate-200">
    <div class="container-site py-12 lg:py-16">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">

            {{-- Brand --}}
            <div class="sm:col-span-2 lg:col-span-1">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-white font-bold text-xl mb-4">
                    <span class="w-8 h-8 rounded-full bg-med-600 flex items-center justify-center text-white text-sm">M</span>
                    <span class="uppercase tracking-wider text-sm">MedSource</span>
                </a>
                <p class="text-sm text-med-100 leading-relaxed mb-4">Global pharmaceutical information. Quality-first professional access.</p>
                <p class="text-xs text-slate-400 leading-relaxed">Information on availability, affordability and regulatory status varies by jurisdiction. No content replaces specific medical advice.</p>
            </div>

            {{-- Explore --}}
            <div>
                <h3 class="text-white font-semibold mb-4 uppercase text-sm tracking-wider">Explore</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('products') }}" class="text-slate-300 hover:text-white transition">Products</a></li>
                    <li><a href="{{ route('therapeutic-areas') }}" class="text-slate-300 hover:text-white transition">Therapeutic Areas</a></li>
                    <li><a href="{{ route('manufacturers') }}" class="text-slate-300 hover:text-white transition">Manufacturers</a></li>
                    <li><a href="{{ route('countries-languages') }}" class="text-slate-300 hover:text-white transition">Countries</a></li>
                </ul>
            </div>

            {{-- Support --}}
            <div>
                <h3 class="text-white font-semibold mb-4 uppercase text-sm tracking-wider">Support</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('how-it-works') }}" class="text-slate-300 hover:text-white transition">How It Works</a></li>
                    <li><a href="{{ route('faq') }}" class="text-slate-300 hover:text-white transition">FAQ</a></li>
                    <li><a href="{{ route('professional-enquiry') }}" class="text-slate-300 hover:text-white transition">Professional Enquiry</a></li>
                    <li><a href="{{ route('contact-hub') }}" class="text-slate-300 hover:text-white transition">Contact</a></li>
                </ul>
            </div>

            {{-- Transparency --}}
            <div>
                <h3 class="text-white font-semibold mb-4 uppercase text-sm tracking-wider">Transparency</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('quality-compliance') }}" class="text-slate-300 hover:text-white transition">Quality</a></li>
                    <li><a href="{{ route('safety-notices') }}" class="text-slate-300 hover:text-white transition">Safety Notices</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-slate-300 hover:text-white transition">Privacy</a></li>
                    <li><a href="{{ route('terms') }}" class="text-slate-300 hover:text-white transition">Terms</a></li>
                    <li><a href="{{ route('accessibility') }}" class="text-slate-300 hover:text-white transition">Accessibility</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="border-t border-navy-800">
        <div class="container-site py-4 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
            <p>&copy; {{ date('Y') }} MedSource. All rights reserved.</p>
            <a href="{{ route('professional-enquiry') }}" class="btn-primary w-full sm:w-auto text-center">Professional Enquiry</a>
        </div>
    </div>
</footer>
