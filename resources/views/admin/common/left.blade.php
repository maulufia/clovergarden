<?php
  // NULL인 변수 초기화. 가비지 코드의 폐해
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
      <li{{ $B1 }}><span{{ $B1_BOLD }}><a href="../community/m_01_list.php">타임라인</a></span></li>
      <li{{ $B2 }}><span{{ $B2_BOLD }}><a href="../community/m_02_list.php">후원자유게시판</a></span></li>
      <!-- <li<?=$B4?>><span<?=$B4_BOLD?>><a href="../community/m_04_list.php">기관자유게시판</a></span></li> -->
      <li{{ $B3 }}><span{{ $B3_BOLD }}><a href="../community/m_03_list.php">배너관리</a></span></li>
      <?php
        break;
        case "C" :
      ?>
        <?php if(Auth::user()->user_state > 6){ ?>
          <li{{ $C1 }}><span{{ $C1_BOLD }}><a href="../clover/m_01_list.php">후원기관목록</a></span></li>
          <li{{ $C2 }}><span{{ $C2_BOLD }}><a href="../clover/m_02_list.php">후원등록/연동관리</a></span></li>
          <?php } ?>
          <li{{ $C3 }}><span{{ $C3_BOLD }}><a href="../clover/m_03_list.php">후원기관소식</a></span></li>
        <?php if(Auth::user()->user_state > 6){ ?>
          <!-- <li<?=$C4?>><span<?=$C4_BOLD?>><a href="../clover/m_04_list.php">후원목록</a></span></li>
          <li<?=$C5?>><span<?=$C5_BOLD?>><a href="../clover/m_05_list.php">* 후원목록</a></span></li> -->
          <li{{ $C6 }}><span{{ $C6_BOLD }}><a href="../clover/m_06_list.php">후원기관 배너등록</a></span></li>
          <!-- <li<?=$C7?>><span<?=$C7_BOLD?>><a href="../clover/m_07_list.php">후원대상 변경신청</a></span></li> -->
  		  <?php } ?>
      <?php
        break;
        case "D" :
      ?>
        <li{{ $D1 }}><span{{ $D1_BOLD }}><a href="../sponsor/m_01_list.php">함꼐하는사람들</a></span></li>
        <li{{ $D2 }}><span{{ $D2_BOLD }}><a href="../sponsor/m_02_list.php">이달의클로버</a></span></li>
			  <li{{ $D3 }}><span{{ $D3_BOLD }}><a href="../sponsor/m_03_list.php">메인] 함께하는 제휴업체</a></span></li>
				<li{{ $D4 }}><span{{ $D4_BOLD }}><a href="../sponsor/m_04_list.php">메인] 이달의 클로버</a></span></li>
      <?php
        break;
        case "E" :
      ?>
        <li{{ $E1 }}><span{{ $E1_BOLD }}><a href="../customer/m_01_list.php">새소식</a></span></li>
        <li{{ $E2 }}><span{{ $E2_BOLD }}><a href="../customer/m_02_list.php">1:1문의</a></span></li>
				<li{{ $E3 }}><span{{ $E3_BOLD }}><a href="../customer/m_03_list.php">자주묻는질문</a></span></li>
      <?php
        break;
				case "H" :
      ?>
        <li{{ $H1 }}><span{{ $H1_BOLD }}><a href="../service/m_01_list.php">봉사스케쥴관리</a></span></li>
      <?php
        break;
        case "G" :
      ?>
        <li{{ $G1 }}><span{{ $G1_BOLD }}><a href="../stats/m_01_list.php">방문통계-일별</a></span></li>
        <li{{ $G2 }}><span{{ $G2_BOLD }}><a href="../stats/m_02_list.php">방문통계-월별</a></span></li>
      <?php
        break;
        case "I" :
      ?>
        <li{{ $I1 }}><span{{ $I1_BOLD }}><a href="../page/p_01.php">회사소개</a></span></li>
        <li{{ $I4 }}><span{{ $I3_BOLD }}><a href="../page/p_04.php">이용안내-개인</a></span></li>
        <li{{ $I2 }}><span{{ $I2_BOLD }}><a href="../page/p_02.php">이용안내-기업</a></span></li>
        <li{{ $I3 }}><span{{ $I3_BOLD }}><a href="../page/p_03.php">이용안내-후원금신청</a></span></li>
      <?php
        break;
        }
      ?>
      </ul>
    </div>
   
</div>