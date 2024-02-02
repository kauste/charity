<?php
namespace Charity{
    class JsonDB{
        private $file, $data;
        public function __construct($file) {
            $this->file = $file;
            if(!file_exists(__DIR__ . '/../data/'. $this->file . '.json')){
                file_put_contents(__DIR__ . '/../data/'. $this->file . '.json', json_encode([]));
                file_put_contents(__DIR__ . '/../data/'. $this->file . '_last_id.json', 0);
            }
            $this->data =  json_decode(file_get_contents(__DIR__ . '/../data/'. $this->file . '.json'), 1);
        }
        public function showAll()
        {
            return $this->data;
        }
        public function getItem($id)
        {
            $item = array_filter($this->data, function($row) use ($id){
                return $row['id'] === $id;
            });
            return $item;
        }
        private function createID(){
            $lastId = (int) json_decode(file_get_contents(__DIR__ . '/../data/'. $this->file . '_last_id.json'));
            $lastId++;
            file_put_contents(__DIR__ . '/../data/'. $this->file . '_last_id.json', $lastId);
            return $lastId;

        }
        public function create(array $data)
        {
           $id = $this->createID();

            $this->data[] = ['id' => $id, ...$data];
            file_put_contents(__DIR__ . '/../data/' . $this->file . '.json', json_encode($this->data));
            return $this->data;
        }
        public function update(int $id, array $data){
            $this->data = array_map(function($row) use ($id, $data){
                if($row['id'] === $id){
                    foreach($data as $key => $item){
                        if($data[$key]) $row[$key] = $item;
                    }
                }
                return $row;
            }, $this->data);
            file_put_contents(__DIR__ . '/../data/' . $this->file . '.json', json_encode($this->data));
            return $this->data;
        }
        public function delete(int $id){
            $this->data = array_filter($this->data, function($row) use ($id){
                return $row['id'] !== $id;
            });
            $this->data = array_values($this->data);
            file_put_contents(__DIR__ . '/../data/' . $this->file . '.json', json_encode($this->data));
            return $this->data;
        }
    }
}