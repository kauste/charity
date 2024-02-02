<?php
namespace Charity\Controllers;
use Charity\Controllers\CharityController;
use Charity\Services\Functions;
use Charity\JsonDB;
use DateTime;
use DateTimeZone;

class DonationController
{
    private $db, $donations;

    public function __construct() 
    {
        $this->db = new JsonDB('donation');
        $this->donations = $this->db->showAll();

    }

    public function create()
    {
        $charities = new CharityController;
        $chartiesTable = $charities->view();
        if($chartiesTable){
            $charityId = Functions::getVariable( 'Enter charity ID for donation: ', 'isValidCharityId', [$charities->charities]);
        }
        else{
            echo "\033[31mCannot not donate at the moment.\033[0m";
            exit();
        }
        $donorName = Functions::getVariable( 'Enter donor name: ', 'isValidDonorName', [$this->donations]);
        $amount = (float) Functions::getVariable( 'Enter amount of donation in euros: ', 'isValidDonationAmount', [$this->donations]);
        $dateTime = new DateTime('now', new DateTimeZone('Europe/Vilnius'));
        
        $this->donations = $this->db->create([
            'donor_name' => $donorName,
            'amount' => $amount,
            'charity_id' => $charityId,
            'date_time' => $dateTime,
        ]);
        echo "\033[32mDonation is added.\033[0m\n";
    
    }
}