
    function release(user_id){
        let confirmdraw = confirm("복구 시키시겠습니까?");
        if(confirmdraw){
            const url = "/api/users/status/" + user_id;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const request = new Request(url,{
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token
                },
                method:'POST',
                credentials: "same-origin",
                // body: JSON.stringify({
                //     user_id:user_id
                // })  
            });

            fetch(request)
            .then(response => {
                if (!response.ok) {
                    throw new Error(response.status + ' : API 응답 오류');
                }
                return response.json();
            })
            .then(data => {
                if(data["errorcode"]==="0"){
                    alert("정지 해제");
                    location.reload(true);
                }
                else{
                    alert("실패");
                }
            })
            .catch(error => console.error('Error:', error));
        }
        else{
            alert("취소 되었습니다.");
        }
}

// .btitle 클래스를 가진 모든 요소 가져오기
let elements = document.querySelectorAll('.btitle');

// 각 요소에 대해 반복문 실행
for (let i = 0; i < elements.length; i++) {
    let element = elements[i];
    let text = element.innerText;

    if(text.length >= 20){
        // 문자열 자르기
        let startIndex = 0; // 시작 인덱스
        let endIndex = 20; // 종료 인덱스 (10번째 문자까지 자르기)
        let cutText = text.substring(startIndex, endIndex) + '...';       
        element.innerHTML = cutText;
    }
}


//체크 박스 기능
function selectAll(selectAll)  {
const checkboxes 
        = document.getElementsByName('delchk[]');

    checkboxes.forEach((checkbox) => {
        checkbox.checked = selectAll.checked;
    })
}

//선택 삭제 일반삭제 구분 기능
// function deleteSingle(id, route) {
//     let form = document.getElementById('Dellbtn');
//     form.action = route; 
//     form.submit();
// }

// 선택 삭제 구문 준비
function massDelete(route) {

    let form = document.createElement('form');
    form.action = route;
    form.method = 'POST';

    // Add _method field
    let method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'DELETE';
    form.appendChild(method);

    // Add CSRF field
    let csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrf);

    // Get all checkboxes
    let checkboxes = document.getElementsByClassName('delchk');

    // Loop through checkboxes and add selected ones to the form
    for (let i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delchk[]';
            input.value = checkboxes[i].value;
            form.appendChild(input);
        }
    }

    // Append form to body and submit
    document.body.appendChild(form);
    form.submit();
}


// function massDelete(route) {
//     let form = document.getElementById('massDeleteForm');
//     form.action = route; 
//     form.submit();
// }

