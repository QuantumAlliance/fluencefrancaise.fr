@php
    $isFr = $isFr ?? request()->is('fr*');

    $defaultEyebrow = $isFr ? "NOUS VOUS ACCOMPAGNONS" : "LET'S GET YOU THERE";
    $defaultTitle = $isFr 
        ? "Votre niveau. Le niveau exigé.<br class='hidden sm:inline'> Nous comblons l'écart." 
        : "Your level. The required level.<br class='hidden sm:inline'> We close the gap.";
    $defaultDescription = $isFr
        ? "Votre premier cours fixe votre niveau dans chaque épreuve et le plan pour atteindre celui de votre dossier. Vous choisissez le rythme : 2 ou 4 heures par semaine."
        : "Your first class sets your level in each section and the plan to reach the one your application needs. You choose the pace: 2 or 4 hours a week.";
    $defaultPrimaryBtnText = $isFr ? "S'inscrire" : "Enroll now";
    $defaultPrimaryBtnUrl = "/register";
    $defaultSecondaryBtnText = $isFr ? "Contactez-nous" : "Talk to us";
    $defaultSecondaryBtnUrl = $isFr ? "/fr/contact/" : "/en/contact/";
    $defaultHelperText = $isFr 
        ? "Cours en direct sur Google Meet · Particulier ou mini-groupe · Espace élève inclus" 
        : "Live classes on Google Meet · 1:1 or small group · Student portal included";

    $ctaEyebrow = $eyebrow ?? $defaultEyebrow;
    $ctaTitle = $title ?? $defaultTitle;
    $ctaDescription = $description ?? $defaultDescription;
    $ctaPrimaryBtnText = $primaryButtonText ?? $defaultPrimaryBtnText;
    $ctaPrimaryBtnUrl = $primaryButtonUrl ?? $defaultPrimaryBtnUrl;
    $ctaShowSecondary = $showSecondaryButton ?? true;
    $ctaSecondaryBtnText = $secondaryButtonText ?? $defaultSecondaryBtnText;
    $ctaSecondaryBtnUrl = $secondaryButtonUrl ?? $defaultSecondaryBtnUrl;
    $ctaHelperText = $helperText ?? $defaultHelperText;
    $ctaReferralNote = $referralNote ?? null;
    $ctaSectionClass = $sectionClass ?? "pb-20 bg-white";
@endphp

<!-- ============ REUSABLE CTA BANNER COMPONENT ============ -->
<section class="{{ $ctaSectionClass }}">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
        <div class="reveal rounded-3xl sm:rounded-4xl md:rounded-[36px] bg-[#0055a4] text-white text-center px-6 sm:px-12 py-14 sm:py-16 md:py-20 relative overflow-hidden shadow-2xl shadow-blue-900/15">
            <!-- Background ambient lighting -->
            <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-blue-400/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-red-400/10 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 max-w-3xl mx-auto">
                <!-- Eyebrow with French Tricolor Bar -->
                @if(!empty($ctaEyebrow))
                <div class="inline-flex items-center justify-center gap-2 mb-4">
                    <span class="tricolor" style="height:3.5px;width:34px;"><i></i><i></i><i></i></span>
                    <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-blue-100">
                        {{ $ctaEyebrow }}
                    </span>
                </div>
                @endif

                <!-- H2 Title -->
                <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-[50px] font-extrabold leading-[1.12] tracking-tight text-white">
                    {!! $ctaTitle !!}
                </h2>

                <!-- Description -->
                @if(!empty($ctaDescription))
                <p class="mt-4 sm:mt-5 text-sm sm:text-base md:text-lg text-blue-100/90 max-w-2xl mx-auto leading-relaxed font-normal">
                    {{ $ctaDescription }}
                </p>
                @endif

                <!-- Buttons -->
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3 sm:gap-4">
                    @if(!empty($ctaPrimaryBtnText))
                    <a href="{{ $ctaPrimaryBtnUrl }}" class="btn btn-onnavy btn-lg">
                        <span>{{ $ctaPrimaryBtnText }}</span>
                        <i class="fas fa-arrow-right ml-2.5"></i>
                    </a>
                    @endif

                    @if($ctaShowSecondary && !empty($ctaSecondaryBtnText))
                    <a href="{{ $ctaSecondaryBtnUrl }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-semibold text-white border-2 border-white/80 hover:bg-white/10 hover:border-white transition-all transform hover:-translate-y-0.5">
                        <span>{{ $ctaSecondaryBtnText }}</span>
                        <i class="fas fa-arrow-right text-xs ml-2.5 text-white"></i>
                    </a>
                    @endif
                </div>

                <!-- Helper Text Under Buttons -->
                @if(!empty($ctaHelperText))
                <p class="mt-6 text-xs sm:text-[13px] text-blue-200/85 font-medium tracking-wide">
                    {{ $ctaHelperText }}
                </p>
                @endif

                <!-- Optional Referral / Extra Note -->
                @if(!empty($ctaReferralNote))
                <div class="mt-8 pt-6 border-t border-white/15 text-xs text-blue-100">
                    {!! $ctaReferralNote !!}
                </div>
                @endif
            </div> 
            <!-- Bottom French Tricolor Stripe -->
            <div class="absolute inset-x-0 bottom-0 h-1.5 flex">
                <div class="w-1/3 h-full bg-[#0055A4]"></div>
                <div class="w-1/3 h-full bg-white"></div>
                <div class="w-1/3 h-full bg-[#ef4135]"></div>
            </div>
        </div>
    </div>
</section>

