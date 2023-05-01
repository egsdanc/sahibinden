<?php

/**
 * Class Sahibinden
 * @author Tayfun Erbilen
 * @blog http://www.erbilen.net
 * @mail tayfunerbilen@gmail.com
 * @date 14.2.2014
 * @update 9.8.2018
 * @updater_mail facfur3@gmail.com
 */
class Sahibinden
{

    static $data = array();

    /**
     * Tüm Kategorileri Listelemek İçin Kullanılır
     *
     * @param null $url
     * @return array
     */
    static function Kategori($url = NULL)
    {
        if ($url != NULL) {
            $serv = new self();
            $open = $serv->Curl('https://www.sahibinden.com/alt-kategori/' . $url);
            //       $open = self::Curl( 'https://www.sahibinden.com/alt-kategori/' . $url );
            preg_match_all('@<li>(.*?)<a href="/(.*?)">(.*?)</a>(.*?)<span>(.*?)</span>(.*?)</li>@si', $open, $result);

            unset($result[2][0]);
            unset($result[3][0]);
            unset($result[5][0]);
            for ($i = 0; $i < count($result[2]); $i++) {
                self::$data[] = array(
                    'title' => $result[3][$i],
                    'icerik' => trim($result[5][$i]),
                    'uri' => trim($result[2][$i]),
                    'url' => 'https://www.sahibinden.com/' . $result[2][$i]
                );
            }
        } else {
            $serv = new self();
            $open = $serv->Curl('https://www.sahibinden.com/');
            //  $open = self::Curl( 'https://www.sahibinden.com/' );
            preg_match_all('@<li class="">(.*?)<a href="/kategori/(.*?)">(.*?)</a>(.*?)<span>((.*?))(.*?)</span>(.*?)</li>@si', $open, $result);
            foreach ($result[2] as $key => $val) {
                self::$data[] = array(
                    'title' => trim($result[3][$key]),
                    'icerik' => trim($result[7][$key]),
                    'uri' => str_replace('/kategori/', '', $result[2][$key]),
                    'url' => 'https://www.sahibinden.com/kategori/' . $result[2][$key]
                );
            }
        }
        return self::$data;
    }

    /**
     * Kategoriye ait ilanları listeler.
     *
     * @param $kategoriLink
     * @param string $sayfa
     * @return array
     */
    static function Liste($kategoriLink, $sayfa = '0')
    {
        $items = array();
        $serv = new self();
        $open = $serv->Curl('https://www.sahibinden.com/' . $kategoriLink."?sorting=date_desc");
        
//      $open1 = $serv->Curl('https://www.sahibinden.com/' . $kategoriLink);

    //    $open = self::Curl('https://www.sahibinden.com/' . $kategoriLink . "?pagingSize=50&sorting=date_desc");
        $string = "/searchResultsItem(\s{2,})/";
        $links = "/(\s)classifiedTitle/";
        $sayi = "/[0-9]+/";
        preg_match_all('@<tr data-id="(.*?)" class="searchResultsItem">(.*?)</tr>@si', $open, $result);
//     preg_match_all('@<input id="(.*?)" type="hidden">(.*?)   </input id>  ', $open1, $result1);
//     print_r($result1);
    // print_r($result[1]);

 
       foreach ($result[2] as $detay) {
            preg_match('@<a class="classifiedTitle" title="(.*?)" href="(.*?)">(.*?)</a>@si', $detay, $title);
            $items[] = array(
                'title' => $title[3],
                'url' => 'https://www.sahibinden.com' . $title[2],
              
             );
        }
   /*     $detayanaliste = array(
            'url' =>  $items["url"],
            'baslik' =>  $items["title"],
            

            );
            print_r($detayanaliste);*/
    //        array_push($items[0], array('foto' => $result[2]));
     return $items;
    }

    /**
     * İlan detaylarını listeler.
     *
     * @param null $url
     * @return array
     */
    static function Detay($url = NULL)
    {
        if ($url != NULL) {
            $serv = new self();
            $open = $serv->Curl($url);
            //    $open = self::Curl( $url );

            // title
            preg_match_all('@<div class="classifiedDetailTitle">\s*<h1>(.*?)<\/h1>\s*<\/div>@si', $open, $titles);

            preg_match_all('@<img src="(.*?)" data-src="(.*?)" (.*?)>@si', $open, $ciktis);

           foreach ($ciktis[2] as $val) {
                $images[] = $val;
            }

            $katlar=[];
            preg_match_all('@<li class="breadcrumbItem">(.*?)</li>@si', $open, $asddd);
            foreach($asddd[0] as $row){
                preg_match('@<span>(.*?)</span>@si', $row, $asdasdasd);
                $katlar[]=$asdasdasd[1];
            }
             // açıklama
            preg_match_all('/<div id="classifiedDescription" class="uiBoxContainer">(.*?)<\/div>/', $open, $desc);
            $serv = new self();

             $description = array(
               'html' => $serv->replaceSpace($desc[1][0]),
                'no_html' => $serv->replaceSpace(strip_tags($desc[1][0]))
            ); 

            // genel özellikler
            preg_match_all('/<ul class="classifiedInfoList">(.*?)<\/ul>/', $open, $propertie);
       //     print_r($propertie);
             $serv1 = new self();
            $prop = $serv1->replaceSpace($propertie[1][0]);
 
             
        
            preg_match_all('/<li> <strong>(.*?)<\/strong>(.*?)<span(.*?)>(.*?)<\/span> <\/li>/', $prop, $p);
            foreach ($p[1] as $index => $val) {
                $properties[] = array(trim($val),str_replace('&nbsp;', '', trim($p[4][$index])));
            }
          //  print_r($properties);
   /*      $i=0;
          $imgg=array();
          foreach (  $images[0] as $item) {
            if ($item === null) {
                break;
            }
            $imgg=$item;
            // yapılacak işlemler
        }                                              */
     

            $detayanadizi = array(
                'url' => $url,
                "kategoriler"=>$katlar,
                'foto' => $images,
                'ozellik' => $properties,
                'aciklama' => $description['no_html']
            );
            print_r($detayanadizi);
     /*
// print_r("aaaa <br>");
            print_r($detayanadizi['url']); 
  //          print_r(" <br>aaaa <br>");
            print_r($detayanadizi['foto']);
    //        print_r("<br>aaaa <br>");
            print_r($detayanadizi['ozellik']);
      //      print_r("<br>aaaa <br>");
            print_r($detayanadizi['aciklama']);
            /*         */
/*
            // tüm özellikleri
            preg_match('/<div class="uiBoxContainer classifiedDescription" id="classifiedProperties">(.*?)<\/div>/', $open, $allProperties); 
            print_r($allProperties);
            $serv2 = new self();

            $allPropertiesString = $serv2->replaceSpace($allProperties[1]);
            preg_match_all('/<h3>(.*?)<\/h3>/', $allPropertiesString, $propertiesTitles);
            preg_match_all('/<ul>(.*?)<\/ul>/', $allPropertiesString, $propertiesResults);
            print_r($propertiesTitles);
            print_r($propertiesResults);

            foreach ($propertiesResults[1] as $index => $val) {
                preg_match_all('/<li class="(.*?)">(.*?)<\/li>/', $val, $result);

                foreach ($result[1] as $index2 => $selected) {
                    $props[$propertiesTitles[1][$index]][] = array($result[2][$index2], $selected);
                }
            }
 
            // price
            preg_match('/<h3>(.*?)<\/h3>/', $open, $extra);
            print_r($extra);
            $serv3 = new self();
            $extras = $serv3->replaceSpace($extra[1]);
            print_r($extras);

            preg_match('/<h3>(.*?)<\/h3>/', $extras, $price);
            print_r($price);

        //    $price = trim($price[1]);
            print_r($price);

            preg_match_all('@<a href="/(.*?)">(.*?)</a>@si', $open, $addrs);
            $address = array(
                'il' => trim($addrs[2][0]),
                'ilce' => trim($addrs[2][1]),
                'mahalle' => trim($addrs[2][2])
            );     */                                              
/*
            // username
            preg_match('/<h5>(.*?)<\/h5>/', $open, $username);
            $username = $username[1];
            print_r("adssssssssssssssssssssssssssssssssss");
           print_r($username);   
            // contact info
            preg_match('/<ul id="phoneInfoPart" class="userContactInfo">(.*?)<\/ul>/', $open, $contact_info);
            print_r($contact_info);
            $serv4 = new self();
            $contact_info = $serv4->replaceSpace($contact_info[1]);
            preg_match_all('@<strong(.*?)>(.*?)</strong>(.*?)<span class="(.*?)">(.*?)</span>@si', $contact_info, $contact);
            foreach ($contact[5] as $index => $val) {
                $contacts[$contact[2][$index]] = $val;
            }
            $data = array(
                'title' => $title,
                'images' => $images,
                'address' => $address,
                'description' => $description,
                'properties' => $properties,
                'all_properties' => $props,
                'price' => $price,
                'user' => array(
                    'name' => $username,
                    'contact' => $contacts
                )
            );
 */
  //          return $data;


        }
    }

    /**
     * Gereksiz boşlukları temizler.
     *
     * @param $string
     * @return string
     */
    private function replaceSpace($string)
    {
        $string = preg_replace("/\s+/", " ", $string);
        $string = trim($string);
        return $string;
    }

    /**
     * @param $url
     * @param null $proxy
     * @return mixed
     */
    private function Curl($url, $proxy = NULL)
    {
        $_deneme = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36";
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT   => $_deneme,
            CURLOPT_HEADER => false,
            CURLOPT_ENCODING => "",
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYPEER => true
        );

        $ch = curl_init("$url");

        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);



        curl_close($ch);


        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        $string = '/searchResultsItem(\s+)"/';
        $links = "/(\s)classifiedTitle/";


        $header['content'] = preg_replace("/\s{2,}/", " ", $content);


        $header['content'] = str_replace(array("\n", "\r", "\t"), "", $header['content']);

        $header['content'] = preg_replace($string, 'searchResultsItem"', $header['content']);
        $header['content'] = preg_replace($links, 'classifiedTitle', $header['content']);

        return $header['content'];



        //return str_replace(array("\n", "\r", "\t"), "", $header['content']);
    }
}
