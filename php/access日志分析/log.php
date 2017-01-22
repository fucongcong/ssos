<?php

$file = file_get_contents("./elb.log");
//匹配模拟user-agent
 preg_match_all("/\"GET.*HTTP\/1.1\"(.*)lb-www\/957362b4be41f72a/", $file, $ips);

//匹配ip
// //  preg_match_all("/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/", $file, $ips);





$res = [];
foreach ($ips[0] as $ip) {
	if (!isset($res[$ip])) {
		$res[$ip] = 1;
	} else {
		$res[$ip]++;
	}
}
echo count($res);

$out = [];
foreach ($res as $ip => $count) {
	if ($count<100) continue;
	if (substr($ip, 0, 7) == "/assets") {
		continue;
	}
	if (substr($ip, 0, 6) == "/admin") {
		continue;
	}
	$out[$count."_".$ip] = $ip;
}

krsort($out, SORT_NUMERIC);

$agents = $out;

foreach ($agents as $agent) {
	//匹配相同user-agent ip数量
	$res = [];
	$allCount = 1;
	$myfile = fopen("elb.log" , "r") or die("Unable to open file!");

	while(!feof($myfile)) {

	    $info = fgets($myfile);

	    if (count(explode($agent, $info)) > 1) {
	    	$allCount++;
	    	preg_match("/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/", $info, $ip);
	    	$ip = $ip[0];
			if (!isset($res[$ip])) {
				$res[$ip] = 1;
			} else {
				$res[$ip]++;
			}
	    }
	}
	fclose($myfile);

	$out = [];

	foreach ($res as $ip => $count) {
		if ($count<5) continue;
		$out[$count."_".$ip] = $ip;
	}

	krsort($out, SORT_NUMERIC);
	print_r($out);
	array_values($out);
	$out = implode(",", $out);
	print_r($out);
	echo "\n";
	echo $allCount;echo "\n";
}


//Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.
//Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko" ECDHE-RSA-AES128-SHA256 TLSv1



// Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .N
// Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko" ECDHE-RSA-AES128-SHA256 TLSv1.2 a
// Mozilla/5.0 (Windows NT 6.2; WOW64; Trident/7.0; rv:11.0) like Gecko" ECDHE-RSA-AES128-SHA256 TLSv1.2 arn:
// Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1
// Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0
// Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727;
// Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.2; WOW64; Trident/7.0; .NET4.0C; .NET4.0E; .NET CLR 2.0.50727; .NET CLR 3.0.3
// Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.2; WOW64; Trident/7.0; .NET4.0E; .NET4.0C)" ECDHE-RSA-AES128-SHA256 TLSv1.2 

// Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.2; WOW64; Trident/7.0; .NET4.0E; .NET4.0C; .NET CLR 3.5.30729;
// Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/7.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729;
// Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729