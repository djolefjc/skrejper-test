<?php
//SkrejpT v1.0

// SA PAGINACIJOM


//Uključivanje prikaza php errora radi debugovanja.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
set_time_limit(9999);
error_reporting(E_ALL);


//Unošenje Simple HTML DOM parser paketa
include "simplehtmldom/simple_html_dom.php";



//VARIJABLE ZA POPUNITI

$stranica_prodavnice = "http://www.agrodan.rs/vesti/agroekonomija/0"; // Stranica prodavnice. Prva u paginaciji.
$dugme = ".vest-prev-title a"; // Klasa dugmeta koji šalje na link proizvoda (sa tačkom)
$broj_stranica = 226; //Broj stranica na prodavnici koju skrejpujemo.
$stranica_stranice = "http://www.agrodan.rs/vesti/agroekonomija/1"; // Stranica prodavnice, paginacija

//VARIJABLE ZA POPUNITI - END




$linkovi = [];
//inicijalizacija curl-a.
$ch = curl_init();

//CURL ima neke opcije koje mozemo da navedemo:

/*1.Dodajemo URL na koji hocemo da idemo.*/
curl_setopt($ch,CURLOPT_URL, $stranica_prodavnice);
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

//Tražimo elemente sa određenom klasom koju smo pronašli puten inspecta.

$products = $html->find($dugme);

//Lupujemo kroz elemente i stavljamo linkove u niz
foreach($products as $p){
 $linkovi[] = $p->href;
}




for($i = 0; $i <= $broj_stranica; $i++){


//inicijalizacija curl-a za paginaciju.
$ch = curl_init();
//CURL ima neke opcije koje mozemo da navedemo:
/*1.Dodajemo URL na koji hocemo da idemo.*/
curl_setopt($ch,CURLOPT_URL, $stranica_stranice.$i);

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

//Tražimo elemente sa određenom klasom.
$products = $html->find($dugme);

//Lupujemo kroz elemente i stavljamo linkove u niz
foreach($products as $p){
 $linkovi[] = $p->href;
}
}

//Izbacivanje duplikata.
$links = array_unique($linkovi);

//Kreiranje fajla i upis linkova u isti.

$myfile = fopen("links.txt", "w") or die("Unable to open file!");
$txt = "";
foreach($links as $link){
  $txt .= $link."\n";
}

fwrite($myfile, $txt);


fclose($myfile);

echo '<a href="index.php"> BACK </a>';
 ?>
