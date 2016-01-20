$(function(){

//全局的loding
$(document).ajaxStart(function() {
  //loading();
});

$(document).ajaxStop(function() {
  finish();
});
$('a').on('click',function(){
    
});
//全局错误处理
$.ajaxSetup({
    beforeSend: function() {
        if($(this).length !== undefined) {
            $(this).prop('disabled', true);
        }
    },
    complete: function() {
        if($(this).length !== undefined) {
            $(this).prop('disabled', false);
        }
    },
    error: function(xhr, textStatus, errorThrown){  
        switch (xhr.status){  
            case(500):  
                alert("服务器系统内部错误");  
                break;  
            case(401):  
                alert("未登录");  
                break;  
            case(403):  
                alert("没有权限执行该操作");  
                break;
            case(404):  
                alert("未找到请求的页面");  
                break;
            case(408):case(502):case(504): 
                alert("请求超时");  
                break; 
            case(429): 
                alert("请求过多,由于限速请求被拒绝。");  
                break;  
              case(0): 
                break;
            default:  
                alert("未知错误");
        }
    }
});
//同一个url的ajax请求，后触发的会取消掉先前触发的
//比如狂点一个按钮触发ajax请求，只有最后一次生效
var pendingRequests = {}; 
$.ajaxPrefilter(function(options, originalOptions, jqXHR ) {
    var key = options.url+options.data;
    var cancel = false;
    if (!pendingRequests[key]) {
        pendingRequests[key] = jqXHR;
    } else {
        cancel = true;
        //jqXHR.abort();                // 放弃后触发的重复提交
        pendingRequests[key].abort();   // 放弃先前触发的提交
    }

    var complete = options.complete;
    options.complete = function(jqXHR, textStatus) {
        pendingRequests[key] = null;
        if ($.isFunction(complete)) {
            complete.apply(this, arguments);
        }
    };
    if (cancel) {
        pendingRequests[key] = jqXHR;
    }
});
$('.abtn').on('click', function(){
    $.ajax({
      method: "POST",
      aysc: true,
      url: "index.php?r=site/test",
      data: { name: "John", location: "Boston" }
    })
    .done(function( msg ) {
        //console.log(this.length === undefined);
    });
    return false;
});

$('.per-page').change(function(){
    var p = $(this).val();
    var link = $(this).data('href');
    window.location.href = link+p;
    
});

});
function loading(){
$('.loading').show();

}
function finish(){
    $('.loading').hide();
}
