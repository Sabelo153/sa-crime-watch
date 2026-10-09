<?php
// Development router. The web server document root must remain public/.
$watchPublic = __DIR__.DIRECTORY_SEPARATOR.'public';
$watchUri = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
$watchFile = realpath($watchPublic.$watchUri);
if ($watchUri !== '/' && $watchFile && str_starts_with($watchFile, $watchPublic.DIRECTORY_SEPARATOR) && is_file($watchFile)) {
    return false;
}
require $watchPublic.'/index.php';
