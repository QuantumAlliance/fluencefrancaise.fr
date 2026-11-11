<?php
    $isFr = $isFr ?? request()->is('fr*');

    $defaultEyebrow = $isFr ? "VOS QUESTIONS, NOS RÉPONSES" : "YOUR QUESTIONS, ANSWERED";
    $defaultTitle = $isFr ? "TCF IRN<br>FAQ" : "TCF IRN<br>FAQ";
    $defaultDescription = $isFr ? "Des réponses claires avant de passer à l'étape suivante." : "Clear answers before you take your next step.";
    $defaultContactText = $isFr ? "Une autre question ?" : "Have another question?";
    $defaultContactUrl = $isFr ? "/fr/contact/" : "/en/contact/";

    $faqEyebrow = $eyebrow ?? $defaultEyebrow;
    $faqTitle = $title ?? $defaultTitle;
    $faqDescription = $description ?? $defaultDescription;
    $faqContactText = $contactText ?? $defaultContactText;
    $faqContactUrl = $contactUrl ?? $defaultContactUrl;
    $faqItems = $faqs ?? [];
    $faqSectionId = $sectionId ?? "faq";
    $faqSectionClass = $sectionClass ?? "py-16 sm:py-24 bg-white border-b border-slate-100";
    $faqContainerClass = $containerClass ?? "container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl";
    $componentUid = 'faq_' . substr(md5($faqSectionId . count($faqItems)), 0, 6);
?>

<!-- ============ REUSABLE FAQ SECTION COMPONENT ============ -->
<section class="<?php echo e($faqSectionClass); ?>" id="<?php echo e($faqSectionId); ?>">
    <div class="<?php echo e($faqContainerClass); ?>">
        <div class="grid lg:grid-cols-12 gap-8 lg:gap-16 items-start">
            <!-- Left Column: Eyebrow, H2, Subtext, Contact link -->
            <div class="lg:col-span-5 lg:sticky lg:top-28 space-y-4">
                <!-- Eyebrow with Blue & Red Dashes -->
                <?php if(!empty($faqEyebrow)): ?>
                <div class="inline-flex items-center gap-1.5">
                    <span class="inline-block w-3.5 h-[2.5px] bg-[#0055A4] rounded-full"></span>
                    <span class="inline-block w-3.5 h-[2.5px] bg-[#EF4135] rounded-full mr-1.5"></span>
                    <span class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase text-slate-500">
                        <?php echo e($faqEyebrow); ?>

                    </span>
                </div>
                <?php endif; ?>

                <!-- Section Title -->
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold font-display text-[#10233e] tracking-tight leading-[1.08]">
                    <?php echo $faqTitle; ?>

                </h2>

                <!-- Subtext -->
                <?php if(!empty($faqDescription)): ?>
                <p class="text-sm sm:text-base text-slate-500 font-normal leading-relaxed max-w-sm pt-1">
                    <?php echo nl2br(e($faqDescription)); ?>

                </p>
                <?php endif; ?>

                <!-- Contact Link -->
                <?php if(!empty($faqContactText)): ?>
                <div class="pt-2">
                    <a href="<?php echo e($faqContactUrl); ?>" class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#0055A4] hover:text-[#002654] group transition-colors">
                        <span><?php echo e($faqContactText); ?></span>
                        <span class="inline-block transform group-hover:translate-x-1 transition-transform font-normal">→</span>
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Clean Divided Accordion List -->
            <div class="lg:col-span-7 divide-y divide-slate-200/80 border-t border-b border-slate-200/80">
                <?php $__currentLoopData = $faqItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $q = $item['q'] ?? ($item['question'] ?? ($item[0] ?? ''));
                        $a = $item['a'] ?? ($item['answer'] ?? ($item[1] ?? ''));
                        $itemId = "{$componentUid}_item_{$idx}";
                    ?>
                    <div class="group">
                        <button 
                            type="button"
                            onclick="toggleFaqItem('<?php echo e($itemId); ?>')"
                            class="w-full py-5 sm:py-6 text-left flex items-center justify-between gap-4 font-semibold text-sm sm:text-[15px] md:text-base text-[#10233e] hover:text-[#0055A4] transition-colors focus:outline-none"
                            aria-expanded="false"
                            id="btn_<?php echo e($itemId); ?>"
                        >
                            <span class="pr-2 leading-snug"><?php echo e($q); ?></span>
                            <span 
                                id="icon_<?php echo e($itemId); ?>" 
                                class="w-7 h-7 sm:w-8 sm:h-8 rounded-full border border-slate-200/80 bg-slate-50/60 group-hover:bg-blue-50/70 group-hover:border-[#0055a4]/30 flex items-center justify-center shrink-0 text-slate-400 group-hover:text-[#0055A4] transition-all duration-300 transform"
                            >
                                <svg class="w-3.5 h-3.5 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </span>
                        </button>
                        <div 
                            id="content_<?php echo e($itemId); ?>" 
                            class="hidden pb-6 pr-4 sm:pr-8 text-sm sm:text-[15px] text-slate-600 leading-relaxed font-normal"
                        >
                            <?php echo is_string($a) ? $a : ''; ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>

<!-- Interactive FAQ Script (Idempotent) -->
<script>
    if (typeof window.toggleFaqItem === 'undefined') {
        window.toggleFaqItem = function(id) {
            const content = document.getElementById('content_' + id);
            const icon = document.getElementById('icon_' + id);
            const btn = document.getElementById('btn_' + id);
            if (!content || !icon) return;

            const isHidden = content.classList.contains('hidden');
            if (isHidden) {
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(45deg)';
                icon.classList.remove('text-slate-400', 'bg-slate-50/60', 'border-slate-200/80');
                icon.classList.add('bg-blue-50', 'text-[#0055A4]', 'border-[#0055A4]/40');
                if (btn) btn.setAttribute('aria-expanded', 'true');
            } else {
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
                icon.classList.add('text-slate-400', 'bg-slate-50/60', 'border-slate-200/80');
                icon.classList.remove('bg-blue-50', 'text-[#0055A4]', 'border-[#0055A4]/40');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            }
        };
    }
</script>
<?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/partials/faq-section.blade.php ENDPATH**/ ?>