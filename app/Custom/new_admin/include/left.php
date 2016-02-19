<div id="left_area">
    
    <div id="nav_sub">
        <h2><span><?=$title_txt?></span></h2>
       
        <ul>
        <?php
            switch($key_large)
            {
                case "A" :
                ?>
                     <li<?=$A1?>><span<?=$A1_BOLD?>><a href="../member/m_01_list.php">관리자</a></span></li>
                     <li<?=$A2?>><span<?=$A2_BOLD?>><a href="../member/m_02_list.php">일반회원</a></span></li>
					 <li<?=$A3?>><span<?=$A3_BOLD?>><a href="../member/m_03_list.php">기업회원</a></span></li>
					 <li<?=$A8?>><span<?=$A8_BOLD?>><a href="../member/m_08_list.php">탈퇴회원</a></span></li>
					 <li<?=$A4?>><span<?=$A4_BOLD?>><a href="../member/m_04_list.php">홍보소식지신청자</a></span></li>
					 <li<?=$A5?>><span<?=$A5_BOLD?>><a href="../member/m_05_list.php">홍보소식지발송이력</a></span></li>
					 <li<?=$A7?>><span<?=$A7_BOLD?>><a href="../member/m_07_list.php">정기소식지발송이력</a></span></li>
					 <li<?=$A6?>><span<?=$A6_BOLD?>><a href="../member/m_06_list.php">약관 관리</a></span></li>
					 <li<?=$A9?>><span<?=$A9_BOLD?>><a href="../member/m_09_list.php">후원대상 변경신청</a></span></li>
                <?
                break;
                case "B" :
                ?>
                     <li<?=$B1?>><span<?=$B1_BOLD?>><a href="../community/m_01_list.php">타임라인</a></span></li>
                     <li<?=$B2?>><span<?=$B2_BOLD?>><a href="../community/m_02_list.php">후원자유게시판</a></span></li>
                     <!-- <li<?=$B4?>><span<?=$B4_BOLD?>><a href="../community/m_04_list.php">기관자유게시판</a></span></li> -->
					 <li<?=$B3?>><span<?=$B3_BOLD?>><a href="../community/m_03_list.php">배너관리</a></span></li>
                <?
                break;
                case "C" :
                ?>
				     <?if($login_state > 6){?>
                     <li<?=$C1?>><span<?=$C1_BOLD?>><a href="../clover/m_01_list.php">후원기관목록</a></span></li>
					 <li<?=$C2?>><span<?=$C2_BOLD?>><a href="../clover/m_02_list.php">후원등록/연동관리</a></span></li>
					 <?}?>
					 <li<?=$C3?>><span<?=$C3_BOLD?>><a href="../clover/m_03_list.php">후원기관소식</a></span></li>
					 <?if($login_state > 6){?>
                     <!-- <li<?=$C4?>><span<?=$C4_BOLD?>><a href="../clover/m_04_list.php">후원목록</a></span></li>
					 <li<?=$C5?>><span<?=$C5_BOLD?>><a href="../clover/m_05_list.php">* 후원목록</a></span></li> -->
					 <li<?=$C6?>><span<?=$C6_BOLD?>><a href="../clover/m_06_list.php">후원기관 배너등록</a></span></li>
					 <!-- <li<?=$C7?>><span<?=$C7_BOLD?>><a href="../clover/m_07_list.php">후원대상 변경신청</a></span></li> -->
					 <?}?>
                <?
                break;
                case "D" :
                ?>
                     <li<?=$D1?>><span<?=$D1_BOLD?>><a href="../sponsor/m_01_list.php">함꼐하는사람들</a></span></li>
                     <li<?=$D2?>><span<?=$D2_BOLD?>><a href="../sponsor/m_02_list.php">이달의클로버</a></span></li>
					 <li<?=$D3?>><span<?=$D3_BOLD?>><a href="../sponsor/m_03_list.php">메인] 함께하는 제휴업체</a></span></li>
					 <li<?=$D4?>><span<?=$D4_BOLD?>><a href="../sponsor/m_04_list.php">메인] 이달의 클로버</a></span></li>
                <?
                break;
                case "E" :
                ?>
                     <li<?=$E1?>><span<?=$E1_BOLD?>><a href="../customer/m_01_list.php">새소식</a></span></li>
                     <li<?=$E2?>><span<?=$E2_BOLD?>><a href="../customer/m_02_list.php">1:1문의</a></span></li>
					 <li<?=$E3?>><span<?=$E3_BOLD?>><a href="../customer/m_03_list.php">자주묻는질문</a></span></li>
                <?
                break;
				case "H" :
                ?>
                     <li<?=$H1?>><span<?=$H1_BOLD?>><a href="../service/m_01_list.php">봉사스케쥴관리</a></span></li>
                <?
                break;
                case "G" :
                ?>
                     <li<?=$G1?>><span<?=$G1_BOLD?>><a href="../stats/m_01_list.php">방문통계-일별</a></span></li>
                     <li<?=$G2?>><span<?=$G2_BOLD?>><a href="../stats/m_02_list.php">방문통계-월별</a></span></li>
                <?
                break;
                case "I" :
                ?>
                     <li<?=$I1?>><span<?=$I1_BOLD?>><a href="../page/p_01.php">회사소개</a></span></li>
                     <li<?=$I4?>><span<?=$I3_BOLD?>><a href="../page/p_04.php">이용안내-개인</a></span></li>
                     <li<?=$I2?>><span<?=$I2_BOLD?>><a href="../page/p_02.php">이용안내-기업</a></span></li>
                     <li<?=$I3?>><span<?=$I3_BOLD?>><a href="../page/p_03.php">이용안내-후원금신청</a></span></li>
                <?
                break;
            }
            ?>
        </ul>
    </div>
   
</div>