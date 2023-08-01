// 음식 선택삭제
function chkDel() {
    let confirmDelete = confirm("음식 정보를 삭제 하시겠습니까?");

    // 체크박스 체크된 값 배열로 반환
    function getCheckedValues() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
        const checkedValues = Array.from(checkboxes).map(checkbox => checkbox.value);
        return checkedValues;
    }

    const arrChkVal = getCheckedValues();
    if (confirmDelete) {
        arrChkVal.forEach(ele => {
            const url = "/api/foods/" + ele;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const request = new Request(url, {
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token
                },
                method: 'DELETE',
                credentials: "same-origin",
                // body: JSON.stringify({
                //     food_id: food_id;
                // })  
            });
            fetch(request)
            .then(data => {
                if (!data.status) {
                    throw new Error(data.status + ' : API 응답 오류');
                }
                return data.json();
            })
            .then(apiData => {
                console.log(apiData);
                console.log(arrChkVal);
            })
            .catch(error => console.error('Error:', error));
        })
        location.reload();
    }
}

// 음식개별삭제
function foodDel(food_id) {
    let confirmDelete = confirm("음식 정보를 삭제 하시겠습니까?");

    if (confirmDelete) {
        const url = "/api/foods/" + food_id;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const request = new Request(url, {
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token
            },
            method: 'DELETE',
            credentials: "same-origin",
            // body: JSON.stringify({
            //     food_id: food_id;
            // })  
        });
        fetch(request)
        .then(data => {
            if (!data.status) {
                throw new Error(data.status + ' : API 응답 오류');
            }
            return data.json();
        })
        .then(apiData => {
            console.log(apiData);
            alert('삭제되었습니다');
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    }
}

function inputReset(){
    const user_id = document.getElementById('user_id');
    const food_name = document.getElementById('food_name');
    const kcal = document.getElementById('kcal');
    const carbs = document.getElementById('carbs');
    const protein = document.getElementById('protein');
    const fat = document.getElementById('fat');
    const sugar = document.getElementById('sugar');
    const sodium = document.getElementById('sodium');
    const ser_unit = document.getElementById('unit0');
    const serving = document.getElementById('serving');

    user_id.value = "";
    food_name.value = "";
    kcal.value = "";
    carbs.value = "";
    protein.value = "";
    fat.value = "";
    sugar.value = "";
    sodium.value = "";
    ser_unit.checked = true;
    serving.value = "";
}

// 관리자 음식 등록
function foodinsert() {
    const url = "/api/foods";
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const user_id = document.getElementById('user_id');
    const food_name = document.getElementById('food_name');
    const kcal = document.getElementById('kcal');
    const carbs = document.getElementById('carbs');
    const protein = document.getElementById('protein');
    const fat = document.getElementById('fat');
    const sugar = document.getElementById('sugar');
    const sodium = document.getElementById('sodium');
    const serving = document.getElementById('serving');


    // 라디오버튼 값 가져오기
    const radioButtons = document.getElementsByName('ser_unit');
    let selectedValue = '';
    for (const radioButton of radioButtons) {
        if (radioButton.checked) {
            selectedValue = radioButton.value;
            break; // 선택된 라디오 버튼을 찾았으므로 반복문 종료
        }
    }

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
            user_id: user_id.value,
            food_name: food_name.value,
            kcal: kcal.value,
            carbs: carbs.value,
            protein: protein.value,
            fat: fat.value,
            sugar: sugar.value,
            sodium: sodium.value,
            ser_unit: selectedValue,
            serving: serving.value,
            userfood_flg: '0'
        })
    });
    fetch(request)
    .then(data => {
        if (!data.status) {
            throw new Error(data.status + ' : API 응답 오류');
        }
        return data.json();
    })
    .then(apiData  => {
        // 유효성검사에 걸린경우
        if(apiData["errorcode"] == 2){
            console.log(apiData);
            let errResult = apiData["msg"].join("\n");
            alert(errResult);
        }
        // 음식이름이 중복되는 경우
        else if(apiData["errorcode"] == 1){
            alert(apiData["msg"]);
        }
        // 그 외 정상처리
        else{
            console.log(apiData);
            alert('음식이 등록되었습니다.');
            // inputReset();
            location.reload();
        }
    });
}

// 모달창닫힐때
// const insertModal = new bootstrap.Modal(document.getElementById('insertModal'));

// 모달이 닫힐 때 이벤트 리스너 등록
// insertModal._element.addEventListener('hidden.bs.modal', function () {
//     location.reload(); // 모달이 닫힐 때 페이지 새로고침
// });

// 관리자 음식 수정
function foodedit(food_id) {
    const url = "/api/foods/" + food_id;
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const user_id = document.querySelector('#editModal' + food_id + ' #user_id');
    const food_name = document.querySelector('#editModal' + food_id + ' #food_name');
    const kcal = document.querySelector('#editModal' + food_id + ' #kcal');
    const carbs = document.querySelector('#editModal' + food_id + ' #carbs');
    const protein = document.querySelector('#editModal' + food_id + ' #protein');
    const fat = document.querySelector('#editModal' + food_id + ' #fat');
    const sugar = document.querySelector('#editModal' + food_id + ' #sugar');
    const sodium = document.querySelector('#editModal' + food_id + ' #sodium');
    const serving = document.querySelector('#editModal' + food_id + ' #serving');


    // 라디오버튼 값 가져오기
    const radioButtons = document.querySelectorAll('#editModal' + food_id + ' input[name="ser_unit"] ');
    let selectedValue = '';
    for (const radioButton of radioButtons) {
        if (radioButton.checked) {
            selectedValue = radioButton.value;
            break; // 선택된 라디오 버튼을 찾았으므로 반복문 종료
        }
    }

    const request = new Request(url, {
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json, text-plain, */*",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": token
            },
        method: 'PUT',
        credentials: "same-origin",
        body: JSON.stringify({
            food_name: food_name.value,
            kcal: kcal.value,
            carbs: carbs.value,
            protein: protein.value,
            fat: fat.value,
            sugar: sugar.value,
            sodium: sodium.value,
            ser_unit: selectedValue,
            serving: serving.value,
        })
    });
    fetch(request)
    .then(data => {
        if (!data.status) {
            throw new Error(data.status + ' : API 응답 오류');
        }
        return data.json();
    })
    .then(apiData  => {
        // 유효성검사에 걸린경우
        if(apiData["errorcode"] == 2){
            console.log(apiData);
            let errResult = apiData["msg"].join("\n");
            alert(errResult);
        }
        // 음식이름이 중복되는 경우
        else if(apiData["errorcode"] == 1){
            alert(apiData["msg"]);
        }
        // 그 외 정상처리
        else{
            console.log(apiData);
            alert('음식이 수정되었습니다.');
            location.reload();
        }
    });
}
