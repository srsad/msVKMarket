<?php
class msVKMarketManagerGetIdListProcessor extends modProcessor
{
    public $objectType = 'miniShop2';
    public $classKey = 'msProduct';

    /**
     * @return mixed|string
     */
    public function process()
    {
        $this->modx->log(1, print_r($this->getProperties(), true));
        $this->modx->log(1, 'sdfsdfsdfs');

        $parents  = $this->getProperty('parents');
        $results = array();


        $q = $this->modx->newQuery($this->classKey);
        $q->select('id');
        $q->where(array(
                'parent:IN' => explode(',', $parents),
                'published' => 1,
                'deleted' => 0,
            )
        );
        $q->limit(0);
        if ($q->prepare() && $q->stmt->execute()) {$arr = $q->stmt->fetchAll(PDO::FETCH_ASSOC);}

        array_walk_recursive($arr, function ($item, $key) use (&$results) {
            $results[] = $item;
        });

        $result = @implode(',', $results);

        return json_encode(array(
            'success' => true,
            'results' => $result
        ));
    }

}
return "msVKMarketManagerGetIdListProcessor";