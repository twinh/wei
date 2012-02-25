jQuery(function($){
    $('#user-grid').jqGrid({
        sortorder: 'id',
        datatype: 'json',
        width: '100%',
        height: 'auto',
        forceFit: true,
        autowidth: true,
        rownumbers: true,
        multiselect: true,
        viewrecords: true,
        pager: '#user-grid-pager',
        rowNum: 15,
        rowList: [15, 30, 50, 100, 500],
        colNames: [],
        colModel: [{
            name: 'id',
            hidden: true
        }, {
            name: 'group_id'
        }, {
            name: 'username'
        }, {
            name: 'email'
        }, {
            name: 'sex'
        }, {
            name: 'date_modified'
        }, {
            name: 'operation',
            align: 'center',
            formatter: function(cellvalue, options, rowObject){
                return '<a href="#">编辑</a> | <a href="#">删除</a>';
            }
        }]
    }).jqGrid('navGrid', '#user-grid-pager', {
        add : false,
        edit : false,
        del : false,
        search : false
    }).jqGrid('fullContainer');
});