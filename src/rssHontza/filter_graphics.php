<?php

$path = "./";
require_once($path."autoloader.php");
require_once($path."functions.php");
require_once($path."db/SQLiteManager.php");


function filter_options_form(){
    $title=t('Filter RSS');
    $form['url']=array(
        '#type'=>'textarea',
        '#title'=>'RSS url to filter',
        '#description' => 'If you want to filter more than one rss, you have to entered its whit ,',
        '#required'=>TRUE,

    );
    $form['regular']=array(
        '#type'=>'checkbox',
        '#title'=>'Use regular expressions',
        '#default_value'=>TRUE,
    );
    $form['and']=array(
        '#type'=>'fieldset',
        '#title'=>'Use AND expression',
        '#description' => 'If you want to use AND expressions between arg1 arg2 and arg3, use this',
    );
    $form['and']['arg1']=array(
        '#type'=>'textarea',
        '#title'=>'Enter arg1',
        '#description' => 'If you want more than one term, separate using ","',
        '#default_value' => '/term1/i,/term2/i,/term3/i',
    );

    $form['and']['arg2']=array(
        '#type'=>'textarea',
        '#title'=>'Enter arg2',
        '#description' => 'If you want more than one term, separate using ","',
        '#default_value' => '/term4/i,/term5/i',
    );
    $form['and']['arg3']=array(
        '#type'=>'textarea',
        '#title'=>'Enter arg3',
        '#description' => 'If you want more than one term, separate using ","',
        '#default_value' => '/term6/i',
    );

    $form['and']['where']=array(
        '#type'=>'fieldset',
        '#title'=>'Where',
           
    );
    $form['and']['where']['description']=array(
        '#type'=>'checkbox',
        '#title'=>'description',
        '#default_value'=>TRUE
        
    );
    $form['and']['where']['title']=array(
        '#type'=>'checkbox',
        '#title'=>'title',
        '#default_value'=>TRUE
        
    );
    $form['and']['where']['link']=array(
        '#type'=>'checkbox',
        '#title'=>'link',
        '#default_value'=>False
        
    );
    $form['or']=array(
        '#type'=>'fieldset',
        '#title'=>'Use OR expression',
        '#description' => 'If you want to use OR expressions between different arguments use this',
    );
    $form['or']['arg1']=array(
        '#type'=>'textarea',
        '#title'=>'Enter arguments',
        '#description' => 'If you want more than one term, separate using ","',
        '#default' => '/term1/i,/term2/i',
    );

    $form['or']['where']=array(
        '#type'=>'fieldset',
        '#title'=>'Where',
           
    );
    $form['or']['where']['description']=array(
        '#type'=>'checkbox',
        '#title'=>'description',
        '#default_value'=>TRUE
        
    );
    $form['or']['where']['title']=array(
        '#type'=>'checkbox',
        '#title'=>'title',
        '#default_value'=>TRUE
        
    );
    $form['or']['where']['link']=array(
        '#type'=>'checkbox',
        '#title'=>'link',
        '#default_value'=>False
        
    );
    $form['not']=array(
        '#type'=>'fieldset',
        '#title'=>'Use Not expression',
        '#description' => 'If you want to use NOT expressions between different arguments use this',
    );
    $form['not']['arg1']=array(
        '#type'=>'textarea',
        '#title'=>'Enter arguments',
        '#description' => 'If you want more than one term, separate using ","',
        '#default' => '/term1/i',
    );

    $form['not']['where']=array(
        '#type'=>'fieldset',
        '#title'=>'Where',
           
    );
    $form['not']['where']['description']=array(
        '#type'=>'checkbox',
        '#title'=>'description',
        '#default_value'=>TRUE
        
    );
    $form['not']['where']['title']=array(
        '#type'=>'checkbox',
        '#title'=>'title',
        '#default_value'=>TRUE
        
    );
    $form['not']['where']['link']=array(
        '#type'=>'checkbox',
        '#title'=>'link',
        '#default_value'=>False
        
    );
    $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Filter'),
    );

    return $form;
    
}

function filter_options_form_submit(){
if($form_state['values']['url']){
    $urls=$form_state['values']['sheet_id'];
    $filter=$form_state['or']['arg1'];
    $nots1=$form_state['values']['not']['arg1'];

    filtrar($urls,$filter,$nots);

}

}

function filtrar($urls,$filter,$nots){

/*$urls=$_GET['url'];
$filter1=$_GET['filter'];
//$filter=$_GET['filter'];
$reg=$_GET['reg'];
$nots1=$_GET['not'];

*/
//$urls = ["https://www.berria.eus/rss/bizigiro.xml", "https://www.berria.eus/rss/ekonomia.xml", "https://www.berria.eus/rss/kirola.xml"];

$urla=explode(",",$urls);
$busqueda=explode(",",$busquedas);
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
    //$url=base64_decode($url);
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
                    for($u=0;$u<count($not)+1;$u++){
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
                    for($u=0;$u<count($not)+1;$u++){
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
                    for($u=0;$u<count($not)+1;$u++){
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
                    for($u=0;$u<count($not)+1;$u++){
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
                    for($u=0;$u<count($not)+1;$u++){
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
                    for($u=0;$u<count($not)+1;$u++){
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
                    for($u=0;$u<count($not)+1;$u++){
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
                    for($u=0;$u<count($not)+1;$u++){
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
                    for($u=0;$u<count($not)+1;$u++){
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
                    for($u=0;$u<count($not)+1;$u++){
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

 

}
?>

