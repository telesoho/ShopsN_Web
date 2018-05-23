<?php
namespace Common\Tool\Extend;

class CombineArray 
{
    /**
     * 被组合的数组
     * @var array
     */
    private $array = [];
    
    //关联key
    private $key = '';
    
    /**
     * 构造方法
     * @param array $array
     * @param string $key
     */
    public function __construct(array $array, $key)
    {
        $this->array = $array;
        
        $this->key = $key;
    }
    /**
     * 处理关联数组
     * @param array $array 要合并的数组
     * @param unknown $byKey 要合并数组的关联key
     * @return [];
     */
    public function parseCombine (array $array, $byKey)
    {
        
        $data = $this->array;
        
        if (empty($data)) {
            return [];
        }
        
        $parseKey = $this->key;
        
        $temp = [];
        foreach ($data as $key => $value)
        {
            $temp[$value[$parseKey]] = $value;
        }
        
        unset($data);
        
        $flag = [];
        foreach ($array as $name => $val) {
            
           $flag[$val[$byKey]] = array_merge(empty($temp[$val[$byKey]]) ? array() : $temp[$val[$byKey]], $val);
        }
        
        return $flag;
    }
    
    public function __destruct()
    {
        unset($this->array);
        unset($this->key);
    }
}