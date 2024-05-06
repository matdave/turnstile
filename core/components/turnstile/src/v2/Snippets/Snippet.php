<?php

namespace Turnstile\v2\Snippets;

abstract class Snippet
{
    /** @var \modX */
    protected $modx;

    /** @var array */
    protected $sp = [];

    /** @var bool */
    protected $debug = false;

    protected string $siteKey;
    protected string $secretKey;

    public function __construct(\modX &$modx, array $sp = [])
    {
        $this->modx =& $modx;
        $this->sp = $sp;
        $this->debug = (bool)$this->getOption('debug', 0);
        $this->siteKey = $this->getOption('turnstile.sitekey');
        $this->secretKey = $this->getOption('turnstile.secretkey');
    }

    abstract public function process();

    protected function getOption($key, $default = null, $skipEmpty = false)
    {
        return $this->modx->getOption($key, $this->sp, $default, $skipEmpty);
    }

    protected function debug(string $msg, $data = null): void
    {
        if (!$this->debug) {
            return;
        }

        if (is_array($data) || is_object($data)) {
            $formattedData = '<pre>' . print_r($data, true) . '</pre>';
        } else {
            $formattedData = $data;
        }

        echo "[" . Snippet::class . "]: " . $msg . ' ' . $formattedData . '<br />';
    }

    public function curl(string $endpoint, string $type, array $fields = []): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if (!empty($fields)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        }
        $response = [
            'result' => curl_exec($ch),
            'error' => curl_error($ch),
            'code' => curl_getinfo($ch, CURLINFO_HTTP_CODE)
        ];
        curl_close($ch);
        if (!empty($response['result'])) {
            $response['result'] = json_decode($response['result']);
        }
        return $response;
    }
}
