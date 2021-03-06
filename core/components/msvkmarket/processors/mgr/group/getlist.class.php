<?php

class msVKMarketGroupGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'VkmGroups';
    public $classKey = 'VkmGroups';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    public $languageTopics = ['msvkmarket'];

    /**
     * We do a special check of permissions
     * because our objects is not an instances of modAccessibleObject
     *
     * @return boolean|string
     */

    public function beforeQuery()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }

    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = trim($this->getProperty('query'));
        $where = trim($this->getProperty('where'));

        if ($query) {
            $c->where([
                'name:LIKE' => "%{$query}%"
            ]);
        } elseif (!empty($where)) {
            $c->where(json_decode($where, true));
        }

        return $c;
    }

    /**
     * @param xPDOObject $object
     *
     * @return array
     */

    public function prepareRow(xPDOObject $object)
    {
        $array = $object->toArray();
        $array['actions'] = [];

        // Edit
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('msvkmarket_item_update'),
            //'multiple' => $this->modx->lexicon('msvkmarket_groups_update'),
            'action' => 'updateGroup',
            'button' => true,
            'menu' => true,
        ];

        if (!$array['status']) {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-green',
                'title' => $this->modx->lexicon('msvkmarket_item_enable'),
                'multiple' => $this->modx->lexicon('msvkmarket_item_enable'),
                'action' => 'enableGroup',
                'button' => true,
                'menu' => true,
            ];
        } else {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-gray',
                'title' => $this->modx->lexicon('msvkmarket_item_disable'),
                'multiple' => $this->modx->lexicon('msvkmarket_items_disable'),
                'action' => 'disableGroup',
                'button' => true,
                'menu' => true,
            ];
        }

        // Remove
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('msvkmarket_item_remove'),
            'multiple' => $this->modx->lexicon('msvkmarket_items_remove'),
            'action' => 'removeGroup',
            'button' => true,
            'menu' => true,
        ];

        return $array;
    }


}

return 'msVKMarketGroupGetListProcessor';