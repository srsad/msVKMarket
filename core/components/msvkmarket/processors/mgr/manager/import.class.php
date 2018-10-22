<?php
include_once MODX_CORE_PATH . 'components/msvkmarket/processors/mgr/vk/event.trait.php';

class msVKMarketManagerImportProcessor extends modProcessor
{
    use msVKMarketVKEventTrait;

    public $classKey = 'VkmProduct';
    public $languageTopics = ['msvkmarket'];

    /**
     * @return array|mixed|string
     */
    public function process()
    {

        $ids         = explode(',', $this->getProperty('id'));
        $step        = $this->getProperty('step');
        $count       = count($ids) + $step;
        $action      = $this->modx->lexicon('msvkmarket_items_import_add');
        $id_groups   = !empty($this->getProperty('id_group')) ? trim($this->getProperty('id_group')) : $this->modx->getOption('msvkm_default_group');
        $albums_id   = explode(',', trim($this->getProperty('album_id')));
        $category_id = !empty($this->getProperty('category_id')) ? trim($this->getProperty('category_id')) : $this->modx->getOption('msvkm_default_category');
        // статус товара в вк, (1 — товар удален/не опубликован, 0 — товар не удален/опубликован)
        $status_vk   = $this->getProperty('status') === 'true' ? 0 : 1;
        $method      = 'market.add';
        $description = '';
        $msg = '';

        if (empty($ids[0])) {
            $msg      = $this->modx->lexicon('msvkmarket_items_import_end');
            $continue = false;
            $level    = xPDO::LOG_LEVEL_INFO;
            return $this->prepareResponse(true, $msg, $level, $continue);
        }

        // ссылочка для описания
        $link = $this->modx->getOption('msvkm_link_desc') . $this->modx->makeUrl($ids[0], '', '', 'full');

        // позиция для импорта
        $p_res                 = $this->modx->getObject('msProduct', $ids[0]);
        $p_option['id']        = $ids[0];
        $p_option['pagetitle'] = $p_res->get('pagetitle');
        $p_option['price']     = $p_res->get('price');
        $p_option['image']     = $p_res->get('image');
        $p_option['published'] = $p_res->get('published');
        $p_option['deleted']   = $p_res->get('deleted');

        // получем и формируем описания
        $description_array = $this->modx->getOption('msvkm_description_json');
        $description_array = json_decode($description_array, true);

        if (is_array($description_array) && count($description_array) > 0) {
            foreach ($description_array as $key => $val) {
                $value = $p_res->get($val);
                if (!empty($value)) {
                    if (is_array($value)) {
                        $value = implode(', ', $value);
                        if (!empty($value)) {
                            $description .= $key . ': ' . $value . "\n";
                        }
                    } else {
                        $description .= $key . ': ' . $value . "\n";
                    }
                }
            }
        }

        $description = strip_tags($description);
        if (empty($description) || strlen($description) < 11) {
            $description = $description . "\n" . $link;
        } elseif (strlen($description . $link) > 755) {
            /**
             * почемут больше 755 символов не проходит, хотя в документауии про ограничения на символы ничего нет
             * например тут https://vk.com/dev/market.add - см. `description`
             */
            $description = mb_substr($description, 0, 750-strlen($link)) . '... ';
        }

        $goods_link = $this->modx->getOption('msvkm_goods_link');
        if ($goods_link == true) { $description = $description . "\n" . $link; }
        $p_option['description'] = $description;

        // была ли ранее импортированна позиция
        /** @var xPDOObject $product */
        $product = $this->modx->getObject($this->classKey, $ids[0]);
        if (is_object($product) && $product->get('product_id')) {
            $p_option['status'] = $product->get('product_status'); // доступ к импорту
            $action = $this->modx->lexicon('msvkmarket_items_import_upd');
            $method = 'market.edit';
        } else {
            $p_option['status'] = $this->modx->getOption('msvkm_default_ststus');
        }

        // статус публикации в вк
        if (!empty($this->getProperty('status'))) {
            $p_option['status_vk'] = $status_vk;
        }

        // тут процесс импорта
        if ($p_option['status'] == true){
            // прогонка по группам
            foreach(explode(',', $id_groups) as $id_group){
                // была ли ранее импортированна позиция + проверка есть ли эта позиция в этой группе
                if ($method === 'market.edit'){
                    // если да, то достаем ее owner_id
                    // если нет, то делаем $method = 'market.edit'
                    $product_categories = $this->modx->getObject('VkmProductCategories', array(
                        'product_id' => $ids[0],
                        'groups_id' => $id_group
                    ));

                    if (is_object($product_categories) && $product_categories->get('owner_id')) {
                        $p_option['item_id'] = $product_categories->get('owner_id');
                    } else {
                        $method = 'market.add';
                    }
                }

                $importItem = json_decode($this->importItem($p_option, $id_group, $albums_id, $category_id, $method), true);

                $msg .= $this->modx->lexicon('msvkmarke_process_log', array(
                    'step' => ($step + 1),
                    'count' => $count,
                    'left' => ($count - ($step + 1)),
                    'id' => $ids[0],
                    'action' => $action,
                    'pagetitle' => $p_option['pagetitle'] . $importItem['result']
                ));
            }
        } else {
            $msg .= $this->modx->lexicon('msvkmarke_process_log', array(
                'step' => ($step + 1),
                'count' => $count,
                'left' => ($count - ($step + 1)),
                'id' => $ids[0],
                'action' => $this->modx->lexicon('msvkmarket_items_import_skp'),
                'pagetitle' => $p_option['pagetitle']
            ));
        }

        $continue = true;
        $level    = xPDO::LOG_LEVEL_INFO;
        return $this->prepareResponse(true, $msg, $level, $continue);
    }


    /**
     * @param $success
     * @param string $msg
     * @param int $level
     * @param bool $continue
     * @return array|string
     */
    protected function prepareResponse($success, $msg = '', $level = xPDO::LOG_LEVEL_INFO, $continue = false)
    {
        $result = array(
            "success" => $success,
            "message" => $msg,
            "level" => $level,
            "continue" => $continue,
            "data" => array()
        );
        if ($this->getProperty("output_format") == "json") {
            $result = json_encode($result);
        }
        return $result;
    }


}
return 'msVKMarketManagerImportProcessor';