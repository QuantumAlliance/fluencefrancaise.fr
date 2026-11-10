<!-- Open Graph / Facebook -->
<meta property="og:type" content="<?php echo e($ogType ?? 'website'); ?>" />
    <meta property="og:url" content="<?php echo e($ogUrl ?? url()->current()); ?>" />
    <meta property="og:title" content="<?php echo e($ogTitle ?? ''); ?>" />
    <meta property="og:description" content="<?php echo e($ogDescription ?? ''); ?>" />
    <meta property="og:site_name" content="<?php echo e($ogSiteName ?? 'FocusFrame'); ?>" />
    <meta property="og:locale" content="en_US" />
    <?php if(isset($ogImage) && $ogImage): ?><meta property="og:image" content="<?php echo e($ogImage); ?>" />
    <?php endif; ?>
<?php if(isset($updatedAt) && $updatedAt): ?><meta property="og:updated_time" content="<?php echo e($updatedAt); ?>" />
    <?php endif; ?>
<?php if(isset($publishedAt) && $publishedAt): ?><meta property="article:published_time" content="<?php echo e($publishedAt); ?>" />
    <?php endif; ?>
<?php if(isset($updatedAt) && $updatedAt && ($ogType ?? 'website') === 'article'): ?><meta property="article:modified_time" content="<?php echo e($updatedAt); ?>" />
    <?php endif; ?>
<!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="<?php echo e($ogUrl ?? url()->current()); ?>" />
    <meta name="twitter:title" content="<?php echo e($ogTitle ?? ''); ?>" />
    <meta name="twitter:description" content="<?php echo e($ogDescription ?? ''); ?>" />
    <?php if(isset($ogImage) && $ogImage): ?><meta name="twitter:image" content="<?php echo e($ogImage); ?>" />
    <?php endif; ?>
<?php if(isset($readTime)): ?><meta name="twitter:label1" content="Time to read" />
    <meta name="twitter:data1" content="<?php echo e($readTime); ?>" />
    <?php endif; ?>
<?php /**PATH C:\Sushil Hemrom\fluencefrancaise\new\fluencefrancaise.fr\resources\views/partials/social-meta.blade.php ENDPATH**/ ?>