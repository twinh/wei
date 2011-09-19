<!-- 定义样式 -->
<style type="text/css">
    .keyword {
        cursor: pointer;
        color: blue;
    }
    .selected {
        color: red;
    }
</style>
<script type="text/javascript">
    jQuery(function($){
        $('.keyword').click(function(){
            alert($.trim($(this).text()));
            // 切换类
            $(this).toggleClass('selected');
        })
    });
</script>
<div class="keywords">
    <span class="keyword">实习生</span>
    <span class="keyword">实习生</span>
    <span class="keyword">实习生</span>
</div>
