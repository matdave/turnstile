<?php

namespace Turnstile\v3\Snippets;

class FormItHook extends Snippet
{
    public function process()
    {
        if (!isset($this->sp['hook'])) {
            return false;
        }
        $hook = $this->sp['hook'];
        $token = $hook->getValue('cf-turnstile-response');
        // Get the user's IP address
        $ip = null;
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        }
        $verify = $this->curl(
            'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            'POST',
            [
                'secret' => $this->secretKey,
                'response' => $token,
                'remoteip' => $ip
            ]
        );
        if (!isset($verify['result']->success) || !$verify['result']->success) {
            if (isset($verify['result']->{'error-codes'})) {
                $hook->addError('cf-turnstile-response', implode(', ', $verify['result']->{'error-codes'}));
            } else {
                $hook->addError('cf-turnstile-response', $verify['error']);
            }
            return false;
        }

        return true;
    }
}
