var dialog = {
    // 错误弹出层
    error: function(message) {
        layer.open({
            content:message,
            icon:2,
            title : msg.err_msg,
        });
    },

    //成功弹出层
    success : function(message,url) {
        layer.open({
            title : msg.tip_msg,
            content : message,
            icon : 1,
            yes : function(){
                location.href=url;
            },
        });
    },

    // 确认弹出层
    confirm : function(message, url) {
        layer.open({
            title : msg.tip_msg,
            content : message,
            icon:3,
            btn : [msg.yes,msg.no],
            yes : function(){
                location.href=url;
            },
        });
    },

    //无需跳转到指定页面的确认弹出层
    toconfirm : function(message) {
        layer.open({
            title : msg.tip_msg,
            content : message,
            icon : 1,
            btn : [msg.determine],
        });
    },
}

