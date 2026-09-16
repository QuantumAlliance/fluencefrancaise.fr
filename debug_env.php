<?php
$env = file_get_contents('.env');
preg_match('/GOOGLE_CLIENT_SECRET=(.*)/', $env, $secretMatches);
preg_match('/GOOGLE_CLIENT_ID=(.*)/', $env, $idMatches);

echo "Checking .env integrity...\n";
echo "GOOGLE_CLIENT_ID length: " . strlen(trim($idMatches[1] ?? '')) . "\n";
echo "GOOGLE_CLIENT_SECRET length: " . strlen(trim($secretMatches[1] ?? '')) . "\n";
echo "GOOGLE_CLIENT_SECRET value (first 5 chars): " . substr(trim($secretMatches[1] ?? ''), 0, 5) . "\n";
echo "Has double quotes? " . (strpos($secretMatches[1] ?? '', '"') !== false ? 'YES' : 'NO') . "\n";
