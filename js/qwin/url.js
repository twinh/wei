// JavaScript Document 中文 2010 1 20 - 30
Qwin.extend({
    url : {
        auto : function(set, addition)
        {
            // 返回的地址
            var url = '';
            var set_2 = {};
            if('object' == typeof(set))
            {
                for(var i = 0;i <= 3;i++)
                {
                    if('undefined' == typeof(set[i]) || '' == set[i])
                    {
                        set_2[qurl['nca'][i]] = 'default';
                    } else {
                        set_2[qurl['nca'][i]] = set[i];
                    }
                }
                url = '?' + this.arrayKey2Url(set_2) + qurl['separator'][0] + this.arrayKey2Url(addition);
            } else {
                url = set + '?' + this.arrayKey2Url(addition);
            }
            return url;
        },
        arrayKey2Url : function(arr)
        {
            var url = '';
            for(var i in arr)
            {
                url += this.array2Url(arr[i], i) + qurl['separator'][0];
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
                        url += this.array2Url(arr[key], name + '[' + key + ']') + qurl['separator'][0];
                    } else if(name) {
                        url += name + '[' + key + ']' + qurl['separator'][1] + arr[key] + qurl['separator'][0];

                    } else {
                        url += name + qurl['separator'][1] + arr[key] + qurl['separator'][0];
                    }
                }
            } else {
                return name + qurl['separator'][1] + arr;
            }
            return url.slice(0, -1);
        }
    }
});