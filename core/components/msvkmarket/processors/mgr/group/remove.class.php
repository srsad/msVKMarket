<?php

class msVKMarketGroupRemoveProcessor extends modObjectProcessor
{
    public $objectType = 'VkmGroups';
    public $classKey = 'VkmGroups';
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
            return $this->failure($this->modx->lexicon('msvkmarket_group_err_ns'));
        }

        foreach ($ids as $id) {
            /** @var VkmGroups $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('msvkmarket_group_err_nf'));
            }

            $object->remove();
        }

        return $this->success();
    }

}

return 'msVKMarketGroupRemoveProcessor';