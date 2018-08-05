<?php

class msVKMarket
{
    /** @var modX $modx */
    public $modx;


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;

        $corePath = MODX_CORE_PATH . 'components/msvkmarket/';
        $assetsUrl = MODX_ASSETS_URL . 'components/msvkmarket/';

        #$corePath = MODX_BASE_PATH . 'msVKMarket/core/components/msvkmarket/';
        #$assetsUrl = MODX_BASE_URL . 'msVKMarket/assets/components/msvkmarket/';

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',

            'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
        ], $config);

        $this->modx->addPackage('msvkmarket', $this->config['modelPath']);
        $this->modx->lexicon->load('msvkmarket:default');
    }

}