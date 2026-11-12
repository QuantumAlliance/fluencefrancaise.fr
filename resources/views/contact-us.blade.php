<!DOCTYPE html>
@php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';
    $footerData = isset($settings['footer_settings']) ? json_decode($settings['footer_settings'], true) : null;
    $isFr = request()->is('fr*');

    $contactEmail = !empty($settings['contact_email'])
        ? $settings['contact_email']
        : (!empty($footerData['contact']['email']) ? $footerData['contact']['email'] : 'contact@fluencefrancaise.fr');
    $contactPhone = !empty($settings['contact_phone'])
        ? $settings['contact_phone']
        : (!empty($footerData['contact']['phone']) ? $footerData['contact']['phone'] : '+33 1 23 45 67 89');
    $contactPhoneTel = preg_replace('/[^0-9+]/', '', $contactPhone);
    $whatsappDigits = preg_replace('/[^0-9]/', '', $contactPhone);

    $title = $isFr ? 'Contact et parrainage – Fluence Française' : 'Contact and Referrals – Fluence Française';
    $metaDesc = $isFr
        ? 'Une question sur nos cours ou votre niveau ? Écrivez-nous. Vous venez avec un ami ? 50 € de remise par mois chacun avec le parrainage.'
        : 'Questions about our classes or your level? Get in touch. Coming with a friend? You each get €50 off per month with our referral offer.';

    $canonicalUrl = $isFr ? 'https://fluencefrancaise.fr/fr/contact/' : 'https://fluencefrancaise.fr/en/contact/';
    $frUrl = 'https://fluencefrancaise.fr/fr/contact/';
    $enUrl = 'https://fluencefrancaise.fr/en/contact/';
    $homeUrl = $isFr ? '/fr' : '/en';
    $formAction = $isFr ? '/fr/contact' : '/en/contact';
    $compareUrl = $isFr ? '/fr/tcf-tef-ou-delf' : '/en/tcf-vs-tef-vs-delf';
    $privacyUrl = '/new-policies';
    $referralId = $isFr ? 'parrainage' : 'referral';

    $goals = $isFr ? [
        'multi-year-permit' => 'Carte pluriannuelle (A2)',
        'resident-card' => 'Carte de résident (B1)',
        'french-citizenship' => 'Naturalisation (B2)',
        'civic-exam' => 'Examen civique',
        'c-permit' => 'Permis C (Suisse)',
        'swiss-citizenship' => 'Naturalisation suisse',
        'exam' => 'Un examen (TCF, TEF, DELF, fide)',
        'other' => 'Autre',
    ] : [
        'multi-year-permit' => 'Multi-year permit (A2)',
        'resident-card' => 'Resident card (B1)',
        'french-citizenship' => 'French citizenship (B2)',
        'civic-exam' => 'Civic exam',
        'c-permit' => 'C permit (Switzerland)',
        'swiss-citizenship' => 'Swiss citizenship',
        'exam' => 'An exam (TCF, TEF, DELF, fide)',
        'other' => 'Other',
    ];

    $steps = $isFr ? [
        'Cochez « Je viens avec un ami » dans le formulaire, avec son nom.',
        'Nous vous contactons tous les 2 pour activer la remise.',
        'Vous progressez chacun à votre rythme, et vous payez moins chaque mois.',
    ] : [
        'Tick “I\'m coming with a friend” in the form and add their name.',
        'We contact you both to activate the discount.',
        'You each learn at your own pace, and pay less every month.',
    ];

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'ContactPage',
                'name' => $title,
                'description' => $metaDesc,
                'url' => $canonicalUrl,
                'inLanguage' => $isFr ? 'fr' : 'en',
            ],
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => $isFr ? 'Accueil' : 'Home', 'item' => $isFr ? 'https://fluencefrancaise.fr/fr' : 'https://fluencefrancaise.fr/en'],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Contact', 'item' => $canonicalUrl],
                ],
            ],
        ],
    ];
@endphp
<html lang="{{ $isFr ? 'fr' : 'en' }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | {{ $siteName }}</title>
    <meta name="description" content="{{ $metaDesc }}">
    <meta name="robots" content="{{ $settings['robots'] ?? 'index, follow' }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="fr" href="{{ $frUrl }}">
    <link rel="alternate" hreflang="en" href="{{ $enUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $enUrl }}">
    @include('partials.social-meta', [
        'ogType' => 'website',
        'ogUrl' => $canonicalUrl,
        'ogTitle' => $title . ' | ' . $siteName,
        'ogDescription' => $metaDesc,
        'ogSiteName' => $siteName,
    ])
    <script
        type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    @include('partials.favicon')
    @include('partials.theme')
    @include('partials.custom-scripts-head')
</head>

<body class="antialiased bg-white">
    @include('partials.custom-scripts-body')
    <div class="landing-page">
        @include('partials.header')

        <section class="relative overflow-hidden bg-mist pt-32 md:pt-40 pb-14 md:pb-20">
            <div class="hero-aurora"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative text-center max-w-3xl">
                <nav aria-label="Breadcrumb"
                    class="mb-5 flex justify-center text-xs text-slate-400 font-medium items-center gap-2">
                    <a href="{{ $homeUrl }}"
                        class="hover:text-primary-600 transition-colors">{{ $isFr ? 'Accueil' : 'Home' }}</a>
                    <span class="text-slate-300">›</span>
                    <span class="text-slate-700 font-semibold">Contact</span>
                </nav>
                <span class="eyebrow justify-center reveal"><span class="tricolor"><i></i><i></i><i></i></span>
                    Contact</span>
                <h1 class="reveal mt-5 text-[2.6rem] leading-[1.05] md:text-6xl font-semibold text-ink tracking-tight">
                    {{ $isFr ? 'Contactez-nous' : 'Contact Us' }}
                </h1>
                <p class="reveal mt-6 text-lg text-slate-600 leading-relaxed">
                    {{ $isFr
    ? 'Une question sur nos cours, votre niveau ou le test à choisir ? Écrivez-nous. Nous répondons sous 24 h ouvrées.'
    : 'A question about our classes, your level or which test to take? Write to us. We reply within 24 business hours.' }}
                </p>
            </div>
        </section>

        <section class="py-16 md:py-20 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                <div class="grid md:grid-cols-2 gap-5">
                    <a href="mailto:{{ $contactEmail }}"
                        class="reveal card-glow group bg-mist rounded-4xl border border-slate-100 p-8 block">
                        <span
                            class="w-14 h-14 rounded-2xl bg-primary-50 text-primary-600 grid place-content-center text-xl group-hover:bg-primary-600 group-hover:text-white transition"><i
                                class="fas fa-envelope"></i></span>
                        <p class="mt-6 text-xl font-bold text-ink">Email</p>
                        <p class="mt-4 text-primary-600 font-bold break-words">{{ $contactEmail }}</p>
                    </a>
                    <div class="reveal card-glow bg-mist rounded-4xl border border-slate-100 p-8"
                        style="transition-delay:.07s">
                        <span
                            class="w-14 h-14 rounded-2xl bg-primary-50 text-primary-600 grid place-content-center text-xl"><i
                                class="fab fa-whatsapp"></i></span>
                        <p class="mt-6 text-xl font-bold text-ink">
                            {{ $isFr ? 'Téléphone / WhatsApp' : 'Phone / WhatsApp' }}
                        </p>
                        <p class="mt-4 text-primary-600 font-bold">
                            <a href="tel:{{ $contactPhoneTel }}" class="hover:underline">{{ $contactPhone }}</a>
                            <span class="text-slate-300 mx-1.5">·</span>
                            <a href="https://wa.me/{{ $whatsappDigits }}" target="_blank" rel="noopener"
                                class="hover:underline">WhatsApp</a>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="pb-16 md:pb-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
                <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span>
                    {{ $isFr ? 'Écrire' : 'Write to us' }}</span>
                <h2 class="mt-4 text-3xl md:text-4xl font-semibold text-ink">{{ $isFr ? 'Formulaire' : 'Form' }}</h2>

                @if(session('contact_sent'))
                    <p class="mt-6 rounded-2xl bg-primary-50 text-primary-700 px-5 py-4 text-sm font-medium">
                        {{ $isFr ? 'Message envoyé. Nous répondons sous 24 h ouvrées.' : 'Message sent. We reply within 24 business hours.' }}
                    </p>
                @endif
                @if(session('contact_error'))
                    <p class="mt-6 rounded-2xl bg-accent-soft text-[#9f2a22] px-5 py-4 text-sm font-medium">
                        {{ $isFr ? 'L\'envoi n\'a pas abouti. Écrivez-nous directement par email.' : 'The message could not be sent. Please email us directly.' }}
                    </p>
                @endif

                <form action="{{ $formAction }}" method="post"
                    class="mt-8 bg-mist rounded-4xl border border-slate-100 p-6 sm:p-8 space-y-5">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-bold text-ink">{{ $isFr ? 'Nom' : 'Name' }} <span
                                class="text-[#EF4135]">*</span></label>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}"
                            class="mt-2 w-full px-4 py-2 text-sm md:text-base bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition placeholder-gray-400">
                        @error('name')
                        <p class="mt-1 text-xs text-[#EF4135]">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-bold text-ink">Email <span
                                class="text-[#EF4135]">*</span></label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}"
                            class="mt-2 w-full px-4 py-2 text-sm md:text-base bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition placeholder-gray-400">
                        @error('email')
                        <p class="mt-1 text-xs text-[#EF4135]">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="phone"
                            class="block text-sm font-bold text-ink">{{ $isFr ? 'Téléphone ou WhatsApp' : 'Phone or WhatsApp' }}</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                            class="mt-2 w-full px-4 py-2 text-sm md:text-base bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition placeholder-gray-400">
                    </div>
                    <div>
                        <label for="goal"
                            class="block text-sm font-bold text-ink">{{ $isFr ? 'Votre objectif' : 'Your goal' }} <span
                                class="text-[#EF4135]">*</span></label>
                        <select id="goal" name="goal" required
                            class="mt-2 w-full px-4 py-2 text-sm md:text-base bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition placeholder-gray-400">
                            <option value="" disabled {{ old('goal') ? '' : 'selected' }}>
                                {{ $isFr ? 'Choisir' : 'Choose' }}
                            </option>
                            @foreach($goals as $value => $label)
                                <option value="{{ $value }}" {{ old('goal') === $value ? 'selected' : '' }}>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('goal')
                        <p class="mt-1 text-xs text-[#EF4135]">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="flex items-start gap-3 text-sm font-bold text-ink cursor-pointer select-none">
                            <input id="with_friend" name="with_friend" type="checkbox" value="1"
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-primary-600 cursor-pointer"
                                style="cursor: pointer" {{ old('with_friend') ? 'checked' : '' }}>
                            <span
                                class="cursor-pointer">{{ $isFr ? 'Je viens avec un ami (parrainage)' : 'I\'m coming with a friend (referral)' }}</span>
                        </label>
                        <div id="friend_name_wrap" class="mt-4" @if(!old('with_friend')) hidden @endif>
                            <label for="friend_name"
                                class="block text-sm font-bold text-ink">{{ $isFr ? 'Nom de votre ami' : 'Your friend\'s name' }}</label>
                            <input id="friend_name" name="friend_name" type="text" value="{{ old('friend_name') }}"
                                class="mt-2 w-full px-4 py-2 text-sm md:text-base bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition placeholder-gray-400">
                            @error('friend_name')
                            <p class="mt-1 text-xs text-[#EF4135]">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-bold text-ink">Message</label>
                        <textarea id="message" name="message" rows="5"
                            class="mt-2 w-full px-4 py-2 text-sm md:text-base bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition placeholder-gray-400">{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">{{ $isFr ? 'Envoyer' : 'Send' }}</button>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        @if($isFr)
                            Nous répondons sous 24 h ouvrées. Vos données servent à vous répondre et, si vous vous
                            inscrivez, à organiser vos cours. Consultez notre <a href="{{ $privacyUrl }}"
                                class="font-semibold text-primary-600 hover:underline">politique de confidentialité</a>.
                        @else
                            We reply within 24 business hours. We use your details to answer you and, if you enrol, to run
                            your classes. See our <a href="{{ $privacyUrl }}"
                                class="font-semibold text-primary-600 hover:underline">Privacy policy</a>.
                        @endif
                    </p>
                </form>
            </div>
        </section>

        <section id="{{ $referralId }}" class="scroll-mt-28 py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
                <div class="max-w-3xl">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span>
                        {{ $isFr ? 'Parrainage' : 'Referral' }}</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">
                        {{ $isFr ? 'Parrainage : 50 € de remise par mois, chacun' : 'Referrals: €50 off per month, each' }}
                    </h2>
                    <p class="mt-6 text-lg text-slate-600 leading-relaxed">
                        {{ $isFr
    ? 'Vous connaissez quelqu\'un qui prépare aussi son titre de séjour, sa naturalisation ou un examen ? Inscrivez-vous ensemble : vous bénéficiez chacun de 50 € de remise par mois.'
    : 'Know someone else preparing a residence card, citizenship or an exam? Sign up together and you each get €50 off per month.' }}
                    </p>
                </div>
                <div class="mt-10 grid md:grid-cols-3 gap-5">
                    @foreach($steps as $i => $step)
                        <article class="bg-white rounded-3xl border border-slate-100 p-7">
                            <span
                                class="text-sm font-bold text-primary-600 bg-primary-600/20 p-2 w-max rounded-full">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <p class="mt-3 text-slate-600 leading-relaxed">{{ $step }}</p>
                        </article>
                    @endforeach
                </div>
                <p class="mt-6 text-sm text-slate-500">
                    {{ $isFr ? 'Offre valable une fois par compte, non cumulable avec d\'autres offres.' : 'Offer valid once per account, not combinable with other offers.' }}
                </p>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
                <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span>
                    {{ $isFr ? 'Ensuite' : 'Next' }}</span>
                <h2 class="mt-4 text-3xl md:text-4xl font-semibold text-ink">
                    {{ $isFr ? 'Avant d\'écrire' : 'Before you write' }}
                </h2>
                <ul class="mt-6 space-y-4 text-lg text-slate-600 leading-relaxed">
                    <li>
                        @if($isFr)
                            Vous ne savez pas quel test choisir ? Lisez <a href="{{ $compareUrl }}"
                                class="font-semibold text-primary-600 hover:underline">TCF, TEF ou DELF : le bon test en 3
                                questions</a>.
                        @else
                            Not sure which test to take? Read <a href="{{ $compareUrl }}"
                                class="font-semibold text-primary-600 hover:underline">TCF, TEF or DELF: the right test in 3
                                questions</a>.
                        @endif
                    </li>
                    <li>
                        {{ $isFr ? 'Vous êtes prêt ?' : 'Ready to go?' }}
                        <a href="/register"
                            class="font-semibold text-primary-600 hover:underline">{{ $isFr ? "S'inscrire" : 'Enroll now' }}</a>
                    </li>
                </ul>
            </div>
        </section>

        @include('partials.footer')
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var box = document.getElementById('with_friend');
            var wrap = document.getElementById('friend_name_wrap');
            var input = document.getElementById('friend_name');
            if (!box || !wrap || !input) return;
            function sync() {
                var on = box.checked;
                wrap.hidden = !on;
                input.required = on;
            }
            box.addEventListener('change', sync);
            sync();
        });
    </script>
</body>

</html>