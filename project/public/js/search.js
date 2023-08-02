
// 메뉴 탭
const tab1 = document.querySelector('.tab1')
const tab2 = document.querySelector('.tab2')

// 탭 내용
const search = document.getElementsByClassName('user_search')
const fav_diets = document.getElementsByClassName('fav_diets')
const user_select = document.getElementsByClassName('user_select')

// 탭 세부 내용
const uselect = document.getElementsByClassName('uselect')
const nosearch = document.querySelector('.nosearch')

// 유저가 입력한 인분 수
const userving = document.getElementsByClassName('userving')

// appendChild 용 div 선언
const fav_food = document.getElementById('fav_food');
const fav_diet = document.getElementById('fav_diet');

// ---------------------------- 체크박스 및 input ----------------------------

function getFoodValue(event, userId)  {    
    // 선택된 목록에서 value 찾기
    let foodId = '';
    let foodAmout = '';
    let food = event.target;
    let flg = document.getElementById('timeFlg').value;
    let date = document.getElementById('date').value;
    
    if(event.target.checked)  {
        foodId = event.target.value;
        foodAmout = parseFloat(food.parentNode.childNodes[7].value);
    }

    if (isNaN(foodAmout) === true) {
        foodAmout = 1.0;
    }

    const url = "/api/carts/foods";
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const request = new Request(url, {
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json, text-plain, */*",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": token
            },
        method: 'POST',
        credentials: "same-origin",
        body: JSON.stringify({
            value1: userId,
            value2: foodId,
            value3: foodAmout,
            value4: flg,
            value5: date
        })
    });
    
    fetch(request)
    .then(data => data.json()) 
    .then( data => { 
        if(data['errorcode'] === '0') {
            // 검색 페이지에서 해당 div 숨기기
            food.parentNode.style.display = "none";

            // 선택된 음식 탭에 추가
            let ffood = document.createElement('span');
            let intake = document.createElement('span');
            let div = document.createElement('div');
            ffood.innerHTML = data['data'].food_name;
            intake.innerHTML = data['data'].amount + ' 인분';
            
            // 삭제 버튼 생성
            let delfbtn = document.createElement('button')
            delfbtn.innerHTML = 'X';
            delfbtn.setAttribute('type', 'button')
            delfbtn.setAttribute('id', 'delete_btn')
            delfbtn.setAttribute('onclick', "deletefood(" + data['data'].user_id + ',' + data['data'].food_id + ',' + data['data'].cart_id + ")")
    
            // 삭제 버튼 div에 넣기
            fav_food.appendChild(div);
            div.appendChild(ffood);
            div.appendChild(intake);
            div.appendChild(delfbtn);

            // 알러트
            alert('선택된 음식 탭에 추가되었습니다.');

            // 선택된 음식 효과
            let selectedFood = document.getElementById('selectedFood');
            selectedFood.innerHTML = 'new';
        } else {
            event.target.checked = false;
            alert(data['msg']);
        }


        //     fav_food.replaceChildren();
        //     apiData.forEach(ele => {
        //     console.log(ele.food_name);
        //     console.log(ele.amount);
        //     console.log(ele.cart_id);

        //     // 선택된 음식 탭에 추가
        //     let ffood = document.createElement('span')
        //     let brp = document.createElement('br')
        //     ffood.innerHTML = ele.food_name+' | '+ele.amount+'인분';
            
        //     // 삭제 버튼
        //     let delfbtn = document.createElement('button')
        //     delfbtn.innerHTML = 'X';
        //     delfbtn.setAttribute('type', 'button')
        //     delfbtn.setAttribute('id', 'delete_btn')
        //     delfbtn.setAttribute('onclick', "deletefood('"+ele.user_id+','+ele.food_id+','+ele.cart_id+"')")

        //     // 삭제 버튼 div에 넣기
        //     fav_food.appendChild(ffood);
        //     fav_food.appendChild(delfbtn)
        //     fav_food.appendChild(brp);
        // })
    })

    
}

// 선택 음식 삭제 함수
function deletefood(userId, foodId, cartId) {
    let flg = document.getElementById('timeFlg').value;
    let date = document.getElementById('date').value;

    const url = "/api/carts/foods";
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const request = new Request(url, {
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json, text-plain, */*",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": token
            },
        method: 'DELETE',
        credentials: "same-origin",
        body: JSON.stringify({
            userId: userId,
            cartId: cartId,
            foodId: foodId,
            flg: flg,
            date: date
        })
    });

    fetch(request)
    .then(res => res.json())
    .then( data => {
        if(data['errorcode'] === '0') {
            fav_food.replaceChildren()
            if(data['data'].length < 1 && fav_diet.children.length < 1) {
                let selectedFood = document.getElementById('selectedFood');
                selectedFood.innerHTML = '';
                return false;
            }
            data['data'].forEach(ele => {
                let ffood = document.createElement('span');
                let intake = document.createElement('span');
                let div = document.createElement('div');
                ffood.innerHTML = ele.food_name;
                intake.innerHTML = ele.amount + ' 인분';
                
                // 삭제 버튼
                let delfbtn = document.createElement('button')
                delfbtn.innerHTML = 'X';
                delfbtn.setAttribute('type', 'button')
                delfbtn.setAttribute('id', 'delete_btn')
                delfbtn.setAttribute('onclick', "deletefood(" + ele.user_id + ',' + ele.food_id + ',' + ele.cart_id + ")")
        
                // 삭제 버튼 div에 넣기
                fav_food.appendChild(div);
                div.appendChild(ffood);
                div.appendChild(intake);
                div.appendChild(delfbtn)
            })
        }
    })
}

// 즐겨찾는 식단 -> 장바구니
function getDietValue(event, userId, favId)  {
    let flg = document.getElementById('timeFlg').value;
    let date = document.getElementById('date').value;

    const url = "/api/carts/diets";
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const request = new Request(url, {
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json, text-plain, */*",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": token
            },
        method: 'POST',
        credentials: "same-origin",
        body: JSON.stringify({
            userId: userId,
            favId: favId,
            flg: flg,
            date: date
        })
    });
    fetch(request)
        .then(res => res.json())
        .then(data => { 
            if (data['errorcode'] === '0') {
                // 선택된 음식 부분 처리
                fav_diet.replaceChildren()
                data['data'].forEach(ele => {
                    let fdiet = document.createElement('span');
                    let div = document.createElement('div');
                    fdiet.innerHTML = ele.fav_name;
                    
                    // 삭제 버튼
                    let deldbtn = document.createElement('button');
                    deldbtn.innerHTML = 'X';
                    deldbtn.setAttribute('type', 'button')
                    deldbtn.setAttribute('id', 'delete_btn')
                    deldbtn.setAttribute('onclick', "deletediet(" + ele.user_id + ',' + ele.cart_id + ',' + ele.fav_id + ")")
    
                    fav_diet.appendChild(div);
                    div.appendChild(fdiet);
                    div.appendChild(deldbtn);
                    })

                // 저장된 식단 부분
                let favDiv = document.getElementById('favId-' + favId);
                favDiv.style.display = "none";

                // 알러트
                alert('선택된 음식 탭에 추가되었습니다.');

                // 선택된 음식
                let selectedFood = document.getElementById('selectedFood');
                selectedFood.innerHTML = 'new';
            } else {
                alert(data['msg']);
                event.target.checked = false;
            }
        })
}

// 선택 식단 삭제 함수
function deletediet(userId, cartId, favId) {
    let flg = document.getElementById('timeFlg').value;
    let date = document.getElementById('date').value;

    const url = "/api/carts/diets";
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const request = new Request(url, {
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json, text-plain, */*",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": token
            },
        method: 'DELETE',
        credentials: "same-origin",
        body: JSON.stringify({
            userId: userId,
            cartId: cartId,
            flg: flg,
            date: date
        })
    });
    fetch(request)
    .then(res => res.json())
    .then( data => {
        fav_diet.replaceChildren()
        if (data['errorcode'] === '0') {
            data['data'].forEach(ele => {
                let ffood = document.createElement('span')
                let brp = document.createElement('br')
                ffood.innerHTML = ele.fav_name;
    
                // 삭제 버튼
                let deldbtn = document.createElement('button')
                deldbtn.innerHTML = 'X';
                deldbtn.setAttribute('type', 'button')
                deldbtn.setAttribute('id', 'delete_btn')
                deldbtn.setAttribute('onclick', "deletediet(" + ele.user_id + ',' + ele.cart_id + ',' + ele.fav_id + ")")
    
                fav_diet.appendChild(ffood);
                fav_diet.appendChild(deldbtn)
                fav_diet.appendChild(brp);
            })
        } else if(data['errorcode'] === '1' && fav_food.children.length - 2 < 1) {
            let selectedFood = document.getElementById('selectedFood');
            selectedFood.innerHTML = '';
        }
        let displayDiv = document.getElementById('favId-' + favId)
        let displayInput = document.getElementById('input-' + favId);
        displayDiv.removeAttribute('style');
        displayInput.checked = false;
    }
)}

// ---------------------------- 저장된 식단 ----------------------------

tab1.addEventListener('click', () => {
    let displaysetting = fav_diets[0].style.display;

    if(displaysetting == 'block'){
        fav_diets[0].style.display = 'none'
    }else{
        fav_diets[0].style.display = 'block'
        user_select[0].style.display = 'none'
        if(search[0]) {
            search[0].style.display = 'none'
        }
    }
});

// ---------------------------- 선택된 음식 ----------------------------

tab2.addEventListener('click', () => {
    let displaysetting = user_select[0].style.display;

    if(displaysetting == 'block'){
        user_select[0].style.display = 'none'
    }else{
        user_select[0].style.display = 'block'
        fav_diets[0].style.display = 'none'
        if(search[0]) {
            search[0].style.display = 'none'
        }
    }
});