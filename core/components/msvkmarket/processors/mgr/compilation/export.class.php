<?php
include_once MODX_CORE_PATH . 'components/msvkmarket/processors/mgr/vk/event.trait.php';

class msVKMarketCompilationExportProcessor extends modObjectProcessor
{
    use msVKMarketVKEventTrait;

    public $objectType = 'VkmCompilation';
    public $classKey = 'VkmCompilation';
    public $languageTopics = ['msvkmarket'];


    public function process()
    {
        $id = $this->getProperty('id');
        if (empty($id)) {
            return $this->failure($this->modx->lexicon('msvkmarket_group_select'));
        }

        //return true;
        /*
        $export_album = json_decode($this->exportAlbum($id), true);

        $this->modx->log(1, print_r($export_album, true));

        if ($export_album['success'] !== true) {
            $this->failure($export_album['result']);
        }

        // todo create new compilation


        return $this->failure('asdasdad');
        */
    }

}

return 'msVKMarketCompilationExportProcessor';