<?php
include_once MODX_CORE_PATH . 'components/msvkmarket/processors/mgr/vk/event.trait.php';

class msVKMarketCompilationCreateProcessor extends modObjectCreateProcessor
{
    use msVKMarketVKEventTrait;

    public $classKey = 'VkmCompilation';
    public $objectType = 'VkmCompilation';
    public $languageTopics = ['msvkmarket'];


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        $group_id = (int)trim($this->getProperty('group_id'));

        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('msvkmarket_compialtion_err_name'));
            return false;
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name, 'group_id' => $group_id])) {
            $this->modx->error->addField('name', $this->modx->lexicon('msvkmarket_compialtion_err_ae'));
            return false;
        } elseif (empty($group_id) || !is_int($group_id)) {
            $this->modx->error->addField('group_id', $this->modx->lexicon('msvkmarket_compialtion_err_group_id'));
            return false;
        }

        $create_album = json_decode($this->createAlbum($group_id, $name), true);

        if ($create_album['success'] == true) {
            $this->setProperty('album_id', $create_album['result']);
        } else {
            $this->modx->log(1, 'Ошибка при создании подборки ' . print_r($create_album, true));
            return $create_album['result'];
        }

        return parent::beforeSet();
    }
}
return 'msVKMarketCompilationCreateProcessor';