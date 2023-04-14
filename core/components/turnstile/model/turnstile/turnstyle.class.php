<?php
$corePath = $modx->getOption(
    'turnstile.core_path',
    [],
    $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/turnstile/'
);
require_once($corePath.'vendor/autoload.php');

return '\Turnstile\v2\Turnstile';
