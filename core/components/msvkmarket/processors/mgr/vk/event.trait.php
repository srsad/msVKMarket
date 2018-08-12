<?php
include_once(MODX_CORE_PATH . 'components/msvkmarket/libs/VK.php');
include_once(MODX_CORE_PATH . 'components/msvkmarket/libs/VKException.php');

trait msVKMarketVKEventTrait
{

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
    private function getGroupParam($group_id)
    {
        $groups_cache = $this->modx->cacheManager->get('groups_data');

        if(@!array_key_exists($group_id, $groups_cache)){
            /** @var VkmGroups $items */
            $items = $this->modx->getCollection('VkmGroups', $group_id);
            $groups = [];
            foreach($items as $item){
                $groups[$item->get('id')] = [
                    'id'        => $item->get('id'),
                    'name'      => $item->get('name'),
                    'app_id'    => $item->get('app_id'),
                    'secretkey' => $item->get('secretkey'),
                    'token'     => $item->get('token'),
                    'group_id'  => $item->get('group_id'),
                    'status'    => $item->get('status'),
                ];
            }

            $groups_cache = !is_array($groups_cache) ? $groups : $groups_cache + $groups;

            $this->modx->cacheManager->set('groups_data', $groups_cache);
        }

        $groups_cache = $this->modx->cacheManager->get('groups_data');
        if (array_key_exists($group_id, $groups_cache)){
            #$this->modx->log(1, print_r($groups_cache, true));
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

        if (isset($add_album['error'])) {
            $error = $this->modx->lexicon('msvkmarket_compilation_creat_albom_error_log');
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
                'result' => $this->modx->lexicon('msvkmarket_compilation_creat_albom_error_id')
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
            $error[] = $this->modx->lexicon('msvkm_creat_albom_error_edit') . print_r($edit_album['error'], 1);
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
        $groups_param   = $this->getGroupParam($id_group);

        if ($groups_param === false) {
            return json_encode(array(
                'success' => false,
                'result' => $this->modx->lexicon('msvkmarket_group_err_not_id')
            ));
        }

        $group_name     = $groups_param['name'];
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
            $error[] = $this->modx->lexicon('msvkm_connect_error', array(
                'action' => $this->modx->lexicon('msvkm_export_albom')
            ));
            $this->modx->log(modX::LOG_LEVEL_ERROR, print_r($error, 1));
        }

        $album_list = $vk->api('market.getAlbums', array(
            'owner_id' => '-' . $group_id,
            'count' => 100
        ));

        if (isset($album_list['error'])) {
            $error[] = $this->modx->lexicon('msvkmarketexport_albom_error') . print_r($album_list['error'], true);
            return json_encode(array(
                'success' => false,
                'result' => $album_list['error']['msg']
            ));
        }

        return json_encode(array(
            'success' => true,
            'result' => array(
                'name' => $group_name,
                'count' => $album_list['response']['count'],
                'items' => $album_list['response']['items']
            )
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
                'action' => 'market.deleteAlbum'
            ));
            $this->modx->log(1, print_r($error, 1));
        }

        $add_album = $vk->api('market.deleteAlbum', array(
            'owner_id' => '-' . $group_id,
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

};