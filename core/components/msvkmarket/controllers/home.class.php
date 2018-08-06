<?php

/**
 * The home manager controller for msVKMarket.
 *
 */
class msVKMarketHomeManagerController extends modExtraManagerController
{
    /** @var msVKMarket $msVKMarket */
    public $msVKMarket;


    /**
     *
     */
    public function initialize()
    {
        #$this->msVKMarket = $this->modx->getService('msVKMarket', 'msVKMarket', MODX_CORE_PATH . 'components/msvkmarket/model/');
        $this->msVKMarket = $this->modx->getService('msVKMarket', 'msVKMarket', MODX_BASE_PATH . 'msVKMarket/core/components/msvkmarket/model/');
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['msvkmarket:default'];
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('msvkmarket');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->msVKMarket->config['cssUrl'] . 'mgr/main.css?time=' . time());
        $this->addJavascript($this->msVKMarket->config['jsUrl'] . 'mgr/msvkmarket.js?time=' . time());
        $this->addJavascript($this->msVKMarket->config['jsUrl'] . 'mgr/misc/utils.js?time=' . time());
        $this->addJavascript($this->msVKMarket->config['jsUrl'] . 'mgr/misc/combo.js?time=' . time());

        $this->addJavascript($this->msVKMarket->config['jsUrl'] . 'mgr/widgets/manager/grid.js?time=' . time());
        $this->addJavascript($this->msVKMarket->config['jsUrl'] . 'mgr/widgets/manager/windows.js?time=' . time());
        $this->addJavascript($this->msVKMarket->config['jsUrl'] . 'mgr/widgets/manager/tree.js?time=' . time());

        $this->addJavascript($this->msVKMarket->config['jsUrl'] . 'mgr/widgets/group/grid.js?time=' . time());
        $this->addJavascript($this->msVKMarket->config['jsUrl'] . 'mgr/widgets/group/window.js?time=' . time());

        $this->addJavascript($this->msVKMarket->config['jsUrl'] . 'mgr/widgets/compilation/grid.js?time=' . time());
        $this->addJavascript($this->msVKMarket->config['jsUrl'] . 'mgr/widgets/compilation/window.js?time=' . time());

        $this->addJavascript($this->msVKMarket->config['jsUrl'] . 'mgr/widgets/home.panel.js?time=' . time());
        $this->addJavascript($this->msVKMarket->config['jsUrl'] . 'mgr/sections/home.js?time=' . time());

        $this->addHtml('<script type="text/javascript">
        msVKMarket.config = ' . json_encode($this->msVKMarket->config) . ';
        msVKMarket.config.connector_url = "' . $this->msVKMarket->config['connectorUrl'] . '";
        Ext.onReady(function() {MODx.load({ xtype: "msvkmarket-page-home"});});
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="msvkmarket-panel-home-div"></div>';

        return '';
    }
}