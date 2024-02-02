<?php
namespace Charity;
class Validations{

    public static function isValidCharityId(string $id, array $charities )
    {
        $id = (int) $id == $id ? (int) $id : $id;
        if(!is_int($id)){
            echo "\033[31mCharity ID should be integer. Please try again.\033[0m\n";
            return false;
        };
        if($id < 1){
            echo "\033[31mCharity ID should be natural number. Please try again.\033[0m\n";
            return false;
        };
        if(!in_array($id, array_column($charities, 'id'))){
            echo "\033[31mCharity iD \"" .$id ."\" does not exists. Please try again.\033[0m\n";
            return false;
        }
        return true;
    }
    public static function isValidCharityName(string $charityName, array $charities)
    {
        if(!$charityName || $charityName === ''){
            echo "\033[31mCharity name is required. Please try again.\033[0m\n";
            return false;
        } 
        if(is_numeric($charityName)){
            echo "\033[31mCharity name must be not numeric string. Please try again.\033[0m\n";
            return false;
        } 
        if(strlen($charityName) > 200){
            echo "\033[31mCharity name must not be longer than 200 symbols. Please try again.\033[0m\n";
            return false;
        }
        if(in_array($charityName, array_column($charities, 'charity_name'))){
            echo "\033[31mCharity name \"" .$charityName ."\" is alread in use. Please try different name.\033[0m\n";
            return false;
        }
        return true;

    }
    public static function isValidCharityEmail(string $charityEmail, array $charities)
    {
        if(!$charityEmail || $charityEmail === ''){
            echo "\033[31mCharity email is required. Please try again.\033[0m\n";
            return false;
        }
        if(is_numeric($charityEmail)){
            echo "\033[31mCharity email must be not numeric string. Please try again.\033[0m\n";
            return false;
        } 
        if(strlen($charityEmail) > 320){
            echo "\033[31mCharity email must not be longer than 320 symbols. Please try again.\033[0m\n";
            return false;
        }
        if(!filter_var($charityEmail, FILTER_VALIDATE_EMAIL)){
            echo "\033[31mCharity email format is not valid.\033[0m\n";
            return false;
        }
        if(in_array($charityEmail, array_column($charities, 'charity_email'))){
            echo "\033[31mCharity email \"" .$charityEmail ."\" is alread in use. Please try different email.\033[0m\n";
            return false;
        }
        
        return true;
    }
    public static function isValidDonorName(string $donorName, array $donors)
    {
        if(!$donorName || $donorName === ''){
            echo "\033[31mDonor name is required. Please try again.\033[0m\n";
            return false;
        } 
        if(is_numeric($donorName)){
            echo "\033[31mDonor name must be not numeric string. Please try again.\033[0m\n";
            return false;
        } 
        if(strlen($donorName) > 200){
            echo "\033[31mDonor name must not be longer than 200 symbols. Please try again.\033[0m\n";
            return false;
        }
        return true;
    }
    public static function isValidDonationAmount(string $amount){
        $amount = str_replace( ',', '.', $amount);
        if(!is_numeric($amount)){
            echo "\033[31mDonation amount should be numeric. Please try again.\033[0m\n";
            return false;
        }
        $amount = (float) $amount;
        if($amount < 0){
            echo "\033[31mDonation should be more that 0. Please try again.\033[0m\n";
            return false;
        }
        if($amount != number_format($amount, 2 ,'.')){
            echo "\033[31mDonation should be decimal with two symbols after dot. Please try again.\033[0m\n";
            return false;
        }
        return true;
    }
}