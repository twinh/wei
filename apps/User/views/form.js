var userForm = {
    autoWidth: true,
    classes: '',
    fields: [{
        collapsible: false,
        label: false,
        title: '基本信息',
        type: 'fieldSet',
        fields: [{
            label: '分组',
            name: 'group_id',
            type: 'select',
            sources: {}
        }, {
            label: '用户名',
            name: 'username'
        }, {
            label: '邮箱',
            name: 'email'
        }, {
            label: '姓名',
            type: 'fieldGroup',
            fields: [{
                fields: [{
                    label: false,
                    name: 'last_name',
                    width: 50
                }, {
                    type: 'label',
                    value: '&nbsp;',
                    width: 0
                },{
                    label: false,
                    name: 'first_name'
                }]
            }]
        }, {
            label: '性别',
            name: 'sex',
            type: 'radio',
            sources: {
                0: '男',
                1: '女',
                2: '未知'
            }
        }, {
            label: '生日',
            name: 'birthday',
            type: 'datepicker',
            options: {
                dateFormat: 'yy-mm-dd'
            }
        }]
    }, {
        collapsible: false,
        label: false,
        title: '界面配置',
        type: 'fieldSet',
        fields: [{
            label: '主题',
            name: 'theme'
        }, {
            label: '语言',
            name: 'language'
        }]
    }, {
        collapsible: false,
        label: false,
        title: '联系信息',
        type: 'fieldSet',
        fields: [{
            label: '电话',
            name: 'telephone'
        }, {
            label: '手机',
            name: 'mobile'
        }, {
            label: '主页',
            name: 'homepage'
        }, {
            label: '地址',
            name: 'address'
        }]
    }],
    buttons: [{
        label: '提交',
        type: 'submit'
    },{
        label: '重置',
        type: 'reset'
    }]
};