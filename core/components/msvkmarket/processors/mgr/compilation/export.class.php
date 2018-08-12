<?php
include_once MODX_CORE_PATH . 'components/msvkmarket/processors/mgr/vk/event.trait.php';

class msVKMarketCompilationExportProcessor extends modProcessor
{
    use msVKMarketVKEventTrait;

    public $classKey = 'VkmCompilation';
    public $languageTopics = ['msvkmarket'];


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $ids   = explode(',', $this->getProperty('id'));
        $step  = $this->getProperty('step');

        if ($ids[0] === ''){
            return $this->prepareResponse(true,'Все',xPDO::LOG_LEVEL_INFO,false);
        }

        $export_album = json_decode($this->exportAlbum($ids[0]), true);

        if ($export_album['success'] === true){

            // todo получаем список подборок в группе
            // todo сверяем с полченным результатом $export_album['result']['items']
            // todo создаем недостающие подборки
            // todo даем ответ

            $msg = $this->modx->lexicon('msvkmarket_compilation_export_response', array(
                'name' => $export_album['result']['name'],
                'count' => $export_album['result']['count'],
                'export' => $export_album['result']['items'],
            ));

            return $this->prepareResponse(
                true,
                $this->modx->lexicon('msvkmarket_console_end'),
                xPDO::LOG_LEVEL_INFO,
                false);
        } else {
            return $this->prepareResponse(
                true,
                $export_album['result'],
                xPDO::LOG_LEVEL_ERROR,
                true);
        }

        $msg = $ids[0] . ' - ' . count($ids) . ' - ' . print_r($ids, true);
        $continue = true;
        $level = xPDO::LOG_LEVEL_INFO;
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
            'success'   => $success,
            'message'   => $msg,
            'level'     => $level,
            'continue'  => $continue,
            'data'      => array()
        );

        if ($this->getProperty("output_format") == "json") { $result = json_encode($result); }
        return $result;
    }

}
return 'msVKMarketCompilationExportProcessor';