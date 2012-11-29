<?php

function rgbhex($red,$green,$blue) {
    $red = str_pad(dechex($red), 2, "0", STR_PAD_LEFT);
    $green = str_pad(dechex($green), 2, "0", STR_PAD_LEFT);
    $blue = str_pad(dechex($blue), 2, "0", STR_PAD_LEFT);
    return "#".strtoupper($red.$green.$blue);
}

function hexrgb($color){
    $color = str_replace('#','',$color);
    $r = substr($color,0,2);
    $g = substr($color,2,2);
    $b = substr($color,4,2);
    return array(hexdec($r),hexdec($g),hexdec($b));
}

//return all nearby colors which difference less than $offset comparing to $color;
function color_close($color,$offset,$hex){
    $arr = array();
    list($center_r,$center_g,$center_b) = hexrgb($color);
    for($r = $center_r - $offset;$r <= $center_r + $offset; $r++){
        if($r < 0){
            continue;
        }
        for($g = $center_g - $offset;$g <= $center_g + $offset; $g++){
            if($g < 0){
                continue;
            }
            for($b = $center_b - $offset;$b <= $center_b + $offset; $b++){
                if($b < 0){
                    continue;
                }
                if($hex == false){
                    $arr[] = array($r,$g,$b);
                }else{
                    $arr[] = rgbhex($r,$g,$b);
                }
            }
        }
    }
    return $arr;
}

function color_group($list,$offset){
    //获取所有颜色名称
    $color_names = array_keys($list);

    //将所有颜色转变成为rgb数组
    $colors = array();
    for($i=0; $i<count($color_names); $i++){
        $colors[$i] = hexrgb($color_names[$i]);
    }

    //双重循环比较颜色之间差距，将相近的颜色放到某个颜色点周围
    $group = array();
    for($i=0; $i<count($colors); $i++){
        $color_one = $colors[$i];
        for($o=0; $o<count($colors); $o++){
            $color_two = $colors[$o];
            $distance = color_distance($color_one,$color_two);
            if($distance < $offset){
                if(!isset($group[rgbhex($colors[$i])])){
                    $group[rgbhex($color_one)] = array();
                }
                $group[rgbhex($color_one[0],$color_one[1],$color_one[2])][] = rgbhex($color_two[0],$color_two[1],$color_two[2]);
            }
        }
    }

    //看哪个颜色点周围的颜色最多，对数组进行排序。
    $group_cnt = array();
    foreach($group as $color => $close_colors){
        $group_cnt[$color] = count($close_colors);
    }

    arsort($group_cnt);

    //从颜色最多的开始，把数据合并到最多的那个点，重复的点不再计算
    $color_used = array();
    $return = array();
    foreach($group_cnt as $color => $cnt){
        if(in_array($color,$color_used)){
            continue;
        }
        $close_colors = $group[$color];
        foreach($close_colors as $color_temp){
            if(!isset($return[$color])){
                $return[$color] = 0;
            }

            $return[$color] = $return[$color] + $list[$color_temp];
            $color_used[] = $color_temp;
        }
    }

    arsort($return);
    return array($return,$group);
}

function color_distance($from,$to){
    return sqrt(abs($from[0]-$to[0]))+sqrt(abs($from[1]-$to[1]))+sqrt(abs($from[2]-$to[2]));
}

function color_group_url($file,$offset,$close,$exclude){
    if(stripos($file,'.jpg')){
        $im = imagecreatefromjpeg($file);
    }else if(stripos($file,'.png')){
        $im = imagecreatefrompng($file);
    }else if(stripos($file,'.gif')){
        $im = imagecreatefromgif($file);
    }else{
        exit('not supported image type');
    }

    $width = imagesx($im);
    $height = imagesy($im);

    $offset = $width / $offset;

    $list = array();
    for($y=0;$y<$height;$y=$y+$offset){
        for($x=0;$x<$width;$x=$x+$offset){
            $rgb = imagecolorat($im, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            $hex = rgbhex($r,$g,$b);
            if(isset($list[$hex])){
                $list[$hex] ++;
            }else{
                $list[$hex] = 1;
            }
        }
    }
    return color_group($list,$close);
}