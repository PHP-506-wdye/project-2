const tab1 = document.querySelector('.tab1')
const tab2 = document.querySelector('.tab2')

const search = document.getElementsByClassName('user_search')
const fav_diets = document.getElementsByClassName('fav_diets')
const user_select = document.getElementsByClassName('user_select')
const uselect = document.getElementsByClassName('uselect')
const nosearch = document.querySelector('.nosearch')

// ---------------------------- 체크박스 및 input ----------------------------

function getFoodValue(userId)  {
    // 선택된 목록 가져오기
    const query = 'input[name="usercheck"]:checked';
    const selectedEls = 
        document.querySelectorAll(query);
    const query2 = 'input[name="userving"]'
    const selectedInp = document.querySelectorAll(query2);
    
    // 선택된 목록에서 value 찾기
    let result = '';
    // let result = [];
    selectedEls.forEach((el) => {
        result = el.value;
        // el.split('/');
        // result.push([
        //     el.value
        // ]);
    });

    // var amount = [];
    var amount = '';
    selectedInp.forEach((el2) => {
        amount += el2.value;
        parseFloat(amount);
    });

    // var amount = amount.filter(function(item){
    //     return item !== null && item !== undefined && item !== '';
    // })

    console.log(result);
    console.log(amount);

    // JSON.parse(data);
    fetch(`/api/cart/${userId}/${result}/${amount}`, {
        method: "post"
    })
    .then(res => res.json())
    .then(data => { console.log(data); console.log(data.error); console.log(data.msg); console.log(data.data); });

    location.reload();
    // $.ajax({
    //     type: "post",
    //     url: `/api/cart/${userId}`,
    //     data: formData,
    // }).done(res => {
    //     console.log(res);
    //     alert("성공");
    // }).fail(error => {
    //     alert("실패");
    // });
    // $.ajax({
    //     type: "post",
    //     url: `/api/cart/${result}/${amount}`,
    //     data: JSON.stringify(result),
    //     contentType: "application/json; charset=utf-8",
    //     dataType: "json"
    // }).done(res => {
    //     alert("성공");
    // }).fail(error => {
    //     alert("실패");
    // });
}

// const serving = document.getElementById('userving');
// serving.addEventListener('input', function() {

//     document.getElementById('resultserving').innerText
//             = serving.value;
    
// })

function getDietValue()  {
    // 선택된 목록 가져오기
    const query = 'input[name="userdiet"]:checked';
    const selectedEls = 
        document.querySelectorAll(query);
    
    // 선택된 목록에서 value 찾기
    let resultdiet = '';
    selectedEls.forEach((el) => {
        resultdiet += el.value;
    });

    $(document).ready(function() {
        $('#resultdiet').val(resultdiet);
    });

    // document.getElementById('resultdiet').innerText
    //         = resultdiet;
}

// todo : 저장된 식단, 선택된 음식 클릭 시 '검색 결과가 없습니다' 메세지 없애기
// ---------------------------- 저장된 식단 ----------------------------

tab1.addEventListener('click', () => {
    var displaysetting = fav_diets[0].style.display;
    
    if(displaysetting == 'block'){
        fav_diets[0].style.display = 'none'
    }else{
        fav_diets[0].style.display = 'block'
        user_select[0].style.display = 'none'
        search[0].style.display = 'none'
    }
    // nosearch.style.display = 'none'
});

// ---------------------------- 선택된 음식 ----------------------------

tab2.addEventListener('click', () => {
    var displaysetting = user_select[0].style.display;

    if(displaysetting == 'block'){
        user_select[0].style.display = 'none'
    }else{
        user_select[0].style.display = 'block'
        fav_diets[0].style.display = 'none'
        search[0].style.display = 'none'
    }
    // nosearch.style.display = 'none'
    // location.reload();
});
