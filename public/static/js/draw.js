var shap = 2; //0 is circle; 1 is rectangle
var orignalX, orignalY;//the coordinate of mouse down
var lastX, lastY;//the coordinate of last mouse position
var isMouseDown = false; // flag of mouse pressing down
var myCanvas = document.getElementById("myCanvas");
var context = myCanvas.getContext('2d');
var width = screen.width, height =screen.height;
if(width>1200) width=1200;
if(height>800) height=800;
myCanvas.setAttribute("width",width);//初始化宽
myCanvas.setAttribute("height",height);//初始化高

var postArr=[];
var length=0;

var data;//storing last canv2as' content
context.strokeStyle = "black";
context.strokeWidth = 1;
context.lineWidth = 1;


document.getElementById('color').onchange = function () {
    context.strokeStyle = this.value
};

/**
 * 橡皮
 */
function doEraser() {
    context.strokeStyle = "white";
    shap = 2;
}

/**
 * 线条粗细
 */
function sizeChange() {
    context.lineWidth = parseInt(document.getElementById('size').value);

}

/**
 * 透明度
 */
function globalChange(){
    context.globalAlpha=parseInt(document.getElementById('global').value)/100;
}

function shapeChange() {
    var myselect = document.getElementById("shape");
    var index = myselect.selectedIndex;
    var myvalue = myselect.options[index].value;
    var mytext = myselect.options[index].text;
    shap = parseInt(myvalue);

}

/**
 * 清除画布
 */
function clearCanvas(){
    data = context.getImageData(0, 0, width, height);
    postArr=[];
    length=0;
    restore.push(data);
    context.clearRect(0,0,width,height);
}


/**
 撤销
 */
var restore = [];

function back() {
    context.putImageData(restore.pop(), 0, 0);
    postArr.pop();
    length--;
}

/**
 鼠标按下
 */
function myCanvasMouseDown(event) {
    isMouseDown = true;
    if(shap==3){
        lastX = event.offsetX;
        lastY = event.offsetY;
        var colorData = document.getElementById("myCanvas").getPixelColor(lastX, lastY);
        console.log(colorData);
        // 获取该点像素的数据
        return;
    }
    //event.preventDefault();
        data = context.getImageData(0, 0, width, height);
        orignalX = event.offsetX;
        orignalY = event.offsetY;
        if(orignalX==null){
            orignalX = event.targetTouches[0].pageX;
            orignalY = event.targetTouches[0].pageY-140;
        }
        context.moveTo(orignalX, orignalY);
        postArr[length]={};
        postArr[length]['line']=[];
        postArr[length]['line'].push([orignalX,orignalY]);
        postArr[length]['status']=shap;
        postArr[length]['color']=context.strokeStyle;
        postArr[length]['size']=context.lineWidth;
        postArr[length]['global']=context.globalAlpha;
        restore.push(data);

}

/**
 鼠标移动
 */
function myCanvasMouseMove(event) {
    if (isMouseDown) {
        //event.preventDefault();

        switch (shap) {
            case 0:
                context.clearRect(0, 0, width, height);
                context.putImageData(data, 0, 0);
                lastX = event.offsetX;
                lastY = event.offsetY;
                postArr[length]['line'].push([lastY,lastY]);
                context.beginPath();
                context.arc(orignalX + (lastX - orignalX) / 2, orignalY + (lastY - orignalY) / 2, Math.abs(lastX - orignalX) / 2, 0, Math.PI * 2, true);
                context.stroke();
                context.closePath();
                break;
            case 1:
                context.clearRect(0, 0, width, height);
                context.putImageData(data, 0, 0);
                lastX = event.offsetX;
                lastY = event.offsetY;
                postArr[length]['line'].push([lastY,lastY]);
                context.strokeRect(orignalX, orignalY, lastX - orignalX, lastY - orignalY);
                break;
            case 2:
                lastX = event.offsetX;
                lastY = event.offsetY;
                if(lastX==null){
                    lastX = event.targetTouches[0].pageX;
                    lastY = event.targetTouches[0].pageY-140;
                }
                postArr[length]['line'].push([lastX,lastY]);
                context.lineTo(lastX, lastY); //根据鼠标路径绘画
                context.stroke(); //立即渲染
                break;
            case 3:
                lastX = event.offsetX;
                lastY = event.offsetY;
                var colorData = document.getElementById("myCanvas").getPixelColor(lastX, lastY);
                console.log(colorData);
                // 获取该点像素的数据
                break;

        }
    }
}

function myCanvasMouseUp(event) {
    if (isMouseDown) {
        //event.preventDefault();

        context.clearRect(0, 0, width, height);
        context.putImageData(data, 0, 0);

        lastX = event.offsetX;
        lastY = event.offsetY;
        if(lastX==null){
            lastX = event.changedTouches[0].clientX;
            lastY = event.changedTouches[0].clientY-140;
        }
        switch (shap) {
            case 0:
                context.beginPath();
                context.arc(orignalX + (lastX - orignalX) / 2, orignalY + (lastY - orignalY) / 2, Math.abs(lastX - orignalX) / 2, 0, Math.PI * 2, true);
                context.stroke();
                context.closePath();
                break;
            case 1:
                context.beginPath();
                context.strokeRect(orignalX, orignalY, lastX - orignalX, lastY - orignalY);
                context.closePath();
                break;
            case 2:
                context.lineTo(lastX, lastY); //根据鼠标路径绘画
                context.stroke(); //立即渲染
                break;
            case 3:
                isMouseDown = false;
                return;
                break;
        }
        postArr[length]['line'].push([lastX,lastY]);
        length++;
        isMouseDown = false;
        lastX = null;
        lastY = null;
        orignalX = null;
        orignalY = null;
        data = context.getImageData(0, 0, width, height);
        context.beginPath();
        context.clearRect(0, 0, width, height);
        context.putImageData(data, 0, 0);
        context.closePath();
    }
}
myCanvas.addEventListener("mousedown", myCanvasMouseDown, false);
myCanvas.addEventListener("mousemove", myCanvasMouseMove, false);
myCanvas.addEventListener("mouseup", myCanvasMouseUp, false);

myCanvas.addEventListener("touchstart", myCanvasMouseDown, false);
myCanvas.addEventListener("touchmove", myCanvasMouseMove, false);
myCanvas.addEventListener("touchend", myCanvasMouseUp, false);


function openImage(thumb){
    var img = new Image();
    img.src = thumb;
    img.onload=function(){
        context.drawImage(img,0,0,width,height);
    }
}

function convertBase64UrlToBlob(urlData,type){
    var bytes=window.atob(urlData.split(',')[1]);        //去掉url的头，并转换为byte
    //处理异常,将ascii码小于0的转换为大于0
    var ab = new ArrayBuffer(bytes.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < bytes.length; i++) {
        ia[i] = bytes.charCodeAt(i);
    }
    return new Blob( [ab] , {type : 'image/'+type});
}

function save(){
    var postData={};
    var dataURL=myCanvas.toDataURL();
    var blob=convertBase64UrlToBlob(dataURL,"jpg");
    var formdata=new FormData();
    formdata.append('file',blob);
    $.ajax({
        url : '/index/index/image',
        data :  formdata,
        processData : false,
        contentType : false,
        dataType: 'json',
        type : "POST",
        success : function(data){
            if(data.status==1){
                postData['title']=document.getElementById('title').value;
                postData['content']=JSON.stringify(postArr);
                postData['thumb']=data.thumb;
                $.post('/index/index/save',postData,function(result){
                    if(result.status == 1) {
                        //成功
                        dialog.success(result.message,'/index/draw');
                    }else if(result.status == 0) {
                        // 失败
                        dialog.error(result.message);
                    }
                },"JSON");
            }else{
                dialog.error(data.message);
            }

        }
    });
}

$('#file_upload_img').uploadify({
    'swf'      : '/static/uploadify/uploadify.swf',
    'uploader' : '/index/index/openImage',
    'buttonText': '打开图片',
    'fileTypeDesc': 'Image Files',
    'fileObjName' : 'file',
    //允许上传的文件后缀
    'fileTypeExts': '*.gif; *.jpg; *.png',
    'onUploadSuccess' : function(file,data,response) {
        // response true ,false
        if(response) {
            var obj = JSON.parse(data); //由JSON字符串转换为JSON对象
            $('#' + file.id).find('.data').html(' 打开成功');

            openImage(obj.thumb);
        }else{
            alert('打开失败');
        }
    },
});