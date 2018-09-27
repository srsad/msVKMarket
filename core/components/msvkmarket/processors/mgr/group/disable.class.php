<?php

class msVKMarketGroupDisableProcessor extends modObjectProcessor
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
            return $this->failure($this->modx->lexicon('msvkmarket_item_err_ns'));
        }

        foreach ($ids as $id) {

            /** @var xPDOObject $object*/
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('msvkmarket_item_err_nf'));
            }

            $object->set('status', false);
            $object->save();
        }

        return $this->success();
    }

}

return 'msVKMarketGroupDisableProcessor';