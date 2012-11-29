<?php
	set_time_limit(0);

	include('function.php');

    if(isset($_GET['url'])){
	    $file = $_GET['url'];
	}else{
	    $file = "sample.jpg";
	}

    if(isset($_GET['exclude'])){
        $exclude = explode("|",$_GET['exclude']);
    }

    if(isset($_GET['bg'])){
        $bg = $_GET['bg'];
        $exclude = color_close($_GET['bg'], 10, true);
    }

    if(isset($_GET['close'])){
        $close = intval($_GET['close']);
    }else{
        $close = 20;
    }

    $offset = 10;

    list($list,$group) = color_group_url($file,$offset,$close,$exclude);

    if(isset($_GET['jsonp'])){
        echo($_GET['jsonp']."(");
    }

    echo(json_encode($list));

    if($_GET['jsonp']){
        echo(")");
    }
?>