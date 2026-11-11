
<?php if(isset($settings['custom_scripts'])): ?>
    <?php
        $scripts = json_decode($settings['custom_scripts'], true);
        $hasBodyScripts = false;
        if (is_array($scripts)) {
            foreach ($scripts as $script) {
                $scriptCode = is_array($script) ? ($script['code'] ?? '') : $script;
                $placement = is_array($script) ? ($script['placement'] ?? 'head') : 'head';
                if (!empty(trim($scriptCode)) && $placement === 'body') {
                    $hasBodyScripts = true;
                    break;
                }
            }
        }
    ?>
    <?php if($hasBodyScripts): ?>
        <!-- Custom Scripts - Body -->
        <?php
            foreach ($scripts as $script) {
                $scriptCode = is_array($script) ? ($script['code'] ?? '') : $script;
                $placement = is_array($script) ? ($script['placement'] ?? 'head') : 'head';
                if (!empty(trim($scriptCode)) && $placement === 'body') {
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
<?php /**PATH C:\Sushil Hemrom\fluencefrancaise\Fluence-Francaise\resources\views/partials/custom-scripts-body.blade.php ENDPATH**/ ?>