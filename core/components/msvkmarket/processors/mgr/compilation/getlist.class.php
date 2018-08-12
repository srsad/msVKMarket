<?php


class msVKMarketGroupGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'VkmCompilation';
    public $classKey = 'VkmCompilation';
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
        $c->leftJoin('VkmGroups', 'VkmGroups', 'VkmCompilation.group_id = VkmGroups.id');
        $c->select('`VkmCompilation`.*, `VkmGroups`.`name` AS groupname');
        $c->where([
            'VkmGroups.status' => true
        ]);

        if ($query) {
            $c->where([
                'name:LIKE' => "%{$query}%"
            ]);
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
            'action' => 'updateCompilation',
            'button' => true,
            'menu' => true,
        ];

        // Remove
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('msvkmarket_item_remove'),
            'multiple' => $this->modx->lexicon('msvkmarket_items_remove'),
            'action' => 'removeCompilation',
            'button' => true,
            'menu' => true,
        ];

        return $array;
    }


}

return 'msVKMarketGroupGetListProcessor';