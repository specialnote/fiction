$(function () {
    //首页站内搜索验证
    var search_form = $('#search_form');
    if (search_form) {
        search_form.validate({
            rules: {
                search_key: {
                    required: true,
                    minlength: 2
                }
            },
            messages: {
                search_key: {
                    required: "请输入需要搜索内容",
                    minlength: "请至少输入两个文字"
                }
            },
            onkeyup: false,
            onfocusout: false,
            errorPlacement: function (error, element) {
                if (error[0] && $(error[0]).text()) {
                    if (layer) {
                        layer.msg($(error[0]).text());
                    }
                }
            }
        })
    }
});

