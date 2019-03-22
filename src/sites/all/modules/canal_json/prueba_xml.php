 <?php
ini_set('memory_limit', '1024M'); // or you could use 1G
$url = '/home/cdejarruti/Escritorio/CDE/listado-exportacion.xml';
//$url = 'http://svpe.hontza.es/crm_exportar/textos_exportar_todas_noticias/todas/0/0?is_duplicate_news=1&is_tag=0&is_export_xml=1&grupo_nid=140933&crm_exportar_listas_id=3';

//'https://spreadsheets.google.com/feeds/list/12M7Ev9T-gDW7XiyAUEAQ5fMrhUw8f-ADWfNWUKHBohk/3/public/values?alt=json';
//$context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));


//$xml = file_get_contents($url, false, $context);

//$xml = simplexml_load_string($xml);
$xml = simplexml_load_file($url);


 
 
  $json_sortzeko=array();
  $json_fields=array();
  $i='0';

 foreach($xml->channel->item as $item) {
    $json_sortzeko[$i]['title'] =(string) "[".$item->date."] ". $item->title;
    $json_sortzeko[$i]['link']=(string) $item->link;
    $json_sortzeko[$i]['description']=(string) $item->description."[".$item->tags->tag->title."] ";
    $json_sortzeko[$i]['date']=(string) $item->date;
    //$json_sortzeko[$i]['url_json']=$url;
    $json_sortzeko[$i]['url_json']=$url;

    $i++;

    
    }
    print_r($json_sortzeko);

    /*

      $fitxero='google_sheet/xml_prueba.json';//hau tenporala izango da. 
      $bidaltzeko=json_encode($json_sortzeko);
      file_put_contents($fitxero, $bidaltzeko);
      //$url='http://192.169.1.17/hontza/google_sheet/id_'.$id.'_'.$orria.'.json';
      $url='http://localhost/hontza/google_sheet/xml_prueba.json';
      
      $json_fields['title'] ='title';
      $json_fields['link']='link';
      $json_fields['description']='description';
      $json_fields['date']='date';
      $json_fields['url_json']=$url;

       $my_grupo=og_get_group_context();
       if(!empty($my_grupo) && isset($my_grupo->nid)){
           $grupo_link=$my_grupo->purl;
       }

       $url_json=base64_encode(json_encode($json_fields));

       $my_lang='/es';
       if($language->language!='en'){
           $my_lang='/'.$language->language;
       }
       $url=url($base_url.$my_lang.'/'.$grupo_link.'/crear/canal-yql',array('query'=>'simple=1&url_json='.$url_json));   
  
       drupal_goto($url);

       
}*/


?>