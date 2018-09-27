<?php

class msVKMarketManagerRemoveProcessor extends modObjectProcessor
{
    public $objectType = 'msVKMarketItem';
    public $classKey = 'msVKMarketItem';
    public $languageTopics = ['msvkmarket'];
    //public $permission = 'remove';


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
            return $this->failure($this->modx->lexicon('msvkmarket_item_err_ns'));
        }

        foreach ($ids as $id) {
            /** @var xPDOObject $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('msvkmarket_item_err_nf'));
            }

            $object->remove();
        }

        return $this->success();
    }

}

return 'msVKMarketManagerRemoveProcessor';