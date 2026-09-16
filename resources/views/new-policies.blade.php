<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    @endphp

    <!-- SEO Meta Tags -->
    <title>Privacy Policy — {{ ($settings['site_name'] ?? 'Fluence Française') }}</title>
    <meta name="description" content="Our privacy policy, refund policy, class rescheduling policy, and other important policies for Fluence Française.">
    <meta name="robots" content="{{ $settings['robots'] ?? 'index, follow' }}">

    <!-- Social Media Meta Tags -->
    @include('partials.social-meta', [
        'ogType' => 'article',
        'ogUrl' => url('/new-policies'),
        'ogTitle' => 'Privacy Policy — ' . ($settings['site_name'] ?? 'Fluence Française'),
        'ogDescription' => 'Our privacy policy, refund policy, class rescheduling policy, and other important policies.',
        'ogSiteName' => $settings['site_name'] ?? 'Fluence Française',
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
        <section class="relative overflow-hidden bg-mist pt-32 md:pt-40 pb-12">
            <div class="hero-aurora"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="text-center reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> Legal</span>
                    <h1 class="mt-5 text-4xl md:text-5xl lg:text-6xl font-semibold text-ink tracking-tight">Privacy &amp; Policy</h1>
                    <p class="mt-4 text-slate-500 text-base">Read our policies carefully before using our services.</p>
                </div>
            </div>
        </section>

        <!-- ===== POLICY CONTENT ===== -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
                <div class="reveal bg-mist rounded-4xl border border-slate-100 p-8 md:p-10 space-y-12">

                    <!-- Refund Policy -->
                    <div>
                        <h2 class="text-xl md:text-2xl font-semibold text-ink mt-0 mb-3">Refund Policy</h2>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            We understand that sometimes things don't go as planned. That's why we have a flexible refund policy to ensure your satisfaction.
                        </p>
                        <ul class="list-disc pl-6 text-slate-600 space-y-2 mb-4">
                            <li>
                                <span class="font-semibold text-ink">Full Refund After First Class</span>
                                <p class="mt-1 leading-relaxed">
                                    If you've made the payment and attended your first class but feel that our program isn't the right fit for you, notify us before your second class, and we'll offer you a full refund.
                                </p>
                            </li>
                            <li>
                                <span class="font-semibold text-ink">Partial Refund</span>
                                <p class="mt-1 leading-relaxed">
                                    We do not provide partial refunds for unused classes. If you decide to discontinue during a pay period, you are welcome to attend the remaining classes within that period.
                                </p>
                            </li>
                        </ul>
                    </div>

                    <!-- Privacy Policy -->
                    <div>
                        <h2 class="text-xl md:text-2xl font-semibold text-ink mt-0 mb-3">Privacy Policy</h2>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            We are committed to protecting your privacy and personal information.
                        </p>
                        <ul class="list-disc pl-6 text-slate-600 space-y-2 mb-4">
                            <li>
                                <span class="font-semibold text-ink">Official Communication</span>
                                <p class="mt-1 leading-relaxed">
                                    Students must refrain from contacting tutors directly outside the official Fluence Française platform for any matters, including scheduling, payments, or private arrangements. All communication should be routed through our official channels. Violations of this policy may result in termination of enrollment without a refund and could lead to further action if necessary.
                                </p>
                            </li>
                            <li>
                                <span class="font-semibold text-ink">Data Protection</span>
                                <p class="mt-1 leading-relaxed">
                                    We do not share your personal information with third parties without your consent.
                                </p>
                            </li>
                            <li>
                                <span class="font-semibold text-ink">Class Recordings</span>
                                <p class="mt-1 leading-relaxed">
                                    Any class recordings are for your personal use only and should not be shared or distributed.
                                </p>
                            </li>
                            <li>
                                <span class="font-semibold text-ink">Material Sharing</span>
                                <p class="mt-1 leading-relaxed">
                                    All course materials provided are for your personal use only. Sharing or distributing our materials without permission is prohibited.
                                </p>
                            </li>
                        </ul>
                    </div>

                    <!-- Class Rescheduling Policy -->
                    <div>
                        <h2 class="text-xl md:text-2xl font-semibold text-ink mt-0 mb-3">Class Rescheduling Policy</h2>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            We strive to accommodate your schedule as much as possible. Here's how we handle rescheduling:
                        </p>
                        <ul class="list-disc pl-6 text-slate-600 space-y-2 mb-4">
                            <li>
                                <span class="font-semibold text-ink">Tutor Missed Classes</span>
                                <p class="mt-1 leading-relaxed">
                                    If your tutor misses a class, we will reschedule it for you.
                                </p>
                            </li>
                            <li>
                                <span class="font-semibold text-ink">Group Classes</span>
                                <p class="mt-1 leading-relaxed">
                                    If you're in a group class and miss a session, we don't reschedule the class. However, you can request a recording of the session if other students were present.
                                </p>
                            </li>
                            <li>
                                <span class="font-semibold text-ink">One-On-One Classes</span>
                                <p class="mt-1 leading-relaxed">
                                    If you're in a group class and miss a session, we don't reschedule the class. However, you can request a recording of the session if other students were present.
                                </p>
                            </li>
                        </ul>

                        <div class="mt-4">
                            <h3 class="font-semibold text-ink mb-2">Notice</h3>
                            <ul class="list-disc pl-6 text-slate-600 space-y-2 mb-4">
                                <li class="leading-relaxed">Classes can be rescheduled if you notify your tutor at least 24 hours in advance.</li>
                                <li class="leading-relaxed">If a class is missed due to personal reasons without providing a minimum of 24 hours' prior notice, it will not be rescheduled or refunded.</li>
                                <li class="leading-relaxed">Rescheduling is subject to the tutor's availability and may not always be possible within the same week.</li>
                            </ul>
                        </div>

                        <p class="text-slate-600 leading-relaxed mb-4">
                            Please note that we can only reschedule a limited number of classes.
                        </p>
                    </div>

                    <!-- Class Recordings Policy -->
                    <div>
                        <h2 class="text-xl md:text-2xl font-semibold text-ink mt-0 mb-3">Class Recordings Policy</h2>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            For quality assurance and your self-study purposes, all classes are automatically recorded by default.
                        </p>
                        <ul class="list-disc pl-6 text-slate-600 space-y-2 mb-4">
                            <li class="leading-relaxed">Recordings are used for internal quality reviews and made available to you for your own study and review.</li>
                            <li class="leading-relaxed">If you wish to opt out of class recordings, you must email us requesting to disable recordings for your account.</li>
                            <li class="leading-relaxed">Requests to disable recordings must be made before your scheduled class.</li>
                            <li class="leading-relaxed">By continuing with your enrollment, you acknowledge and consent to this default recording setting unless you request otherwise.</li>
                        </ul>
                    </div>

                    <!-- Result Guarantee Policy -->
                    <div>
                        <h2 class="text-xl md:text-2xl font-semibold text-ink mt-0 mb-3">Result Guarantee Policy</h2>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            We believe in providing high-quality education, but we cannot guarantee specific results. Your success depends on your efforts and dedication. While many of our students have passed their exams within 6-9 months, individual results may vary.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        @include('partials.footer')
    </div>
</body>
</html>
