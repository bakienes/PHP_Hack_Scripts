<?php
//bacoked by rieqy
ini_set("output_buffering", "Off");
set_time_limit(0);
//:dead
if(isset($_POST['submit'])){
    if(!empty($_POST['domain'])){
        $domain = trim($_POST['domain']);
    }else $domain = null;
    
}else $domain = null;
?>
<html>
<head>
<title>Subdomain Scanner</title>
<meta name="author" content="Baki Enes Yalçın">
<meta name="description" content="Subdomain Scanner">
</head>
<body>
<form action="<?php $_SERVER['PHP_SELF']; ?>" method=POST>
<label for="subdomain">Domain Name</label>&nbsp<input type="text" value="<?php echo $domain; ?>" name="domain" style="width:200px" placeholder="https://beybilisimtasarim.com"><input type="submit" name="submit" value="Scan"><br>
<textarea placeholder="subdomain" rows="20" cols="35" readonly>
<?php
if(isset($domain) && !empty($domain)){
    scan($domain);
}
function curl($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $exec = curl_exec($ch);
    curl_close($ch);
    return $exec;
}
function scan($url){
    $i=1;
    $jum=0;
    $reg = '@^(https?\://)?(www\.)?([a-z0-9]([a-z0-9]|(\-[a-z0-9]))*\.)+[a-z]+$@i';
    if(preg_match($reg, $url)){
        while(1){
            $curl = curl("http://www.bing.com/search?q=domain:".$url."&first=".$i);
            $data = preg_match_all('#\<div class\="sb_meta"\>\<cite\>(.*?)\</cite\>#is', $curl, $m) ? $m[1] : null;
            if($data==null){
                $count=0;
                goto a;
            }
            foreach($data as $dat){
                $dat_ = preg_match("|/|i", $dat) ? strstr($dat, "/", 1) : $dat ;
                $urls[$i][] = $dat_;
            }
            $count = count($urls[$i]);
            $urls_ = array_unique($urls[$i]);
            sort($urls_);
            foreach($urls_ as $url_){
                echo $url_."\n";
                ob_flush();flush();
                $jum++;
            }
            $i=$i+10;
            a:
            if($count<10 || $data==null){
                echo "\nsubdomain piece: ".$jum;
                ob_flush();flush();
                exit;
            }    
        }
    }else{
        echo "URL error";
        ob_flush();flush();
        exit;
    }
}
?>
</textarea>
</form>
</body>
</html> 
