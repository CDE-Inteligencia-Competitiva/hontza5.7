<?php

$path = "./";
require_once($path."autoloader.php");
require_once($path."functions.php");
require_once($path."db/SQLiteManager.php");

$urls=$_GET['url'];
//$filter=$_GET['filter'];
if(isset($_GET['reg'])){
    $reg=$_GET['reg'];
}else{
    $reg=0;
}



if(isset($_GET['not'])){
   $nots1=$_GET['not'];
   $nots1=base64_decode($nots1);
}else{
    $not[0][0]='/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/';
    $not[0][1]='title';
}

if(isset($_GET['filter'])){
    $filter1=$_GET['filter'];
    $filter1=base64_decode($filter1);
}elseif(isset($_GET['and'])){   
    if($reg==1){
        $filtroa[0][0]='/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/';
        $filtroa[0][1]='title';
    }else
        $filtroa[0][0]='aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $filtroa[0][1]='title';
    
}else{
    if($reg==1){
        $filtroa[0][0]='//';
        $filtroa[0][1]='title';
    }else
        $filtroa[0][0]=' ';
        $filtroa[0][1]='title';
}

//$urls = ["https://www.berria.eus/rss/bizigiro.xml", "https://www.berria.eus/rss/ekonomia.xml", "https://www.berria.eus/rss/kirola.xml"];

$urla=explode(",",$urls);
//$busqueda=explode(",",$busquedas);
$emaitza=array();
if(isset($nots1)){
    $nots2=explode(",",$nots1);
    for ($y=0;$y<count($nots2);$y++){
    $divisor=explode("_",$nots2[$y]);
    $not[$y][0]=$divisor[0];
    $not[$y][1]=$divisor[1];
}
}


if (isset($filter1)){
    $filter2=explode(",", $filter1);
        for ($i=0;$i<count($filter2);$i++){
            $divisoria=explode("_",$filter2[$i]);
            $filtroa[$i][0]=$divisoria[0];
            $filtroa[$i][1]=$divisoria[1];
        }
}

$articles = [];
$i = 0;
$a=0;




foreach($urla as $url) {
    $url=base64_decode($url);

    //feec cleanerra aplikatzkeo
   // $url=str_replace('&','%26',$url);
    //$url='http://feed-cleaner.appspot.com/sanitize?url='.$url.'&format=rss';

    $feed = new SimplePie();
    $feed->enable_cache(false);
    $feed->set_feed_url($url);
    $feed->force_feed(true);
    $feed->init();

    // This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).
   

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
$or1=0;
$or2=0;
$or3=0;

//$andtot=$_GET['and'];
//andtot=/term1/i_or1·title,/term1/i_or1·title,/term2/i_or3·description

if (isset($_GET['and'])){
    $andtot=$_GET['and'];
    $andtot=base64_decode($andtot);
    $andak=explode(",",$andtot);
        for($o=0;$o<count($andak);$o++){
            $sep=explode("_",$andak[$o]);
            $att=explode("·",$sep[1]);
            //tituluan deskripzioan edo 
            if($att[0]=='or1'){
                $and1[$or1][0]=$sep[0];
                $and1[$or1][1]=$att[0];
                $and1[$or1][2]=$att[1];
                $or1++;
            }
            if($att[0]=='or2'){
                $and2[$or2][0]=$sep[0];
                $and2[$or2][1]=$att[0];//hemen or1,or2edo or3
                $and2[$or2][2]=$att[1];//hemen title link edo description
                $or2++;
            }
            if($att[0]=='or3'){
                $and3[$or3][0]=$sep[0];
                $and3[$or3][1]=$att[0];
                $and3[$or3][2]=$att[1];
                $or3++;
            }       
        }
        $y=0;

        
        //or1eko kondizioa:
        for($x=0;$x<$i;$x++){
            for($b=0;$b<$or1;$b++){    
                if(preg_match($and1[$b][0],$articles[$x][$and1[$b][2]])){
                    $emaitzaprobisionala1[$y]['link']=$articles[$x]['link'];
                    $emaitzaprobisionala1[$y]['title']=$articles[$x]['title'];
                    $emaitzaprobisionala1[$y]['description']=$articles[$x]['description'];
                    $b=$or1;
                    $y++;
                }
            }
        }

        //or2ko kondizioa    
        $v=0;
        if ($or2>0){
            for($x=0;$x<$y;$x++){
                for($b=0;$b<$or2;$b++){
                    if(preg_match($and2[$b][0],$emaitzaprobisionala1[$x][$and2[$b][2]])){
                        $emaitzaprobisionala2[$v]['link']=$emaitzaprobisionala1[$x]['link'];
                        $emaitzaprobisionala2[$v]['title']=$emaitzaprobisionala1[$x]['title'];
                        $emaitzaprobisionala2[$v]['description']=$emaitzaprobisionala1[$x]['description'];
                        $b=$or2;
                        $v++;
                    }
                }
            }  
        }else{
            $z=$y;
            $emaitzaprobisionala3=$emaitzaprobisionala1;
        }
        

        $z=0;
        //or3ko kondizio
        if ($or3>0){
            for($x=0;$x<$v;$x++){
                for($b=0;$b<$or3;$b++){
                    if(preg_match($and3[$b][0],$emaitzaprobisionala2[$x][$and3[$b][2]])){
                        $emaitzaprobisionala3[$z]['link']=$emaitzaprobisionala2[$x]['link'];
                        $emaitzaprobisionala3[$z]['title']=$emaitzaprobisionala2[$x]['title'];
                        $emaitzaprobisionala3[$z]['description']=$emaitzaprobisionala2[$x]['description'];
                        $b=$or3;
                        $z++;
                    }
                }
            }  
        }else{        
            if($or2>0){
                $z=$v;
                $emaitzaprobisionala3=$emaitzaprobisionala2;
            }else{
                $z=$y;
                $emaitzaprobisionala3=$emaitzaprobisionala1;
            }           
        }
        
        //print_r($emaitzaprobisionala3);
        //notik ez duela ikusi, eta ez baldin badu, grabatu. 
        for($x=0;$x<$z;$x++){
            for($u=0;$u<count($not);$u++){
                    if (!preg_match($not[$u][0], $emaitzaprobisionala3[$x][$not[$u][1]])){
                        if(($u+1)==count($not)){
                            $emaitza[$a]['link']=$emaitzaprobisionala3[$x]['link'];
                            $emaitza[$a]['title']= $emaitzaprobisionala3[$x]['title'];
                            $emaitza[$a]['description']=$emaitzaprobisionala3[$x]['description'];
                            $a++;
                        }
                    }else{
                        $u=count($not);
                    }
            }
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

//idatzi baina lehenago, emaitza pixka bat txukunduko dugu desiro ez ditugun elementuak azaldu ez daitezen, adibidez &amp; ordez & azaltzea. 

for($kont=0;$kont<$a;$kont++){
    $emaitza[$kont]['link']=str_replace('&amp;','&',$emaitza[$kont]['link']);
    $emaitza[$kont]['description']=str_replace('&amp;','&',$emaitza[$kont]['description']);
    $emaitza[$kont]['title']=str_replace('&amp;','&',$emaitza[$kont]['title']);

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

