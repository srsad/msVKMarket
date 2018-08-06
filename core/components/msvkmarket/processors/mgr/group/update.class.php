<?php

class msVKMarketGroupUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'VkmGroups';
    public $classKey = 'VkmGroups';
    public $languageTopics = ['msvkmarket'];


    /**
     * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     *
     * @return bool|string
     */
    public function beforeSave()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $id = (int)$this->getProperty('id');
        $fields = array(
            'name'      => trim($this->getProperty('name')),
            'group_id'  => trim($this->getProperty('group_id')),
            'app_id'    => trim($this->getProperty('app_id')),
            'secretkey' => trim($this->getProperty('secretkey')),
            'token'     => trim($this->getProperty('token')),
        );

        if (empty($id)) {
            return $this->modx->lexicon('msvkmarket_item_err_ns');
        }

        $this->checkFields($fields, $id);

        return parent::beforeSet();
    }


    /**
     * @param array $array
     * @param $id
     */
    public function checkFields($array = array(), $id)
    {
        foreach ($array as $key => $val) {
            if (empty($val)) {
                $this->modx->error->addField($key, $this->modx->lexicon('msvkmarket_group_err_' . $key));
            } elseif ($this->modx->getCount($this->classKey, [$key => $val, 'id:!=' => $id])) {
                $this->modx->error->addField($key, $this->modx->lexicon('msvkmarket_group_err_ae'));
            }
        }

    }

}

return 'msVKMarketGroupUpdateProcessor';
