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

    if(isset($_GET['offset'])){
        $offset = intval($_GET['offset']);
    }else{
        $offset = 20;
    }


    if(isset($_GET['close'])){
        $close = intval($_GET['close']);
    }else{
        $close = 15;
    }

    list($list,$group) = color_group_url($file,$offset,$close,$exclude);
?>
<div style='position:fixed;right:0;top:0;'>
<form method="get">
    <input name="url" value="<?php echo $file;?>" /> url <br/>
    <input name="bg" value="<?php echo $bg;?>" /> bg <br/>
    <input name="offset" value="<?php echo $offset;?>" /> offset <br/>
    <input name="close" value="<?php echo $close;?>" /> close <br/>
    <input type="submit" value="submit" />
</form>
<img src="<?php echo $file;?>" />
</div>
<?
    foreach($list as $color => $cnt){
        if(!in_array($color,$exclude)){
            echo("$color ($cnt)<div style='height:12px;width:".$cnt."px;box-shadow:2px 2px 5px #969696;background-color:".$color.";'></div><br/>");
            if(isset($group[$color])){
                foreach($group[$color] as $i => $c){
                    echo("<div style='height:12px;width:12px;background-color:$c;display:inline-block;' title='$c'></div>|");
                }
            }
            echo("<hr/>");
        }
    }
?>