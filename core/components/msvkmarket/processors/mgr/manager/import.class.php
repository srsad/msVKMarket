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
        $status      = $this->getProperty('status') === 'true' ? 1 : 0;
        $description = '';

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

        //$this->modx->log(1, print_r($p_option, true));

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

        if (empty($description) || strlen($description) < 11) {
            $description = $description . "\n" . $link;
        } else {
            $link = $this->modx->getOption('msvkm_goods_link');
            if ($link == true) {
                $description = $description . "\n" . $link;
            }
        }

        $p_option['description'] = $description;

        // была ли ранее импортированна позиция
        /** @var xPDOObject $product */
        $product = $this->modx->getObject($this->classKey, $ids[0]);
        if (is_object($product) && $product->get('product_id')) {
            $p_option['status'] = $product->get('product_status');
            $action = $this->modx->lexicon('msvkmarket_items_import_upd');
            // была ли она ранее в данной группе
            // тут процесс импорта - обновление
            //$this->updItem();

        } else {
            // статус для раннее не импортированных позици по умолчанию
            if ($this->modx->getOption('msvkm_default_ststus') == false) {
                $action = $this->modx->lexicon('msvkmarket_items_import_skp');
            } else {
                // тут процесс импорта
                foreach(explode(',', $id_groups) as $id_group){
                    $this->addItem($p_option, $id_group, $albums_id, $category_id);
                }
            }

        }

        //$this->modx->log(1, print_r($this->modx->getOption('msvkm_default_ststus'), true));

        $msg = $this->modx->lexicon('msvkmarke_process_log', array(
            'step' => ($step + 1),
            'count' => $count,
            'left' => ($count - ($step + 1)),
            'id' => $ids[0],
            'action' => $action,
            'pagetitle' => $p_option['pagetitle']
        ));

        //

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