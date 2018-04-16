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
var n=1;

var data;

function show(){
   for(var i in arr){
       choose(arr[i].status,arr[i].color,arr[i].size,arr[i].global,arr[i].line);
   }
}

function choose(status,color,size,global,line){
    for(var j in line){
        if(j==0){
            setTimeout("drawbegin("+status+",'"+color+"',"+size+","+global+","+line[j][0]+","+line[j][1]+")",time);
            time+=300;
        }else if(j== line.length-1){
            setTimeout("drawend("+status+","+line[j][0]+","+line[j][1]+")",time);
            time+=10;
        }else{
            setTimeout("drawmove("+status+","+line[j][0]+","+line[j][1]+")",time);
            time+=10;
        }
    }
}
function drawbegin(shap,color,size,global,orignalX, orignalY){
    context.strokeStyle = color;
    context.lineWidth= size;
    context.globalAlpha= global;
    data = context.getImageData(0, 0, width, height);
    context.moveTo(orignalX, orignalY);
}
function drawmove(shap,lastX,lastY){
    context.lineTo(lastX, lastY); //根据鼠标路径绘画
    context.stroke(); //立即渲染
}

function drawend(shap,lastX,lastY){
    context.clearRect(0, 0, width, height);
    context.putImageData(data, 0, 0);
    context.lineTo(lastX, lastY); //根据鼠标路径绘画
    context.stroke(); //立即渲染

    data = context.getImageData(0, 0, width, height);
    context.beginPath();
    context.clearRect(0, 0, width, height);
    context.putImageData(data, 0, 0);
    context.closePath();
}
