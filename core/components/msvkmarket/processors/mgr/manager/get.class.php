<?php

class msVKMarketManagerGetProcessor extends modObjectGetProcessor
{
    public $objectType = 'msVKMarketItem';
    public $classKey = 'msVKMarketItem';
    public $languageTopics = ['msvkmarket:default'];

    /**
     * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     *
     * @return mixed
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        return parent::process();
    }

}

return 'msVKMarketManagerGetProcessor';