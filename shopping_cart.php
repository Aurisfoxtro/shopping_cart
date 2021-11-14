<?php

function readEcho ($file){
    while (!feof ($file)){
        $line = fgets($file);
        $line = str_replace(",",";",$line);
        $pLine = explode (";",$line);
        foreach ($pLine as $element){
            echo $element." ";
        }
        echo "\n";
    }
};

function curMod ($string){

    if ($string == 'EUR'){
        return 1;
    } elseif ($string == 'USD'){
        return 1.14;
    } elseif ($string == 'GBP'){
        return 0.88;
    }else echo "Error. Unknown currency.";

};

function readSum ($file, $sum){
    while (!feof ($file)){
        $line = fgets($file);
        $line = str_replace(",",";",$line);
        if (strlen($line) > 1){
            $pLine = explode (";",$line);
            $sum = $sum + round( (floatval($pLine[2]) * floatval($pLine[3]) ) / curMod(trim($pLine[4])), 2);  //<<< neranda elem
        }
    }   
    return $sum;
};

$name = null;

if ($name === null) {

echo "Welcome to ShoppingCart.\n";
echo "To start shopping, tell us your name?\n"; 

$name = trim (fgets (STDIN, 1024));

if(strlen($name) == 0) exit("No name, no game :) \n");  // <<<<<<<<<<<<<<<
//Creates or opens existing user cart file.
$userCart = fopen ($name."cart.txt", "a+");

} 
    
echo "\nWelcome, ".$name."\n";
    
    // Checks if cart file is empty.
if(trim(file_get_contents( $name."cart.txt")) == ""){   
    $sumEur = 0;
    echo "Your cart is empty. \n";
    echo "Sum: ".$sumEur." Eur \n";
    echo "We wish you a good shopping experience.\n";
 } else{
    echo "Your current cart items:\n";
    echo "-----------------------------------------\n";
    //Outputs user cart contents
    $userCart = fopen ($name."cart.txt", "r");
    readEcho ($userCart);
    
    $userCart = fopen ($name."cart.txt", "r"); 
    $sumEur = 0;
    $sumEur = readSum($userCart, $sumEur);  
    echo "-----------------------------------------\n";
    echo "Sum: ".$sumEur." Eur \n";
}
    
echo "Our current stock:\n";
echo "___________________________________________________\n";
// Outputs shop stock
$fileShop = fopen ("shop.txt", "a+");
readEcho ($fileShop);
echo "___________________________________________________\n";

// explains commands (add, remove) 
echo "To add items to your shopping cart, write:\n input ID name quantity price currency\n";
echo "To remove items from your shopping cart, write:\n input ID name -quantity price currency\n";
        
$command = trim (fgets (STDIN, 1024));
$comElem = explode(" ", $command);
   
if ($comElem[0] == "input"){
        
    //Checks if input command line has enough elements.
    if (count($comElem) == 7){    
        
        $elemLine = "";

        for ($i = 1; $i<7; $i++){
            if ($i == 2) {
                $elemLine = $elemLine.$comElem[$i]." ";
            }elseif ($i == 6) {
                $elemLine = $elemLine.$comElem[$i].PHP_EOL;    
            }else{
                $elemLine = $elemLine.$comElem[$i].";";
            }
        }
    //Saves input into userCart.txt file
    $userCart = fopen ($name."cart.txt", "a+");
    fwrite($userCart, $elemLine); 
    
    echo "\nYour current cart items:\n";
    echo "-----------------------------------------\n";
    //Outputs user cart contents
    $userCart = fopen ($name."cart.txt", "r");
    readEcho ($userCart);
    echo "-----------------------------------------\n";
    
    $sumEur = 0;
    $userCart = fopen ($name."cart.txt", "r");
    $sumEur = readSum($userCart, $sumEur);

    echo "Sum: ".$sumEur." Eur \n";
    }else {
        echo "Not a correct input line.\n";}

    } else echo "Unknown command\n";

fclose($userCart);
fclose($fileShop);
