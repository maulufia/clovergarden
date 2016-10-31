<!DOCTYPE html>
<html>
	<head>
		<title>일시후원 신청하기</title>
		<meta http-equiv='X-UA-Compatible' content='IE=edge'>
		<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi"/>
		<link rel="stylesheet" type="text/css" href="/css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="/css/mobile-pay.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="/js/mobile-pay.js"></script>
  </head>
  <body>
    <div id="mobile-pay-container" class="center">
      <img class="check" src="/imgs/pay_mobile/check.png" />
			<p style="font-size: 18px">
				감사합니다!<br />일시후원이 완료되었습니다.
			</p>

			<div class="msg-big" style="margin-top: 40px">
				<table>
					<colgroup>
						<col width="100px">
						<col width="auto">
					</colgroup>
					<tbody>
						<tr>
							<td>후원기관</td>
							<td>{{ $clover_name }}</td>
						</tr>
						<tr>
							<td>후원금</td>
							<td>{{ number_format($price) }}원</td>
						</tr>
					</tbody>
				</table>
			</div>

			<a href="clovergardenapp://" class="btn mid grey" style="margin-top: 50px;">홈으로 이동하기</a>
    </div>
  </body>
