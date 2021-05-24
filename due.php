<?php
//Uključivanje prikaza php errora radi debugovanja.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('max_execution_time', '1200'); //300 seconds = 5 minutes

//Unošenje Simple HTML DOM parser paketa
include "simplehtmldom/simple_html_dom.php";



//Proverava da li je tekstualni dokument sa linkovima prazan
if (filesize('links.txt') != 0){

//Otvara links.txt
$lines = file('links.txt');
//Brojac za varijablu koju kasnije koristimo za export proizvoda u csv.
$i = 1;
//Niz koji koristimo za export proizvoda u CSV
$row = [];

foreach($lines as $line){
  //inicijalizacija curl-a.
  $ch = curl_init();


  //CURL ima neke opcije koje mozemo da navedemo:

  /*1.Dodajemo URL na koji hocemo da idemo.*/
  curl_setopt($ch,CURLOPT_URL,trim($line));

  /*2. Kazemo mu da prati lokaciju, u slucaju da Gugle redirektuje negde
  */
  curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);

  /*3.Zelimo da dobijemo odgovor od Gugla, ako posalje*/
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

  /*Ovde čuvamo odgovor koji dobijamo od Gugla kada mu pošaljemo req*/
  $response = curl_exec($ch);

  /*Obavezno se zatvara CURL veza na kraju.*/
  curl_close($ch);


  //Inicjializujemo simple html dom i dajemo mu response sa curl-a.
  $html = new simple_html_dom();
  $html->load($response);


$product_img = "";
$product_title = "";
$product_info = ""; 



//VARIJABLE ZA POPUNITI\

$slika_proizvoda = "#prod_img_main"; //Klasa slike proizvoda (href)
$ime_proizvoda = ".title-buy";
$info_proizvoda = "#buy_prod_description";

//VARIJABLE ZA POPUNITI - END


//Izvlacenje vrednosti sa stranice.
foreach($html->find($slika_proizvoda) as $t){
$product_img = $t->src;
}

foreach($html->find($info_proizvoda) as $t){
 $product_info = $t->plaintext;
}

foreach($html->find($ime_proizvoda) as $t){
  $product_title = $t->plaintext;
}



//Dodavanje vrednosti u niz koji koristimo za export u csv.
  $row[$i]['img'] = $product_img;
  $row[$i]['title'] = $product_title;
  $row[$i]['cat'] = "Bračni kreveti";
  $row[$i]['desc'] = $product_info;



$i++;


} //Kraj foreach loop-a linkova





//EXPORT U CSV

$delimiter = ",";

$filename = "kuhinje-nove" . ".csv"; // Create file name

// Napravi pointer
$f = fopen('memory.txt', 'r+');

//Postavi hedere za kolone tabele.
$fields = array('Slika','Ime','Kategorija','Opis');

//Izbaci sve podatke u redu i stavi u pointer.
foreach($row as $r){

   $lineData = array($r['img'],$r['title'],$r['cat'],substr($r['desc'],5));
   fputcsv($f, $lineData, $delimiter);
 }

//Vrati se na pocetak fajla.
fseek($f, 0);

// Postavljanje hedera fajla
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '";');

// Piši po postavljenom pointeru.
fpassthru($f);

// EXPORT U CSV - END


} //Zatvoren if links.txt prazan
 ?>
