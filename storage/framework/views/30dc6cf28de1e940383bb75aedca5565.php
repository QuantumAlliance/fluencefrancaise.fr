
<?php if(isset($settings['custom_scripts'])): ?>
    <?php
        $scripts = json_decode($settings['custom_scripts'], true);
        $hasHeadScripts = false;
        if (is_array($scripts)) {
            foreach ($scripts as $script) {
                $scriptCode = is_array($script) ? ($script['code'] ?? '') : $script;
                $placement = is_array($script) ? ($script['placement'] ?? 'head') : 'head';
                if (!empty(trim($scriptCode)) && $placement === 'head') {
                    $hasHeadScripts = true;
                    break;
                }
            }
        }
    ?>
    <?php if($hasHeadScripts): ?>
        <!-- Custom Scripts - Head -->
        <?php
            foreach ($scripts as $script) {
                $scriptCode = is_array($script) ? ($script['code'] ?? '') : $script;
                $placement = is_array($script) ? ($script['placement'] ?? 'head') : 'head';
                if (!empty(trim($scriptCode)) && $placement === 'head') {
                    // Check if script already has <script> tags
                    $trimmedCode = trim($scriptCode);
                    if (stripos($trimmedCode, '<script') === 0) {
                        echo $scriptCode;
                    } else {
                        echo '<script>' . "\n" . $scriptCode . "\n" . '</script>';
                    }
                }
            }
        ?>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/partials/custom-scripts-head.blade.php ENDPATH**/ ?>