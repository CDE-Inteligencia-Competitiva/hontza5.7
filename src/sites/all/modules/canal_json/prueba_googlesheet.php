<?php




  $url = 'https://spreadsheets.google.com/feeds/list/12M7Ev9T-gDW7XiyAUEAQ5fMrhUw8f-ADWfNWUKHBohk/3/public/values?alt=json';


  $file= file_get_contents($url);
  $json = json_decode($file);
  $rows = $json->{'feed'}->{'entry'};
  $json_sortzeko=array();
  $json_fields=array();
  $i='0';

 foreach($rows as $row) {
    $json_sortzeko[$i]['title'] =$row->{'gsx$title'}->{'$t'};
    $json_sortzeko[$i]['link']=$row->{'gsx$link'}->{'$t'};
    $json_sortzeko[$i]['description']=$row->{'gsx$description'}->{'$t'};
    $json_sortzeko[$i]['url_json']=$url;
    $i++;
    }

 print_r($json_sortzeko);

?>