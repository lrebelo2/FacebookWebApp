<?php
   
    require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
    $fb = new Facebook\Facebook([
      'app_id' => '1191416027640633',
      'app_secret' => '38e4034a7f88f6ac1830c8bb812a7621',
      'default_graph_version' => 'v2.8',
    ]);
    #$access_token = "EAAQ7likbczkBANXPrjETeKH7uFbkzpGUXuyLl8M3sndbCQsDTqi7wu7gol0DiKtmIuhFRs4Fe4y7HQZAAl9Dfi16zwLADn2kLhh6o33slstxPgP72X4bT5xAYNAZCfSWHcxQ4FG2zukAFr8NkgA6QLv0xzONQZD";
    $access_token =  $facebook->getAccessToken();
    $fb->setDefaultAccessToken($access_token);

    $url_basic = "https://graph.facebook.com/v2.8/search?q=";
    
    $url_token=html_entity_decode("&")."access_token=".$access_token;
    $keyword =isset($_GET["keyword"])? $_GET["keyword"]:"";
    $lat =isset($_GET["lat"])? $_GET["lat"]:"34.0216685";
    $long =isset($_GET["long"])? $_GET["long"]:"-118.2826306";
    $lat =34.0216685;
    $long =-118.2826306;
    $keyword = str_replace(' ','%20',$keyword);
    $url_comp_users=html_entity_decode("&")."type=user&fields=id,name,picture.width(700).height(700)";  $url_comp_pages=html_entity_decode("&")."type=page&fields=id,name,picture.width(700).height(700)";
    $url_comp_events=html_entity_decode("&")."type=event&fields=id,name,picture.width(700).height(700)";
     if($lat!="" || $long != "")
        $url_comp_places="https://graph.facebook.com/v2.8/search?center=34.0216685,-118.2826306&q=".$keyword.html_entity_decode("&")."type=place&fields=id,name,picture.width(700).height(700)";
    else
        $url_comp_places="https://graph.facebook.com/v2.8/search?q=".$keyword.html_entity_decode("&")."type=place&fields=id,name,picture.width(700).height(700)";
    $url_comp_groups=html_entity_decode("&")."type=group&fields=id,name,picture.width(700).height(700)";
   
    $url_users=$url_basic.$keyword.$url_comp_users.$url_token;
    $url_pages=$url_basic.$keyword.$url_comp_pages.$url_token;
    $url_events=$url_basic.$keyword.$url_comp_events.$url_token;
    $url_places=$url_comp_places.$url_token;
    $url_groups=$url_basic.$keyword.$url_comp_groups.$url_token;
    $req_response_users=file_get_contents($url_users);
    $req_response_pages=file_get_contents($url_pages);
    $req_response_events=file_get_contents($url_events);
    $req_response_places=file_get_contents($url_places);
    $req_response_groups=file_get_contents($url_groups);
    $arrayResponse=array('users'=>$req_response_users,'pages'=>$req_response_pages,'events'=>$req_response_events,'places'=>$req_response_places,'groups'=>$req_response_groups);
    echo json_encode($arrayResponse);
    
?>