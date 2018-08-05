<?php

class msVKMarketManagerCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'msVKMarketItem';
    public $classKey = 'msVKMarketItem';
    public $languageTopics = ['msvkmarket'];
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('msvkmarket_item_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name])) {
            $this->modx->error->addField('name', $this->modx->lexicon('msvkmarket_item_err_ae'));
        }

        return parent::beforeSet();
    }

}

return 'msVKMarketManagerCreateProcessor';