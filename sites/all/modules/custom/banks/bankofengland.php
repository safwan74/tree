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
