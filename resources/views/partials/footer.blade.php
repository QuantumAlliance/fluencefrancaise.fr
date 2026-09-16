@php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';
    $footerData = isset($settings['footer_settings']) ? json_decode($settings['footer_settings'], true) : null;

    $description = "Live online French classes that take you from A1 to B2 and into DELF, DALF, TCF and TEF exam readiness — structured, teacher-led, built for the life you're creating in France.";

    $quickLinks = $footerData['quickLinks'] ?? [
        ['text' => 'Home', 'url' => '/'],
        ['text' => 'Courses', 'url' => '/our-courses'],
        ['text' => 'Pricing', 'url' => '/#pricing'],
        ['text' => 'Student Portal', 'url' => '/#portal'],
        ['text' => 'FAQ', 'url' => '/#faq'],
        ['text' => 'Contact Us', 'url' => '/contact-us']
    ];

    $legalLinks = $footerData['legalLinks'] ?? [
        ['text' => 'Privacy Policy', 'url' => '/new-policies']
    ];

    $contactEmail = $footerData['contact']['email'] ?? 'contact@fluencefrancaise.com';
    $contactPhone = $footerData['contact']['phone'] ?? '+33 1 23 45 67 89';
    $contactAddress = $footerData['contact']['address'] ?? 'Online · Based in France';

    $socialLinks = $footerData['social'] ?? [
        'facebook' => '#',
        'twitter' => '#',
        'instagram' => '#',
        'linkedin' => '#'
    ];

    $copyrightRaw = $footerData['copyrightText'] ?? '© {year} {siteName}. All rights reserved.';
    $copyrightText = str_replace(['{year}', '{siteName}'], [date('Y'), $siteName], $copyrightRaw);

    $creditText = $footerData['creditText'] ?? 'Fait avec <i class="fas fa-heart text-[#EF4135]"></i> pour les apprenants du français';
@endphp

<footer id="contact" class="bg-[#002654] text-slate-300 relative">
    <div class="h-1.5 w-full" style="background:linear-gradient(90deg,#0055A4 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 mb-10">
            <!-- About Section -->
            <div class="md:col-span-5">
                <div class="flex items-center gap-2.5 mb-5">
                    @if(isset($settings['site_logo']) && $settings['site_logo'])
                        <span class="inline-flex bg-white rounded-2xl p-2"><img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="{{ $siteName }}" class="h-12 w-auto"></span>
                    @else
                        <span class="inline-flex h-[26px] w-[9px] rounded-sm overflow-hidden shadow"><i class="block flex-1" style="height:33.3%;background:#0055A4"></i></span>
                        <span style="font-family:'Urbanist',system-ui,sans-serif" class="text-2xl font-bold text-white tracking-tight">Fluence<span class="text-[#EF4135]">.</span></span>
                    @endif
                </div>
                <p class="text-sm leading-relaxed text-slate-300/90 max-w-md">{{ $description }}</p>
                <div class="flex gap-3 mt-6">
                    @foreach(['facebook'=>'fa-facebook-f','instagram'=>'fa-instagram','twitter'=>'fa-twitter','linkedin'=>'fa-linkedin-in'] as $key => $icon)
                        @if(isset($socialLinks[$key]) && !empty($socialLinks[$key]) && $socialLinks[$key] !== '#')
                            <a href="{{ $socialLinks[$key] }}" target="_blank" class="w-9 h-9 rounded-full flex items-center justify-center bg-white/10 hover:bg-[#EF4135] text-white transition"><i class="fab {{ $icon }}"></i></a>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Quick Links -->
            <div class="md:col-span-3">
                <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Explore</h4>
                <ul class="space-y-3 text-sm">
                    @foreach($quickLinks as $link)
                        <li><a href="{{ $link['url'] }}" class="text-slate-300 hover:text-white hover:translate-x-1 inline-block transition">{{ $link['text'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Contact Information -->
            <div class="md:col-span-4">
                <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Get in touch</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center gap-3"><i class="fas fa-envelope text-[#5993d0] w-4"></i> <a href="mailto:{{ $contactEmail }}" class="hover:text-white transition">{{ $contactEmail }}</a></li>
                    <li class="flex items-center gap-3"><i class="fas fa-phone text-[#5993d0] w-4"></i> {{ $contactPhone }}</li>
                    <li class="flex items-center gap-3"><i class="fas fa-map-marker-alt text-[#5993d0] w-4"></i> {{ $contactAddress }}</li>
                </ul>
                <div class="mt-5 flex flex-wrap gap-2">
                    @foreach(['DELF','DALF','TCF','TEF'] as $exam)
                        <span class="text-[11px] font-bold tracking-wide px-3 py-1 rounded-full bg-white/10 text-white">{{ $exam }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="border-t border-white/10 pt-7 flex justify-between items-center flex-wrap gap-4">
            <p class="text-sm text-slate-400">{{ $copyrightText }}</p>
            <div class="flex items-center gap-4 text-sm text-slate-400">
                @foreach($legalLinks as $link)
                    <a href="{{ $link['url'] }}" class="hover:text-white transition">{{ $link['text'] }}</a>
                @endforeach
                <span class="hidden sm:inline text-slate-600">·</span>
                <span class="hidden sm:inline">{!! $creditText !!}</span>
            </div>
        </div>
    </div>
</footer>

@if(isset($settings['custom_scripts']))
    @php
        $scripts = json_decode($settings['custom_scripts'], true);
        if (is_array($scripts)) {
            foreach ($scripts as $script) {
                $scriptCode = is_array($script) ? ($script['code'] ?? '') : $script;
                $placement = is_array($script) ? ($script['placement'] ?? 'head') : 'head';
                if (!empty(trim($scriptCode)) && $placement === 'footer') {
                    $trimmedCode = trim($scriptCode);
                    if (stripos($trimmedCode, '<script') === 0) {
                        echo $scriptCode;
                    } else {
                        echo '<script>' . "\n" . $scriptCode . "\n" . '</script>';
                    }
                }
            }
        }
    @endphp
@endif
