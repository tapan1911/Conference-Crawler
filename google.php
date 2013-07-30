<?php
	$key="AIzaSyDOXsUEqNijGwlQKgOcHtWmKW2jt1NFqec";
	$query="conferencesearch";
	$url="https://www.googleapis.com/customsearch/v1?key=$key&cx=013036536707430787589:_pqjad5hr1a&q=$query&alt=json";
	/*echo $url;
	$urlArray = array();
	$ch = curl_init();
	
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_NOPROGRESS, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $htm = curl_exec($ch);
        $html = $html.$htm;
	echo $html;*/
	/*$f=fopen("/var/www/Crawler/flex/glinks.txt","w");
        $wr = fwrite($f,$html);
        fclose($f);
        $out = "";
        exec("./flex/dates < flex/in.txt",$out,$ret);
	*/
	 $urlArray = array();
        //echo $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if(! $result = curl_exec($ch))
        {
                echo "<br>you are fucked";
                die("<br>Curl failed: " . curL_error($ch));
        }
        //$regex='|<a.*?href="(.*?)"|';
	$regex='|"link": "(.*?)"|';
        preg_match_all($regex,$result,$parts);
        $links=$parts[1];
        foreach($links as $link){
            array_push($urlArray, $link);
        }
        curl_close($ch);
	$conn=mysql_connect("localhost", $DBUSER, $DBPASS);
        mysql_select_db($DBNAME,$conn);
	$m=0;
	foreach($urlArray as $value){
		if($m<3)
		{	
			echo "<br>".$value;
			$q="insert into links (Name, Scanned, parent, level) values ('$value',0,'http://',0)";
			mysql_query($q);
		}
		$m++;
	}
	$m=0;
?>
