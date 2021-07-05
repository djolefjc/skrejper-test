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
$product_desc = "";
$product_author = "";
$product_date = "";
$product_desc_img = [];


//VARIJABLE ZA POPUNITI

$title = ".breadcrumb .active";
$img = ".vest-singl-body .glavna";
$cat = ".vest-singl-meta .vest-singl-cat a";
$author = ".vest-singl-izvor";
$desc = ".vest-singl-body";
$desc_img = ".vest-singl-body img";
$date = ".vest-singl-date";
$slike_test = "";

//VARIJABLE ZA POPUNITI - END


//Izvlacenje vrednosti sa stranice.
foreach($html->find($title) as $t){
  $product_title = $t->plaintext;
}

foreach($html->find($img) as $t){
$product_img = $t->src;
}

foreach($html->find($desc) as $t){
 $product_desc = $t->plaintext;
}

foreach($html->find($desc_img) as $t){
  if($t->class != 'glavna'){
    array_push($product_desc_img,$t->src);
  }


}

foreach($html->find($cat) as $t){
 $product_cat = $t->plaintext;
}

foreach($html->find($author) as $t){
 $product_author = $t->plaintext;
}

foreach($html->find($date) as $t){
 $product_date = $t->plaintext;
}


$product_desc = str_replace("Celu vest možete pogledati ovde...","",$product_desc);
foreach($product_desc_img as $ss){
  $product_desc .= "<br>". "<img src='".$ss."' height='420' width='750' class='attachment-large size-large wp-post-image' alt='Vesti - Agroekonomija - Agrodan'>";
}

//Dodavanje vrednosti u niz koji koristimo za export u csv.
  $row[$i]['img'] = $product_img;
  $row[$i]['title'] = $product_title;
  $row[$i]['cat'] = 'Vesti > '.$product_cat;
  $row[$i]['desc'] = $product_desc;
  $row[$i]['author'] = str_replace("Izvor:","",$product_author);
  $row[$i]['date'] = $product_date;





$i++;



} //Kraj foreach loop-a linkova





//EXPORT U CSV

$delimiter = ",";

$filename = "vesti-agrodan-agroekonomija" . ".csv"; // Create file name

// Napravi pointer
$f = fopen('memory.txt', 'r+');

//Postavi hedere za kolone tabele.
$fields = array('featured_image','post_title','post_category','post_content','post_author','post_date','post_format','comment_status','post_status');

//Izbaci sve podatke u redu i stavi u pointer.
foreach($row as $r){

   $lineData = array($r['img'],$r['title'],$r['cat'],$r['desc'],$r['author'],$r['date'],'post-standard','open','publish');
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
