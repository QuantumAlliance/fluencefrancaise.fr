<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    @endphp

    <!-- SEO Meta Tags -->
    <title>Why French — {{ ($settings['site_name'] ?? 'Fluence Française') }}</title>
    <meta name="description" content="For immigrants, residents and expats in France, French is the most powerful investment you can make. Reach A2, B1 and B2 with focused DELF, DALF, TCF and TEF preparation for residency, citizenship, career and real belonging.">
    <meta name="robots" content="{{ $settings['robots'] ?? 'index, follow' }}">

    <!-- Social Media Meta Tags -->
    @include('partials.social-meta', [
        'ogType'        => 'article',
        'ogUrl'         => url('/why-french'),
        'ogTitle'       => 'Why French — ' . ($settings['site_name'] ?? 'Fluence Française'),
        'ogDescription' => 'French for the life you are building in France — for residency, citizenship, career and real belonging.',
        'ogSiteName'    => $settings['site_name'] ?? 'Fluence Française',
        'ogImage'       => asset('images/hero-france.webp'),
    ])

    @include('partials.favicon')
    @include('partials.theme')
    @include('partials.custom-scripts-head')
</head>
<body class="antialiased bg-white">
    @include('partials.custom-scripts-body')
    <div class="landing-page">
        @include('partials.header')

        <!-- ===== HERO ===== -->
        <section class="relative overflow-hidden bg-white pt-32 md:pt-40 pb-14 md:pb-20">
            <div class="hero-aurora"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <div class="reveal">
                        <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> Why French?</span>
                        <h1 class="mt-5 text-[2.5rem] leading-[1.06] md:text-6xl font-semibold text-ink tracking-tight">French for the life you're building in <span class="swish text-primary-600">France</span></h1>
                        <p class="mt-6 text-lg text-slate-600 leading-relaxed">
                            For immigrants, temporary residents and expats planning a real life in France, learning French is the single most powerful investment you can make — the bridge between visa uncertainty and long-term stability, between feeling like an outsider and truly belonging.
                        </p>
                        <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                            Our live online classes take you from beginner to exam-ready levels (A2, B1, B2) with focused preparation for <strong class="text-ink">DELF, DALF, TCF and TEF</strong> — the exact certifications France requires.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-4">
                            <a href="/register" class="btn btn-primary btn-lg">Enroll Now <i class="fas fa-arrow-right text-xs"></i></a>
                            <a href="/contact-us" class="btn btn-ghost btn-lg">Book a free assessment</a>
                        </div>
                    </div>
                    <div class="reveal" style="transition-delay:.1s">
                        <div class="relative">
                            <div class="absolute inset-0 rotate-3 rounded-5xl bg-primary-600/5"></div>
                            <img src="{{ asset('images/hero-france.webp') }}" alt="People waving the French tricolour flag in front of the Louvre in Paris" class="relative rounded-5xl shadow-lift w-full object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== RESIDENCY & CITIZENSHIP REQUIREMENTS 2026 ===== -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> Residency &amp; citizenship</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">French language requirements in France <span class="swish text-primary-600">(2026)</span></h2>
                    <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                        France updated its rules in January 2026. Official proof of French proficiency is now mandatory for most residency permits and citizenship. Recognized certificates include <strong class="text-ink">DELF/DALF diplomas</strong> and <strong class="text-ink">TCF/TEF exam results</strong>.
                    </p>
                </div>

                @php $reqs = [
                    ['code'=>'A2','title'=>'Multi-Year Residence Permit','sub'=>'Carte de Séjour Pluriannuelle','desc'=>'Required for most first long-stay permits, family reunification and OFII integration contracts. This foundational level lets you handle everyday situations and basic administrative procedures.','tag'=>'First long-stay permits'],
                    ['code'=>'B1','title'=>'10-Year Resident Card','sub'=>'Carte de Résident','desc'=>'Now required (raised from A2). Perfect for spouses of French citizens, parents of French children and long-term residents seeking maximum stability and fewer renewals.','tag'=>'Raised from A2'],
                    ['code'=>'B2','title'=>'French Citizenship','sub'=>'Naturalisation','desc'=>'Mandatory since January 2026 (raised from B1). At B2 you understand complex arguments, express nuanced opinions in speaking and writing, and fully participate in French civic life. A separate civic integration test also applies.','tag'=>'Raised from B1'],
                ]; @endphp
                <div class="mt-12 grid md:grid-cols-3 gap-6">
                    @foreach($reqs as $i => $r)
                    <div class="reveal card-glow bg-white rounded-3xl border border-slate-100 p-7 shadow-card flex flex-col" style="transition-delay:{{ $i*70 }}ms">
                        <div class="flex items-center justify-between">
                            <span class="w-14 h-14 rounded-2xl bg-primary-600 text-white grid place-content-center text-xl font-bold" style="font-family:'Urbanist',sans-serif">{{ $r['code'] }}</span>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-[#EF4135] bg-accent-soft px-3 py-1 rounded-full">{{ $r['tag'] }}</span>
                        </div>
                        <h3 class="mt-5 text-xl font-bold text-ink leading-tight">{{ $r['title'] }}</h3>
                        <p class="text-sm text-primary-600 font-semibold italic mt-1">{{ $r['sub'] }}</p>
                        <p class="mt-3 text-slate-600 leading-relaxed text-[15px]">{{ $r['desc'] }}</p>
                    </div>
                    @endforeach
                </div>

                <p class="mt-8 text-slate-600 leading-relaxed max-w-3xl border-l-2 border-[#EF4135] pl-5 reveal">
                    Starting structured, exam-focused preparation early removes uncertainty from your immigration timeline and prevents costly delays or refusals.
                </p>
            </div>
        </section>

        <!-- ===== EVERYDAY LIFE & INTEGRATION ===== -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> Everyday life &amp; belonging</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">French for everyday life, integration &amp; real <span class="swish text-primary-600">belonging</span></h2>
                    <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                        Beyond paperwork, French transforms how you experience France day-to-day. Many expats describe reaching A2–B1 as the moment they stopped feeling like temporary visitors and started feeling at home.
                    </p>
                </div>

                @php $life = [
                    ['fa-stamp','Administrative independence','Handle all administrative tasks — préfecture, impôts, CPAM, town hall — independently, without translators or stress.'],
                    ['fa-stethoscope','Confident healthcare','Communicate clearly with doctors, pharmacists and healthcare providers for better care and peace of mind.'],
                    ['fa-graduation-cap',"Your children's schooling",'Talk to teachers, understand the school system and help with homework as your children grow up in France.'],
                    ['fa-people-group','Authentic belonging','Build real French friendships, join local associations and experience the culture, humor and "art de vivre" beyond expat bubbles.'],
                    ['fa-file-signature','Protect your family','Understand contracts, leases, banking, consumer rights and local services — protecting yourself and the people you love.'],
                ]; @endphp
                <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($life as $i => $l)
                    <div class="reveal card-glow bg-white rounded-3xl border border-slate-100 p-7 {{ $i === 4 ? 'sm:col-span-2 lg:col-span-1' : '' }}" style="transition-delay:{{ $i*60 }}ms">
                        <span class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 grid place-content-center text-lg"><i class="fas {{ $l[0] }}"></i></span>
                        <h3 class="mt-5 text-lg font-bold text-ink">{{ $l[1] }}</h3>
                        <p class="mt-2 text-sm text-slate-600 leading-relaxed">{{ $l[2] }}</p>
                    </div>
                    @endforeach
                </div>

                <p class="mt-8 text-slate-600 leading-relaxed max-w-3xl border-l-2 border-[#EF4135] pl-5 reveal">
                    Even moderate proficiency dramatically reduces isolation and accelerates genuine integration into French society.
                </p>
            </div>
        </section>

        <!-- ===== CAREER GROWTH ===== -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> Career &amp; opportunity</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">French for career growth &amp; <span class="swish text-primary-600">opportunity</span></h2>
                    <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                        France is Europe's third-largest economy and home to world-leading companies in luxury, aerospace, energy, pharmaceuticals, finance and tech. French language skills give you a real competitive edge.
                    </p>
                </div>

                @php $career = [
                    ['fa-briefcase','Wider job access','Many roles — client-facing, management, public-adjacent and SME positions — list French as required or strongly preferred. English-only limits you to narrow international niches.'],
                    ['fa-arrow-trend-up','Higher earnings & advancement','Bilingual (French + English) professionals frequently command better compensation and faster internal mobility within French multinationals and EU organizations.'],
                    ['fa-handshake','Networking & trust','Speaking French builds instant credibility and deeper relationships with colleagues, clients and partners.'],
                    ['fa-lightbulb','Entrepreneurship','Building a business or freelance career on a French visa? Negotiating contracts, understanding regulations (URSSAF, etc.) and marketing locally becomes far more effective.'],
                ]; @endphp
                <div class="mt-12 grid sm:grid-cols-2 gap-5">
                    @foreach($career as $i => $c)
                    <div class="reveal card-glow bg-white rounded-3xl border border-slate-100 p-7 shadow-card" style="transition-delay:{{ $i*70 }}ms">
                        <span class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 grid place-content-center text-lg"><i class="fas {{ $c[0] }}"></i></span>
                        <h3 class="mt-5 text-lg font-bold text-ink">{{ $c[1] }}</h3>
                        <p class="mt-2 text-slate-600 leading-relaxed text-[15px]">{{ $c[2] }}</p>
                    </div>
                    @endforeach
                </div>

                <p class="mt-8 text-slate-600 leading-relaxed max-w-3xl border-l-2 border-[#EF4135] pl-5 reveal">
                    Whether you hold a Talent Passport, are transitioning from student status, or are on a family/work visa, French proficiency future-proofs your career in France and opens doors across the Francophone world.
                </p>
            </div>
        </section>

        <!-- ===== FRANCE'S VISION — NAVY BAND ===== -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal relative overflow-hidden rounded-5xl bg-[#002654] text-white p-8 md:p-14">
                    <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                    <div class="absolute -bottom-24 -right-16 w-80 h-80 rounded-full bg-[#EF4135]/20 blur-3xl"></div>
                    <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full bg-[#0055A4]/30 blur-3xl"></div>
                    <div class="relative max-w-3xl">
                        <span class="eyebrow text-[#8eb8e1]"><span class="tricolor"><i></i><i></i><i></i></span> France's vision</span>
                        <h2 class="mt-5 text-3xl md:text-4xl font-semibold leading-tight">Language, integration &amp; long-term success</h2>
                        <p class="mt-6 text-slate-300 leading-relaxed">
                            France views the French language as a cornerstone of social cohesion and republican values. The government invests heavily in Français Langue Étrangère (FLE) programs, and many new residents receive courses through OFII as part of the Contrat d'Intégration Républicaine. But for permit renewals — and especially citizenship — official certification at the required level is non-negotiable.
                        </p>
                        <p class="mt-4 text-slate-300 leading-relaxed">
                            By reaching A2, B1 or B2 with proper exam preparation, you align with what France expects from successful, contributing residents and future citizens. This signals respect, commitment and readiness to participate fully — qualities valued by both immigration authorities and employers.
                        </p>
                        <p class="mt-4 text-slate-300 leading-relaxed">
                            Long-term, strong French unlocks permanent residency, citizenship, voting rights and a deeply enriched personal life — letting you enjoy French culture, regional diversity, gastronomy and global influence with genuine depth and authenticity.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== FINAL CTA ===== -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal rounded-5xl bg-primary-600 text-white text-center px-6 py-14 md:py-16 relative overflow-hidden">
                    <div class="absolute inset-x-0 bottom-0 h-1.5" style="background:linear-gradient(90deg,#003d7a 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>
                    <span class="eyebrow justify-center text-[#bcd5ee]"><span class="tricolor"><i></i><i></i><i></i></span> Ready to begin?</span>
                    <h2 class="mt-5 text-3xl md:text-5xl font-semibold leading-tight max-w-3xl mx-auto">Build your life in France with confidence</h2>
                    <p class="mt-5 text-lg text-primary-100 max-w-2xl mx-auto leading-relaxed">
                        Stop relying on scattered apps, random tutors or last-minute cramming. Fluence Française delivers structured live online classes — group and 1-on-1 — designed to move you level-by-level to exam success in DELF, DALF, TCF and TEF, with speaking practice, writing correction, listening training, mock tests and personal support every step of the way.
                    </p>
                    <p class="mt-6 text-base font-semibold text-white">Every level has structure. Every student gets support. Every step has a purpose.</p>
                    <div class="mt-8 flex flex-wrap justify-center gap-4">
                        <a href="/register" class="btn btn-onnavy btn-lg">Enroll Now — Choose Your Program</a>
                        <a href="/contact-us" class="btn btn-lg" style="box-shadow:inset 0 0 0 2px #fff;color:#fff;background:transparent">Book a Free Level Assessment</a>
                    </div>
                    <p class="mt-8 text-sm text-primary-100/90 max-w-xl mx-auto">
                        <strong class="text-white">Referral offer:</strong> Refer a friend and both of you get €50 off every month while enrolled. The discount applies once per student account.
                    </p>
                </div>
            </div>
        </section>

        @include('partials.footer')
    </div>
</body>
</html>
