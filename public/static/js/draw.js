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
    context.strokeStyle = "black";
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
    restore.push(data);
    context.clearRect(0,0,width,height);
}


/**
 撤销
 */
var restore = [];

function back() {
    context.putImageData(restore.pop(), 0, 0);
}

/**
 鼠标按下
 */
function myCanvasMouseDown(event) {
    //event.preventDefault();
        data = context.getImageData(0, 0, width, height);
        orignalX = event.offsetX;
        orignalY = event.offsetY;
        if(orignalX==null){
            orignalX = event.targetTouches[0].pageX;
            orignalY = event.targetTouches[0].pageY-140;
        }
        context.moveTo(orignalX, orignalY);
        restore.push(data);
        isMouseDown = true;
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
                context.strokeRect(orignalX, orignalY, lastX - orignalX, lastY - orignalY);
                break;
            case 2:
                lastX = event.offsetX;
                lastY = event.offsetY;
                if(lastX==null){
                    lastX = event.targetTouches[0].pageX;
                    lastY = event.targetTouches[0].pageY-140;
                }
                context.lineTo(lastX, lastY); //根据鼠标路径绘画
                context.stroke(); //立即渲染
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
        }
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

//   var img=new Image()
// img.src="https://www.wzyhome.date/erciyuan/Public/editor/20180326/1522043600859516.jpg"
// context.drawImage(img,0,0,width,height);
// var imgs = new Image();
// imgs.src = "https://www.wzyhome.date/erciyuan/Public/editor/20180326/1522043600859516.jpg";
// imgs.onload = createPat;//图片加载完成再执行
// function createPat(){
//     var bg = context.createPattern(imgs,"no-repeat");
//     context.fillStyle = bg;
//     context.fillRect(0,0,500,500);
// }