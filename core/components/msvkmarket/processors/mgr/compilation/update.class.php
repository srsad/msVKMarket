<?php
include_once MODX_CORE_PATH . 'components/msvkmarket/processors/mgr/vk/event.trait.php';

class msVKMarketCompilationUpdateProcessor extends modObjectUpdateProcessor
{
    use msVKMarketVKEventTrait;

    public $objectType = 'VkmCompilation';
    public $classKey = 'VkmCompilation';
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

        if (empty($id)) {
            return $this->modx->lexicon('msvkmarket_item_err_ns');
        }

        $id         = (int)trim($this->getProperty('id'));
        $name       = trim($this->getProperty('name'));
        $group_id   = (int)trim($this->getProperty('group_id'));
        $album_id   = (int)trim($this->getProperty('album_id'));

        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('msvkmarket_compialtion_err_name'));
            return false;
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name, 'group_id' => $group_id, 'id:!=' => $id])) {
            $this->modx->error->addField('name', $this->modx->lexicon('msvkmarket_compialtion_err_ae'));
            return false;
        } elseif (empty($group_id) || !is_int($group_id)) {
            $this->modx->error->addField('group_id', $this->modx->lexicon('msvkmarket_compialtion_err_group_id'));
            return false;
        }

        $update_album = json_decode($this->updateAlbum($group_id, $album_id, $name), true);

        return $update_album['success'] !== true ? $update_album['result'] : parent::beforeSet();
    }

}

return 'msVKMarketCompilationUpdateProcessor';
