// 칭호 변경페이지로 이동
const medal = document.getElementById('medal');
const medalChange = document.getElementById('medalChange');

medal.addEventListener('mouseenter',function(){
    medalChange.innerHTML = "칭호변경하러가기";
    medal.style.cursor = "pointer";
    medalChange.style.color = "green";
    medal.style.color = "black";
});

medal.addEventListener('mouseleave',function(){
    medalChange.innerHTML = "";
});

// nav 하이라이트
const navItems = document.querySelectorAll('.list-group-item');

let currentURL = window.location.href; // 현재 URL

    navItems.forEach(function(item) {
        let itemUrl = item.href;
        if (currentURL.includes(itemUrl)) {
            item.classList.add('active');
        }
    });

// 이전 페이지로 가는 버튼
const backBtn = document.getElementById('backBtn');

function redirectBack(){
    window.history.back();
};

// const test = document.getElementsByClassName('list-group-item');

// let li = null;

// for (let i = 0; i < test.length; i++) {
//         li = test[i];
// }

// const today = new Date();
// const birthDate = document.getElementById('user_birth'); 
// const userage = document.getElementById('userage');
// let age = today.getFullYear() - birthDate.getFullYear() + 1;

// const nknamechk = document.getElementById('nkname');

// userage.innerHTML = age;

// nknamechk.addEventListener('change', function(){
//         const nk = document.getElementById('nkname');
//         const url = "/api/user/usernkchk/" + nk.value;
//         fetch(url)
//         .then(data => {
//         if (!data.status) {
//                 throw new Error(data.status + ' : API Response Error' );
//         }
//         return data.json();
//         })
//         .then(apiData  => {
//         const idspan = document.getElementById('nkRegexm');
//                 if(apiData["flg"] === "1") {
//                 idspan.innerHTML = apiData["msg"];
//                 greenBtn.disabled = true;
//                 } else {
//                 idspan.innerHTML = "사용가능한 닉네임 입니다. "
//                 greenBtn.disabled = false;
//                 }
//         });
// });
