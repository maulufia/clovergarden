<?php
  // NULL인 변수 초기화. 가비지 코드의 폐해
  // 회원관리
  $A1 = isset($A1) ? $A1 : null;
  $A2 = isset($A2) ? $A2 : null;
  $A3 = isset($A3) ? $A3 : null;
  $A4 = isset($A4) ? $A4 : null;
  $A5 = isset($A5) ? $A5 : null;
  $A6 = isset($A6) ? $A6 : null;
  $A7 = isset($A7) ? $A7 : null;
  $A8 = isset($A8) ? $A8 : null;
  $A9 = isset($A9) ? $A9 : null;

  $A1_BOLD = isset($A1_BOLD) ? $A1_BOLD : null;
  $A2_BOLD = isset($A2_BOLD) ? $A2_BOLD : null;
  $A3_BOLD = isset($A3_BOLD) ? $A3_BOLD : null;
  $A4_BOLD = isset($A4_BOLD) ? $A4_BOLD : null;
  $A5_BOLD = isset($A5_BOLD) ? $A5_BOLD : null;
  $A6_BOLD = isset($A6_BOLD) ? $A6_BOLD : null;
  $A7_BOLD = isset($A7_BOLD) ? $A7_BOLD : null;
  $A8_BOLD = isset($A8_BOLD) ? $A8_BOLD : null;
  $A9_BOLD = isset($A9_BOLD) ? $A9_BOLD : null;

  // 커뮤니티관리
  $B1 = isset($B1) ? $B1 : null;
  $B2 = isset($B2) ? $B2 : null;
  $B3 = isset($B3) ? $B3 : null;

  $B1_BOLD = isset($B1_BOLD) ? $B1_BOLD : null;
  $B2_BOLD = isset($B2_BOLD) ? $B2_BOLD : null;
  $B3_BOLD = isset($B3_BOLD) ? $B3_BOLD : null;

  // 후원기관관리
  $C1 = isset($C1) ? $C1 : null;
  $C2 = isset($C2) ? $C2 : null;
  $C3 = isset($C3) ? $C3 : null;
  $C6 = isset($C6) ? $C6 : null;
  $C8 = isset($C8) ? $C8 : null;

  $C1_BOLD = isset($C1_BOLD) ? $C1_BOLD : null;
  $C2_BOLD = isset($C2_BOLD) ? $C2_BOLD : null;
  $C3_BOLD = isset($C3_BOLD) ? $C3_BOLD : null;
  $C6_BOLD = isset($C6_BOLD) ? $C6_BOLD : null;
  $C8_BOLD = isset($C8_BOLD) ? $C8_BOLD : null;

  // 클로버관리
  $D1 = isset($D1) ? $D1 : null;
  $D2 = isset($D2) ? $D2 : null;
  $D3 = isset($D3) ? $D3 : null;
  $D4 = isset($D4) ? $D4 : null;

  $D1_BOLD = isset($D1_BOLD) ? $D1_BOLD : null;
  $D2_BOLD = isset($D2_BOLD) ? $D2_BOLD : null;
  $D3_BOLD = isset($D3_BOLD) ? $D3_BOLD : null;
  $D4_BOLD = isset($D4_BOLD) ? $D4_BOLD : null;

  // 고객센터
  $E1 = isset($E1) ? $E1 : null;
  $E2 = isset($E2) ? $E2 : null;
  $E3 = isset($E3) ? $E3 : null;

  $E1_BOLD = isset($E1_BOLD) ? $E1_BOLD : null;
  $E2_BOLD = isset($E2_BOLD) ? $E2_BOLD : null;
  $E3_BOLD = isset($E3_BOLD) ? $E3_BOLD : null;

  // 통계관리
  $G1 = isset($G1) ? $G1 : null;
  $G2 = isset($G2) ? $G2 : null;

  $G1_BOLD = isset($G1_BOLD) ? $G1_BOLD : null;
  $G2_BOLD = isset($G2_BOLD) ? $G2_BOLD : null;

  // 봉사스케줄관리
  $H1 = isset($H1) ? $H1 : null;

  $H1_BOLD = isset($H1_BOLD) ? $H1_BOLD : null;

  // 페이지관리
  $I1 = isset($I1) ? $I1 : null;
  $I2 = isset($I2) ? $I2 : null;
  $I3 = isset($I3) ? $I3 : null;
  $I4 = isset($I4) ? $I4 : null;

  $I1_BOLD = isset($I1_BOLD) ? $I1_BOLD : null;
  $I2_BOLD = isset($I2_BOLD) ? $I2_BOLD : null;
  $I3_BOLD = isset($I3_BOLD) ? $I3_BOLD : null;
  $I4_BOLD = isset($I4_BOLD) ? $I4_BOLD : null;

  // 설정
  $J1 = isset($J1) ? $J1 : null;
?>

<div id="left_area">

  <div id="nav_sub">
    <h2><span>{{ $title_txt }}</span></h2>

    <ul>
    <?php
      switch($key_large)
      {
        case "A" :
    ?>
      <li{{ $A1 }}><span{{ $A1_BOLD }}><a href="{{ route('admin/member', array('item' => 'list_admin')) }}">관리자</a></span></li>
      <li{{ $A2 }}><span{{ $A2_BOLD }}><a href="{{ route('admin/member', array('item' => 'list_normal')) }}">일반회원</a></span></li>
      <li{{ $A3 }}><span{{ $A3_BOLD }}><a href="{{ route('admin/member', array('item' => 'list_company')) }}">기업회원</a></span></li>
      <li{{ $A8 }}><span{{ $A8_BOLD }}><a href="{{ route('admin/member', array('item' => 'list_out')) }}">탈퇴회원</a></span></li>
      <li{{ $A4 }}><span{{ $A4_BOLD }}><a href="{{ route('admin/member', array('item' => 'list_apply')) }}">홍보소식지신청자</a></span></li>
      <li{{ $A5 }}><span{{ $A5_BOLD }}><a href="{{ route('admin/member', array('item' => 'history_promotion')) }}">홍보소식지발송이력</a></span></li>
      <li{{ $A7 }}><span{{ $A7_BOLD }}><a href="{{ route('admin/member', array('item' => 'history_regular')) }}">정기소식지발송이력</a></span></li>
      <li{{ $A6 }}><span{{ $A6_BOLD }}><a href="{{ route('admin/member', array('item' => 'terms')) }}">약관 관리</a></span></li>
      <li{{ $A9 }}><span{{ $A9_BOLD }}><a href="{{ route('admin/member', array('item' => 'change_clover')) }}">후원대상 변경신청</a></span></li>
      <?php
        break;
        case "B" :
      ?>
      <li{{ $B1 }}><span{{ $B1_BOLD }}><a href="{{ route('admin/community', array('item' => 'timeline')) }}">타임라인</a></span></li>
      <li{{ $B2 }}><span{{ $B2_BOLD }}><a href="{{ route('admin/community', array('item' => 'board_sponsor')) }}">후원자유게시판</a></span></li>
      <li{{ $B3 }}><span{{ $B3_BOLD }}><a href="{{ route('admin/community', array('item' => 'banner')) }}">배너관리</a></span></li>
      <?php
        break;
        case "C" :
      ?>
        <?php if(Auth::user()->user_state > 6){ ?>
          <li{{ $C1 }}><span{{ $C1_BOLD }}><a href="{{ route('admin/clover', array('item' => 'list_clover')) }}">후원기관목록</a></span></li>
          <li{{ $C2 }}><span{{ $C2_BOLD }}><a href="{{ route('admin/clover', array('item' => 'apply_clover')) }}">후원등록/연동관리</a></span></li>
        <?php } ?>
          <li{{ $C3 }}><span{{ $C3_BOLD }}><a href="{{ route('admin/clover', array('item' => 'news')) }}">후원기관소식</a></span></li>
        <?php if(Auth::user()->user_state > 6){ ?>
          <li{{ $C6 }}><span{{ $C6_BOLD }}><a href="{{ route('admin/clover', array('item' => 'banner')) }}">후원기관 배너등록</a></span></li>
  		  <?php } ?>
          <li{{ $C8 }}><span{{ $C8_BOLD }}><a href="{{ route('admin/clover', array('item' => 'list_urgency')) }}">긴급후원관리</a></span></li>
      <?php
        break;
        case "D" :
      ?>
        <li{{ $D1 }}><span{{ $D1_BOLD }}><a href="{{ route('admin/sponsor', array('item' => 'companion')) }}">함꼐하는사람들</a></span></li>
        <li{{ $D2 }}><span{{ $D2_BOLD }}><a href="{{ route('admin/sponsor', array('item' => 'deans')) }}">이달의클로버</a></span></li>
			  <li{{ $D3 }}><span{{ $D3_BOLD }}><a href="{{ route('admin/sponsor', array('item' => 'main_companion')) }}">메인] 함께하는 제휴업체</a></span></li>
				<li{{ $D4 }}><span{{ $D4_BOLD }}><a href="{{ route('admin/sponsor', array('item' => 'main_deans')) }}">메인] 이달의 클로버</a></span></li>
      <?php
        break;
        case "E" :
      ?>
        <li{{ $E1 }}><span{{ $E1_BOLD }}><a href="{{ route('admin/customer', array('item' => 'news')) }}">새소식</a></span></li>
        <li{{ $E2 }}><span{{ $E2_BOLD }}><a href="{{ route('admin/customer', array('item' => 'qna')) }}">1:1문의</a></span></li>
				<li{{ $E3 }}><span{{ $E3_BOLD }}><a href="{{ route('admin/customer', array('item' => 'faq')) }}">자주묻는질문</a></span></li>
      <?php
        break;
				case "H" :
      ?>
        <li{{ $H1 }}><span{{ $H1_BOLD }}><a href="{{ route('admin/service', array('item' => 'home')) }}">봉사스케쥴관리</a></span></li>
      <?php
        break;
        case "G" :
      ?>
        <li{{ $G1 }}><span{{ $G1_BOLD }}><a href="{{ route('admin/stat', array('item' => 'stat_day')) }}">방문통계-일별</a></span></li>
        <li{{ $G2 }}><span{{ $G2_BOLD }}><a href="{{ route('admin/stat', array('item' => 'stat_month')) }}">방문통계-월별</a></span></li>
      <?php
        break;
        case "I" :
      ?>
        <li{{ $I1 }}><span{{ $I1_BOLD }}><a href="{{ route('admin/page', array('item' => 'intro')) }}">회사소개</a></span></li>
        <li{{ $I4 }}><span{{ $I3_BOLD }}><a href="{{ route('admin/page', array('item' => 'information_private')) }}">이용안내-개인</a></span></li>
        <li{{ $I2 }}><span{{ $I2_BOLD }}><a href="{{ route('admin/page', array('item' => 'information_company')) }}">이용안내-기업</a></span></li>
        <li{{ $I3 }}><span{{ $I3_BOLD }}><a href="{{ route('admin/page', array('item' => 'apply_support')) }}">이용안내-후원금신청</a></span></li>
      <?php
        break;
        case "J":
      ?>
        <li{{ $J1 }}><span{{ $J1_BOLD }}><a href="{{ route('admin/setting', array('item' => 'popup')) }}">팝업 관리</a></span></li>
      <?php
        break;
        }
      ?>
      </ul>
    </div>

</div>
