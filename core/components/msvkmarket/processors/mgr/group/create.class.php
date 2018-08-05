<?php

class msVKMarketGroupCreateProcessor extends modObjectCreateProcessor
{
    public $classKey = 'VkmGroups';
    public $objectType = 'VkmGroups';
    public $languageTopics = ['msvkmarket'];

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        $group_id = trim($this->getProperty('group_id'));

        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('msvkmarket_item_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name])) {
            $this->modx->error->addField('name', $this->modx->lexicon('msvkmarket_item_err_ae'));
        }

        if (empty($group_id)) {
            $this->modx->error->addField('group_id', $this->modx->lexicon('msvkmarket_item_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['group_id' => $group_id])) {
            $this->modx->error->addField('group_id', $this->modx->lexicon('msvkmarket_item_err_ae'));
        }



        return parent::beforeSet();
    }

}
return 'msVKMarcetGroupCreate';
