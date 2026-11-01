<?php
/** CLI: php tools/restore-database.php --key-file=/private/key.txt --output=/private/restore.sql */
if (PHP_SAPI !== 'cli') { http_response_code(403); exit('CLI only'); }
$args = getopt('', array('key-file:', 'output:'));
if (empty($args['key-file']) || empty($args['output'])) {
    fwrite(STDERR, "Usage: php tools/restore-database.php --key-file=/private/key.txt --output=/private/restore.sql\n");
    exit(1);
}
$keyHex = trim(file_get_contents($args['key-file']));
if (!preg_match('/^[a-f0-9]{64}$/iD', $keyHex)) { fwrite(STDERR, "Invalid key file\n"); exit(1); }
$encrypted = file_get_contents(dirname(__DIR__) . '/database/latest.sql.gz.enc');
if (strlen($encrypted) < 33 || substr($encrypted, 0, 5) !== 'ATDB1') { fwrite(STDERR, "Invalid encrypted database\n"); exit(1); }
$compressed = openssl_decrypt(substr($encrypted, 33), 'aes-256-gcm', hex2bin($keyHex), OPENSSL_RAW_DATA, substr($encrypted, 5, 12), substr($encrypted, 17, 16), 'ATDB1');
if ($compressed === false) { fwrite(STDERR, "Decryption failed; key mismatch or file damaged\n"); exit(1); }
$sql = gzdecode($compressed);
if ($sql === false) { fwrite(STDERR, "Database decompression failed\n"); exit(1); }
$out = fopen($args['output'], 'xb');
if (!$out) { fwrite(STDERR, "Output must be a new writable file; nothing overwritten\n"); exit(1); }
$written = fwrite($out, $sql); fclose($out);
if ($written !== strlen($sql)) { fwrite(STDERR, "Incomplete output; check disk space\n"); exit(1); }
echo "Database restored to the requested local output. Import into your selected hosting database.\n";
