<?php
    $path = "./";
    require_once($path."autoloader.php");
    require_once($path."functions.php");
    require_once($path."db/SQLiteManager.php");

    $urls=$_GET['url'];
    $buscar=$_GET['buscar'];
    $filter=$_GET['filter'];
    //$urls = ["https://www.berria.eus/rss/bizigiro.xml", "https://www.berria.eus/rss/ekonomia.xml", "https://www.berria.eus/rss/kirola.xml"];

    $urla=explode(",",$urls);


    $articles = [];
    $i = 0;
    $a=0;
  foreach($urla as $url) {
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

          //  $articles[$i][$title] = $summary;

             $articles[$i]['title'] = $title;
             $articles[$i]['link'] = $link;
             $articles[$i]['description'] = $summary;

             $i++;
             
        }

       
 }



if($filter=='description'){

for ($x=0;$x<$i;$x++){
   // print_r($articles[$x]['link']);
   // print_r($articles[$x]['title']);
   // print_r($articles[$x]['description']);
   
                    $var_1 = strtoupper($articles[$x]['description']);
                    $var_2 = strtoupper($buscar);
                    similar_text($title, "hautsak", $similarity);
                    if (strpos($var_1, $var_2) !== false) {
                            $emaitza[$a]['link']=$articles[$x]['link'];
                            $emaitza[$a]['title']= $articles[$x]['title'];
                            $emaitza[$a]['description']=$articles[$x]['description'];
                            $a++;

                           // print ">> ".$articles[$x]['link']." - ".$articles[$x]['title']." - ".$articles[$x]['description']." <br>";
                        }

}

}

if($filter=='link'){
    for ($x=0;$x<$i;$x++){
   // print_r($articles[$x]['link']);
   // print_r($articles[$x]['title']);
   // print_r($articles[$x]['description']);
   
                    $var_1 = strtoupper($articles[$x]['link']);
                    $var_2 = strtoupper($buscar);
                    similar_text($title, "hautsak", $similarity);
                    if (strpos($var_1, $var_2) !== false) {
                           $emaitza[$a]['link']=$articles[$x]['link'];
                            $emaitza[$a]['title']= $articles[$x]['title'];
                            $emaitza[$a]['description']=$articles[$x]['description'];
                            $a++;
                           // print ">> ".$articles[$x]['link']." - ".$articles[$x]['title']." - ".$articles[$x]['description']." <br>";
                        }

}

}

if($filter=='title'){
                for ($x=0;$x<$i;$x++){
               // print_r($articles[$x]['link']);
               // print_r($articles[$x]['title']);
               // print_r($articles[$x]['description']);
               
                                $var_1 = strtoupper($articles[$x]['title']);
                                $var_2 = strtoupper($buscar);
                                print_r($buscar);
                                similar_text($title, "hautsak", $similarity);
                                if (strpos($var_1, $var_2) !== false) {
                                       $emaitza[$a]['link']=$articles[$x]['link'];
                                        $emaitza[$a]['title']= $articles[$x]['title'];
                                        $emaitza[$a]['description']=$articles[$x]['description'];
                                        $a++;

                                       // print ">> ".$articles[$x]['link']." - ".$articles[$x]['title']." - ".$articles[$x]['description']." <br>";
                                    }

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

