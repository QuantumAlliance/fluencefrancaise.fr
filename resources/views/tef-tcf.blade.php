<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    @endphp

    <title>TEF / TCF — {{ ($settings['site_name'] ?? 'Fluence Française') }}</title>
    <meta name="description" content="Learn about TEF Canada and TCF Canada exams for Canadian immigration. Understand exam formats, scoring charts, costs, and how Fluence Française prepares you to achieve your target NCLC level.">
    <meta name="robots" content="{{ $settings['robots'] ?? 'index, follow' }}">

    @include('partials.social-meta', [
        'ogType'       => 'article',
        'ogUrl'        => url('/tef-tcf'),
        'ogTitle'      => 'TEF / TCF — ' . ($settings['site_name'] ?? 'Fluence Française'),
        'ogDescription'=> 'Learn about TEF Canada and TCF Canada exams for Canadian immigration. Understand exam formats, scoring charts, costs, and requirements.',
        'ogSiteName'   => $settings['site_name'] ?? 'Fluence Française',
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
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative text-center max-w-3xl">
                <span class="eyebrow justify-center reveal"><span class="tricolor"><i></i><i></i><i></i></span> Canadian Immigration French Exams</span>
                <h1 class="reveal mt-5 text-[2.6rem] leading-[1.05] md:text-6xl font-semibold text-ink tracking-tight">Understanding the TEF &amp; TCF <span class="swish text-primary-600">Exams</span></h1>
                <p class="reveal mt-6 text-lg text-slate-600 leading-relaxed">Both the TEF Canada and TCF Canada are official French-language proficiency tests accepted for Canadian immigration applications. Fluence Française prepares you to achieve the NCLC level your application requires — with structured, live online coaching across all four tested skills.</p>
                <div class="reveal mt-8">
                    <a href="/register" class="btn btn-primary btn-lg">Start Exam Prep <i class="fas fa-arrow-right text-xs"></i></a>
                </div>
            </div>
        </section>

        <!-- ===== WHAT ARE THESE EXAMS ===== -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> Four skills. One day.</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">Both exams test the same core skills</h2>
                    <p class="mt-4 text-slate-600 leading-relaxed">Whether you sit the TEF Canada or TCF Canada, Canadian authorities require you to complete all four sections on the same day. Each section assesses a distinct communication skill that is scored separately for your NCLC profile.</p>
                </div>

                @php $skills = [
                    ['fa-headphones','Listening','Oral Comprehension','Understand spoken French across a range of everyday and formal contexts.'],
                    ['fa-microphone-lines','Speaking','Oral Expression','Express yourself clearly on given topics in a timed, structured format.'],
                    ['fa-book-open','Reading','Written Comprehension','Interpret written texts from practical to complex subject matter.'],
                    ['fa-pen-nib','Writing','Written Expression','Produce clear, organised written responses on assigned topics.'],
                ]; @endphp
                <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($skills as $i => $sk)
                    <div class="reveal card-glow bg-white rounded-3xl border border-slate-100 p-7 text-center" style="transition-delay:{{ $i*70 }}ms">
                        <span class="w-14 h-14 rounded-2xl bg-primary-50 text-primary-600 grid place-content-center text-2xl mx-auto"><i class="fas {{ $sk[0] }}"></i></span>
                        <p class="mt-4 text-xs font-bold uppercase tracking-widest text-slate-400">{{ $sk[2] }}</p>
                        <h3 class="mt-1 text-xl font-bold text-ink">{{ $sk[1] }}</h3>
                        <p class="mt-2 text-sm text-slate-600 leading-relaxed">{{ $sk[3] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ===== TEF CANADA ===== -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">

                <div class="max-w-2xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> TEF Canada</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">Test d'Évaluation de Français</h2>
                    <p class="mt-4 text-slate-600 leading-relaxed">All tests must be completed on the same day for your certificate to be recognised by Canadian authorities. The TEF Canada is computer-based and assesses all four language skills with a mix of multiple-choice questions and structured production tasks.</p>
                </div>

                <!-- TEF sections grid -->
                @php $tefSections = [
                    ['fa-headphones','Oral Comprehension','40 minutes','40 Multiple Choice Questions'],
                    ['fa-microphone-lines','Oral Expression','15 minutes','2 topics to cover'],
                    ['fa-book-open','Written Comprehension','60 minutes','40 Multiple Choice Questions'],
                    ['fa-pen-nib','Written Expression','60 minutes','2 topics to cover'],
                ]; @endphp
                <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($tefSections as $i => $ts)
                    <div class="reveal card-glow bg-white rounded-3xl border border-slate-100 p-6 shadow-card" style="transition-delay:{{ $i*70 }}ms">
                        <span class="w-11 h-11 rounded-xl bg-primary-50 text-primary-600 grid place-content-center text-lg"><i class="fas {{ $ts[0] }}"></i></span>
                        <h3 class="mt-4 text-lg font-bold text-ink">{{ $ts[1] }}</h3>
                        <div class="mt-3 space-y-2">
                            <p class="flex items-center gap-2 text-sm text-slate-600"><i class="fas fa-clock text-primary-400 w-4 shrink-0"></i> {{ $ts[2] }}</p>
                            <p class="flex items-center gap-2 text-sm text-slate-600"><i class="fas fa-list-check text-primary-400 w-4 shrink-0"></i> {{ $ts[3] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- TEF info band -->
                <div class="reveal mt-10 rounded-3xl bg-[#002654] text-white overflow-hidden relative">
                    <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                    <div class="grid md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-white/10 px-6 py-8">
                        <div class="flex items-center gap-4 pb-5 md:pb-0 md:pr-8">
                            <span class="w-12 h-12 rounded-2xl bg-white/10 grid place-content-center text-xl shrink-0"><i class="fas fa-dollar-sign"></i></span>
                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-wider">Exam Cost</p>
                                <p class="text-2xl font-bold mt-0.5" style="font-family:'Urbanist',sans-serif">$390–$450</p>
                                <p class="text-xs text-slate-400 mt-1">Depends on centre &amp; additional materials</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 py-5 md:py-0 md:px-8">
                            <span class="w-12 h-12 rounded-2xl bg-white/10 grid place-content-center text-xl shrink-0"><i class="fas fa-calendar-check"></i></span>
                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-wider">Validity</p>
                                <p class="text-2xl font-bold mt-0.5" style="font-family:'Urbanist',sans-serif">2 Years</p>
                                <p class="text-xs text-slate-400 mt-1">From date of examination</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 pt-5 md:pt-0 md:pl-8">
                            <span class="w-12 h-12 rounded-2xl bg-white/10 grid place-content-center text-xl shrink-0"><i class="fas fa-laptop"></i></span>
                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-wider">Exam Mode</p>
                                <p class="text-2xl font-bold mt-0.5" style="font-family:'Urbanist',sans-serif">Computer</p>
                                <p class="text-xs text-slate-400 mt-1">All sections are computer-based</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TEF Scoring Chart -->
                <div class="reveal mt-12">
                    <h3 class="text-2xl font-semibold text-ink mb-6">TEF Canada — NCLC Scoring Chart</h3>
                    <div class="overflow-x-auto rounded-3xl border border-slate-100 shadow-card">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-primary-600 text-white">
                                    <th class="py-4 px-5 text-left font-semibold rounded-tl-3xl">NCLC Level</th>
                                    <th class="py-4 px-5 text-left font-semibold">Reading</th>
                                    <th class="py-4 px-5 text-left font-semibold">Writing</th>
                                    <th class="py-4 px-5 text-left font-semibold">Listening</th>
                                    <th class="py-4 px-5 text-left font-semibold rounded-tr-3xl">Speaking</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-slate-100 hover:bg-mist transition-colors">
                                    <td class="py-3.5 px-5 font-semibold text-ink">10</td>
                                    <td class="py-3.5 px-5 text-slate-600">546–699</td>
                                    <td class="py-3.5 px-5 text-slate-600">546–699</td>
                                    <td class="py-3.5 px-5 text-slate-600">546–699</td>
                                    <td class="py-3.5 px-5 text-slate-600">546–699</td>
                                </tr>
                                <tr class="border-b border-slate-100 bg-mist hover:bg-primary-50 transition-colors">
                                    <td class="py-3.5 px-5 font-semibold text-ink">9</td>
                                    <td class="py-3.5 px-5 text-slate-600">503–545</td>
                                    <td class="py-3.5 px-5 text-slate-600">512–557</td>
                                    <td class="py-3.5 px-5 text-slate-600">503–545</td>
                                    <td class="py-3.5 px-5 text-slate-600">518–555</td>
                                </tr>
                                <tr class="border-b border-slate-100 hover:bg-mist transition-colors">
                                    <td class="py-3.5 px-5 font-semibold text-ink">8</td>
                                    <td class="py-3.5 px-5 text-slate-600">462–502</td>
                                    <td class="py-3.5 px-5 text-slate-600">472–511</td>
                                    <td class="py-3.5 px-5 text-slate-600">462–502</td>
                                    <td class="py-3.5 px-5 text-slate-600">494–517</td>
                                </tr>
                                <tr class="border-b border-slate-100 bg-primary-50 hover:bg-primary-100 transition-colors">
                                    <td class="py-3.5 px-5 font-bold text-primary-600">7</td>
                                    <td class="py-3.5 px-5 font-semibold text-primary-700">434–461</td>
                                    <td class="py-3.5 px-5 font-semibold text-primary-700">428–471</td>
                                    <td class="py-3.5 px-5 font-semibold text-primary-700">434–461</td>
                                    <td class="py-3.5 px-5 font-semibold text-primary-700">456–493</td>
                                </tr>
                                <tr class="border-b border-slate-100 hover:bg-mist transition-colors">
                                    <td class="py-3.5 px-5 font-semibold text-ink">6</td>
                                    <td class="py-3.5 px-5 text-slate-600">393–433</td>
                                    <td class="py-3.5 px-5 text-slate-600">379–427</td>
                                    <td class="py-3.5 px-5 text-slate-600">393–433</td>
                                    <td class="py-3.5 px-5 text-slate-600">422–455</td>
                                </tr>
                                <tr class="border-b border-slate-100 bg-mist hover:bg-primary-50 transition-colors">
                                    <td class="py-3.5 px-5 font-semibold text-ink">5</td>
                                    <td class="py-3.5 px-5 text-slate-600">352–392</td>
                                    <td class="py-3.5 px-5 text-slate-600">330–378</td>
                                    <td class="py-3.5 px-5 text-slate-600">352–392</td>
                                    <td class="py-3.5 px-5 text-slate-600">387–421</td>
                                </tr>
                                <tr class="hover:bg-mist transition-colors">
                                    <td class="py-3.5 px-5 font-semibold text-ink rounded-bl-3xl">4</td>
                                    <td class="py-3.5 px-5 text-slate-600">306–351</td>
                                    <td class="py-3.5 px-5 text-slate-600">268–329</td>
                                    <td class="py-3.5 px-5 text-slate-600">306–351</td>
                                    <td class="py-3.5 px-5 text-slate-600 rounded-br-3xl">328–386</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="mt-3 text-xs text-slate-400 text-center">NCLC 7 (highlighted) is the common minimum threshold for many Canadian immigration categories.</p>
                </div>
            </div>
        </section>

        <!-- ===== NAVY FACT BAND ===== -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal relative overflow-hidden rounded-5xl bg-[#002654] text-white p-8 md:p-14">
                    <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                    <div class="absolute -bottom-24 -right-16 w-80 h-80 rounded-full bg-[#EF4135]/20 blur-3xl"></div>
                    <div class="relative grid md:grid-cols-2 gap-10 items-center">
                        <div>
                            <span class="eyebrow text-[#8eb8e1]"><span class="tricolor"><i></i><i></i><i></i></span> Key requirement</span>
                            <h2 class="mt-4 text-3xl md:text-4xl font-semibold leading-tight">All four sections must be taken <span class="text-[#EF4135]">on the same day</span></h2>
                            <p class="mt-5 text-lg text-slate-300 leading-relaxed max-w-lg">Canadian immigration authorities only recognise your TEF Canada or TCF Canada certificate if listening, speaking, reading and writing were all completed in a single sitting. Preparation across every skill matters.</p>
                            <a href="/register" class="btn btn-onnavy btn-lg mt-8">Prepare with us</a>
                        </div>
                        <div class="md:justify-self-end w-full max-w-sm bg-white/5 backdrop-blur-sm border border-white/10 rounded-4xl p-7 space-y-5">
                            @php $facts = [
                                ['fa-calendar-check','Valid for 2 years','Results are accepted by IRCC for 2 years from the exam date.'],
                                ['fa-laptop','Computer-based','Both TEF Canada and TCF Canada are taken on a computer at an authorised centre.'],
                                ['fa-dollar-sign','$390–$450','Costs vary by centre and any additional materials included.'],
                            ]; @endphp
                            @foreach($facts as $f)
                            <div class="flex items-start gap-4">
                                <span class="w-10 h-10 rounded-xl bg-white/10 grid place-content-center text-sm shrink-0"><i class="fas {{ $f[0] }}"></i></span>
                                <div>
                                    <p class="font-bold text-white">{{ $f[1] }}</p>
                                    <p class="text-sm text-slate-300 mt-0.5">{{ $f[2] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== TCF CANADA ===== -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">

                <div class="max-w-2xl reveal">
                    <span class="eyebrow"><span class="tricolor"><i></i><i></i><i></i></span> TCF Canada</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">Test de Connaissance du Français</h2>
                    <p class="mt-4 text-slate-600 leading-relaxed">TCF Canada has four mandatory sections: listening, reading, speaking, and writing. The exam comprises 78 items incorporating listening and reading comprehension — all multiple-choice questions where candidates must pick one correct answer from 4 options.</p>
                </div>

                <!-- TCF sections grid -->
                @php $tcfSections = [
                    ['fa-headphones','Listening Comprehension','35 minutes','39 Multiple Choice Questions'],
                    ['fa-pen-nib','Written Expression','60 minutes','3 tasks'],
                    ['fa-book-open','Reading Comprehension','60 minutes','39 Multiple Choice Questions'],
                    ['fa-microphone-lines','Oral Expression','12 minutes','3 tasks'],
                ]; @endphp
                <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($tcfSections as $i => $ts)
                    <div class="reveal card-glow bg-white rounded-3xl border border-slate-100 p-6 shadow-card" style="transition-delay:{{ $i*70 }}ms">
                        <span class="w-11 h-11 rounded-xl bg-primary-50 text-primary-600 grid place-content-center text-lg"><i class="fas {{ $ts[0] }}"></i></span>
                        <h3 class="mt-4 text-lg font-bold text-ink">{{ $ts[1] }}</h3>
                        <div class="mt-3 space-y-2">
                            <p class="flex items-center gap-2 text-sm text-slate-600"><i class="fas fa-clock text-primary-400 w-4 shrink-0"></i> {{ $ts[2] }}</p>
                            <p class="flex items-center gap-2 text-sm text-slate-600"><i class="fas fa-list-check text-primary-400 w-4 shrink-0"></i> {{ $ts[3] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- TCF info band -->
                <div class="reveal mt-10 rounded-3xl bg-[#002654] text-white overflow-hidden relative">
                    <div class="tricolor-bar absolute top-0 left-0 right-0"></div>
                    <div class="grid md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-white/10 px-6 py-8">
                        <div class="flex items-center gap-4 pb-5 md:pb-0 md:pr-8">
                            <span class="w-12 h-12 rounded-2xl bg-white/10 grid place-content-center text-xl shrink-0"><i class="fas fa-dollar-sign"></i></span>
                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-wider">Exam Cost</p>
                                <p class="text-2xl font-bold mt-0.5" style="font-family:'Urbanist',sans-serif">$390–$450</p>
                                <p class="text-xs text-slate-400 mt-1">Depends on centre &amp; additional materials</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 py-5 md:py-0 md:px-8">
                            <span class="w-12 h-12 rounded-2xl bg-white/10 grid place-content-center text-xl shrink-0"><i class="fas fa-calendar-check"></i></span>
                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-wider">Validity</p>
                                <p class="text-2xl font-bold mt-0.5" style="font-family:'Urbanist',sans-serif">2 Years</p>
                                <p class="text-xs text-slate-400 mt-1">From date of examination</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 pt-5 md:pt-0 md:pl-8">
                            <span class="w-12 h-12 rounded-2xl bg-white/10 grid place-content-center text-xl shrink-0"><i class="fas fa-laptop"></i></span>
                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-wider">Exam Mode</p>
                                <p class="text-2xl font-bold mt-0.5" style="font-family:'Urbanist',sans-serif">Computer</p>
                                <p class="text-xs text-slate-400 mt-1">All sections are computer-based</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TCF Scoring Chart -->
                <div class="reveal mt-12">
                    <h3 class="text-2xl font-semibold text-ink mb-6">TCF Canada — NCLC Scoring Chart</h3>
                    <div class="overflow-x-auto rounded-3xl border border-slate-100 shadow-card">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-primary-600 text-white">
                                    <th class="py-4 px-5 text-left font-semibold rounded-tl-3xl">NCLC Level</th>
                                    <th class="py-4 px-5 text-left font-semibold">Reading</th>
                                    <th class="py-4 px-5 text-left font-semibold">Writing</th>
                                    <th class="py-4 px-5 text-left font-semibold">Listening</th>
                                    <th class="py-4 px-5 text-left font-semibold rounded-tr-3xl">Speaking</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-slate-100 hover:bg-mist transition-colors">
                                    <td class="py-3.5 px-5 font-semibold text-ink">10</td>
                                    <td class="py-3.5 px-5 text-slate-600">549–699</td>
                                    <td class="py-3.5 px-5 text-slate-600">16–20</td>
                                    <td class="py-3.5 px-5 text-slate-600">549–699</td>
                                    <td class="py-3.5 px-5 text-slate-600">16–20</td>
                                </tr>
                                <tr class="border-b border-slate-100 bg-mist hover:bg-primary-50 transition-colors">
                                    <td class="py-3.5 px-5 font-semibold text-ink">9</td>
                                    <td class="py-3.5 px-5 text-slate-600">524–548</td>
                                    <td class="py-3.5 px-5 text-slate-600">14–15</td>
                                    <td class="py-3.5 px-5 text-slate-600">523–548</td>
                                    <td class="py-3.5 px-5 text-slate-600">14–15</td>
                                </tr>
                                <tr class="border-b border-slate-100 hover:bg-mist transition-colors">
                                    <td class="py-3.5 px-5 font-semibold text-ink">8</td>
                                    <td class="py-3.5 px-5 text-slate-600">499–523</td>
                                    <td class="py-3.5 px-5 text-slate-600">12–13</td>
                                    <td class="py-3.5 px-5 text-slate-600">503–522</td>
                                    <td class="py-3.5 px-5 text-slate-600">12–13</td>
                                </tr>
                                <tr class="border-b border-slate-100 bg-primary-50 hover:bg-primary-100 transition-colors">
                                    <td class="py-3.5 px-5 font-bold text-primary-600">7</td>
                                    <td class="py-3.5 px-5 font-semibold text-primary-700">453–498</td>
                                    <td class="py-3.5 px-5 font-semibold text-primary-700">10–11</td>
                                    <td class="py-3.5 px-5 font-semibold text-primary-700">458–502</td>
                                    <td class="py-3.5 px-5 font-semibold text-primary-700">10–11</td>
                                </tr>
                                <tr class="border-b border-slate-100 hover:bg-mist transition-colors">
                                    <td class="py-3.5 px-5 font-semibold text-ink">6</td>
                                    <td class="py-3.5 px-5 text-slate-600">406–452</td>
                                    <td class="py-3.5 px-5 text-slate-600">7–9</td>
                                    <td class="py-3.5 px-5 text-slate-600">398–457</td>
                                    <td class="py-3.5 px-5 text-slate-600">7–9</td>
                                </tr>
                                <tr class="border-b border-slate-100 bg-mist hover:bg-primary-50 transition-colors">
                                    <td class="py-3.5 px-5 font-semibold text-ink">5</td>
                                    <td class="py-3.5 px-5 text-slate-600">375–405</td>
                                    <td class="py-3.5 px-5 text-slate-600">6</td>
                                    <td class="py-3.5 px-5 text-slate-600">369–397</td>
                                    <td class="py-3.5 px-5 text-slate-600">6</td>
                                </tr>
                                <tr class="hover:bg-mist transition-colors">
                                    <td class="py-3.5 px-5 font-semibold text-ink rounded-bl-3xl">4</td>
                                    <td class="py-3.5 px-5 text-slate-600">342–374</td>
                                    <td class="py-3.5 px-5 text-slate-600">4–5</td>
                                    <td class="py-3.5 px-5 text-slate-600">331–368</td>
                                    <td class="py-3.5 px-5 text-slate-600 rounded-br-3xl">4–5</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="mt-3 text-xs text-slate-400 text-center">NCLC 7 (highlighted) is the common minimum threshold for many Canadian immigration categories.</p>
                </div>
            </div>
        </section>

        <!-- ===== HOW WE PREPARE YOU ===== -->
        <section class="py-16 md:py-24 bg-mist">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto reveal">
                    <span class="eyebrow justify-center"><span class="tricolor"><i></i><i></i><i></i></span> How we prepare you</span>
                    <h2 class="mt-4 text-3xl md:text-[2.7rem] leading-tight font-semibold text-ink">Live, structured coaching for every section</h2>
                    <p class="mt-4 text-slate-600 leading-relaxed">At Fluence Française, exam preparation is not a crash course. It is built on a solid language foundation from A1 to B2 — so when you reach exam-level training, every skill is ready to be refined.</p>
                </div>
                @php $prep = [
                    ['fa-diagram-project','Structured levels','We move you through A1, A2, B1 and B2 with real assessments at each stage — not just timed seat time.'],
                    ['fa-headphones','Listening practice','Authentic audio texts and exam-style recordings used progressively from your first level onward.'],
                    ['fa-microphone-lines','Speaking simulations','Timed speaking tasks and guided oral expression practice that mirrors the TEF and TCF format.'],
                    ['fa-pen-nib','Writing correction','Teacher-reviewed written tasks with structured feedback on grammar, coherence and register.'],
                    ['fa-book-open','Reading strategies','Text-based comprehension exercises at increasing complexity to prepare you for exam-length passages.'],
                    ['fa-clipboard-check','Mock tests','Full timed practice under exam conditions, with result analysis against the NCLC scoring chart.'],
                ]; @endphp
                <div class="mt-12 grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($prep as $i => $p)
                    <div class="reveal card-glow bg-white rounded-3xl border border-slate-100 p-7" style="transition-delay:{{ $i*70 }}ms">
                        <span class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 grid place-content-center text-lg"><i class="fas {{ $p[0] }}"></i></span>
                        <h3 class="mt-5 text-xl font-bold text-ink">{{ $p[1] }}</h3>
                        <p class="mt-2 text-slate-600 leading-relaxed">{{ $p[2] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ===== CTA ===== -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal rounded-5xl bg-primary-600 text-white text-center px-6 py-14 md:py-16 relative overflow-hidden">
                    <div class="absolute inset-x-0 bottom-0 h-1.5" style="background:linear-gradient(90deg,#003d7a 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>
                    <h2 class="text-3xl md:text-5xl font-semibold leading-tight max-w-2xl mx-auto">Ready to prepare for your TEF / TCF exam?</h2>
                    <p class="mt-4 text-lg text-primary-100 max-w-xl mx-auto">Join {{ $settings['site_name'] ?? 'Fluence Française' }} and get expert guidance to achieve your target NCLC level.</p>
                    <div class="mt-8 flex flex-wrap justify-center gap-4">
                        <a href="/register" class="btn btn-onnavy btn-lg">Start Learning Now</a>
                        <a href="/contact-us" class="btn btn-lg" style="box-shadow:inset 0 0 0 2px #fff;color:#fff;background:transparent">Contact Us</a>
                    </div>
                </div>
            </div>
        </section>

        @include('partials.footer')
    </div>
</body>
</html>
