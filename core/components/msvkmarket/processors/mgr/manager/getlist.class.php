<?php

class msVKMarketItemGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'miniShop2';
    public $classKey = 'msProduct';
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
        $c->leftJoin('VkmProduct', 'VkmProduct', 'msProduct.id = VkmProduct.product_id');
        $c->leftJoin('msProductData', 'Data', 'msProduct.id = Data.id');
        $c->leftJoin('msCategory', 'Category', 'msProduct.parent = Category.id');
        $c->select('`msProduct`.`pagetitle`, `msProduct`.`id`, `msProduct`.`parent`,  `Data`.`thumb`, 
                    `Category`.`pagetitle` AS category_name,
                    `VkmProduct`.`product_id`, `VkmProduct`.`product_status`, `VkmProduct`.`published` AS vkpublished, 
                    `VkmProduct`.`image_sync`, `VkmProduct`.`date_sync` ');

        $category   = (int)$this->getProperty('category', 0);
        $categories = $this->getProperty('categories', '[]');
        $categories = json_decode($categories, true);
        $where[]    = array(
            'class_key' => $this->classKey,
            'deleted' => false,
            'published' => true
        );

        // todo пеервести $category в массив и использовать только одно услови `msProduct.parent:IN`
        if ($category > 0) {
            $where[] = array('msProduct.parent' => $category);
        }
        if (count($categories) > 0) {
            $where[] = array('msProduct.parent:IN' => $categories);
        }
        $c->where($where);
        $query = trim($this->getProperty('query'));
        if ($query) {
            $c->where([
                'msProduct.pagetitle:LIKE' => "%{$query}%",
                'OR:msProduct.longtitle:LIKE' => "%{$query}%",
                'OR:msProduct.description:LIKE' => "%{$query}%",
                'OR:msProduct.introtext:LIKE' => "%{$query}%",
                'OR:Data.article:LIKE' => "%{$query}%",
                'OR:Data.made_in:LIKE' => "%{$query}%",
                'OR:Vendor.name:LIKE' => "%{$query}%",
                'OR:Category.pagetitle:LIKE' => "%{$query}%",
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
            'action' => 'updateItem',
            'button' => true,
            'menu' => true,
        ];

        // доступен для экспорта, поумолчанию - ддоступен
        if (!$array['published']) {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-green',
                'title' => $this->modx->lexicon('msvkmarket_item_enable'),
                'multiple' => $this->modx->lexicon('msvkmarket_items_enable'),
                'action' => 'publishedEnable',
                'button' => true,
                'menu' => true,
            ];
        } else {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-gray',
                'title' => $this->modx->lexicon('msvkmarket_item_disable'),
                'multiple' => $this->modx->lexicon('msvkmarket_items_disable'),
                'action' => 'publishedDisable',
                'button' => true,
                'menu' => true,
            ];
        }

        // был ли экспортирован ранее
        if (!$array['product_status']) {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-eye action-green',
                'title' => $this->modx->lexicon('msvkmarket_item_enable'),
                'multiple' => $this->modx->lexicon('msvkmarket_items_enable'),
                'action' => 'productStatusEnableItem',
                'button' => true,
                'menu' => true,
            ];
        } else {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-eye-slash action-red',
                'title' => $this->modx->lexicon('msvkmarket_item_disable'),
                'multiple' => $this->modx->lexicon('msvkmarket_items_disable'),
                'action' => 'productStatusDisable',
                'button' => true,
                'menu' => true,
            ];
        }

/*
        // Remove
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('msvkmarket_item_remove'),
            'multiple' => $this->modx->lexicon('msvkmarket_items_remove'),
            'action' => 'removeItem',
            'button' => true,
            'menu' => true,
        ];
*/
        return $array;
    }

}

return 'msVKMarketItemGetListProcessor';