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
        $parents = $this->getProperty('parents');
        $results = array();
        $where   = array('published' => 1, 'deleted' => 0, 'class_key' => $this->classKey);

        if ($parents !== '0') { $where['parent:IN'] = explode(',', $parents); }

        $q = $this->modx->newQuery($this->classKey);
        $q->select('id');
        $q->where($where);
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