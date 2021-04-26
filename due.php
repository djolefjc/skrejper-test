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
  curl_setopt($ch,CURLOPT_URL,trim("https://www.formaideale.rs/".$line));

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







//VARIJABLE ZA POPUNITI\

$slika_proizvoda = ".product--images--main--image"; //Klasa slike proizvoda
$ime_proizvoda = ".product--details--info--desc h1"; // Klasa imena proizvoda
$kategorija_proizvoda = ".breadcrumbs a"; // Klasa za kategorije
$sifra_proizvoda = ".product--code"; // Klasa za sifru proizvoda.
$cena_proizvoda = ".product--old--price"; // Klasa za cenu $ime_proizvoda
$cena_proizvoda2 = ".product--new--price";
$opis_proizvoda = ".product--details--info--desc p"; // Klasa za opis proizvoda

//VARIJABLE ZA POPUNITI - END




// Deklarisanje varijabli za vrednosti koje izvlacimo.
$product_img = "";
$product_title = "";
$product_cat = "";
$product_price = "";
$product_sku = "";
$product_price2 = "";
$product_desc = "";



//Izvlacenje vrednosti sa stranice.
foreach($html->find($slika_proizvoda) as $t){
$product_img = $t->src;
}
foreach($html->find($ime_proizvoda) as $t){
$product_title = $t->plaintext;
}
foreach($html->find($kategorija_proizvoda,2) as $t){
  $product_cat = $t->plaintext;
}
foreach($html->find($cena_proizvoda) as $t){

  $product_price = $t->plaintext;
}
foreach($html->find($cena_proizvoda2) as $t){

  $product_price2 = $t->plaintext;
}
foreach($html->find($sifra_prizvoda) as $t){
  $product_sku = $t->plaintext;
}

foreach($html->find($opis_proizvoda) as $t){
  $product_desc = $t->plaintext;
}


//Dodavanje vrednosti u niz koji koristimo za export u csv.
  $row[$i]['sku'] = $product_sku;
  $row[$i]['img'] = $product_img;
  $row[$i]['cat'] = $product_cat;
  $row[$i]['title'] = $product_title;
  $row[$i]['price'] = $product_price;
  $row[$i]['price2'] = $product_price2;
  $row[$i]['desc'] = $product_desc;


$i++;


} //Kraj foreach loop-a linkova





//EXPORT U CSV

$delimiter = ",";

$filename = "proizvodi_" . date('d.m.Y') . ".csv"; // Create file name

// Napravi pointer
$f = fopen('memory.txt', 'r+');

//Postavi hedere za kolone tabele.
$fields = array('sku', 'img', 'title', 'price','price_sale', 'cat','desc');
fputcsv($f, $fields, $delimiter);

//Izbaci sve podatke u redu i stavi u pointer.
foreach($row as $r){

   $lineData = array($r['sku'], $r['img'], $r['title'], $r['price'],$r['price_sale'], $r['cat'],$r['desc']);
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
