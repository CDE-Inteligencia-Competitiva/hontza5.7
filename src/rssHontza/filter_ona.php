<?php

$path = "./";
require_once($path."autoloader.php");
require_once($path."functions.php");
require_once($path."db/SQLiteManager.php");

$urls=$_GET['url'];
$filter1=$_GET['filter'];
//$filter=$_GET['filter'];
$reg=$_GET['reg'];
$nots1=$_GET['not'];

//$urls = ["https://www.berria.eus/rss/bizigiro.xml", "https://www.berria.eus/rss/ekonomia.xml", "https://www.berria.eus/rss/kirola.xml"];

$urla=explode(",",$urls);
//$busqueda=explode(",",$busquedas);
$emaitza=array();
$nots2=explode(",",$nots1);
for ($y=0;$y<count($nots2);$y++){
    $divisor=explode("_",$nots2[$y]);
    $not[$y][0]=$divisor[0];
    $not[$y][1]=$divisor[1];
}

$filter2=explode(",", $filter1);
for ($i=0;$i<count($filter2);$i++){
    $divisoria=explode("_",$filter2[$i]);
    $filtroa[$i][0]=$divisoria[0];
    $filtroa[$i][1]=$divisoria[1];
}
$articles = [];
$i = 0;
$a=0;

foreach($urla as $url) {
    $url=base64_decode($url);
    $feed = new SimplePie();
    $feed->enable_cache(false);
    $feed->set_feed_url($url);
    $feed->init();
    // This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).
    $feed->handle_content_type();

    foreach($feed->get_items() as $item) {
        $title = $item->get_title();
        $summary = $item->get_description();
        $link = $item -> get_link();

      // $articles[$i][$title] = $summary;

         $articles[$i]['title'] = $title;
         $articles[$i]['link'] = $link;
         $articles[$i]['description'] = $summary;

         $i++;
         
    }

}

foreach($filtroa as $filtro){
$buscar=$filtro[0];
$filter=$filtro[1];
    switch ($filter) {
        case 'description':
        if ($reg==1){
           for ($x=0;$x<$i;$x++){
                $var_1 = $articles[$x]['description'];
                $var_2 = $buscar;
                if (preg_match($var_2, $var_1)) {
                    for($u=0;$u<count($not);$u++){
                        if (!preg_match($not[$u][0], $articles[$x][$not[$u][1]])){
                            if(($u+1)==count($not)){
                                $emaitza[$a]['link']=$articles[$x]['link'];
                                $emaitza[$a]['title']= $articles[$x]['title'];
                                $emaitza[$a]['description']=$articles[$x]['description'];
                                $a++;
                            }
                        }else{
                            $u=count($not);
                        }
                    }
                }
            }

        }elseif($reg==0){
            for ($x=0;$x<$i;$x++){
                $var_1 = strtoupper($articles[$x]['description']);
                $var_2 = strtoupper($buscar);
                if (strpos($var_1, $var_2) !== false) {
                    for($u=0;$u<count($not);$u++){
                        $no=strtoupper($not[$u][0]);
                        if (strpos(strtoupper($articles[$x][$not[$u][1]]), $no)== false){
                            if(($u+1)==count($not)){
                                $emaitza[$a]['link']=$articles[$x]['link'];
                                $emaitza[$a]['title']= $articles[$x]['title'];
                                $emaitza[$a]['description']=$articles[$x]['description'];
                                $a++;
                            }
                        }else{
                            $u=count($not);
                        }
                    }
                }
            }
        }else{
            die("Expresion regular o no no decidida. reg=1 regular, reg=0 no regular");
            break;
        }

            break;

        case 'link':
           if ($reg==1){
           for ($x=0;$x<$i;$x++){
                $var_1 = $articles[$x]['link'];
                $var_2 = $buscar;
                if (preg_match($var_2, $var_1)) {
                    for($u=0;$u<count($not);$u++){
                        if (!preg_match($not[$u][0], $articles[$x][$not[$u][1]])){
                            if(($u+1)==count($not)){
                                $emaitza[$a]['link']=$articles[$x]['link'];
                                $emaitza[$a]['title']= $articles[$x]['title'];
                                $emaitza[$a]['description']=$articles[$x]['description'];
                                $a++;
                            }
                        }else{
                            $u=count($not);
                        }
                    }
                }
            }
        }elseif($reg==0){
            for ($x=0;$x<$i;$x++){
                $var_1 = strtoupper($articles[$x]['link']);
                $var_2 = strtoupper($buscar);
                if (strpos($var_1, $var_2) !== false) {
                    for($u=0;$u<count($not);$u++){
                        $no=strtoupper($not[$u][0]);
                        if (strpos(strtoupper($articles[$x][$not[$u][1]]), $no)== false){
                            if(($u+1)==count($not)){
                                $emaitza[$a]['link']=$articles[$x]['link'];
                                $emaitza[$a]['title']= $articles[$x]['title'];
                                $emaitza[$a]['description']=$articles[$x]['description'];
                                $a++;
                            }
                        }else{
                            $u=count($not);
                        }
                    }
                }
            }
        }else{
            die("Expresion regular o no no decidida. reg=1 regular, reg=0 no regular");
            break;
        }
            break;

        case 'title':
        if ($reg==1){
           for ($x=0;$x<$i;$x++){
                $var_1 = $articles[$x]['title'];
                $var_2 = $buscar;
                if (preg_match($var_2, $var_1)) {
                    for($u=0;$u<count($not);$u++){
                        if (!preg_match($not[$u][0], $articles[$x][$not[$u][1]])){
                            if(($u+1)==count($not)){
                                $emaitza[$a]['link']=$articles[$x]['link'];
                                $emaitza[$a]['title']= $articles[$x]['title'];
                                $emaitza[$a]['description']=$articles[$x]['description'];
                                $a++;
                            }
                        }else{
                            $u=count($not);
                        }
                    }
                }
            }
        }elseif($reg==0){
            for ($x=0;$x<$i;$x++){
                $var_1 = strtoupper($articles[$x]['title']);
                $var_2 = strtoupper($buscar);
                if (strpos($var_1, $var_2) !== false) {
                    for($u=0;$u<count($not);$u++){
                        $no=strtoupper($not[$u][0]);
                        if (strpos(strtoupper($articles[$x][$not[$u][1]]), $no)== false){
                            if(($u+1)==count($not)){
                                $emaitza[$a]['link']=$articles[$x]['link'];
                                $emaitza[$a]['title']= $articles[$x]['title'];
                                $emaitza[$a]['description']=$articles[$x]['description'];
                                $a++;
                            }
                        }else{
                            $u=count($not);
                        }
                    }
                }
            }
        }else{
            die("Expresion regular o no no decidida. reg=1 regular, reg=0 no regular");
            break;
        }
            break;

        case 'title,description':
        case 'description,title':
        if ($reg==1){
           for ($x=0;$x<$i;$x++){
                $var_1 = $articles[$x]['description'];
                $var_2 = $articles[$x]['title'];
                $var_3 = $buscar;
                if (preg_match($var_3, $var_1) || preg_match($var_3, $var_2)) {
                    for($u=0;$u<count($not);$u++){
                        if (!preg_match($not[$u][0], $articles[$x][$not[$u][1]])){
                            if(($u+1)==count($not)){
                            $emaitza[$a]['link']=$articles[$x]['link'];
                            $emaitza[$a]['title']= $articles[$x]['title'];
                            $emaitza[$a]['description']=$articles[$x]['description'];
                            $a++;
                            }  
                        }else{
                            $u=count($not);
                        }
                    }                     
                }
            }
        }elseif($reg==0){
            for ($x=0;$x<$i;$x++){
                $var_1 = strtoupper($articles[$x]['description']);
                $var_2 = strtoupper($articles[$x]['title']);
                $var_3 = strtoupper($buscar);
                if (strpos($var_1, $var_3) !== false || strpos($var_2, $var_3) !== false) {
                    for($u=0;$u<count($not);$u++){
                        $no=strtoupper($not[$u][0]);
                        if (strpos(strtoupper($articles[$x][$not[$u][1]]), $no)== false){
                            if(($u+1)==count($not)){
                                $emaitza[$a]['link']=$articles[$x]['link'];
                                $emaitza[$a]['title']= $articles[$x]['title'];
                                $emaitza[$a]['description']=$articles[$x]['description'];
                                $a++;
                            }
                        }else{
                            $u=count($not);
                        }
                    }
                }
            }
        }else{
            die("Expresion regular no decidida. reg=1 regular, reg=0 no regular");
            break;
        }
            break;

        case 'title,link,description':
        case 'title,description,link':
        case 'description,title,link':
        case 'description,link,title':
        case 'link,title,description':
        case 'link,description,title':
        if ($reg==1){
           for ($x=0;$x<$i;$x++){
                $var_3 = $articles[$x]['description'];
                $var_2 = $articles[$x]['title'];
                $var_4 = $articles[$x]['link'];
                $var_1 = $buscar;
                if (preg_match($var_1, $var_2) || preg_match($var_1, $var_3) || preg_match($var_1, $var_4)) {
                    for($u=0;$u<count($not);$u++){
                        if (!preg_match($not[$u][0], $articles[$x][$not[$u][1]])){
                            if(($u+1)==count($not)){
                                $emaitza[$a]['link']=$articles[$x]['link'];
                                $emaitza[$a]['title']= $articles[$x]['title'];
                                $emaitza[$a]['description']=$articles[$x]['description'];
                                $a++;
                            }
                        }else{
                            $u=count($not);
                        }
                    }                   
                }
            }
        }elseif($reg==0){
            for ($x=0;$x<$i;$x++){
                $var_4 = strtoupper($articles[$x]['description']);
                $var_3 = strtoupper($articles[$x]['title']);
                $var_2 = strtoupper($articles[$x]['link']);
                $var_1 = strtoupper($buscar);
                if (strpos($var_2, $var_1) !== false || strpos($var_3, $var_1) !== false || strpos($var_4, $var_1) !== false) {
                    for($u=0;$u<count($not);$u++){
                        $no=strtoupper($not[$u][0]);
                        if (strpos(strtoupper($articles[$x][$not[$u][1]]), $no)== false){
                            if(($u+1)==count($not)){
                                $emaitza[$a]['link']=$articles[$x]['link'];
                                $emaitza[$a]['title']= $articles[$x]['title'];
                                $emaitza[$a]['description']=$articles[$x]['description'];
                                $a++;
                            } 
                        }else{
                            $u=count($not);
                        }
                    }                     
                }
            }
        }else{
            die("Expresion regular o no no decidida. reg=1 regular, reg=0 no regular");
            break;
        }
            break;

        default:
           die("filtro no establecido");
            break;
    }

}

  

$rssfeed = '';

    $rssfeed = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $rssfeed .= '<rss version="2.0">' . "\n";
    $rssfeed .= '<channel>' . "\n";
    $rssfeed .= '<title>RSS de hontza</title>' . "\n";
    $rssfeed .= '<link>http://www.hontza.es</link>' . "\n";
    $rssfeed .= '<description>This is an HONTZA RSS feed</description>' . "\n";
    $rssfeed .= '<language>en-us</language>' . "\n";

    $dateq = '';

    if (isset($_GET['from'])) {
        $p['from'] = $_GET['from'];
        $dateq .= " AND date > '" . date('Y-m-d H:i:s', $p['from']) . "'";
        
    }
    if (isset($_GET['to'])) {
    $p['to'] = $_GET['to'];
        $dateq .= " AND date < '" . date('Y-m-d H:i:s', $p['to']) . "'";
    }


      foreach ($emaitza as $feed){

            $rssfeed .= '<item>' . "\n";
            $rssfeed .= '<title><![CDATA[' . $feed['title'] . ']]></title>' . "\n";
            $rssfeed .= '<description><![CDATA[' .  $feed['description'] . ']]></description>' . "\n";
            $rssfeed .= '<link><![CDATA[' . $feed['link'] . ']]></link>' . "\n";
            $rssfeed .= '</item>' . "\n";        
      }
           
        

    $rssfeed .= '</channel>' . "\n";
    $rssfeed .= '</rss>' . "\n";
    echo $rssfeed;

 
?>

