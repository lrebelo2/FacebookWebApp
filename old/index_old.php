<html>

<head>
    <title>Homework 6</title>
    <style type="text/css">
        h1 {
            font-family: Times, serif;
            text-align: center;
            margin-bottom: 10px;
            font-weight: 100;
            font-style: italic;
        }
        
        #divisor {
            height: 1px;
            margin: auto;
            width: 95%;
            background-color: black;
            margin-bottom: 10px;
        }
        
        .container {
            background-color: #f4f4f4;
            width: 95%;
            margin: auto;
            height: auto;
            padding-bottom: 3px;
        }
        
        #myForm {
            margin-left: 3px;
        }
        
        #select {
            margin-left: 28px;
        }
        
        .buttons {
            margin-left: 68px;
            margin-top: 5px;
        }
        
        .locInput {
            margin-left: 2px;
            margin-top: 1px;
        }
        
        .locdist {
            visibility: hidden;
        }
        
        #detailsbutton {
            text-decoration: underline;
            text-decoration-color: blue;
        }
        
        .tableDiv,
        #albumsdiv,
        #postsdiv {
            background-color: #f4f4f4;
            width: 95%;
            margin: auto;
            margin-top: 10px;
            text-align: center;
            height: auto;
        }
        
        #containertableP,
        #containertableA {
            width: 95%;
            margin: auto;
            margin-top: 10px;
            text-align: center;
            height: auto;
            display: none;
        }
        
        #tableA,
        #tableP {
            width: 100%;
            margin-top: 40px;
        }
        
        .rowImgAlb {
            display: none;
        }
        
        #cellD {
            vertical-align: middle;
        }
        
        .subttn {
            background-color: transparent;
            text-decoration: underline;
            border: none;
            color: blue;
            cursor: pointer;
            background: none!important;
            font: inherit;
            border: none;
            padding: 0!important;
            vertical-align: middle;
            text-align: left;
            margin-top: 5px;
            margin-bottom: 0px;
        }
        
        .subttn:focus {
            outline: none;
        }
        
        .header {
            text-align: left;
        }
    </style>
</head>
<script type="text/javascript">
    function setVisibility() {
        var y = document.getElementsByClassName("locdist")[0];
        if (document.getElementById("select").value == "place") {
            y.style.visibility = "visible";
        }
        else {
            y.style.visibility = "hidden";
        }
    }

    function clearForm() {
        document.getElementById("location").value = "";
        document.getElementById("keyword").value = "";
        document.getElementById("distance").value = "";
        document.getElementsByClassName("locdist")[0].style.visibility = "hidden";
        document.getElementById("select").selectedIndex = "0";
        document.getElementById("wub").style.visibility = "hidden";
        document.getElementsByClassName("tableDiv")[0].style.visibility = "hidden";
    }

    function imgOpen(src) {
        console.log(src);
        s = "<html><head><title>Image</title></head><body><img src=" + src + "></body></hmtl>";
        ImgW = window.open();
        ImgW.document.write(s);
    }
    window.onload = setVisibility;
</script>
<?php
    require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
    $fb = new Facebook\Facebook([
      'app_id' => '1191416027640633',
      'app_secret' => '38e4034a7f88f6ac1830c8bb812a7621',
      'default_graph_version' => 'v2.8',
    ]);
    $accessToken = "EAAQ7likbczkBANXPrjETeKH7uFbkzpGUXuyLl8M3sndbCQsDTqi7wu7gol0DiKtmIuhFRs4Fe4y7HQZAAl9Dfi16zwLADn2kLhh6o33slstxPgP72X4bT5xAYNAZCfSWHcxQ4FG2zukAFr8NkgA6QLv0xzONQZD";
    $fb->setDefaultAccessToken($accessToken);
    //set form input variables
    $keyword =isset($_POST["keyword"])? $_POST["keyword"]:"";
    $type =  isset($_POST["type"])? $_POST["type"]:"";
    $location = isset($_POST["location"])? $_POST["location"]:"";
    $location = str_replace(' ', '', $location);
    $distance = isset($_POST["distance"])? $_POST["distance"]:"";
    

?>

    <body>
        <div class="container">
            <h1>Facebook Search PHP</h1>
            <div id="divisor"></div>
            <form action="index.php" method="post" id="myForm">
                <div class="input">
                    <input type="hidden" name="identifier" value="formsubmit"></input>
                    <label class="inline" for="keyword">Keyword: </label>
                    <input type="text" id="keyword" name="keyword" class="formItem" value="<?php echo isset($_POST['keyword']) ? $_POST['keyword'] : '' ?>" required>
                    <br>
                    <label class="inline" for="select">Type: </label>
                    <select name="type" id="select" class="formItem" onchange="setVisibility()">
                        <option value="user" <?php if($type=='user' ) echo "{ selected }" ?>>Users</option>
                        <option value="page" <?php if($type=='page' ) echo "{ selected }" ?>>Pages</option>
                        <option value="event" <?php if($type=='event' ) echo "{ selected }" ?>>Events</option>
                        <option value="group" <?php if($type=='group' ) echo "{ selected }" ?>>Groups</option>
                        <option value="place" <?php if($type=='place' ) echo "{ selected }" ?>>Places</option>
                    </select>
                    <br> </div>
                <div class="locdist">
                    <label class="inline" for="location">Location: </label>
                    <input type="text" id="location" name="location" class="locInput" value="<?php echo isset($_POST['location']) ? $_POST['location'] : '' ?>">
                    <label class="inline" for="distance">Distance (meters): </label>
                    <input type="text" id="distance" name="distance" class="formItem" value="<?php echo isset($_POST['distance']) ? $_POST['distance'] : '' ?>"> </div>
                <div class="buttons">
                    <button type="submit" name="submit">Search</button>
                    <button type="button" onclick="clearForm()">Clear</button>
                    <br> </div>
            </form>
        </div>
        <?php
    
        if(isset($_POST["submit"]) && ($_POST["identifier"]=="formsubmit")) {
               
            if($_POST["type"] != "place"){
                $request="/search?q=".$keyword."&type=".$type."&fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name, picture}},posts.limit(5)";
            }else{
                $key="AIzaSyAmeX_r_myO18mgpbzdrcLjnfYH7xD7Ml0";
                if($location != "" && isset($location)){
                $urlgoogle="https://maps.googleapis.com/maps/api/geocode/json?address=".$location."&key=".$key;
                $google= json_decode(file_get_contents($urlgoogle),true);
                $lat = ($google['results'][0]['geometry']['location']['lat']);
                $lng = ($google['results'][0]['geometry']['location']['lng']);
                $request="/search?q=".$keyword."&type=".$type."&center=".$lat.",".$lng."&distance=".$distance."&fields=id,name,picture.width(700).height(700)";
                }else{
                    $request="/search?q=".$keyword."&type=".$type."&fields=id,name,picture.width(700),albums.limit(5){name,photos.limit(2){name, picture}},posts.limit(5)";
                }
                }
           
            $response = $fb->get($request);
            $arrayResponse = $response -> getDecodedBody();
            if(sizeOf($arrayResponse['data']) == 0){
                echo "<div class=\"tableDiv\">No Records have been found</div>";
            }else{    
                echo "<div class=\"tableDiv\">";
                echo "<table border=\"1\" width=\"100% \"><thead>";
                echo "<tr>";
                echo "<th width=\"30%\" class=\"header\">Profile Photo</th>";
                echo "<th width=\"50%\" class=\"header\">Name</th>";
                echo "<th width=\"20%\" class=\"header\">Details</th>";
                echo "</tr></thead><tbody>";
                
                for($i=0;$i<sizeOf($arrayResponse['data']);$i++){
                    $name = isset($arrayResponse['data'][$i]['name'])?$arrayResponse['data'][$i]['name']:"";
                    $id = isset($arrayResponse['data'][$i]['id'])?$arrayResponse['data'][$i]['id']:"";
                    $pictureURL=isset($arrayResponse['data'][$i]['picture']['data']['url'])?$arrayResponse['data'][$i]['picture']['data']['url']:"";
                
                    
                    echo "<tr><td><img onclick=imgOpen(this.src) width=40 height=30 src=".$pictureURL."></td>";
                    echo "<td>".$name."</td>";
                    //make this a form and resubmit
                    echo "<td id=\"cellD\"><form action=\"index.php\" name=\"form\" method=\"post\"><input type=\"hidden\" name=\"identifier\" value=\"detailssubmit\"></input><input type=\"hidden\" name=\"id\" value=\"".$id."\"><input type=\"hidden\" name=\"keyword\" value=\"".$keyword."\"></input><input type=\"hidden\" name=\"type\" value=\"".$type."\"></input><input type=\"hidden\" name=\"location\" value=\"".$location."\"></input><input type=\"hidden\" name=\"distance\" value=\"".$distance."\"></input><input class=\"subttn\" type=\"submit\" name=\"details\" value=\"Details\"></input></form></td></tr>";
         
                  
                }
                
                
                echo "</tbody></table>";
                echo "</div>";
                   echo  "<div type=\"hidden\" id=\"wub\"></div>"; //dummycode
                   //echo "<div type=\"hidden\" id=\"postsdiv\"></div>";
                
                
                //new tables originally hidden
                // div, div, table (hide rows),div, table(hiderows)
            }
           
            
        }
                           
        if(isset($_POST["identifier"]) && $_POST["identifier"]=="detailssubmit") {
            error_reporting(~E_NOTICE);
            $keyword =isset($_POST["keyword"])? $_POST["keyword"]:"";
            $type =  isset($_POST["type"])? $_POST["type"]:"";
            $location = isset($_POST["location"])? $_POST["location"]:"";
            $distance = isset($_POST["distance"])? $_POST["distance"]:"";
            
            $id=$_POST["id"];
            
            if($type != "event") {
            $request="/".$id."?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name, picture}},posts.limit(5)";
            $response = $fb->get($request);
            $arrayResponse = $response -> getDecodedBody();
                
            }else {
                $request="/".$id."?fields=id,name,picture.width(700).height(700)";
            }
            
            $name = isset($arrayResponse['name'])?$arrayResponse['name']:"";
           
            //populate albums
                   
                    $albums=isset($arrayResponse['albums']['data'])?$arrayResponse['albums']['data']:"";
                    $albumdata=array();
                    if($albums!==""){
                        for($j=0;$j<sizeOf($albums);$j++){
                            //get name
                                
                                    if(isset($albums[$j]['name'])){
                                        $albumName=$albums[$j]['name'];
                                        array_push($albumdata,$albumName);
                            }
                            //get pictures
                            if(isset($albums[$j]['photos']['data'])){
                               
                                for($k=0;$k<sizeOf($albums[$j]['photos']['data']);$k++){
                                    $picid=$albums[$j]['photos']['data'][$k]['id'];
                                    $rpic="https://graph.facebook.com/v2.8/".$picid."/picture?access_token=".$accessToken;
                                    $picurl=$rpic;      
                                    array_push($albumdata,$picurl);
                                }
                            }
                           
                            
                        }
                        //structure of albumPass=(albumdata(name,photo,photo,name,photo,photo,...))
                        //structure of postsPass=((1,2,3,4,5))
                       
                    }else{$albumdata="";}
                    
                    //populate posts
                    $posts=isset($arrayResponse['posts']['data'])?$arrayResponse['posts']['data']:"";
                    $postsarray=array();
                    if($posts!=""){
                        for($j=0;$j<sizeOf($posts);$j++){
                            if(isset($posts[$j]['message'])){
                                $message=$posts[$j]['message'];
                                array_push($postsarray,$message);
                        }
                    }
                        
                    }else{$postsarray="";}
            
            
            //generate html tables
            echo "<div id=\"wub\">";
            echo "<div id=\"albumsdiv\">";
            if($albumdata !=""){
                $albumsLink= "albumsLink";
                
                echo "<a id=\"albumsLink\" href=\"#!\" onClick=\"javascript:toggleAlbums(this.id)\">Albums</a></div>";
                echo "<div id=\"containertableA\">";
                echo "<table id=\"tableA\" border=\"1\"><thead><title=\"albumtable\"></thead><tbody>";
                
                for($i=0;$i<sizeOf($albumdata);$i+=3){
                       
                           
                           if($albumdata[$i+1]!="" && isset($albumdata[$i+1])){
                               echo "<tr><td><a id=\"row".$i."\" href=\"#!\" onClick=toggle(this.id)>".$albumdata[$i]."</a></td></tr>";
                               echo "<tr class=\"rowImgAlb\"><td><img onClick=imgOpen(this.src) width=80 height=80  src=".$albumdata[$i+1].">";
                               if($albumdata[$i+2]!="" && isset($albumdata[$i+2])) {
                               echo "<img onClick=imgOpen(this.src) width=80 height=80  src=".$albumdata[$i+2]."></td></tr>";
                               }
                           
                               
                           }else{
                                  //no pics
                                   echo "<tr><td>".$albumdata[$i]."</td></tr>";
                               }
                           
                    
                }
                echo "</tbody></table></div>";
            }else{ echo "No Albums have been found</div>"; }
            
            echo "<div id=\"postsdiv\">";
             if($postsarray !=""){
                 $postsLink = "postsLink";
                 if(sizeOf($postsarray) ==0){echo "Posts</div>";}
                 else { echo "<a id=\"postsLink\" href=\"#!\" onClick=\"javascript:togglePosts(this.id)\">Posts</a></div>";}
                 echo "<div id=\"containertableP\">";
                 echo "<table id=\"tableP\" border=\"1\"><thead><title=\"poststable\"></thead><tbody>";
                 for($i=0;$i<sizeOf($postsarray);$i++){
                 if($postsarray[$i]!="") echo "<tr><td>".$postsarray[$i]."</td></tr>";
                 }
                 echo "</tbody></table></div>";
            }else{ echo "No Posts have been found</div>"; }
            echo "<div type=\"hidden\" class=\"tableDiv\"></div>";//dummy code
           
            echo "</div>";
        }
        

    ?>
            <script>
                function toggleAlbums(id) {
                    x = document.getElementById("containertableA");
                    x.style.display = x.style.display == "block" ? "none" : "block";
                    document.getElementById("containertableP").style.display = "none";
                    return false;
                }

                function togglePosts(id) {
                    x = document.getElementById("containertableP");
                    x.style.display = x.style.display == "block" ? "none" : "block";
                    document.getElementById("containertableA").style.display = "none";
                    return false;
                }

                function toggle(id) {
                    x = document.getElementById(id).parentElement.parentElement.nextElementSibling;
                    x.style.display = x.style.display == "block" ? "none" : "block";
                    return false;
                }
            </script>
    </body>

</html>