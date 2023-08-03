
function chkpass(){
    const drawbtn = document.getElementById('drawbutton');
    const url = "/api/users/passwords";
    // const pwForm = document.getElementById('pwForm');
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const request = new Request(url, {
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token
            // "Accept": "application/json, text-plain, */*",
            // "X-Requested-With": "XMLHttpRequest",
            },
        method: 'POST',
        credentials: "same-origin",
        body: JSON.stringify({
            value1: document.getElementById('bpassword').value
            ,value2: document.getElementById('id').value
        })
    });

    fetch(request)
    .then(data => {
        if (!data.status) {
            throw new Error(data.status + ' : API 응답 오류');
        }
        return data.json();
    })
    .then(data  => {
        if (data["errorcode"] === "0") {
            alert('비밀번호가 일치합니다.');
            drawbtn.disabled = false;
            // document.getElementById('passwordchg').disabled = false;
        } else {
            alert('비밀번호를 확인해주세요');
            drawbtn.disabled = true;
            return;
        }
    });
}

function userdraw(user_id){
        let confirmdraw = confirm("정말 탈퇴하시겠습니까?");
        
        if(confirmdraw){
            const url = "/api/users/"+ user_id;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const request = new Request(url,{
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token
                },
                method: 'DELETE',
                credentials: "same-origin",
                // body: JSON.stringify({
                //     user_id: document.getElementById('id').value
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
                    alert("탈퇴가 완료 되었습니다. 이용해 주셔서 감사합니다.");
                    window.location.replace("http://localhost/user/login"); // replace : 페이지를 완전 새로 시작(기존 페이지에 덮어서 앞으로 가기를 막는다.)
                }
                else{
                    alert("탈퇴 실패");
                }
            })
            .catch(error => console.error('Error:', error));
        }
        else{
            alert("취소 되었습니다.");
        }
    }





