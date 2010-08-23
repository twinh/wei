// JavaScript Document 中文 2010 1 20 - 30
Qwin.urlSeparator = {
    0: '&',
    1: '='
};
Qwin.extend({
    url : {
        createUrl : function(array1, array2)
        {
            // TODO: 合并数组1和2
            if('undefined' != typeof(array2))
            {
                for(var i in array2)
                {
                    array1[i] = array2[i];
                }
            }
            return '?' + this.arrayKey2Url(array1);
        },
        arrayKey2Url : function(arr)
        {
            var url = '';
            for(var i in arr)
            {
                url += this.array2Url(arr[i], i) + Qwin.urlSeparator[0];
            }
            return url.slice(0, -1);
        },
        array2Url : function(arr, name)
        {
            var url = '';
            if('object' == typeof(arr))
            {
                for(var key in arr)
                {
                    if('object' == typeof(arr[key]))
                    {
                        url += this.array2Url(arr[key], name + '[' + key + ']') + Qwin.urlSeparator[0];
                    } else if(name) {
                        url += name + '[' + key + ']' + Qwin.urlSeparator[1] + arr[key] + Qwin.urlSeparator[0];

                    } else {
                        url += name + Qwin.urlSeparator[1] + arr[key] + Qwin.urlSeparator[0];
                    }
                }
            } else {
                return name + Qwin.urlSeparator[1] + arr;
            }
            return url.slice(0, -1);
        }
    }
});

function qw_url()
{
    //return Qwin.
}