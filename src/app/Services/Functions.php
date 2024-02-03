<?php 
namespace Charity\Services;

class Functions {
    public static function getKeys($data)
    {
        // table names
        $keys = array_map(function($item) use ($data){
            $key = array_search($item, $data[0]); 
            return strtoupper(str_replace('_', ' ', $key));
        }, $data[0]);
        return $keys;
    }
    public static function renderTable($data)
    {
        $keys = self::getKeys($data);
        $data = [$keys, ...$data];
        // longs columns
        $longestColumns = [];
        foreach($data as $rowKey => $row){
            foreach($row as $itemKey => $item){
                if(!array_key_exists($itemKey, $longestColumns) 
                || $longestColumns[$itemKey] < strlen($item)){
                    $longestColumns[$itemKey] = strlen($item);
                }
            }
        }
        // table string
        $table = "\n";
        foreach($data as $rowKey => $row){
            foreach($row as $itemKey => $item){
                $table = $table . str_pad($item, $longestColumns[$itemKey], ' ') . "\t";
            }
            $table .= "\n";
        }
        echo $table;        

    }
    public static function getVariable(string $text, string $functionName, array $functionVariables)
    {
        $isValidVariable = false;
        while(!$isValidVariable){
            echo "\033[33m ". $text .":\033[0m ";
            $variable = trim(fgets(STDIN));
            $isValidVariable =  call_user_func_array(['Charity\Validations', $functionName], [$variable , ...$functionVariables]);
        }
        return $variable;
    }

}