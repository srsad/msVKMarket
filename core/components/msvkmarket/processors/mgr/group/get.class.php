<?php

class msVKMarketGroupGetProcessor extends modObjectGetProcessor
{
    public $objectType = 'VkmGroups';
    public $classKey = 'VkmGroups';
    public $languageTopics = ['msvkmarket:default'];

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        return parent::process();
    }

}

return 'msVKMarketGroupGetProcessor';