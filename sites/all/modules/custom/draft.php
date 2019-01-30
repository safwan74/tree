<?php

        
    function getBankOfEnglandInterestRate($url, $classname){   
    $interestRate = "";

    $file = file_get_contents($url);
    $dom = new DOMDocument;
 
    @$dom->loadHTML($file);

    
    $bankOfEngland = new DomXPath($dom);
    $nodes = $bankOfEngland->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
    $tmp = new DOMDocument(); 
   
    foreach ($nodes as $node) 
    {
    $tmp->appendChild($tmp->importNode($node,true));
   
    }
    $interestRate.=trim($tmp->saveHTML()); 
    
    return $interestRate;
    }
    
    function calculateRepayment($repayment, $interestRate, $moneyBorrowed){
        
        
        $interest = calculateInterest($interestRate, $moneyBorrowed);
        return $repayment - $interest;
        
    }
    
    function calculateInterest($interestRate, $moneyBorrowed){
        $interest = $moneyBorrowed * str_replace(array('%'), '', strip_tags($interestRate))/100;
        return $interest;
    }
    
    $url = "https://www.bankofengland.co.uk/monetary-policy/the-interest-rate-bank-rate";
    $classname = 'stat-figure';
    $bankOfEnglandInterestRate =  getBankOfEnglandInterestRate($url, $classname);
    $moneyBorrowed = 120000;
    $monthly = 600;
    $repayment = $monthly * 12;
    $totalYearlyInterest = calculateInterest($bankOfEnglandInterestRate, $moneyBorrowed);
   
    $paymentsTowardMortage = calculateRepayment($repayment, $bankOfEnglandInterestRate, $moneyBorrowed);
    
    #England rate
    print "Interest rate is " . $bankOfEnglandInterestRate;
    print "<div><b>Total yearly Interest is <br></b> $totalYearlyInterest</div>";
    print "<h2>Repayments Toward the loan</h2><p>".$paymentsTowardMortage . 
           "as in ". $repayment. "-". $totalYearlyInterest ."</p>";
  
  $anotherBankInterestRate = 5;
 $anotherBankInterest = calculateInterest($anotherBankInterestRate, $moneyBorrowed); //%; 
$anotherPaymentsTowardMortage = calculateRepayment($repayment, $anotherBankInterestRate, $moneyBorrowed); 
$anothertotalYearlyInterest = calculateInterest($anotherBankInterestRate, $moneyBorrowed);

   print "The new interest rate is ". $anotherBankInterestRate . " ";
    print "<h2 style='color:red;'>The new Repayments Toward the loan</h2><p>".$anotherPaymentsTowardMortage . 
           "as in ". $repayment. "-". $anotherBankInterest ."</p>";
    
    /**
     * Questions needed to be answered as follows'
     * 1- How the interest rate is calculated example combined for the whole duration 
     *  or normal over 2 years, or yearly
     * 2- Fixed term, does it mean combine the interest rate over two year add it to the original loan then make this as 
     * the base loan?
     * 3- Is there any API for finacial data related to morgatages?
     * 
     */