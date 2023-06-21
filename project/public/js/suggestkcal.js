const suggetskcal = document.getElementById('suggest');


suggetskcal.addEventListener('click', function(){
    const gender = document.getElementById('gender').value;
    const birthDate = new Date(document.getElementById('user_birth').value);
    const tall = document.getElementById('tall').value;
    const weight = document.getElementById('weight').value;
    const acctivaty = document.querySelector('input[name="acctivaty"]:checked').value; //입력된 값안에서 이름이 name으로 된 부분에서 체크된 부분의 value를 반환시킨다. 
    const today = new Date();
    // const suggest_kcal = document.getElementById('goal_kcal');
    let age = today.getFullYear() - birthDate.getFullYear() + 1;

    if(gender === "0"){
        let BMR = (10 * weight)+(6.25 * tall)-(5 * age)+5
            if(acctivaty ==="0"){
                document.getElementById('goal_kcal').value = BMR * 1.2;
            }
            else if(acctivaty ==="1"){
                document.getElementById('goal_kcal').value = BMR * 1.55;
            }
            else{
                document.getElementById('goal_kcal').value = BMR * 1.9;
            }
        }
    else{
        let BMR = (10 * weight)+(6.25 * tall)-(5 * age)-161
            if(acctivaty ==="0"){
                document.getElementById('goal_kcal').value = BMR * 1.2;
            }
            else if(acctivaty ==="1"){
                document.getElementById('goal_kcal').value = BMR * 1.55;
            }
            else{
                document.getElementById('goal_kcal').value = BMR * 1.9;
            }
        }
    });



// {(10*체중)  + ( 6.25*키)   -( 5*나이 )  + 5}*1.2(활동지수)
// {(10*체중) + (6.25*키) -(5*나이)-161}*1.2(활동지수)

//남자{(10*weight)+(6.25*tall) - (5*age)+ 5}*(acctivaty) 
//여자{(10*weight)+(6.25*tall) - (5*age)-161}*(acctivaty)

// 활동지수 : 적음 = 1.2 / 보통 = 1.55 / 많음 = 1.9