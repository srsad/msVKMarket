<?php
include_once MODX_CORE_PATH . 'components/msvkmarket/processors/mgr/vk/event.trait.php';

class msVKMarketCompilationRemoveProcessor extends modObjectProcessor
{
    use msVKMarketVKEventTrait;

    public $objectType = 'VkmCompilation';
    public $classKey = 'VkmCompilation';
    public $languageTopics = ['msvkmarket'];

    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('msvkmarket_compilation_err_ns'));
        }

        foreach ($ids as $id) {
            // todo удаление из контача
            // todo удаление из бд
            /** @var VkmCompilation $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('msvkmarket_compilation_err_nf'));
            }

            $group_id = $object->get('group_id');
            $album_id = $object->get('album_id');

            $remove = json_decode($this->removeAlbum($group_id, $album_id), true);

            if ($remove['success'] === true) {
                $object->remove();
            } else {
                $this->modx->log(1, print_r($remove, true));
                return false;
            }

        }

        return $this->success();
    }

}

return 'msVKMarketCompilationRemoveProcessor';