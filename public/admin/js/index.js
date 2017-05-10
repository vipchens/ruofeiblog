window.onload = function () {
    var contentBody = document.getElementsByClassName('content-body'),
        selectAll = document.getElementsByClassName('selectAll'),
        selectItem = document.getElementsByClassName('select-item'),
        selectTotal = document.getElementsByClassName('selectTotal'),

        order = document.getElementsByClassName('order'),

        // 获取表格hang元素
        row = contentBody[0].children;


    // //点击单元格时自动选中此行数据
    for(var i = 0; i<row.length;i++){
        for(var x = 0; x<row[i].cells.length;x++){
            if(x!=5){
                row[i].cells[x].onclick = function () {
                    stopBubbling(event);
                    if(this.parentNode.getElementsByTagName('input')[0].checked){
                        this.parentNode.getElementsByTagName('input')[0].checked = false;
                        selectAll[0].checked = false;
                    }else{
                        this.parentNode.getElementsByTagName('input')[0].checked = true;
                    }
                    select_total();
                };
            }
        }

    }

    // //点击表格行时自动选中此行数据
    // for(var i = 0; i<row.length;i++){
    //     row[i].onclick = function () {
    //         if(this.children[0].getElementsByTagName('input')[0].checked){
    //             this.children[0].getElementsByTagName('input')[0].checked = false;
    //             selectAll[0].checked = false;
    //         }else{
    //             this.children[0].getElementsByTagName('input')[0].checked = true;
    //         }
    //         select_total();
    //     };
    // }

    //全选复选
    for(var i = 0; i<selectItem.length;i++){
        selectItem[i].onclick = function () {
            //由于行元素和input元素都有click事件，所以调用阻止冒泡时间
            stopBubbling(event);
            if(this.className.indexOf('selectAll')>0){
                if(!this.checked){
                    for(var j = 0; j<selectItem.length;j++) {
                        selectItem[j].checked = false;
                    }
                }else {
                    for(var j = 0; j<selectItem.length;j++) {
                        selectItem[j].checked = true;
                    }
                }
            }
            if(!this.checked){
                selectAll[0].checked = false;
            }
            select_total();
        }
    }

    // 选择计数
    function select_total() {
        var Total = 0;
        var array = [];
        for(var j = 0; j<row.length;j++){
            if(row[j].children[0].getElementsByTagName('input')[0].checked){
                // 获取checkbox name值为文章id，存为数组
                array.push(row[j].children[0].getElementsByTagName('input')[0].getAttribute('name'));
                Total++;
            }
        }

        selectTotal[0].style.display = 'inline-block';
        selectTotal[0].children[0].innerHTML = Total;

        //输出选中数据的id
        console.log(array);
    }


    //阻止事件 冒泡传播
    function stopBubbling(e) {
        e = window.event || e;
        if (e.stopPropagation) {
            e.stopPropagation();      //阻止事件 冒泡传播
        } else {
            e.cancelBubble = true;   //ie兼容
        }
    }

}