<?php

class msVKMarketCompilationRemoveProcessor extends modObjectProcessor
{
    public $objectType = 'VkmCompilation';
    public $classKey = 'VkmCompilation';
    public $languageTopics = ['msvkmarket'];

    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('msvkmarket_compilation_err_ns'));
        }

        foreach ($ids as $id) {
            /** @var VkmCompilation $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('msvkmarket_compilation_err_nf'));
            }

            $object->remove();
        }

        return $this->success();
    }

}

return 'msVKMarketCompilationRemoveProcessor';