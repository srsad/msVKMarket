<?php

class msVKMarketCompilationreateProcessor extends modObjectCreateProcessor
{
    public $classKey = 'VkmCompilation';
    public $objectType = 'VkmCompilation';
    public $languageTopics = ['msvkmarket'];

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        $group_id = (int)trim($this->getProperty('group_id'));

        $this->modx->log(1, print_r($this->getProperties(), true));

        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('msvkmarket_compialtion_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name])) {
            $this->modx->error->addField('name', $this->modx->lexicon('msvkmarket_compialtion_err_ae'));
        } elseif (empty($group_id) && !is_int($group_id)) {
            $this->modx->error->addField('group_id', $this->modx->lexicon('msvkmarket_compialtion_err_group_id'));
        }

        return parent::beforeSet();
    }
}
return 'msVKMarketCompilationreateProcessor';
