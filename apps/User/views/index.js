jQuery(function($){
    $('#qw-jqgrid').jqGrid({
        sortorder: 'id',
        datatype: 'json',
        url: '?module=user',
        width: '100%',
        height: 'auto',
        forceFit: true,
        autowidth: true,
        rownumbers: true,
        multiselect: true,
        viewrecords: true,
        pager: '#qw-jqgrid-pager',
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
            formatter: function(){
                return '1';
            }
        }]
    }).jqGrid('navGrid', '#qw-jqgrid-pager', {
        add : false,
        edit : false,
        del : false,
        search : false
    }).jqGrid('fullContainer');
});