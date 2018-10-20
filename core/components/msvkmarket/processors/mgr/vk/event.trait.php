<?php
include_once(MODX_CORE_PATH . 'components/msvkmarket/libs/VK.php');
include_once(MODX_CORE_PATH . 'components/msvkmarket/libs/VKException.php');

trait msVKMarketVKEventTrait
{
    public $languageTopics = ['msvkmarket'];

    public function testTrait()
    {
        $this->modx->log(modX::LOG_LEVEL_ERROR, 'test trait');
    }

    /**
     * Возвращаем параметры группы
     *
     * @param $group_id - group id
     * @return mixed
     */
    public function getGroupParam($group_id)
    {
        $groups_cache = $this->modx->cacheManager->get('groups_data');

        if(@!array_key_exists($group_id, $groups_cache)){
            /** @var xPDOObject $items */
            $items = $this->modx->getCollection('VkmGroups', $group_id);
            $groups = array();
            // параметры подборок данной группы
            /** @var xPDOObject $compilations */
            $compilations = $this->modx->getCollection('VkmCompilation', array( 'group_id' => $group_id ));
            /** @var xPDOObject $val */
            foreach ($compilations as $val) { $compilation[$val->get('id')] = $val->get('album_id'); }

            /** @var xPDOObject $item */
            foreach($items as $item){
                $groups[$item->get('id')] = [
                    'id'          => $item->get('id'),
                    'name'        => $item->get('name'),
                    'app_id'      => $item->get('app_id'),
                    'secretkey'   => $item->get('secretkey'),
                    'token'       => $item->get('token'),
                    'group_id'    => $item->get('group_id'),
                    'status'      => $item->get('status'),
                    'compilation' => $compilation
                ];
            }

            $groups_cache = !is_array($groups_cache) ? $groups : $groups_cache + $groups;

            $this->modx->cacheManager->set('groups_data', $groups_cache);
            $groups_cache = $this->modx->cacheManager->get('groups_data');
        }

        if (array_key_exists($group_id, $groups_cache)){
            return $groups_cache[$group_id];
        }

        return false;
    }


    /**
     * Создание подборки в указанной группе
     *
     * @param $id_group
     * @param $album_name
     * @return array|string
     */
    public function createAlbum($id_group, $album_name)
    {
        $groups_param   = $this->getGroupParam($id_group);

        if ($groups_param === false || empty($album_name)) {
            return json_encode(array(
                'success' => false,
                'result' => $this->modx->log('msvkmarket_compilation_creat_albom_error_name')
            ));
        }

        $app_id         = $groups_param['app_id'];
        $api_secret     = $groups_param['secretkey'];
        $access_token   = $groups_param['token'];
        $group_id       = $groups_param['group_id'];

        try {
            $vk = new VK\VK($app_id, $api_secret, $access_token);
            $vk->setApiVersion('5.59');
        }
        catch (VK\VKException $error) {
            $vk      = '';
            $error[] = $this->modx->lexicon('msvkmarket_connect_error', array(
                'action' => 'market.addAlbum'
            ));
            $this->modx->log(1, print_r($error, 1));
        }

        $add_album = $vk->api('market.addAlbum', array(
            'owner_id' => '-' . $group_id,
            'title' => $album_name
            // 'photo_id'  => ''
        ));

        // todo разобраться с этой ошибкой (при отключенном интернете)
        if (!isset($add_album['response']['market_album_id'])) {
            $this->modx->log(1, '[msVKMarket] ' . $this->modx->lexicon('msvkmarket_compilation_create_album_error_album_id'));
            return json_encode(array(
                'success' => false,
                'result' => $this->modx->lexicon('msvkmarket_compilation_create_album_error_album_id')
            ));
        }

        if (isset($add_album['error'])) {
            $error = $this->modx->lexicon('msvkmarket_compilation_create_albom_error_log');
            $this->modx->log(1, '[msVKMarket] ' . print_r($add_album, 1));
            return json_encode(array(
                'success' => false,
                'result' => $error
            ));
        }

        return json_encode(array(
            'success' => true,
            'result' => $add_album['response']['market_album_id']
        ));
    }


    /**
     * Редактирование подборки
     *
     * @param $id_group
     * @param $album_id
     * @param $new_name
     * @return string
     */
    public function updateAlbum($id_group, $album_id, $new_name)
    {
        $groups_param   = $this->getGroupParam($id_group);

        if ($groups_param === false || empty($album_id)) {
            return json_encode(array(
                'success' => false,
                'result' => $this->modx->lexicon('msvkmarket_compilation_create_albom_error_id')
            ));
        }

        $app_id         = $groups_param['app_id'];
        $api_secret     = $groups_param['secretkey'];
        $access_token   = $groups_param['token'];
        $group_id       = $groups_param['group_id'];

        try {
            $vk = new VK\VK($app_id, $api_secret, $access_token);
            $vk->setApiVersion('5.59');
        }
        catch (VK\VKException $error) {
            $vk      = '';
            $error[] = $this->modx->lexicon('msvkmarket_connect_error', array(
                'action' => 'market.editAlbum'
            ));
            $this->modx->log(1, print_r($error, 1));
        }

        $edit_album = $vk->api('market.editAlbum', array(
            'owner_id' => '-' . $group_id,
            'title' => $new_name,
            'album_id' => $album_id
        ));

        if (isset($edit_album['error'])) {
            $error[] = $this->modx->lexicon('msvkm_create_albom_error_edit') . print_r($edit_album['error'], 1);
            $this->modx->log(1, '[msVKMarket] - ' . print_r($edit_album['error'], 1));

            return json_encode(array(
                'success' => false,
                'result' => $edit_album['error']['error_msg']
            ));
        }
        //usleep(100);

        return json_encode(array(
            'success' => true,
            'result' => $this->modx->lexicon('msvkmarket_success')
        ));
    }


    /**
     * Экспорт подборок
     *
     * @param $id_group
     * @return string
     */
    public function exportAlbum($id_group)
    {
        $groups_param = $this->getGroupParam($id_group);

        if ($groups_param === false) {
            return json_encode(array(
                'success' => false,
                'result' => $this->modx->lexicon('msvkmarket_group_err_not_id')
            ));
        }

        try {
            $vk = new VK\VK($groups_param['app_id'], $groups_param['secretkey'], $groups_param['token']);
            $vk->setApiVersion('5.59');
        }
        catch (VK\VKException $error) {
            $vk      = '';
            $error[] = $this->modx->lexicon('msvkmarket_connect_error', array(
                'action' => 'market.getAlbums'
            ));
            $this->modx->log(modX::LOG_LEVEL_ERROR, print_r($error, 1));
        }

        $album_list = $vk->api('market.getAlbums', array(
            'owner_id' => '-' . $groups_param['group_id'],
            'count' => 100
        ));

        if (isset($album_list['error'])) {
             // $error[] = $this->modx->lexicon('msvkmarketexport_albom_error') . print_r($album_list['error'], true);
            $msg = empty($album_list) ? $this->modx->lexicon('msvkmarket_export_album_error') : $album_list['error']['msg'];
            return json_encode(array(
                'success' => false,
                'result' => $msg
            ));
        }

        $compilations_group = $this->modx->getCollection('VkmCompilation', array( 'group_id' => $groups_param['id'] ));
        $export_count = 0;

        foreach ($album_list['response']['items'] as $album){
            $availabel = true;
            foreach ($compilations_group as $item) {
                if($album['id'] == $item->get('album_id')){
                    $availabel = false;
                    break;
                }
            }

            if($availabel == true && $album['title'] != ''){
                $new_album = $this->modx->newObject('VkmCompilation');
                $new_album->set('group_id', $groups_param['id']);
                $new_album->set('name', $album['title']);
                $new_album->set('album_id', $album['id']);
                $new_album->save();
                $export_count++;
            }
        }

        return json_encode(array(
            'success' => true,
            'result' => $this->modx->lexicon('msvkmarket_compilation_export_response', array(
                'name' => $groups_param['name'],
                'count' => $album_list['response']['count'],
                'export' => $export_count
            ))
        ));
    }


    /**
     * Удаление подборки
     *
     * @param $id_group
     * @param $album_id
     * @return string
     */
    public function removeAlbum($id_group, $album_id)
    {
        $groups_param   = $this->getGroupParam($id_group);

        if ($groups_param === false && empty($album_id)) {
            return json_encode(array(
                'success' => false,
                'result' => $this->modx->lexicon('msvkmarket_compilation_creat_albom_error_id')
            ));
        }

        try {
            $vk = new VK\VK($groups_param['app_id'], $groups_param['secretkey'], $groups_param['token']);
            $vk->setApiVersion('5.59');
        }
        catch (VK\VKException $error) {
            $vk      = '';
            $error[] = $this->modx->lexicon('msvkmarket_connect_error', array(
                'action' => 'market.deleteAlbum'
            ));
            $this->modx->log(1, print_r($error, 1));
        }

        $add_album = $vk->api('market.deleteAlbum', array(
            'owner_id' => '-' . $groups_param['group_id'],
            'album_id' => $album_id
        ));

        if (isset($add_album['error'])
            && strcasecmp($add_album['error']['error_msg'], 'Album not found') != 0
            && strcasecmp($add_album['error']['error_msg'], 'Internal server error') != 0
            // рудименты vk
            && $add_album['error']['request_params'][2]['value'] != '-1'
            && $add_album['error']['request_params'][2]['value'] != 0
        ) {

            $error[] = '[msVKMarket] - ' . $this->modx->lexicon('msvkmarket_compilation_remove_albom_error', array(
                    'msg' => $add_album['error']['error_msg']
                ));
            $this->modx->log(1, '[msVKMarket] - ' . print_r($add_album, 1));

            return json_encode(array(
                'success' => false,
                'result' => $error
            ));

        } else {
            return json_encode(array(
                'success' => true,
                'result' => $add_album['response']['market_album_id']
            ));
        }
    }


    /**
     * Добавлеия подборки
     *
     * @param array $item - массив с объектом данных
     * @param $id_group - id группы
     * @param array $albums_id - id альбома
     * @param $category_id - id категории, список https://vk.com/dev/market.getCategories?params[count]=100&params[v]=5.85
     * @return string
     *
    public function addItem(array $item, $id_group, array $albums_id, $category_id)
    {
        $msg = '';

        $groups_param   = $this->getGroupParam($id_group);
        if ($groups_param === false && empty($album_id)) {
            return json_encode(array(
                'success' => false,
                'result' => $this->modx->lexicon('msvkmarket_compilation_creat_albom_error_id')
            ));
        }

        //
        if (!empty($groups_param['compilation']) && count($albums_id) > 0) {
            $compilation_id = '';
            $album_id       = '';
            foreach ($groups_param['compilation'] as $key => $val) {
                if (in_array($key, $albums_id)) {
                    $compilation_id .= $key . ',';
                    $album_id .= $val . ',';
                }
            }
            $item['compilation_id'] = substr($compilation_id, 0, -1);
            $item['album_ids']      = substr($album_id, 0, -1);
        }

        // тут импорт
        // todo
        $vk_import = $this->vkImport($item, $groups_param, 'market.add');

        if ($vk_import['success'] === true && !empty($vk_import['result']) && !empty($vk_import['main_photo_id'])){
            $this->setProducts($item, $groups_param, $vk_import);
        } elseif (empty($vk_import['result'])) {
            $msg = ' - <span class="red"> ' . $this->modx->lexicon('msvkmarket_el_empty_item_id') . ' </span>';
        } else {
            $msg = ' - <span class="red"> ' . $vk_import['result'] . ' </span>';
        }

        return json_encode(array(
            'success' => true,
            'result' => $msg
        ));
    }
    */


    /**
     * Обновление
     *
     * @param array $item - массив с объектом данных
     * @param $id_group - id группы
     * @param $albums_id - id альбома
     * @param $category_id - id категории, список https://vk.com/dev/market.getCategories?params[count]=100&params[v]=5.85
     * @param $action - market.add/market.edit
     * @return string
     */
    public function importItem($item, $id_group, $albums_id, $category_id, $action)
    {
        $msg = '';
        $groups_param   = $this->getGroupParam($id_group);
        if ($groups_param === false && empty($album_id)) {
            return json_encode(array(
                'success' => false,
                'result' => $this->modx->lexicon('msvkmarket_compilation_creat_albom_error_id')
            ));
        }

        if (!empty($groups_param['compilation']) && count($albums_id) > 0) {
            $compilation_id = '';
            $album_id       = '';
            foreach ($groups_param['compilation'] as $key => $val) {
                if (in_array($key, $albums_id)) {
                    $compilation_id .= $key . ',';
                    $album_id .= $val . ',';
                }
            }
            $item['compilation_id'] = substr($compilation_id, 0, -1);
            $item['album_ids']      = substr($album_id, 0, -1);
        }

        $vk_import = $this->vkImport($item, $groups_param, $action);

        if ($action === 'market.add') {
            if ($vk_import['success'] === true && !empty($vk_import['result']) && !empty($vk_import['main_photo_id'])){
                $this->setProducts($item, $groups_param, $vk_import);
            } elseif (empty($vk_import['result'])) {
                $msg = ' - <span class="red"> ' . $this->modx->lexicon('msvkmarket_el_empty_item_id') . ' </span>';
            } else {
                $msg = ' - <span class="red"> ' . $vk_import['result'] . ' </span>';
            }
        }else{
            if ($vk_import['success'] === true) {
                $this->setProducts($item, $groups_param, $vk_import);
            } else {
                $msg = ' - <span class="red"> ' . $this->modx->lexicon('msvkmarket_err_el_upd') . ' </span>';
            }
        }


        return json_encode(array(
            'success' => true,
            'result' => $msg
        ));
    }


    /**
     * Импорт, в качестве ответа возвращает статус
     *
     * @param array $item
     * @param array $group
     * @param $action - market.add/market.edit
     * @return array
     */
    public function vkImport($item, $group, $action){

        if ((!is_array($group) && count($group) == 0) && (!is_array($item) && count($item) == 0)) {
            $this->modx->log(1, $this->modx->lexicon('msvkmarket_error_not_options', array(
                'method' => __METHOD__
            )));
            return array(
                'success' => false
            );
        }

        if (empty($action)) {
            $this->modx->log(1, $this->modx->lexicon('msvkmarket_err_not_action', array(
                'method' => __METHOD__
            )));
            return array(
                'success' => false
            );
        }

        $product = $item;
        $warning = '';

        $app_id       = $group['app_id'];
        $api_secret   = $group['secretkey'];
        $access_token = $group['token'];
        $group_id     = $group['group_id'];
        //$status       = $group['status'];

        try {
            $vk = new VK\VK($app_id, $api_secret, $access_token);
            $vk->setApiVersion('5.59');
        } catch (VK\VKException $error) {
            $this->modx->log(1, $this->modx->lexicon('msvkmarket_err_vk_connect'));
            return array(
                'success' => false
            );
        }

        if ($this->modx->getOption('msvkm_synk_image_update') == false && @array_key_exists('main_photo_id', $item)) {
            $goods_options['main_photo_id'] = $item['main_photo_id'];
        } else {
            usleep($this->modx->getOption('msvkm_delay'));
            // получения сервера для загрузки
            $up_server = $vk->api('photos.getMarketUploadServer', array(
                'group_id' => $group_id,
                'main_photo' => 1
            ));
            // ошибка при getMarketUploadServer
            if (isset($up_server['error'])) {
                $this->modx->log(1, print_r($up_server, 1));
                return array(
                    'success' => false,
                    'result' => $up_server
                );
            }

            $server = $up_server['response']['upload_url'];
            // обрезаем фотографию
            $img = $this->imagePath($product['image']);
            if ($img['success'] == false) {
                $this->modx->log(1, $img['result']);
                return array(
                    'success' => false
                );
            }

            // загружаем и сохраняем
            $up_img     = $this->imgUpload($server, $img['result']);
            $save_image = $vk->api('photos.saveMarketPhoto', array(
                'group_id' => $group_id,
                'server' => $up_img['server'],
                'photo' => $up_img['photo'],
                'hash' => $up_img['hash'],
                'crop_data' => $up_img['crop_data'],
                'crop_hash' => $up_img['crop_hash']
            ));

            if (isset($save_image['error'])) {
                return array(
                    'success' => false,
                    'result' => $this->modx->lexicon('msvkm_log_synk_error_save_thumb')
                );
            }
            $goods_options['main_photo_id'] = $save_image["response"][0]["id"];
        }

        // параметры
        $goods_options['owner_id']    = '-' . $group_id;
        $goods_options['name']        = $product['pagetitle'];
        $goods_options['price']       = $product['price'] != 0 ? $product['price'] : 1;
        $goods_options['description'] = $product['description'];
        $goods_options['category_id'] = empty($product['categories']) ? 1 : $product['categories'];
        $goods_options['deleted']     = $product['published'] == 1 ? 0 : 1; // статус товара в вк, (1 — товар удален, 0 — товар не удален)

        if ($action === 'market.edit') {
            $goods_options['item_id'] = $product['item_id'];
        }

        $import_goods = $vk->api($action, $goods_options);

        if (isset($import_goods['error'])) {
            return array(
                'success' => false,
                'result' => $import_goods['error']['error_msg']
            );
        }

          /*
        if (empty($import_goods['response']['market_item_id'])) {
            $this->modx->log(1, $this->modx->lexicone('msvkmarket_el_empty_item_id'));
            $this->modx->log(1, print_r($import_goods, true));
            return array(
                'success' => false,
                'result' => $this->modx->lexicone('msvkmarket_el_empty_item_id')
            );
        }
        */

        // если создание и есть подборки
        if ($action === 'market.add' && !empty($product['album_ids'])) {
            $goods_options['album_ids'] = $product['album_ids'];
            $goods_options['item_id']   = $import_goods['response']['market_item_id'];

            $add_to_album = $this->addToAlbum($group, $goods_options);
            if ($add_to_album['success'] != true) {
                $warning = $add_to_album['result'];
            }
        }

        // если обновление и есть подборки
        if ($action === 'market.edit' && !empty($product['album_ids'])) {
            $add_to_album = $this->addToAlbum($group, $product);
            if ($add_to_album['success'] != true) {
                $warning = $add_to_album['result'];
            }
        }

        return array(
            'success' => true,
            'result' => $import_goods['response']['market_item_id'], // если было создание, то получаем item_id
            'warning' => $warning,
            'main_photo_id' => $goods_options['main_photo_id']
        );

    }


    /**
     * Добавление позиции в подборку
     *
     * @param array $group
     * @param array $product
     * @return array
     */
    private function addToAlbum(array $group, array $product)
    {
        $app_id       = $group['app_id'];
        $api_secret   = $group['secretkey'];
        $access_token = $group['token'];
        $group_id     = $group['group_id'];
        $success      = true;
        $warning      = '';

        try {
            $vk = new VK\VK($app_id, $api_secret, $access_token);
            $vk->setApiVersion('5.59');
        } catch (VK\VKException $error) {
            $this->modx->log(1, '[msVKMarket] Ошибка при продключении к vk!');
            return array(
                'success' => false
            );
        }

        $add_to_album = $vk->api('market.addToAlbum', array(
            'owner_id' => '-' . $group_id,
            'item_id' => $product['item_id'],
            'album_ids' => $product['album_ids']
        ));

        if (isset($add_to_album['error']) && strnatcasecmp($add_to_album['error']['error_msg'], 'Item already added to album') != 0) {
            $this->modx->log(1, 'Ошибка при добавлении в альбом! ' . print_r($add_to_album['error'], 1));
            $this->modx->log(1, 'Опции товара: ' . print_r($product, 1));
            $warning = $this->modx->lexicon('msvkm_log_synk_warning_goods_not_compil', array(
                'error' => $add_to_album['error']['error_msg']
            ));
            $success = false;
        }

        return array(
            'success' => $success,
            'result' => $warning
        );

    }


    /**
     * Возвращаем путь к сжатой картинке
     * @param   string $image
     * @return  array
     */
    private function imagePath($image)
    {
        if (empty($image)) {
            return array(
                'success' => true,
                'result' => MODX_BASE_PATH . 'assets/components/msvkmarket/img/mgr/noimg.jpg'
            );
        }

        if ($image{0} === '/') {
            $image = substr($image, 1);
        }

        $img   = MODX_BASE_PATH . $image;
        $error = array();
        if (!file_exists($img)) {
            $error[] = $this->modx->lexicon('msvkm_log_synk_img_not_font', array(
                'img' => $img
            ));
        }

        $img_size = getimagesize($img);
        if ($img_size[0] >= 400 && $img_size[1] >= 400) {
            return array(
                'success' => true,
                'result' => $img
            );
        }

        // если файл существует
        if (empty($error)) {
            $img_name  = 'min_' . basename($img);
            $tmp_path  = MODX_ASSETS_PATH . 'components/msvkmarket/tmp/mgr/';
            $tmp_thumb = $tmp_path . $img_name; // Уменьшенная копия

            $img_params = array(
                'w' => 500,
                'h' => 500,
                'bg' => 'ffffff',
                'q' => 95,
                'zc' => 'C',
                'f' => 'jpg'
            );

            /** @var modPhpThumb $phpThumb */
            $phpThumb   = $this->modx->getService('modphpthumb', 'modPhpThumb', MODX_CORE_PATH . 'model/phpthumb/', array());
            $phpThumb->setSourceFilename($img);

            foreach ($img_params as $k => $v) {
                $phpThumb->setParameter($k, $v);
            }

            if ($phpThumb->GenerateThumbnail()) {
                $this->removeDirectory($tmp_path);
                if (!$phpThumb->renderToFile($tmp_thumb)) {
                    $error[] = $this->modx->lexicon('msvkm_log_synk_error_save_thumb', array(
                        'tmp_thumb' => $tmp_thumb
                    ));
                }
            } else {
                $error[] = $this->modx->lexicon('msvkm_log_synk_error_thumb_generate') . "\n" . print_r($phpThumb->debugmessages, 1);
            }
        }

        if (is_array($error) && count($error) > 1) {
            $this->modx->log(1, print_r($error, 1));
            return array(
                'success' => false,
                'result' => print_r($error, 1)
            );
        }

        return array(
            'success' => true,
            'result' => $tmp_thumb
        );
    }


    /**
     * Загрузка картинки
     *
     * @param $server
     * @param $file
     * @param string $mime
     * @return mixed
     */
    private function imgUpload($server, $file, $mime = 'text/plain')
    {
        // Для поддержки старых версий PHP
        if (!function_exists('curl_file_create')) {
            function curl_file_create($filename, $mimetype = '', $postname = '')
            {
                if (file_exists($filename)) {
                    return "@$filename;filename=" . ($postname ?: basename($filename)) . ($mimetype ? ';type=text/plain' : '');
                } else {
                    $this->modx->log(1, '[msVKMarket] Error! File ' . $filename . ' not found!');
                    exit('Error! File ' . $filename . ' not found!');
                }
            }
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $server);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'file' => curl_file_create($file, $mime, basename($file))
        ));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $headers   = array();
        $headers[] = 'Content-Type: multipart/form-data';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = json_decode(curl_exec($ch), true);
        if (curl_errno($ch) && !empty($result['error'])) {
            $this->modx->log(1, '[msVKMarket] ошибка при curl загрузке изображения:' . curl_error($ch));
        }
        curl_close($ch);

        return $result;
    }


    /**
     * Запись данных в бд
     *
     * @param array $product_array - параметры позиции
     * @param array $group_array - параметры группы
     * @param array $import_result - ответ vk
     * @return array
     */
    public function setProducts($product_array, $group_array, $import_result)
    {
        $error = array();

        if (!is_array($group_array) && !is_array($product_array)) {
            $error[] = $this->modx->lexicon('msvkm_log_synk_group_or_goods_not_array');
        }

        if (count($group_array) == 0 && count($product_array) == 0) {
            $error[] = $this->modx->lexicon('msvkm_log_synk_group_or_goods_empty');
        }

        // проверяем есть ли она в VkmProduct
        /** @var xPDOObject $get_VkmProduct */
        $get_VkmProduct = $this->modx->getObject('VkmProduct', $product_array['id']);

        if (is_object($get_VkmProduct) && $get_VkmProduct->get('product_id')) {
            $get_VkmProduct->set('published', $product_array['published']);
            $get_VkmProduct->set('date_sync', date('Y-m-d G:i:s')); // обновляем последнюю дату синхронизации
            $get_VkmProduct->save();
        } else {
            /** @var xPDOObject $new_VkmProduct */
            $new_VkmProduct = $this->modx->newObject('VkmProduct');
            $new_VkmProduct->set('product_id', $product_array['id']);
            $new_VkmProduct->set('product_status', 1);
            $new_VkmProduct->set('image_sync', 0);
            $new_VkmProduct->set('published', $product_array['published']);
            $new_VkmProduct->set('date_sync', date('Y-m-d G:i:s'));
            $new_VkmProduct->save();
        }

        // есть ли эта позиция в VkmProductCategories через group_id
        /** @var xPDOObject $get_VkmProductCategories */
        $get_VkmProductCategories = $this->modx->getObject('VkmProductCategories', array(
            'product_id' => $product_array['id'],
            'groups_id' => $group_array['id']
        ));

        if (is_object($get_VkmProductCategories) && $get_VkmProductCategories->get('product_id')) {
            $compilation_id = $get_VkmProductCategories->get('compilation_id') . ',' . $product_array['compilation_id'];
            $compilation_id = array_unique(explode(',', $compilation_id));
            $compilation_id = array_diff($compilation_id, array(''));
            $compilation_id = implode(',', $compilation_id);
            $get_VkmProductCategories->set('category_id', $product_array['categories']);
            $get_VkmProductCategories->set('compilation_id', $compilation_id);
            $get_VkmProductCategories->set('main_photo_id', $import_result['main_photo_id']);
            $get_VkmProductCategories->save();

        } else {
            /** @var xPDOObject $new_VkmProductCategories */
            $new_VkmProductCategories = $this->modx->newObject('VkmProductCategories');
            $new_VkmProductCategories->set('product_id', $product_array['id']);
            $new_VkmProductCategories->set('groups_id', $group_array['id']);
            $new_VkmProductCategories->set('compilation_id', $product_array['compilation_id']);
            $new_VkmProductCategories->set('product_status_sinc', 1);
            $new_VkmProductCategories->set('owner_id', $import_result['result']);
            $new_VkmProductCategories->set('category_id', $product_array['categories']);
            $new_VkmProductCategories->set('main_photo_id', $import_result['main_photo_id']);
            $new_VkmProductCategories->set('photo_ids', $import_result['photo_ids']);
            $new_VkmProductCategories->save();
        }

        if (count($error) >= 1) {
            return array(
                'success' => false,
                'result' => $this->modx->lexicon('msvkm_log_synk_set_db_error') . print_r($error, 1)
            );
        }

        return array(
            'success' => true,
            'result' => $this->modx->lexicon('msvkm_log_synk_set_db_success')
        );
    }


    /**
     * Отчещаем папку
     *
     * todo сделатьь отчистку только в указанном каталоке
     *
     * @param $dir
     */
    private function removeDirectory($dir)
    {
        if ($objs = glob($dir . "/*")) {
            foreach ($objs as $obj) {
                is_dir($obj) ? $this->removeDirectory($obj) : unlink($obj);
            }
        }
    }
};