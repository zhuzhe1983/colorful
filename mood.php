<?php
    if(isset($_GET['keyword'])){
        $keyword = $_GET['keyword'];
    }else{
        $keyword = 'green';
    }

?>
<!DOCTYPE html>
<html>
<head>
  <style>
  *{ margin:0; padding:0; list-style-type:none;}
  img{ height:150px; display:block;}
  #images li li{height:100px;width:100px;display:inline-block;}
  </style>
  <script src="http://cn.yimg.com/js/jquery/jquery-1.6.4.min.js"></script>
</head>
<body>
    <form method="get">
        <input name="keyword" value="<?php echo $keyword;?>" /> keyword
        <input type="submit" value="submit" />
        <p>please select the picture which matchs your search.</p>
    </form>
    <ul id="images"></ul>
<script>
$(document).ready(function(){
    $('#images img').live('click',function(){
        if($(this).data('loaded')=='true'){
            return;
        }else{
            $(this).data('loaded','true');
        }
        var url = $(this).attr('src');
        var li = $(this).parent();
        $.getJSON('service.php?jsonp=?&url='+url,function(data){
            for(var color in data){
                li.append($("<li/>").css('background',color).attr('title',color));
            }
        });
    });

    $.getJSON("http://api.flickr.com/services/feeds/photos_public.gne?jsoncallback=?",{
        tags: "<?php echo $keyword;?>",
        tagmode: "any",
        lang: "zh-hk",
        format: "json"
    },function(data) {
        $.each(data.items, function(i,item){
            var url = item.media.m;
            $("<li><img src='"+url+"'/></li>").appendTo("#images").find('img').click();
            //if ( i == 3 ) return false;
        });
    });

});
</script>
</body>
</html>