{{-- Shared theme: French-tricolor design system (blue #0055A4 · white · red #EF4135) --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Urbanist:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    display: ['Urbanist', 'system-ui', 'sans-serif'],
                    sans: ['DM Sans', 'system-ui', 'sans-serif'],
                },
                colors: {
                    /* legacy token names kept so older utilities still resolve */
                    brand: '#0055A4',
                    'brand-dark': '#003d7a',
                    navy: '#002654',
                    primary: {
                        50: '#eff5fb', 100: '#dbe9f6', 200: '#bcd5ee', 300: '#8eb8e1',
                        400: '#5993d0', 500: '#2f72ba', 600: '#0055A4', 700: '#00468a',
                        800: '#003366', 900: '#002654',
                    },
                    accent: { DEFAULT: '#EF4135', dark: '#cf2e22', soft: '#fde8e6' },
                    ink: '#16213E',
                    mist: '#f4f7fb',
                },
                boxShadow: {
                    soft: '0 10px 30px -12px rgba(0, 85, 164, 0.18)',
                    card: '0 1px 2px rgba(16,33,62,.04), 0 12px 32px -16px rgba(0,85,164,.22)',
                    lift: '0 24px 60px -20px rgba(0,38,84,.35)',
                },
                borderRadius: { '4xl': '2rem', '5xl': '2.75rem' },
            }
        }
    }
</script>

<style>
    :root {
        --blue: #0055A4; --blue-dark: #003d7a; --navy: #002654;
        --red: #EF4135; --ink: #16213E; --mist: #f4f7fb;
    }
    * { transition-property: color, background-color, border-color, fill, stroke; transition-duration: .2s; }
    html { scroll-behavior: smooth; }
    body { font-family: 'DM Sans', system-ui, sans-serif; color: var(--ink); background: #fff; -webkit-font-smoothing: antialiased; }
    h1, h2, h3, h4, .font-display { font-family: 'Urbanist', system-ui, sans-serif; letter-spacing: -0.02em; }

    /* ---- Signature tricolor motif ---- */
    .tricolor { display:inline-flex; height:4px; width:64px; border-radius:9999px; overflow:hidden; }
    .tricolor i { flex:1; }
    .tricolor i:nth-child(1){ background:var(--blue);} .tricolor i:nth-child(2){ background:#fff; box-shadow:inset 0 0 0 1px rgba(22,33,62,.08);} .tricolor i:nth-child(3){ background:var(--red);}
    .tricolor-bar { height:6px; background:linear-gradient(90deg,var(--blue) 0 33.3%,#fff 33.3% 66.6%,var(--red) 66.6% 100%); }

    /* hand-drawn red underline under a key word */
    .swish { position:relative; white-space:nowrap; }
    .swish::after {
        content:""; position:absolute; left:-2%; right:-2%; bottom:-.12em; height:.34em;
        background: var(--red);
        -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 14' preserveAspectRatio='none'%3E%3Cpath d='M2 9 C40 2 80 2 120 6 C150 9 175 7 198 3' stroke='black' stroke-width='5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") no-repeat center/100% 100%;
                mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 14' preserveAspectRatio='none'%3E%3Cpath d='M2 9 C40 2 80 2 120 6 C150 9 175 7 198 3' stroke='black' stroke-width='5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") no-repeat center/100% 100%;
        opacity:.95;
    }

    /* soft centered gradient wash behind hero (galaxysites-style, tricolor) */
    .hero-aurora {
        position: absolute; inset: 0; pointer-events: none;
        background:
            radial-gradient(48% 55% at 15% 8%, rgba(0,85,164,.16), transparent 70%),
            radial-gradient(46% 50% at 85% 4%, rgba(239,65,53,.12), transparent 72%),
            radial-gradient(44% 46% at 52% -8%, rgba(123,91,214,.10), transparent 72%),
            radial-gradient(60% 55% at 50% 120%, rgba(0,85,164,.05), transparent 70%);
    }

    /* full-bleed saturated hero wash (galaxysites-style, homepage) */
    .hero-aurora-strong {
        position: absolute; inset: 0; pointer-events: none; overflow: hidden;
        background:
            /* gentle white lift behind the headline so text stays crisp */
            radial-gradient(46% 44% at 50% 46%, rgba(255,255,255,.55), transparent 78%),
            /* main color fields — fill the WHOLE hero like the reference */
            radial-gradient(75% 110% at 0% 0%, rgba(123,91,214,.55), transparent 64%),
            radial-gradient(70% 105% at 100% 0%, rgba(64,170,255,.50), transparent 62%),
            radial-gradient(55% 80% at 38% 8%, rgba(0,85,164,.30), transparent 66%),
            radial-gradient(60% 85% at 88% 78%, rgba(255,170,130,.40), transparent 64%),
            radial-gradient(55% 80% at 10% 85%, rgba(239,65,53,.22), transparent 64%),
            linear-gradient(160deg, #e9e2f7 0%, #e7eefb 45%, #fdf0ea 100%);
    }
    /* fade to white at the very bottom so it blends into the next section */
    .hero-aurora-strong::before {
        content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 26%;
        background: linear-gradient(to bottom, rgba(255,255,255,0), #ffffff 92%);
    }
    /* sparkle dots */
    .hero-aurora-strong::after {
        content: ""; position: absolute; inset: 0;
        background-image:
            radial-gradient(rgba(255,255,255,.9) 1.2px, transparent 1.8px),
            radial-gradient(rgba(255,255,255,.7) 1px, transparent 1.5px);
        background-size: 190px 170px, 120px 140px;
        background-position: 0 0, 60px 80px;
        opacity: .8;
    }

    /* eyebrow label */
    .eyebrow { display:inline-flex; align-items:center; gap:.55rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; font-size:.72rem; color:var(--blue); }

    /* ---- Buttons ---- */
    .btn { display:inline-flex; align-items:center; justify-content:center; gap:.55rem; font-weight:700; border-radius:9999px; transition:all .25s ease; cursor:pointer; line-height:1; text-decoration:none; }
    .btn-lg { padding:1.05rem 2.2rem; font-size:.95rem; } .btn-md { padding:.8rem 1.6rem; font-size:.875rem; }
    .btn-primary { background:var(--blue); color:#fff; box-shadow:0 10px 24px -10px rgba(0,85,164,.6); }
    .btn-primary:hover { background:var(--blue-dark); transform:translateY(-2px); box-shadow:0 16px 30px -12px rgba(0,85,164,.7); }
    .btn-accent { background:var(--red); color:#fff; box-shadow:0 10px 24px -10px rgba(239,65,53,.55); }
    .btn-accent:hover { background:var(--accent-dark,#cf2e22); transform:translateY(-2px); }
    .btn-outline { background:#fff; color:var(--blue); box-shadow:inset 0 0 0 2px var(--blue); }
    .btn-outline:hover { background:var(--blue); color:#fff; }
    .btn-ghost { color:var(--ink); } .btn-ghost:hover { color:var(--blue); }
    .btn-onnavy { background:#fff; color:var(--navy); } .btn-onnavy:hover { background:var(--red); color:#fff; transform:translateY(-2px); }

    /* ---- Reveal on scroll ---- */
    .reveal { opacity:0; transform:translateY(22px); transition:opacity .7s cubic-bezier(.16,1,.3,1), transform .7s cubic-bezier(.16,1,.3,1); }
    .reveal.in { opacity:1; transform:none; }
    @media (prefers-reduced-motion: reduce){ .reveal{opacity:1;transform:none;transition:none;} }

    /* ---- Gradient-border glow on hover (galaxysites-style, tricolor) ---- */
    .card-glow { position: relative; z-index: 0; transition: transform .35s cubic-bezier(.16,1,.3,1), box-shadow .35s ease; }
    .card-glow::before {
        content: ""; position: absolute; inset: -2px; border-radius: inherit; padding: 2px;
        background: linear-gradient(135deg, var(--blue) 0%, #7b5bd6 50%, var(--red) 100%);
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
                mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor; mask-composite: exclude;
        opacity: 0; transition: opacity .35s ease; pointer-events: none;
    }
    .card-glow:hover { transform: translateY(-6px); box-shadow: 0 26px 52px -22px rgba(0,85,164,.45); }
    .card-glow:hover::before { opacity: 1; }
    @media (prefers-reduced-motion: reduce){ .card-glow:hover{transform:none;} }

    /* ---- Mobile type scale matched to galaxysites.ca (max-767px) ---- */
    @media (max-width: 767px) {
        .landing-page h1 { font-size: 32px !important; line-height: 1.15 !important; }
        .landing-page h2 { font-size: 24px !important; line-height: 1.22 !important; }
        .landing-page h3 { font-size: 20px !important; line-height: 1.3 !important; }
        .landing-page h4 { font-size: 18px !important; }
        .landing-page p.text-lg, .landing-page p.text-xl { font-size: 14px !important; line-height: 1.65 !important; }
        .landing-page p { font-size: 14px; }
        /* big display numbers (prices, €50) */
        .landing-page span.text-5xl, .landing-page p.text-5xl { font-size: 32px !important; }
        .landing-page p.text-7xl { font-size: 40px !important; }
        .btn-lg { padding: .9rem 1.8rem; font-size: 14px; }
    }

    .container { max-width: 1200px !important; }
    .no-scrollbar::-webkit-scrollbar { display:none; } .no-scrollbar { -ms-overflow-style:none; scrollbar-width:none; }
    body.dashboard-page .global-header { display:none !important; }
    body.dashboard-page { padding-top:0 !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var els = document.querySelectorAll('.reveal');
        if (!('IntersectionObserver' in window) || !els.length) { els.forEach(e => e.classList.add('in')); return; }
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en, i) {
                if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
        els.forEach(function (e) { io.observe(e); });
    });
</script>
