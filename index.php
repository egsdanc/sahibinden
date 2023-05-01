<?php

header('Content-type: text/html; charset=utf8');
require 'sahibinden.class.php';

/*// ana kategoriler
print_r( Sahibinden::Kategori() );

// alt kategoriler

Sahibinden::Kategori('emlak');


// kategori içerikleri
 
Sahibinden::Liste('emlak', 20); // 2. sayfa
 
 */
// içerik detayı (henüz tamamlanmadı)

 //$link = Sahibinden::Detay("https://www.sahibinden.com/ilan/vasita-otomobil-bmw-rmz-den-2015-m-sport-e.bagaj-recore-k.isitma-g.gorus-hafiza-1074337470/detay");
                 



  /* foreach ($link as $row) {
    echo "<a href='" . $row['url'] . "' target='_blank'>" . $row['title'] . "</a></br>";
    $test++;
}  
 $link= Sahibinden::Liste('otomobil');
  foreach ($link as $row) {

 $detay = Sahibinden::Detay($row['url']);
    print_r($detay);


    echo "<a href='" . $row['url'] . "' target='_blank'>" . $row['title'] . "</a></br>";
} 
*/

// $link = Sahibinden::Detay("https://www.sahibinden.com/ilan/vasita-otomobil-mercedes-benz-c63-amg-black-series-dunyada-degil-ama-trde-tek-1078145003/detay");
$link= Sahibinden::Liste('otomobil');
