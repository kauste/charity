<?php
namespace Charity\Controllers;
use Charity\Services\Functions;
use Charity\SerializationDB;
use DateTime;
use DateTimeZone;

class CharityController{
    public $db, $charities;

    public function __construct() {
        $this->db = new SerializationDB('charity');
        $this->charities = $this->db->showAll();

    }
    private function yesNoOptions(string $question, callable $yesCase, $data = null)
    {
        while(true){
            echo "\033[33m" . $question ."? [yes/no]:\033[0m ";
            $value = trim(fgets(STDIN));
            if(!$value || strtolower(trim($value)) === 'yes'){
                if($data){
                    return $yesCase($data);
                }
                return $yesCase();               
            }
            else if(strtolower(trim($value)) === 'no'){
                return null;
            }
            else echo "\033[31mInvalid option.\033[0m\n";
        }
    }

    private function getCharityId()
    {
        return (int) Functions::getVariable( 'Enter chosen charity ID', 'isValidCharityId', [$this->charities]);
    }
    private function getCharityName()
    {
        return Functions::getVariable( 'Enter charity name', 'isValidCharityName', [$this->charities]);
    }
    private function getCharityEmail()
    {
        return Functions::getVariable( 'Enter charity email', 'isValidCharityEmail', [$this->charities]);
    }

    public function view()
    {
        if(count($this->charities) === 0){
            echo "\033[31mNo charities added yet.\033[0m\n";
            return false;
        }
        else{
            Functions::renderTable($this->charities);
            return true;
        }
    }
    public function create()
    {

        $charityName = $this->getCharityName();
        $charityEmail = $this->getCharityEmail();

        $this->charities = $this->db->create(['charity_name' => $charityName, 
                                        'charity_email' => $charityEmail]);
        echo "\033[32mCharity \"" . $charityName . "\" is created.\033[0m\n";
    
    }
    public function update()
    {
        //get id
        $id = $this->getCharityId();
        //show chosen item
        $item =  array_values($this->db->getItem($id));
        Functions::renderTable($item);
        // get new variables

        $charityName =  $this->yesNoOptions('Would you like to update charity name', fn() => $this->getCharityName());
        $charityEmail = $this->yesNoOptions('Would you like to update charity email', fn() => $this->getCharityEmail());
        
        //update new variables
        if($charityName || $charityEmail){
            $this->charities = $this->db->update($id, ['charity_name' => $charityName, 
                                     'charity_email' => $charityEmail]);
            echo "\033[32mCharity \"" . ($charityName  ?? $item[0]['charity_name']) . "\" is updated.\033[0m\n";
        }


    }
    public function delete()
    {
        $id = $this->getCharityId();
        $item = array_values($this->db->getItem($id));
        Functions::renderTable($item);
        $charityData = $this->yesNoOptions('Are you sure you want to delete this Charity?', fn($id) => $this->db->delete($id), $id);
        if($charityData){
            $this->charities = $charityData;
            echo "\033[32mCharity \"" . $item[0]['charity_name'] . "\" is deleted.\033[0m\n";
        }

    }
    public function getCVS(){
        if ($this->charities) {
            $dateTime = (new DateTime('now', new DateTimeZone('Europe/Vilnius')))->format('-Y-m-d-H-i-s') ;
            $keys = Functions::getKeys($this->charities);
            $output = fopen(BASE_DIR . '/cvs/charities'. $dateTime.'.csv', 'w');
            fputcsv($output, $keys);
            foreach ($this->charities as $row) {
                fputcsv($output, $row);
            }
            fclose( $output );

            echo "CSV file generated successfully.\n";
        } else {
            echo "No data to export.\n";
        }
    }
}