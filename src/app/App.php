<?php
namespace Charity;
use Charity\Controllers\CharityController;
use Charity\Controllers\DonationController;

class App{
    public static function route(){
        $charityController = new CharityController;
        $donationController = new DonationController;
        while (true) {
            // Display menu options
            echo "\n";
            echo "1 Display Charities\n";
            echo "2 Add Charity\n";
            echo "3 Update Charity\n";
            echo "4 Delete Charity\n";
            echo "5 Add Donation\n";
            echo "6 Exit\n";
            echo "\033[33mChoose an option:\033[0m";
        
            // Get user input
            $choice = trim(fgets(STDIN));
            $choice = $choice == (int) $choice? (int) $choice : $choice;
            match ($choice) {
                1 => $charityController->view(),
                2 => $charityController->create(),
                3 => $charityController->update(),
                4 => $charityController->delete(),
                5 => $donationController->create(),
                6 => exit(),
                default => self::invalidArgumentException() ,
            };
        }

    }
    private  static function invalidArgumentException(){
        echo "\033[31mInvalid option. Please try again.\033[0m\n";
    }

}