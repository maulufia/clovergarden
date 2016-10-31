// 숫자 타입에서 쓸 수 있도록 format() 함수 추가
Number.prototype.format = function(){
    if(this==0) return 0;

    var reg = /(^[+-]?\d+)(\d{3})/;
    var n = (this + '');

    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');

    return n;
};

$(document).ready(function() {
	// Variable
	// 우편번호 찾기 화면을 넣을 element
	element_layer = document.getElementById('postLayer');

	// Set UI
	$('#price-container a').click(function() {
		$('#price-container a').removeClass('on');
		$(this).addClass('on');

		if ($(this).is($('#price-container a').last())) {
			$('#price-input').show();
		} else {
			$('#price-input').hide();
		}
	});

	// Set value
	$('#price-container a').click(function() {
		if ($(this).is($('#price-container a').eq(0))) {
			$('input[name=price]').val('10000');
			$('input[name=Amt]').val('10000');
			$('#point').text('10,000');
		} else if ($(this).is($('#price-container a').eq(1))) {
			$('input[name=price]').val('30000');
			$('input[name=Amt]').val('30000');
			$('#point').text('30,000');
		} else if ($(this).is($('#price-container a').eq(2))) {
			$('input[name=price]').val('50000');
			$('input[name=Amt]').val('50000');
			$('#point').text('50,000');
		} else if ($(this).is($('#price-container a').eq(3))) {
			$('input[name=price]').val('70000');
			$('input[name=Amt]').val('70000');
			$('#point').text('70,000');
		} else if ($(this).is($('#price-container a').eq(4))) {
			$('input[name=price]').val('100000');
			$('input[name=Amt]').val('100000');
			$('#point').text('100,000');
		} else if ($(this).is($('#price-container a').eq(5))) {
			var price = $('input[name=price_option]').val();
			$('#point').text((price * 1000).format());
		}
	});

	// Set UI
	$('#method-reserve > ul li').click(function() {
		$('#method-reserve > ul li').removeClass('on');
		$(this).addClass('on');

		if ($(this).is($('#method-reserve > ul li').eq(0))) {
			$('#payment-bankbook').show();
			$('#payment-card').hide();
			$('#payment-point').hide();
		} else if ($(this).is($('#method-reserve > ul li').eq(1))) {
			$('#payment-bankbook').hide();
			$('#payment-card').show();
			$('#payment-point').hide();
		} else if ($(this).is($('#method-reserve > ul li').eq(2))) {
			$('#payment-bankbook').hide();
			$('#payment-card').hide();
			$('#payment-point').show();
		}
	});

	// Set value
	$('#method-reserve > ul li').click(function() {
		if ($(this).is($('#method-reserve > ul li').eq(0))) {
			$('input[name=otype]').val('자동이체');
		} else if ($(this).is($('#method-reserve > ul li').eq(1))) {
			$('input[name=otype]').val('신용카드');
		} else if ($(this).is($('#method-reserve > ul li').eq(2))) {
			$('input[name=otype]').val('포인트');
		}
	});

	// Set UI
	$('#method-temp > ul li').click(function() {
		$('#method-temp > ul li').removeClass('on');
		$(this).addClass('on');

		if ($(this).is($('#method-temp > ul li').eq(0))) {
			$('#payment-point').hide();

			$('#btnSubmit').show();
			$('#btnSubmitPoint').hide();
		} else if ($(this).is($('#method-temp > ul li').eq(1))) {
			$('#payment-point').show();

			$('#btnSubmit').hide();
			$('#btnSubmitPoint').show();
		}
	});
});

// 포인트 후원 시
function doPayByPoint(form){
	if (!proceedValidation(true)) {
		return;
	}

	if(parseInt(form.price.value) > parseInt(form.usepoint.value)){
		alert('보유한 포인트보다 후원할 포인트가 더 많습니다.');
		return;
	}

	form.action = "/mobile/execTempPointSupport";
	form.submit();
}

// 폼 검증
function proceedValidation(isTempSupport)
{
	// 후원금액 기타로 설정한 경우
	if ($('#price-container a').last().hasClass('on')) {
		var price = $('input[name=price_option]').val().replace(/,/g, '');
		if (price == '' || price == 0) {
			alert('후원금액을 입력해주세요.');
			return false;
		}
		$('input[name=price]').val(price * 1000);
		$('input[name=Amt]').val(price * 1000);
	}

	var fm = document.frmags5pay;

	if (isTempSupport) { // 일시후원 값 설정
		fm.OrdPhone.value = fm.cell1.value + fm.cell2.value + fm.cell3.value;
		fm.OrdAddr.value = fm.addr.value;
	} else { // 정기후원 값 설정
		// 자동이체일 때
		if ($('#method-reserve > ul li').eq(0).hasClass('on')) {
			if(fm.bank.value == ''){
				alert('은행명을 선택해주세요.');
				return false;
			}
			if(fm.banknum.value == ''){
				alert('계좌번호를 입력해주세요.');
				return false;
			}
		}
		// 신용카드일 때
		else if ($('#method-reserve > ul li').eq(1).hasClass('on')) {
			if(fm.card.value == ''){
				alert('카드사를 선택해주세요.');
				return false;
			}
			if(fm.cardnum.value == ''){
				alert('카드번호를 입력해주세요.');
				return false;
			}
			if(fm.carddate1.value == ''){
				alert('유효기간을 입력해주세요.');
				return false;
			}
			if(fm.carddate2.value == ''){
				alert('유효기간을 입력해주세요.');
				return false;
			}
		}
	}

	// 공통 정보
	if(fm.birth.value == ''){
		alert('생년월일을 입력해주세요.');
		return false;
	} else if (fm.birth.value.length != 6) {
		alert('생년월일은 6자리여야 합니다.');
		return false;
	}
	if(fm.cell1.value == ''){
		alert('휴대폰 번호를 입력해주세요.');
		return false;
	}
	if(fm.cell2.value == ''){
		alert('휴대폰 번호를 입력해주세요.');
		return false;
	}
	if(fm.cell3.value == ''){
		alert('휴대폰 번호를 입력해주세요.');
		return false;
	}

	if(confirm('기입해주신 정보로 후원이 신청됩니다.')){
			return true;
	}

	return false;
}

function closeDaumPostcode() {
    // iframe을 넣은 element를 안보이게 한다.
    element_layer.style.display = 'none';
}

// 우편번호 찾기
function execDaumPostcode()
{
	new daum.Postcode({
		oncomplete: function(data) {
            // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

            // 각 주소의 노출 규칙에 따라 주소를 조합한다.
            // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
            var fullAddr = data.address; // 최종 주소 변수
            var extraAddr = ''; // 조합형 주소 변수

            // 기본 주소가 도로명 타입일때 조합한다.
            if(data.addressType === 'R'){
                //법정동명이 있을 경우 추가한다.
                if(data.bname !== ''){
                    extraAddr += data.bname;
                }
                // 건물명이 있을 경우 추가한다.
                if(data.buildingName !== ''){
                    extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                }
                // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
            }

            // 우편번호와 주소 정보를 해당 필드에 넣는다.
            document.getElementById("postcode1").value = data.postcode1;
            document.getElementById("postcode2").value = data.postcode2;
            document.getElementById("address").value = fullAddr;
						document.getElementById("address2").value = "";

            // 커서를 상세주소 필드로 이동한다.
            document.getElementById("address2").focus();

            // iframe을 넣은 element를 안보이게 한다.
            // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
            element_layer.style.display = 'none';
        },
        width : '100%',
        height : '100%'
    }).embed(element_layer);

    // iframe을 넣은 element를 보이게 한다.
    element_layer.style.display = 'block';

    // iframe을 넣은 element의 위치를 화면의 가운데로 이동시킨다.
    initLayerPosition();
}

// 브라우저의 크기 변경에 따라 레이어를 가운데로 이동시키고자 하실때에는
// resize이벤트나, orientationchange이벤트를 이용하여 값이 변경될때마다 아래 함수를 실행 시켜 주시거나,
// 직접 element_layer의 top,left값을 수정해 주시면 됩니다.
function initLayerPosition(){
    var width = 300; //우편번호서비스가 들어갈 element의 width
    var height = 460; //우편번호서비스가 들어갈 element의 height
    var borderWidth = 5; //샘플에서 사용하는 border의 두께

    // 위에서 선언한 값들을 실제 element에 넣는다.
    element_layer.style.width = width + 'px';
    element_layer.style.height = height + 'px';
    element_layer.style.border = borderWidth + 'px solid';
    // 실행되는 순간의 화면 너비와 높이 값을 가져와서 중앙에 뜰 수 있도록 위치를 계산한다.
    element_layer.style.left = (((window.innerWidth || document.documentElement.clientWidth) - width)/2 - borderWidth) + 'px';
    // element_layer.style.top = (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px';
		element_layer.style.top = 133 + 'px';
}


// 금액 포매팅
function inputNumberFormat(object)
{
	updatePoint($(object).val());

	var num = $(object).val().replace(/(\s)/g, '');
  $(object).val(function(index, value) {
    return value
    .replace(/\D/g, "")
    .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    ;
  });
}

function updatePoint(price)
{
	$('#point').text((price * 1000).format());
}
