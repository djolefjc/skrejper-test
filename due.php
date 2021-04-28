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







//VARIJABLE ZA POPUNITI\

$slika_proizvoda = ".vc_single_image-wrapper"; //Klasa slike proizvoda (href)
$kategorija_proizvoda = ".wpb_wrapper"; // Klasa za kategorije
$ime_proizvoda = ".entry-title";
$info_proizvoda = "table tbody tr";

//VARIJABLE ZA POPUNITI - END




// Deklarisanje varijabli za vrednosti koje izvlacimo.
$product_img = "";

$product_cat = "";
$product_sub_cat = "";

$product_info = [];
$product_info_name = [];
$product_title = "";



//Izvlacenje vrednosti sa stranice.
foreach($html->find($slika_proizvoda) as $t){
$product_img = $t->href;
}

// $kk = $html->find($kategorija_proizvoda,0);
// foreach($kk->find("p") as $t){
//   $product_cat = $t->children(1)->plaintext;
//   $product_sub_cat = $t->children(2)->plaintext;
// }

foreach($html->find($info_proizvoda) as $t){
 $product_info[] = $t->children(1)->plaintext;
}

foreach($html->find($info_proizvoda) as $t){
 $product_info_name[] = $t->children(0)->plaintext;
}

foreach($html->find($ime_proizvoda) as $t){
  $product_title = $t->plaintext;
}


//Dodavanje vrednosti u niz koji koristimo za export u csv.
  $row[$i]['img'] = $product_img;
  $row[$i]['title'] = $product_title;
  $row[$i]['cat'] = "PLOČASTI MATERIJALI";
  $row[$i]['subcat'] = "COMPACT PLOČE";
  $row[$i]['atribut0'] = (array_key_exists(0,$product_info) ? $product_info[0] : "");
  $row[$i]['atribut1'] = (array_key_exists(1,$product_info) ? $product_info[1] : "");
  $row[$i]['atribut2'] = (array_key_exists(2,$product_info) ? $product_info[2] : "");
  $row[$i]['atribut3'] = (array_key_exists(3,$product_info) ? $product_info[3] : "");
  $row[$i]['atribut4'] = (array_key_exists(4,$product_info) ? $product_info[4] : "");
  $row[$i]['atribut5'] = (array_key_exists(5,$product_info) ? $product_info[5] : "");
  $row[$i]['atribut6'] = (array_key_exists(6,$product_info) ? $product_info[6] : "");
  $row[$i]['atribut7'] = (array_key_exists(7,$product_info) ? $product_info[7] : "");
  $row[$i]['atribut8'] = (array_key_exists(8,$product_info) ? $product_info[8] : "");
  $row[$i]['atribut9'] = (array_key_exists(9,$product_info) ? $product_info[9] : "");
  $row[$i]['atribut10'] = (array_key_exists(10,$product_info) ? $product_info[10] : "");
  $row[$i]['atribut11'] = (array_key_exists(11,$product_info) ? $product_info[11] : "");
  $row[$i]['atribut12'] = (array_key_exists(12,$product_info) ? $product_info[12] : "");
  $row[$i]['atribut13'] = (array_key_exists(13,$product_info) ? $product_info[13] : "");
  $row[$i]['atribut14'] = (array_key_exists(14,$product_info) ? $product_info[14] : "");
  $row[$i]['ime_atribut0'] = (array_key_exists(0,$product_info_name) ? $product_info_name[0] : "");
  $row[$i]['ime_atribut1'] = (array_key_exists(1,$product_info_name) ? $product_info_name[1] : "");
  $row[$i]['ime_atribut2'] = (array_key_exists(2,$product_info_name) ? $product_info_name[2] : "");
  $row[$i]['ime_atribut3'] = (array_key_exists(3,$product_info_name) ? $product_info_name[3] : "");
  $row[$i]['ime_atribut4'] = (array_key_exists(4,$product_info_name) ? $product_info_name[4] : "");
  $row[$i]['ime_atribut5'] = (array_key_exists(5,$product_info_name) ? $product_info_name[5] : "");
  $row[$i]['ime_atribut6'] = (array_key_exists(6,$product_info_name) ? $product_info_name[6] : "");
  $row[$i]['ime_atribut7'] = (array_key_exists(7,$product_info_name) ? $product_info_name[7] : "");
  $row[$i]['ime_atribut8'] = (array_key_exists(8,$product_info_name) ? $product_info_name[8] : "");
  $row[$i]['ime_atribut9'] = (array_key_exists(9,$product_info_name) ? $product_info_name[9] : "");
  $row[$i]['ime_atribut10'] = (array_key_exists(10,$product_info_name) ? $product_info_name[10] : "");
  $row[$i]['ime_atribut11'] = (array_key_exists(11,$product_info_name) ? $product_info_name[11] : "");
  $row[$i]['ime_atribut12'] = (array_key_exists(12,$product_info_name) ? $product_info_name[12] : "");
  $row[$i]['ime_atribut13'] = (array_key_exists(13,$product_info_name) ? $product_info_name[13] : "");
  $row[$i]['ime_atribut14'] = (array_key_exists(14,$product_info_name) ? $product_info_name[14] : "");


$i++;


} //Kraj foreach loop-a linkova





//EXPORT U CSV

$delimiter = ",";

$filename = "plocasti_materijal-compact_ploce" . ".csv"; // Create file name

// Napravi pointer
$f = fopen('memory.txt', 'r+');

//Postavi hedere za kolone tabele.
$fields = array('Slika','Ime','Kategorija','Ime atributa 1','Atribut 1','Ime atributa 2','Atribut 2',
'Ime atributa 3','Atribut 3','Ime atributa 4','Atribut 4','Ime atributa 5','Atribut 5','Ime atributa 6','Atribut 6',
'Ime atributa 7','Atribut 7','Ime atributa 8','Atribut 8','Ime atributa 9','Atribut 9','Ime atributa 10','Atribut 10','Ime atributa 11','Atribut 11','Ime atributa 12','Atribut 12','Ime atributa 13','Atribut 13','Ime atributa 14','Atribut 14');
fputcsv($f, $fields, $delimiter);

//Izbaci sve podatke u redu i stavi u pointer.
foreach($row as $r){

   $lineData = array($r['img'],$r['title'],$r['cat']." > " .$r['subcat'],$r['ime_atribut0'],$r['atribut0'],$r['ime_atribut1'],$r['atribut1'],
   $r['ime_atribut2'],$r['atribut2'],$r['ime_atribut3'],$r['atribut3'],$r['ime_atribut4'],$r['atribut4'],$r['ime_atribut5'],$r['atribut5']
 ,$r['ime_atribut6'],$r['atribut6'],$r['ime_atribut7'],$r['atribut7'],$r['ime_atribut8'],$r['atribut8'],$r['ime_atribut9'],$r['atribut9'],
$r['ime_atribut10'],$r['atribut10'],$r['ime_atribut11'],$r['atribut11'],$r['ime_atribut12'],$r['atribut12'],$r['ime_atribut13'],$r['atribut13'],$r['ime_atribut14'],$r['atribut14']);
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
