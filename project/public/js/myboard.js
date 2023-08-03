const boardTab = document.getElementById('tab1');
const replyTab = document.getElementById('tab2');
const boardBtn = document.getElementById('boardBtn');
const replyBtn = document.getElementById('replyBtn');

function tabChange1(){ 
    boardBtn.style.color = "#195F1C";
    replyBtn.style.color = "black";
}

function tabChange2(){
    boardBtn.style.color = "black";
    replyBtn.style.color = "#195F1C";
}