<?php




    //$urlcomposatua=$node->field_nombrefuente_canal[0]['value'];
    ///http://hound.hontza.es/rssHontza/filter.php?url=aHR0cHM6Ly93d3cuYmVycmlhLmV1cy9yc3MvZWtvbm9taWEueG1s,aHR0cHM6Ly93d3cuYmVycmlhLmV1cy9yc3MvYml6aWdpcm8ueG1s&filter=/((?=.*ondare)|(?=.*maskulinitate)|(?=.*modu))((?=.*galaraziaren)|(?=.*ereduei)|(?=.*arinean))((?=.*gibelean)|(?=.*so)|(?=.*da))/i_description,/((?=.*ondare)|(?=.*maskulinitate)|(?=.*modu))((?=.*galaraziaren)|(?=.*ereduei)|(?=.*arinean))((?=.*gibelean)|(?=.*so)|(?=.*da))/i_title&reg=1
    $urlcomposatua='http://hound.hontza.es/rssHontza/filter.php?url=aHR0cHM6Ly93d3cuYmVycmlhLmV1cy9yc3MvZWtvbm9taWEueG1s,aHR0cHM6Ly93d3cuYmVycmlhLmV1cy9yc3MvYml6aWdpcm8ueG1s&filter=/((?=.*ondare)|(?=.*maskulinitate)|(?=.*modu))((?=.*galaraziaren)|(?=.*ereduei)|(?=.*arinean))/i_description,/((?=.*ondare)|(?=.*maskulinitate)|(?=.*modu))((?=.*galaraziaren)|(?=.*ereduei)|(?=.*arinean))((?=.*gibelean)|(?=.*so)|(?=.*da))/i_link&reg=1';
    $urlsep= explode("&",$urlcomposatua);
    
    
    //obtener urls
    $urlsep1=explode("?",$urlsep[0]);
    $urlak=substr($urlsep1[1],4,strlen($urlsep1[1]));
    $urlaksep=explode(",",$urlak);
    for($x=0;$x<count($urlaksep);$x++){
        $urldecode=base64_decode($urlaksep[$x]);
        if($x+1==count($urlaksep))
        {
           $url.=$urldecode; 
        }else{
            $url.=$urldecode.',';
        }
        
    }

    //obtener filter
    $filter1=$urlsep[1];
    $filter2=substr($filter1,7,strlen($filter1));
    if($filter2[0]=='(' || $filter2[1]=='('){
        ///((?=.*ondare)|(?=.*maskulinitate)|(?=.*modu))((?=.*galaraziaren)|(?=.*ereduei)|(?=.*arinean))((?=.*gibelean)|(?=.*so)|(?=.*da))/i_description,/((?=.*ondare)|(?=.*maskulinitate)|(?=.*modu))((?=.*galaraziaren)|(?=.*ereduei)|(?=.*arinean))((?=.*gibelean)|(?=.*so)|(?=.*da))/i_title

        $filtersep=explode(",",$filter2);
        //filtroen nungoa ateratzeko. 
        $wheresep1=explode("_",$filtersep[0]);
        if(isset($filtersep[1])){
             $wheresep2=explode("_",$filtersep[1]);
        }
        if(isset($filtersep[2])){
            $wheresep2=explode("_",$filtersep[2]);
        }
        if($wheresep1[1]=='description' || $wheresep2[1]=='description' || $wheresep3[1]=='description'){
            $descriptionand=TRUE;
        }else{
           $descriptionand=FALSE; 
        }
        if($wheresep1[1]=='title' || $wheresep2[1]=='title' || $wheresep3[1]=='title'){
            $titleand=TRUE;
        }else{
           $titleand=FALSE; 
        }
        if($wheresep1[1]=='link' || $wheresep2[1]=='link' || $wheresep3[1]=='link'){
            $linkand=TRUE;
        }else{
           $linkand=FALSE; 
        }

        //separar los filtros del and
        $filtrotot=$filtersep[0];
        $x=0;
        for($x=0;$x<3;$x++){

            print_r("filtro totala:");
            print_r($filtrotot);
            print_r('<br>');
            $muga1=strpos($filtrotot,"((");
            $muga1=$muga1+2;
            $muga2=strpos($filtrotot,"))");
            $muga2=$muga2+1;
            $tartea=$muga2-$muga1;
            $filtroand=substr($filtrotot,$muga1,$tartea);
            $filtroandmuga=$filtroand;
            for($y=0;$y<substr_count($filtroandmuga, "?=.*");$y++){
                print_r("ziklo kopurua:");
                print_r(substr_count($filtroandmuga, "?=.*"));
                print_r("Ziklua: ".$y);
                print_r('<br>');
                print_r("filtro and ".$filtroand);
                print_r('<br>');
                $mugax=strpos($filtroand,"*");
                $mugax=$mugax+1;
                print_r("muga x:".$mugax);
                print_r('<br>');
                $mugay=strpos($filtroand,")");
                $tartea=$mugay-$mugax;
                print_r("tartea:".$tartea);
                print_r('<br>');
                
                if($y+1==substr_count($filtroandmuga, "?=.*")){
                    $argumentua[$x].=substr($filtroand,$mugax,$tartea);
                }else{
                    $argumentua[$x].=substr($filtroand,$mugax,$tartea).",";
                }
                
                
                $mugay=$mugay+1;
                $tartea=strlen($filtroand)-$mugay;
                $filtroand=substr($filtroand,$mugay,$tartea);
                print_r("argumentua:");
                print_r($argumentua[$x]);
                print_r('<br>');
            }
           
            $muga2=$muga2+1;
            $tartea=strlen($filtrotot)-$muga2;
            $filtrotot=substr($filtrotot,$muga2,$tartea);

        }
      

        $arg1and=$argumentua[0];
        $arg2and=$argumentua[1];
        $arg3and=$argumentua[2];
        $filteror='';
    }
    else{
        $filteror=$filtersep;
        $filterand='';
    }


    //obtener not o reg
    $valor=$urlsep[2];
    
   if($valor[0]=='n'){
   
        $not=substr($urlsep[2],4,strlen($urlsep[2]));
        if (isset($urlsep[3])){
            $reg=substr($urlsep[3],4,5);
        }
    }
    else{
        $reg=substr($urlsep[2],4,strlen($urlsep[2]));
        $not='';
    }
   
