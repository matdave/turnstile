<?php

namespace Turnstile\v2;

class Turnstile
{
    /** @var \modX $modx */
    public $modx;

    public $namespace = 'turnstile';

    public $version = '1.0.0';

    /** @var array $config */
    public $config = [];

    public function __construct(\modX &$modx, array $config = [])
    {
        $this->modx =& $modx;

        $corePath = $this->getOption(
            'core_path',
            $config,
            $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/turnstile/'
        );

        $this->config = array_merge(
            [
                'corePath'  => $corePath,
                'srcPath'   => $corePath . 'src/',

                'templatesPath' => $corePath . 'templates/',
                'processorsPath' => $corePath . 'src/Processors',
            ],
            $config
        );

        $this->modx->addPackage('turnstile', $corePath.'model/');
        $this->modx->lexicon->load('turnstile:default');
    }


    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param  string  $key  The option key to search for.
     * @param  array  $options  An array of options that override local options.
     * @param  mixed  $default  The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     *
     * @return mixed The option value or the default value specified.
     */
    public function getOption(string $key, $options = [], $default = null)
    {
        $option = $default;

        if (empty($key)) {
            return $option;
        }

        if ($options != null && array_key_exists($key, $options)) {
            return $options[$key];
        }

        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }

        if (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
            return $this->modx->getOption("{$this->namespace}.{$key}");
        }

        return $option;
    }
}
