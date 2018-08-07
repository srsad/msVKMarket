<?php

class msVKMarketCompilationGetProcessor extends modObjectGetProcessor
{
    public $objectType = 'VkmCompilation';
    public $classKey = 'VkmCompilation';
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

return 'msVKMarketCompilationGetProcessor';