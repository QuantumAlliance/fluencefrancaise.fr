<?php
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
?>

<footer id="contact" class="bg-[#002654] text-slate-300 relative">
    <div class="h-1.5 w-full" style="background:linear-gradient(90deg,#0055A4 0 33.3%,#fff 33.3% 66.6%,#EF4135 66.6% 100%)"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 mb-10">
            <!-- About Section -->
            <div class="md:col-span-5">
                <div class="flex items-center gap-2.5 mb-5">
                    <?php if(isset($settings['site_logo']) && $settings['site_logo']): ?>
                        <span class="inline-flex bg-white rounded-2xl p-2"><img src="<?php echo e(asset('storage/' . $settings['site_logo'])); ?>" alt="<?php echo e($siteName); ?>" class="h-12 w-auto"></span>
                    <?php else: ?>
                        <span class="inline-flex h-[26px] w-[9px] rounded-sm overflow-hidden shadow"><i class="block flex-1" style="height:33.3%;background:#0055A4"></i></span>
                        <span style="font-family:'Urbanist',system-ui,sans-serif" class="text-2xl font-bold text-white tracking-tight">Fluence<span class="text-[#EF4135]">.</span></span>
                    <?php endif; ?>
                </div>
                <p class="text-sm leading-relaxed text-slate-300/90 max-w-md"><?php echo e($description); ?></p>
                <div class="flex gap-3 mt-6">
                    <?php $__currentLoopData = ['facebook'=>'fa-facebook-f','instagram'=>'fa-instagram','twitter'=>'fa-twitter','linkedin'=>'fa-linkedin-in']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $icon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(isset($socialLinks[$key]) && !empty($socialLinks[$key]) && $socialLinks[$key] !== '#'): ?>
                            <a href="<?php echo e($socialLinks[$key]); ?>" target="_blank" class="w-9 h-9 rounded-full flex items-center justify-center bg-white/10 hover:bg-[#EF4135] text-white transition"><i class="fab <?php echo e($icon); ?>"></i></a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="md:col-span-3">
                <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Explore</h4>
                <ul class="space-y-3 text-sm">
                    <?php $__currentLoopData = $quickLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><a href="<?php echo e($link['url']); ?>" class="text-slate-300 hover:text-white hover:translate-x-1 inline-block transition"><?php echo e($link['text']); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>

            <!-- Contact Information -->
            <div class="md:col-span-4">
                <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Get in touch</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center gap-3"><i class="fas fa-envelope text-[#5993d0] w-4"></i> <a href="mailto:<?php echo e($contactEmail); ?>" class="hover:text-white transition"><?php echo e($contactEmail); ?></a></li>
                    <li class="flex items-center gap-3"><i class="fas fa-phone text-[#5993d0] w-4"></i> <?php echo e($contactPhone); ?></li>
                    <li class="flex items-center gap-3"><i class="fas fa-map-marker-alt text-[#5993d0] w-4"></i> <?php echo e($contactAddress); ?></li>
                </ul>
                <div class="mt-5 flex flex-wrap gap-2">
                    <?php $__currentLoopData = ['DELF','DALF','TCF','TEF']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="text-[11px] font-bold tracking-wide px-3 py-1 rounded-full bg-white/10 text-white"><?php echo e($exam); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <div class="border-t border-white/10 pt-7 flex justify-between items-center flex-wrap gap-4">
            <p class="text-sm text-slate-400"><?php echo e($copyrightText); ?></p>
            <div class="flex items-center gap-4 text-sm text-slate-400">
                <?php $__currentLoopData = $legalLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($link['url']); ?>" class="hover:text-white transition"><?php echo e($link['text']); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <span class="hidden sm:inline text-slate-600">·</span>
                <span class="hidden sm:inline"><?php echo $creditText; ?></span>
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
<?php /**PATH C:\Sushil Hemrom\fluencefrancaise\new\fluencefrancaise.fr\resources\views/partials/footer.blade.php ENDPATH**/ ?>