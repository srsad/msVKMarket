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
        $fields = array(
            'name'      => trim($this->getProperty('name')),
            'group_id'  => trim($this->getProperty('group_id')),
            'app_id'    => trim($this->getProperty('app_id')),
            'secretkey' => trim($this->getProperty('secretkey')),
            'token'     => trim($this->getProperty('token')),
        );

        $this->checkFields($fields);

        return parent::beforeSet();
    }


    /**
     * Проверка уникальности параметров группы
     *
     * @param array $fields
     */
    public function checkFields ($fields = array())
    {
        foreach ($fields as $key => $val) {
            if (empty($val)) {
                $this->modx->error->addField($key, $this->modx->lexicon('msvkmarket_group_err_' . $key));
            } elseif ($this->modx->getCount($this->classKey, [$key => $val])) {
                $this->modx->error->addField($key, $this->modx->lexicon('msvkmarket_group_err_ae'));
            }
        }

    }

}
return 'msVKMarketGroupCreateProcessor';
