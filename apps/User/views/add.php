<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->options['charset'] ?>" />
<title></title>
<?php
$minify->add(array(
    $jQuery->getTheme($this->options['theme']),
    $jQuery->get('jquery, ui, effects, metadata, qui, button, ui.form, datepicker, selectmenu, validate'),
    $jQuery->getDir() . '/jqGrid/i18n/grid.locale-cn.js',
    $this->getFile('views/style.js'),
    $this->getFile('views/style.css'),
));
?>
</head>
<body>
    <form id="t"></form>
<script type="text/javascript">
jQuery(function($){
    /*$('#t').form({
        fields: [{
            fields: [{
                name: 'n',
                type: 'text'
            }, {
                type: 'label',
                value: 'Clike me!'
            }]
        }]
    });*/
    $('#user-form').validate({
        rules: {
            username: {
                required: true,
                minlength: 2
            },
            password: {
                required: true,
                minlength: 5
            }
        },
        highlight: function(input) {
            $(input).addClass('ui-state-highlight');
        },
        unhighlight: function(input) {
            $(input).removeClass('ui-state-highlight');
        }
    });
    $('#user-form').form({
        title : '测试表单',
        width: 1200,
        style: {
            margin: '20px 200px'
        },
        data: {
            to2: 123
        },
        fieldDefaults: {
            type: 'text',
            attr: []
        },
        labelDefaults: {
            width: 55
        },
        fieldSetDefaults: {

        },
        afterRendered: function(){
            $('#birthday').datepicker();
            $('#username3').selectmenu({
                transferClasses: false
            });
            $('#button6').click(function(){
                $(this).prev().val('');
            });
        },
        fields: [{
            labelDefaults: {
                separator: '#'
            },
            fields: [{
                    type: 'label',
                    value: '('
            }, {
                    name: 'username',
                    label: false
                }, ')']
            }, {
                type: 'fieldGroup',
                labelDefaults: {
                    width: 30
                },
                label: {
                    title: '电话',
                    target: 'fff'
                },
                fields: [{
                    fields: [
                        '(',
                        {
                            name: 'fff',
                           label: false,
                           width: 30
                        },
                        ')',
                        {
                            label: false,
                            width: 30
                        },
                        '-',
                        {
                            label: false,
                            width: 60
                        }
                    ]
                }]
            }, {
            fields: [{
                //label: false,
                type: 'hidden',
                name: 'hidden filed',
                value: 'secret'
            }, {
                name: 213
            }]

        }, {
                type: 'fieldGroup',
                label: {
                    title: '界面配置',
                    target: 'email1'
                },
                fields: [{
                    fields: [{
                        label: false,
                        name: 'email1'
                    }, {
                        name: 'email2'
                    }]
                }]
            }, {
                fields: [{
                    label: {
                        title: 'ttt'
                    }
                }, {
                    type: 'label',
                    width: 5,
                    value: '',
                    style: {
                        padding: 0
                    }
                }, {
                    label: false
                }]
            }, {
            fields: [{
                type: 'text',
                name: 'id',
                label: '1',
                value: 'input ',
                //width: 50,
                required: true,
                formatter: function(value){
                    return value + 'formatted!'
                },
                buttons: [{
                    icon: 'ui-icon-arrowreturnthick-1-w'
                }, {
                    icon: 'ui-icon-search'
                }]
            }, {
                fields: [{
                    type: 'text',
                    name: 'id',
                    label: '2-1',
                    value: 'input ',
                    //width: 50,
                    required: true,
                    formatter: function(value){
                        return value + 'formatted!'
                    },
                    buttons: [{
                        icon: 'ui-icon-arrowreturnthick-1-w'
                    }, {
                        icon: 'ui-icon-search'
                    }]
                }, {
                    type: 'text',
                    name: 'id2',
                    label: '2-2',
                    value: 'input ',
                    //width: 200,
                    required: true,
                    formatter: function(value){
                        return value + 'formatted!'
                    },
                    buttons: [{
                        icon: 'ui-icon-arrowreturnthick-1-w'
                    }]
                }]
            }]
        }, {
            fields: [{
                type: 'text',
                name: 'id',
                label: '2',
                value: 'input ',
                width: 50,
                required: true,
                formatter: function(value){
                    return value + 'formatted!'
                },
                buttons: [{
                    icon: 'ui-icon-arrowreturnthick-1-w'
                }, {
                    icon: 'ui-icon-search'
                }]
            }, {
                type: 'text',
                name: 'id2',
                label: '2-2',
                value: 'input ',
                //width: 200,
                required: true,
                formatter: function(value){
                    return value + 'formatted!'
                },
                buttons: [{
                    icon: 'ui-icon-arrowreturnthick-1-w'
                }]
            }, {
                fields: [{
                    label: '2-3-1'
                }, {
                    label: '2-3-2'
                }]
            }]
        }, {
            type: 'text',
            name: 'id2',
            label: '3',
            value: 'input ',
            //width: 200,
            required: true,
            formatter: function(value){
                return value + 'formatted!'
            },
            buttons: [{
                icon: 'ui-icon-arrowreturnthick-1-w'
            }]
        }, {
                name: 'group_id',
                readonly: true,
                type: 'file'
            }, {
                name: 'username',
                type: 'select',
                sources: {
                    1: '请选择',
                    2: '第二项',
                    3: '请仔细阅读规定请仔细阅读规定请仔细阅读规定请仔细阅读规定请仔细阅读规定请仔细阅读规定请仔细阅读规定请仔细阅读规定请仔细阅读规定', 4: '蓝色', 5: '蓝色', 6: '蓝色', 7: '蓝色', 8: '蓝色',
                    9: '蓝色', 10: '蓝色', 11: '蓝色'
                },
                buttons: [{
                    icon: 'ui-icon-arrowreturnthick-1-w',
                    text: '撤销'
                }, {
                    icon: 'ui-icon-search',
                    text: '撤销'
                }]
            }, {
                name: 'username3',
                type: 'select',
                sources: {
                    1: '请选择',
                    2: '第二项',
                    3: '请仔细阅读规定请仔细阅读规定请仔细阅读规定请仔细阅读规定请仔细阅读规定请仔细阅读规定请仔细阅读规定请仔细阅读规定请仔细阅读规定', 4: '蓝色', 5: '蓝色', 6: '蓝色', 7: '蓝色', 8: '蓝色',
                    9: '蓝色', 10: '蓝色', 11: '蓝色'
                }
            }, {
                name: 'password',
                type: 'password',
                value: '124'
            }, {
                name: 'sex',
                type: 'radio',
                sources: {
                    3: '蓝色', 4: '蓝色', 5: '蓝色', 6: '蓝色', 7: '蓝色', 8: '蓝色',
                    9: '蓝色', 10: '蓝色', 11: '蓝色'
                }
            }, {
                name: 'birthday',
                afterRender: function() {

                }
            }, {
                name: 'language',
                type: 'checkbox',
                sources: {
                    3: '请仔细阅读规定', 4: '蓝色', 5: '蓝色', 6: '蓝色', 7: '蓝色', 8: '蓝色',
                    9: '蓝色', 10: '蓝色', 11: '蓝色'
                }
            }, {
                label: '邮箱',
                name: 'email',
                type: 'plain',
                value: '游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客'
            }, {
                label: '邮箱',
                name: 'email',
                type: 'plain',
                label: false,
                value: '游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客游客'
            }, {
                name: 'first_name',
                type: 'checkbox',
                sources: {
                    1: '请仔细阅读规定'
                }
            }, {
                'name': 'last_name'
            }, {
                'name': 'photo',
                label: false
            }, {
                'name': 'reg_ip'
            }, {
                'name': 'theme2'
            }, {
                'name': 'telephone'
            }, {
                'name': 'mobile'
            }, {
                'name': 'homepage'
            }, {
                'name': 'address'
            }, {
            fields: [{
                    name: 'from',
                    label: '从'
                }, {
                    name: 'to',
                    label: '到'
                }]
            }, {
            fields: [{
                    label: false,
                    type: 'fieldSet',
                    title: '界面配置0',
                    fields: [{
                        fields: [{
                                name: 'e1',
                                width: 100
                        }, {
                            name: 'e2'
                        }]
                    }, {
                        name: 'email2'
                    }, {
                        name: 'email3'
                    }, {
                        name: 'email4'
                    }]
                }, {
                    type: 'fieldSet',
                    checkbox: true,
                    label: false,
                    title: '界面配置2',
                    //collapsed: true,
                    fields: [{
                        name: 'email1'
                    }, {
                        name: 'email2'
                    }, {
                        name: 'email3'
                    }, {
                        name: 'email4',
                        type: 'textarea'
                    }]
                }]
            }, {
            fields: [{
                    name: 'first'
                }, {
                    name: 'second'
                }]
            }, {
                type: 'fieldSet',
                //checkbox: true,
                label: false,
                title: '界面配置',
                fields: [{
                    name: 'email1'
                }, {
                    name: 'email2'
                }, {
                    name: 'email3'
                }, {
                    name: 'email4'
                }]
            }, {
                type: 'fieldSet',
                label: false,
                title: '界面配置',
                fields: [{
                    name: 'email1'
                }, {
                    name: 'email2'
                }, {
                    name: 'email3'
                }, {
                    name: 'email4'
                }]
            }, {
                type: 'fieldSet',
                checkbox: true,
                label: false,
                title: '界面配置2',
                fields: [{
                    name: 'email1'
                }, {
                    name: 'email2'
                }, {
                    name: 'email3'
                }, {
                    name: 'email4',
                    type: 'textarea'
                }]
            }, {
                fields: [{
                    type: 'textarea',
                    name: 't1',
                    label: '描述2'
                }, {
                    type: 'textarea',
                    name: 't2',
                    label: '描述'
                }]
            }
        ],
        buttons: [{
                'type': 'submit',
                'label': 'Submit',
                'icon': 'ui-icon-check'
            }, {
                'type': 'reset',
                'label': 'Reset',
                'icon': 'ui-icon-arrowreturnthick-1-w'
            }
     ]});
});
</script>
<form id="user-form" method="post" action="">
</form>
</body>
</html>