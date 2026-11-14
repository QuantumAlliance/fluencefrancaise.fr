<?php
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $siteName = $settings['site_name'] ?? 'Fluence Française';
    $footerData = isset($settings['footer_settings']) ? json_decode($settings['footer_settings'], true) : null;
    
    // French homepage is /, English homepage is /en. Other French pages use /fr.
    $isFr = request()->is('fr*') || request()->is('/') || (!request()->is('en*') && app()->getLocale() === 'fr');
    
    $description = $isFr 
        ? "Fluence Française : cours de français en ligne, en direct avec un tuteur, du A1 au B2. Préparation au TCF IRN, au TEF IRN, au DELF et au test fide, examen civique inclus, pour vos démarches en France et en Suisse."
        : "Fluence Française: live online French classes with a tutor, A1 to B2. Prep for the TCF IRN, TEF IRN, DELF and fide test, civic exam included, for applications in France and Switzerland.";

    $quickLinks = [
        ['text' => $isFr ? 'Accueil' : 'Home', 'url' => $isFr ? '/' : '/en'],
        ['text' => $isFr ? 'Cours' : 'Courses', 'url' => $isFr ? '/fr/cours-de-francais-en-ligne' : '/en/online-french-classes'],
        ['text' => $isFr ? 'Parrainage' : 'Referral', 'url' => $isFr ? '/fr/contact/#parrainage' : '/en/contact#referral'],
        ['text' => 'FAQ', 'url' => $isFr ? '/#faq' : '/en#faq']
    ];

    $examLinks = [
        ['text' => 'TCF IRN', 'url' => $isFr ? '/fr/tcf-irn' : '/en/tcf-irn'],
        ['text' => 'TEF IRN', 'url' => $isFr ? '/fr/tef-irn' : '/en/tef-irn'],
        ['text' => 'DELF', 'url' => $isFr ? '/fr/delf' : '/en/delf'],
        ['text' => $isFr ? 'Test fide' : 'fide test', 'url' => $isFr ? '/fr/test-fide' : '/en/fide-test'],
        ['text' => $isFr ? 'Examen civique' : 'Civic exam', 'url' => $isFr ? '/fr/examen-civique' : '/en/french-civic-exam']
    ];

    $legalLinks = [
        ['text' => $isFr ? 'Mentions légales' : 'Legal notice', 'url' => '/new-policies#legal'],
        ['text' => $isFr ? 'Politique de confidentialité' : 'Privacy policy', 'url' => '/new-policies']
    ];

    $contactEmail = !empty($settings['contact_email']) ? $settings['contact_email'] : (!empty($footerData['contact']['email']) ? $footerData['contact']['email'] : 'contact@fluencefrancaise.fr');
    $contactPhone = !empty($settings['contact_phone']) ? $settings['contact_phone'] : (!empty($footerData['contact']['phone']) ? $footerData['contact']['phone'] : '');
    $showPhone = preg_replace('/\D+/', '', (string) $contactPhone) !== '' && preg_replace('/\D+/', '', (string) $contactPhone) !== '33123456789';

    $socialLinks = $footerData['social'] ?? [
        'facebook' => '#',
        'instagram' => '#',
        'twitter' => '#',
        'linkedin' => '#'
    ];

    $independenceLine = $isFr
        ? "Fluence Française est une école de préparation indépendante. Nous ne faisons pas passer les examens TCF, TEF, DELF, DALF, fide ni l'examen civique, et nous ne sommes affiliés à aucun organisme certificateur."
        : "Fluence Française is an independent prep school. We don't administer the TCF, TEF, DELF, DALF, fide or civic exams, and we're not affiliated with any exam body.";
?>

<footer id="contact" class="bg-[#002654] text-slate-300 relative">
    <!-- Signature Tricolor Bar -->
    <div class="h-1.5 w-full" style="background:linear-gradient(90deg,#0055A4 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 mb-12">
            <!-- Brand & Description Column -->
            <div class="md:col-span-4">
                <div class="flex items-center gap-2.5 mb-5">
                    <img src="<?php echo e(asset('images/brand-mark.png')); ?>" alt="<?php echo e($siteName); ?>" class="h-9 w-auto">
                    <span style="font-family:'Urbanist',system-ui,sans-serif" class="text-2xl font-bold text-white tracking-tight">
                        Fluence <span class="text-[#EF4135]">Française</span>
                    </span>
                </div>
                <p class="text-sm leading-relaxed text-slate-300/90 max-w-sm"><?php echo e($description); ?></p>
                <div class="flex gap-3 mt-6">
                    <?php $__currentLoopData = ['facebook'=>'fa-facebook-f','instagram'=>'fa-instagram','twitter'=>'fa-twitter','linkedin'=>'fa-linkedin-in']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $icon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(isset($socialLinks[$key]) && !empty($socialLinks[$key]) && $socialLinks[$key] !== '#'): ?>
                            <a href="<?php echo e($socialLinks[$key]); ?>" target="_blank" class="w-9 h-9 rounded-full flex items-center justify-center bg-white/10 hover:bg-[#EF4135] text-white transition">
                                <i class="fab <?php echo e($icon); ?>"></i>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Column 1: Explore -->
            <div class="md:col-span-2 md:col-start-6">
                <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">
                    <?php echo e($isFr ? 'Explorer' : 'Explore'); ?>

                </h4>
                <ul class="space-y-3 text-sm">
                    <?php $__currentLoopData = $quickLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="<?php echo e($link['url']); ?>" class="text-slate-300 hover:text-white hover:translate-x-1 inline-block transition">
                                <?php echo e($link['text']); ?>

                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>

            <!-- Column 2: Contact -->
            <div class="md:col-span-3">
                <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Contact</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center gap-3">
                        <i class="fas fa-envelope text-[#5993d0] w-4"></i> 
                        <a href="mailto:<?php echo e($contactEmail); ?>" class="hover:text-white transition"><?php echo e($contactEmail); ?></a>
                    </li>
                    <?php if($showPhone): ?>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-phone text-[#5993d0] w-4"></i> 
                        <span><?php echo e($contactPhone); ?></span>
                    </li>
                    <?php endif; ?>
                    <?php if (! ($isFr)): ?>
                    <li class="flex items-center gap-3">
                        <i class="fab fa-whatsapp text-[#5993d0] w-4"></i> 
                        <span>WhatsApp available</span>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Column 3: Exams -->
            <div class="md:col-span-2">
                <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">
                    <?php echo e($isFr ? 'Examens' : 'Exams'); ?>

                </h4>
                <ul class="space-y-3 text-sm">
                    <?php $__currentLoopData = $examLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="<?php echo e($link['url']); ?>" class="text-slate-300 hover:text-white hover:translate-x-1 inline-block transition">
                                <?php echo e($link['text']); ?>

                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>

        <!-- Independence Legal Disclaimer Line -->
        <div class="border-t border-white/10 pt-6 pb-6 text-xs text-slate-400 leading-relaxed max-w-4xl">
            <p><?php echo e($independenceLine); ?></p>
        </div>

        <!-- Copyright & Legal Links -->
        <div class="border-t border-white/10 pt-6 flex justify-between items-center flex-wrap gap-4 text-sm text-slate-400">
            <p>© <?php echo e(date('Y')); ?> <?php echo e($siteName); ?>. <?php echo e($isFr ? 'Tous droits réservés.' : 'All rights reserved.'); ?></p>
            <div class="flex items-center gap-4">
                <?php $__currentLoopData = $legalLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($link['url']); ?>" class="hover:text-white transition"><?php echo e($link['text']); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</footer>

<?php if(isset($settings['custom_scripts'])): ?>
    <?php
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
    ?>
<?php endif; ?>
<?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/partials/footer.blade.php ENDPATH**/ ?>