<?php

namespace Turnstile\v3\Snippets;

class Render extends Snippet
{
    public function process()
    {
        $api = 'https://challenges.cloudflare.com/turnstile/v0/api.js';
        $callback = $this->getOption('callback', null, true);
        if ($callback) {
            $api.= '?onload='.$callback;
        }
        $this->modx->regClientScript(
            "<script src='$api' async defer></script>"
        );
        $tpl = $this->getOption('tpl', 'tsRender');
        return $this->modx->getChunk($tpl, [
            'sitekey' => $this->siteKey,
            'callback' => $callback,
            'data-callback' => ($callback) ? "data-callback=\"$callback\"" : ''
        ]);
    }
}
