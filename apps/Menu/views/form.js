var menuForm = {
    autoWidth: true,
    classes: '',
    data: {},
    fields: [{
        label: '编号',
        name: 'id',
        type: 'hidden'
    }, {
        label: '分组',
        name: 'category_id',
        type: 'select',
        sources: {}
    }, {
        label: '名称',
        name: 'title'
    }, {
        label: '链接',
        name: 'url'
    }, {
        label: '链接目标',
        name: 'target',
        value: '_self'
    }, {
        label: '顺序',
        name: 'order',
        value: 50
    }],
    buttons: [{
        label: '提交',
        type: 'submit'
    },{
        label: '重置',
        type: 'reset'
    }]
};