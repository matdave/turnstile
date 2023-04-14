<?php

/**
 * @var \modX $modx
 * @var array $scriptProperties
 */
$corePath = $modx->getOption(
    'turnstile.core_path',
    [],
    $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/turnstile/'
);
require_once($corePath.'vendor/autoload.php');
$version = 2;
$v = 'v2';
if (!isset($modx->version)) {
    $modx->getVersionData();
}
if (isset($this->modx->version['version'])) {
    $version = (int) $this->modx->version['version'];
}
if ($version > 2) {
    $v = "v3";
}

$snippet = "\\Turnstile\\$v\\Snippets\\Render";
if (class_exists($snippet)) {
    return (new $snippet($modx, $scriptProperties))->process();
} else {
    $modx->log(\modX::LOG_LEVEL_ERROR, "Turnstile: Snippet {$snippet} not found");
}