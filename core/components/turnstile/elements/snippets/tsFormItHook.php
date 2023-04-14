<?php

/**
 * @var \modX $modx
 * @var array $scriptProperties
 */
$version = 2;
$v = 'v2';
if (!isset($modx->version)) {
    $modx->getVersionData();
}
if (isset($this->modx->version['version'])) {
    $version = (int) $this->modx->version['version'];
}
if ($version < 3) {
    $turnstile = $modx->getService(
        'turnstile',
        'Turnstile',
        $modx->getOption(
            'turnstile.core_path',
            null,
            $modx->getOption('core_path') . 'components/turnstile/'
        ) . 'model/turnstile/'
    );
}else {
    $v = "v3";
}

$snippet = "\\Turnstile\\$v\\Snippets\\FormItHook";
if (class_exists($snippet)) {
    return (new $snippet($modx, $scriptProperties))->process();
} else {
    $modx->log(\modX::LOG_LEVEL_ERROR, "Turnstile: Snippet {$snippet} not found");
}