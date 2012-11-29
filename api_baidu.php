<?php
//not finish yet
$keyword = $_GET['keyword'];
$jsonp = $_GET['jsonp'];
$url = "http://image.baidu.com/i?tn=baiduimagejson&ct=201326592&cl=2&lm=-1&st=-1&fm=result&fr=&sf=1&fmq=1354157448982_R&pv=&ic=0&nc=1&z=&se=1&showtab=0&fb=0&width=&height=&face=0&istype=2&ie=utf-8&word=".urlencode($keyword)."&oe=utf-8&rn=60&pn=120&200275853232.9689&676377331976.1566";

echo($jsonp."(".file_get_content($url).")");

?>