var myCanvas = document.getElementById("myCanvas");
var context = myCanvas.getContext('2d');
var width = screen.width, height =screen.height;
if(width>1200) width=1200;
if(height>800) height=800;
myCanvas.setAttribute("width",width);//初始化宽
myCanvas.setAttribute("height",height);//初始化高

var isMouseDown = false;
var line=[];

var time=50;
var n=10;

var data;

var sd=10;
$('#jia').click(function(){
    sd<50?sd++:sd;
    $('#sd').html(sd/10);
});
$('#jian').click(function(){
    sd>1?sd--:sd;
    $('#sd').html(sd/10);
});

function show(){
    clearCanvas();
    n/=sd/10;
   for(var i in arr){
       choose(arr[i].status,arr[i].color,arr[i].size,arr[i].global,arr[i].line);
   }
}

/**
 * 清除画布
 */
function clearCanvas(){
    time=50;
    context.clearRect(0,0,width,height);
}

function choose(status,color,size,global,line){
    for(var j in line){
        if(j==0){
            setTimeout("drawbegin("+status+",'"+color+"',"+size+","+global+","+line[j][0]+","+line[j][1]+")",time);
            time+=(n*30);
        }else if(j== line.length-1){
            setTimeout("drawend("+status+","+line[j][0]+","+line[j][1]+")",time);
            time+=n;
        }else{
            setTimeout("drawmove("+status+","+line[j][0]+","+line[j][1]+")",time);
            time+=n;
        }
    }
}
var orignalX,orignalY;
function drawbegin(shap,color,size,global,lastX,lastY){
    context.strokeStyle = color;
    context.lineWidth= size;
    context.globalAlpha= global;
    data = context.getImageData(0, 0, width, height);
    orignalX=lastX;
    orignalY=lastY;
    context.moveTo(lastX,lastY);
}
function drawmove(shap,lastX,lastY){
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

function drawend(shap,lastX,lastY){
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
