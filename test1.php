<?php
include("variables.php");
function getContent($url,$conf)
{
	//ini_set('max_execution_time', 300);
	include("variables.php");
	$html = "------------$url-----------";
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
	$conn=mysql_connect("localhost", $DBUSER, $DBPASS);
	mysql_select_db($DBNAME,$conn);
	$query="update links set Scanned=1 where Name='$url'";
	mysql_query($query);
	//echo $url."<br>";
	curl_close ($ch);
	$f=fopen("/var/www/Crawler/flex/in.txt","w");
	$wr = fwrite($f,$html);
	fclose($f);
	$out = "";
	exec("./flex/dates < flex/in.txt",$out,$ret);
	//var_dump($out);
	//$conf="Model Driven Engineering Languages and Systems";
	//var_dump($out);
	$pos = strpos($out, $in);
	/*if($pos != false)
	{
		echo "GOT IT----------";
		$pos=$pos-10;
		while($j<10)
			echo $out[$pos+$j];
		echo "<br>";
	}*/
	
	

        if(count($out)>2)
        {
                foreach ($out as $in)
                {
                        echo "$in<br>";
			if(strpos($in,$conf)!==false)
			{
                		$pos = array_search($in, $out);
				echo "<br><br>Paper Submission Deadline for ".$out[$pos]."----- : ".$out[$pos-1];
				/*$pos=$pos-10;
				$j=0;
				while($j<10)
                        	{
					echo $out[$pos+$j];
                			$j++;
				}*/
				echo "<br>";
			}
		}
	}
	
	/*
	$len=count($out);
	foreach ($i as $out)
        {
		echo "*********************".$i;
		break;
	}
	foreach ($i as $out)
	{
		if($i=='\n')
			$j++;
		else
			$str[$j]=$str[$j].$i;
	}
	for($k=1;$k<$j;$k++)
	{
		$str2=$str[$k-1];
		if($str[$k]==$conf)
			echo "RESULT : <br>$str2";

	//	if($wr==false)
	//		echo "FALSE\n";
	//	$f1=fopen("/var/www/Crawler/flex/in1.txt","w");
        //	fwrite($f1,$html);		
	}*/		
	//return $html;
}

function urlLooper($url,$base,$level){
		include("variables.php");
        $urlArray = array();
	//echo "---------Looper---------".$url;
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

	if(! $result = curl_exec($ch))
	{
		echo "<br>you are fucked in ths link $url";
		//die("<br>Curl failed: " . curl_error($ch));
		echo "<br>Curl failed: " . curl_error($ch);
	}
	$regex='|<a.*?href="(.*?)"|';
        preg_match_all($regex,$result,$parts);
        $links=$parts[1];
        foreach($links as $link){
            array_push($urlArray, $link);
        }
        curl_close($ch);
	$conn=mysql_connect("localhost", $DBUSER, $DBPASS);
        mysql_select_db($DBNAME,$conn);
        foreach($urlArray as $value){
            //echo $value . '<br />';
			//$conn=mysql_connect("localhost", $DBUSER, $DBPASS);
			//mysql_select_db($DBNAME,$conn);
			//$q="select * from links where Name='$url'";
			//$r=mysql_query($q);
			//$a=mysql_fetch_array($r);
			if($value[0]=="/")
			{	//echo "**************----$value";
				$value=$base.$value;	
			}
			if($value[0]!="h")
                        {       //echo "**************----$value";
                                $value=$base.$value;
                        }
			$c=$level+1;
			echo $value."<br>";
			//$pos=strrpos($va1lue,"?");
			
			//echo "<br>QMark------------$pos--------$value<br>";
			/*$temp=strstr($value,"?",true);
			if($value!=$temp)
			{
				//echo "--------------------$temp";
				$q="select * from links where Name='$temp'";
				$r=mysql_query($q);
				$numr=mysql_num_rows($r);
				if($numr>0)
				{
					$a=mysql_fetch_array($r);
					$c=$a['level'];
				}	
			}*/
			$q="select * from links where Name='$value'";
			//echo "$q";
			$r=mysql_query($q);
			if(!mysql_num_rows($r))
			{
				//echo "-------in<br>";	
				$query="insert into links (Name, Scanned, parent, level) values ('$value',0,'$url',$c)";
				mysql_query($query);
			}
			//else
				//echo "-------out<br>";
		}
}
	
$agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
//$url = "http://www.ieee.org/web/conferences/callforpapers/index.html";
//$url = "http://www.ieee.org/web/conferences/callforpapers/index.html?var1=&var2=&var3=&loc=10$20$1";
//$base="http://www.ieee.org";
/*$url="http://www.confsearch.org/confsearch/";
$base="http://www.confsearch.org";
$depth=2;
$html=getContent($url);
//echo $html;
$conn=mysql_connect("localhost", $DBUSER, $DBPASS);
mysql_select_db($DBNAME,$conn);
$q="insert into links (Name, Scanned, parent, level) values ('$url',1,'http://',0)";
mysql_query($q);
urlLooper($url,$base,0);
*/
$depth=2;
include("google.php");
$query="select * from links where Scanned=0 and level<$depth";
//$res=mysql_query($query);
while($arr=mysql_fetch_array(mysql_query($query)))
{
	$url=$arr['Name'];
	$level=$arr['level'];
	$conf = $_GET['name'];
	$html=getContent($url,$conf);
	//echo $html;
	for($j=8;$j<strlen($url);$j++)
	{
		if($url[$j]=='/')
			break;
	}
	$base = substr($url,0,$j);
	if($level<$depth-1)
	{	//echo "*<br>";
		urlLooper($url,$base,$level);
	}
}

?>
