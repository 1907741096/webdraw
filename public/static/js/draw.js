var shap = 2;
var orignalX, orignalY;
var lastX, lastY;
var isMouseDown = false;
var colorMouse = false;
var myCanvas = document.getElementById("myCanvas");
var context = myCanvas.getContext('2d');

var colorpanCanvas = document.getElementById("colorpan");
var colorpan = colorpanCanvas.getContext('2d');

var width = screen.width, height = screen.height;
if (width > 1200) width = 1200;
if (height > 800) height = 800;
myCanvas.setAttribute("width", width);//初始化宽
myCanvas.setAttribute("height", height);//初始化高

var imageData = context.getImageData(0, 0, myCanvas.width, myCanvas.height);
for(var i = 0; i < imageData.data.length; i += 4) {
// 当该像素是透明的,则设置成白色
    if(imageData.data[i + 3] === 0) {
        imageData.data[i] = 255;
        imageData.data[i + 1] = 255;
        imageData.data[i + 2] = 255;
        imageData.data[i + 3] = 255;
    }
}
context.putImageData(imageData, 0, 0);

var postArr = [];
var length = 0;

var data;//storing last canv2as' content
context.strokeStyle = "black";
context.strokeWidth = 1;
context.lineWidth = 1;

colorpan.strokeStyle = "black";
colorpan.strokeWidth = 1;
colorpan.lineWidth = 1;


document.getElementById('color').onchange = function () {
    context.strokeStyle = this.value;
    colorpan.strokeStyle = this.value;
};

/**
 * 橡皮
 */
function doEraser() {
    if (document.getElementById('eraser').style.color == "rgb(16, 208, 122)") {
        var color = document.getElementById('color').value;
        context.strokeStyle = color;
        colorpan.strokeStyle = color;
        document.getElementById('eraser').style.color = "#333";
    } else {
        context.strokeStyle = "white";
        colorpan.strokeStyle = "white";
        document.getElementById('eraser').style.color = "#10D07A";
        // context.lineWidth = 10;
        // colorpan.lineWidth = 10;
        shap = 2;
    }


}

/**
 * 线条粗细
 */
function sizeChange() {
    context.lineWidth = parseInt(document.getElementById('size').value);
    colorpan.lineWidth = parseInt(document.getElementById('size').value);
}

/**
 * 透明度
 */
function globalChange() {
    context.globalAlpha = parseInt(document.getElementById('global').value) / 100;
    colorpan.globalAlpha = parseInt(document.getElementById('global').value) / 100;
}

function shapeChange() {
    var myselect = document.getElementById("shape");
    var index = myselect.selectedIndex;
    var myvalue = myselect.options[index].value;
    var mytext = myselect.options[index].text;
    shap = parseInt(myvalue);
    document.getElementById('eraser').style.color = "#333";
    colorpan.strokeStyle = document.getElementById('color').value;
    context.strokeStyle = document.getElementById('color').value;
}

/**
 * 清除画布
 */
function clearCanvas() {
    data = context.getImageData(0, 0, width, height);
    postArr = [];
    length = 0;
    restore.push(data);
    context.clearRect(0, 0, width, height);
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
    if (shap == 4) {
        return
    }
    if (shap == 3) {
        lastX = event.offsetX;
        lastY = event.offsetY;
        var colorData = document.getElementById("myCanvas").getPixelColor(lastX, lastY);
        document.getElementById('color').value = colorData.hex;
        context.strokeStyle = colorData.hex;
        // 获取该点像素的数据
        return;
    }
    //event.preventDefault();
    data = context.getImageData(0, 0, width, height);
    orignalX = event.offsetX;
    orignalY = event.offsetY;
    if (orignalX == null) {
        orignalX = event.targetTouches[0].pageX;
        orignalY = event.targetTouches[0].pageY - 140;
    }
    context.moveTo(orignalX, orignalY);
    postArr[length] = {};
    postArr[length]['line'] = [];
    postArr[length]['line'].push([orignalX, orignalY]);
    postArr[length]['status'] = shap;
    postArr[length]['color'] = context.strokeStyle;
    postArr[length]['size'] = context.lineWidth;
    postArr[length]['global'] = context.globalAlpha;
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
                postArr[length]['line'].push([lastY, lastY]);
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
                postArr[length]['line'].push([lastY, lastY]);
                context.strokeRect(orignalX, orignalY, lastX - orignalX, lastY - orignalY);
                break;
            case 2:
                lastX = event.offsetX;
                lastY = event.offsetY;
                if (lastX == null) {
                    lastX = event.targetTouches[0].pageX;
                    lastY = event.targetTouches[0].pageY - 140;
                }
                postArr[length]['line'].push([lastX, lastY]);
                context.lineTo(lastX, lastY); //根据鼠标路径绘画
                context.stroke(); //立即渲染
                break;
            case 3:
                lastX = event.offsetX;
                lastY = event.offsetY;
                var colorData = document.getElementById("myCanvas").getPixelColor(lastX, lastY);
                document.getElementById('color').value = colorData.hex;
                context.strokeStyle = colorData.hex;
                // 获取该点像素的数据
                break;

        }
    }
}

var dirs = [
    [-1, 0],
    [0, 1],
    [1, 0],
    [0, -1]
]

/**
 鼠标抬起
 */
function myCanvasMouseUp(event) {
    if (isMouseDown) {
        //event.preventDefault();
        if (shap == 3) {
            isMouseDown = false;
            return;
        }

        context.clearRect(0, 0, width, height);
        context.putImageData(data, 0, 0);

        lastX = event.offsetX;
        lastY = event.offsetY;
        if (lastX == null) {
            lastX = event.changedTouches[0].clientX;
            lastY = event.changedTouches[0].clientY - 140;
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
            case 4:
                lastX = event.offsetX;
                lastY = event.offsetY;
                var colorData = document.getElementById("myCanvas").getPixelColor(lastX, lastY);
                var maze = [];
                for (var i = 0; i <= width; i++) {
                    maze[i] = [];
                    for (var j = 0; j <= height; j++) {
                        maze[i][j] = 0;
                    }
                }
                var firstPoint = [lastX, lastY];
                var queue = [];
                queue.push(firstPoint);
                maze[lastX][lastY] = 1;
                while (queue.length > 0) {
                    nowPoint = queue.shift();
                    for (i = 0; i < dirs.length; i++) {
                        nowX = nowPoint[0] + dirs[i][0];
                        nowY = nowPoint[1] + dirs[i][1];
                        if (nowX < 0
                            || nowY < 0
                            || nowX > width
                            || nowY > height
                            || maze[nowX][nowY] === 1) {
                            continue;
                        }
                        maze[nowX][nowY] = 1;
                        var nowColorData = document.getElementById("myCanvas").getPixelColor(nowX, nowY);
                        if (colorData.hex === nowColorData.hex) {
                            queue.push([nowX, nowY])
                        }
                        context.lineTo(nowX, nowY);
                    }
                }
                context.stroke(); //立即渲染
                break;
        }
        if (shap !== 4) {
            postArr[length]['line'].push([lastX, lastY]);
        }
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

function colorpanMouseDown(event) {
    colorMouse = true;
    if (shap == 3) {
        lastX = event.offsetX;
        lastY = event.offsetY;
        var colorData = document.getElementById("colorpan").getPixelColor(lastX, lastY);
        document.getElementById('color').value = colorData.hex;
        colorpan.strokeStyle = colorData.hex;
        // 获取该点像素的数据
        return;
    }
    //event.preventDefault();
    data = colorpan.getImageData(0, 0, width, height);
    orignalX = event.offsetX;
    orignalY = event.offsetY;
    if (orignalX == null) {
        orignalX = event.targetTouches[0].pageX;
        orignalY = event.targetTouches[0].pageY - 140;
    }
    colorpan.moveTo(orignalX, orignalY);

}

function colorpanMouseMove(event) {
    if (colorMouse) {
        //event.preventDefault();

        switch (shap) {
            case 0:
                colorpan.clearRect(0, 0, width, height);
                colorpan.putImageData(data, 0, 0);
                lastX = event.offsetX;
                lastY = event.offsetY;
                colorpan.beginPath();
                colorpan.arc(orignalX + (lastX - orignalX) / 2, orignalY + (lastY - orignalY) / 2, Math.abs(lastX - orignalX) / 2, 0, Math.PI * 2, true);
                colorpan.stroke();
                colorpan.closePath();
                break;
            case 1:
                colorpan.clearRect(0, 0, width, height);
                colorpan.putImageData(data, 0, 0);
                lastX = event.offsetX;
                lastY = event.offsetY;
                colorpan.strokeRect(orignalX, orignalY, lastX - orignalX, lastY - orignalY);
                break;
            case 2:
                lastX = event.offsetX;
                lastY = event.offsetY;
                if (lastX == null) {
                    lastX = event.targetTouches[0].pageX;
                    lastY = event.targetTouches[0].pageY - 140;
                }
                colorpan.lineTo(lastX, lastY); //根据鼠标路径绘画
                colorpan.stroke(); //立即渲染
                break;
            case 3:
                lastX = event.offsetX;
                lastY = event.offsetY;
                var colorData = document.getElementById("colorpan").getPixelColor(lastX, lastY);
                document.getElementById('color').value = colorData.hex;
                colorpan.strokeStyle = colorData.hex;
                // 获取该点像素的数据
                break;

        }
    }
}

function colorpanMouseUp(event) {
    if (colorMouse) {
        //event.preventDefault();
        if (shap == 3) {
            colorMouse = false;
            return;
        }

        colorpan.clearRect(0, 0, width, height);
        colorpan.putImageData(data, 0, 0);

        lastX = event.offsetX;
        lastY = event.offsetY;
        if (lastX == null) {
            lastX = event.changedTouches[0].clientX;
            lastY = event.changedTouches[0].clientY - 140;
        }
        switch (shap) {
            case 0:
                colorpan.beginPath();
                colorpan.arc(orignalX + (lastX - orignalX) / 2, orignalY + (lastY - orignalY) / 2, Math.abs(lastX - orignalX) / 2, 0, Math.PI * 2, true);
                colorpan.stroke();
                colorpan.closePath();
                break;
            case 1:
                colorpan.beginPath();
                colorpan.strokeRect(orignalX, orignalY, lastX - orignalX, lastY - orignalY);
                colorpan.closePath();
                break;
            case 2:
                colorpan.lineTo(lastX, lastY); //根据鼠标路径绘画
                colorpan.stroke(); //立即渲染
                break;
        }
        colorMouse = false;
        lastX = null;
        lastY = null;
        orignalX = null;
        orignalY = null;
        data = colorpan.getImageData(0, 0, width, height);
        colorpan.beginPath();
        colorpan.clearRect(0, 0, width, height);
        colorpan.putImageData(data, 0, 0);
        colorpan.closePath();
    }
}

myCanvas.addEventListener("mousedown", myCanvasMouseDown, false);
myCanvas.addEventListener("mousemove", myCanvasMouseMove, false);
myCanvas.addEventListener("mouseup", myCanvasMouseUp, false);

colorpanCanvas.addEventListener("mousedown", colorpanMouseDown, false);
colorpanCanvas.addEventListener("mousemove", colorpanMouseMove, false);
colorpanCanvas.addEventListener("mouseup", colorpanMouseUp, false);
// myCanvas.addEventListener("touchstart", myCanvasMouseDown, false);
// myCanvas.addEventListener("touchmove", myCanvasMouseMove, false);
// myCanvas.addEventListener("touchend", myCanvasMouseUp, false);


function openImage(thumb) {
    var img = new Image();
    img.src = "/webdraw/public" + thumb;
    img.onload = function () {
        context.drawImage(img, 0, 0, width, height);
    }
}

function convertBase64UrlToBlob(urlData, type) {
    var bytes = window.atob(urlData.split(',')[1]);        //去掉url的头，并转换为byte
    //处理异常,将ascii码小于0的转换为大于0
    var ab = new ArrayBuffer(bytes.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < bytes.length; i++) {
        ia[i] = bytes.charCodeAt(i);
    }
    return new Blob([ab], {type: 'image/' + type});
}

function save() {
    var postData = {};
    var dataURL = myCanvas.toDataURL();
    var blob = convertBase64UrlToBlob(dataURL, "jpg");
    var formdata = new FormData();
    formdata.append('file', blob);
    $.ajax({
        url: '/webdraw/public/index.php/index/index/image',
        data: formdata,
        processData: false,
        contentType: false,
        dataType: 'json',
        type: "POST",
        success: function (data) {
            if (data.status == 1) {
                postData['title'] = document.getElementById('title').value;
                postData['content'] = JSON.stringify(postArr);
                postData['thumb'] = "/webdraw/public" + data.thumb;
                $.post('/webdraw/public/index.php/index/index/save', postData, function (result) {
                    if (result.status == 1) {
                        //成功
                        dialog.success(result.message, '/webdraw/public/index.php/index/draw');
                    } else if (result.status == 0) {
                        // 失败
                        dialog.error(result.message);
                    }
                }, "JSON");
            } else {
                dialog.error(data.message);
            }

        }
    });
}

$('#file_upload_img').uploadify({
    'swf': '/webdraw/public/static/uploadify/uploadify.swf',
    'uploader': '/webdraw/public/index.php/index/index/openImage',
    'buttonText': '打开图片',
    'fileTypeDesc': 'Image Files',
    'fileObjName': 'file',
    //允许上传的文件后缀
    'fileTypeExts': '*.gif; *.jpg; *.png',
    'onUploadSuccess': function (file, data, response) {
        // response true ,false
        if (response) {
            var obj = JSON.parse(data); //由JSON字符串转换为JSON对象
            $('#' + file.id).find('.data').html(' 打开成功');

            openImage(obj.thumb);
        } else {
            alert('打开失败');
        }
    },
});

$('#file_upload_txt').uploadify({
    'swf': '/webdraw/public/static/uploadify/uploadify.swf',
    'uploader': '/webdraw/public/index.php/index/index/openTxt',
    'buttonText': '上传过程',
    'fileTypeDesc': 'Txt Files',
    'fileObjName': 'file',
    //允许上传的文件后缀
    'fileTypeExts': '*.txt',
    'onUploadSuccess': function (file, data, response) {
        // response true ,false
        if (response) {
            var obj = JSON.parse(data); //由JSON字符串转换为JSON对象
            $('#' + file.id).find('.data').html(' 打开成功');
            postArr = obj.content;
            length = postArr.length;
            show();
        } else {
            alert('打开失败');
        }
    },
});

document.onkeydown = function (e) {
    var pressEvent = e || window.event;
    var keyCode = '';
    if (pressEvent.keyCode) {
        keyCode = pressEvent.keyCode;
    }
    if (keyCode == 67) {
        clearCanvas();
    }
    if (keyCode == 69) {
        doEraser();
    }
    if (keyCode == 90) {
        back();
    }
}

var time = 50;
var n = 1;

function show() {
    clearShowCanvas();
    for (var i in postArr) {
        choose(postArr[i].status, postArr[i].color, postArr[i].size, postArr[i].global, postArr[i].line);
    }
}

/**
 * 清除画布
 */
function clearShowCanvas() {
    context.clearRect(0, 0, width, height);
}

function choose(status, color, size, global, line) {
    for (var j in line) {
        if (j == 0) {
            setTimeout("drawbegin(" + status + ",'" + color + "'," + size + "," + global + "," + line[j][0] + "," + line[j][1] + ")", time);
            time += (n * 30);
        } else if (j == line.length - 1) {
            setTimeout("drawend(" + status + "," + line[j][0] + "," + line[j][1] + ")", time);
            time += n;
        } else {
            setTimeout("drawmove(" + status + "," + line[j][0] + "," + line[j][1] + ")", time);
            time += n;
        }
    }
}

var orignalX, orignalY;

function drawbegin(shap, color, size, global, lastX, lastY) {
    context.strokeStyle = color;
    context.lineWidth = size;
    context.globalAlpha = global;
    data = context.getImageData(0, 0, width, height);
    orignalX = lastX;
    orignalY = lastY;
    context.moveTo(lastX, lastY);
}

function drawmove(shap, lastX, lastY) {
    context.lineTo(lastX, lastY); //根据鼠标路径绘画
    context.stroke(); //立即渲染

    switch (shap) {
        case 0:
            context.clearRect(0, 0, width, height);
            context.putImageData(data, 0, 0);
            context.beginPath();
            context.arc(orignalX + (lastX - orignalX) / 2, orignalY + (lastY - orignalY) / 2, Math.abs(lastX - orignalX) / 2, 0, Math.PI * 2, true);
            context.stroke();
            context.closePath();
            break;
        case 1:
            context.clearRect(0, 0, width, height);
            context.putImageData(data, 0, 0);
            context.strokeRect(orignalX, orignalY, lastX - orignalX, lastY - orignalY);
            break;
        case 2:
            context.lineTo(lastX, lastY); //根据鼠标路径绘画
            context.stroke(); //立即渲染
            break;

    }
}

function drawend(shap, lastX, lastY) {
    context.clearRect(0, 0, width, height);
    context.putImageData(data, 0, 0);
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
    }
    data = context.getImageData(0, 0, width, height);
    context.beginPath();
    context.clearRect(0, 0, width, height);
    context.putImageData(data, 0, 0);
    context.closePath();
}