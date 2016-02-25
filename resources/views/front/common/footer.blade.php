	<script type="text/javascript" src="{{ url('js/jquery.imageScroller.js') }}"></script>
	
	<style type="text/css">
	.prtner_scroll {width:1000px; overflow:hidden;margin:0 auto; margin-top:30px;}
	.partner {width:100px; float:left; margin-right:20px;}
	.scroll {float:left; margin-left:15px;}

	div#scroller {position:relative;height:50px; width:850px; margin:0; clear:both; overflow:hidden; }

	/*좌우버튼*/
	div#scroller .title { height:30px; position:absolute; top:0px; left:0px; }
	#btn1, #btn2 {cursor:pointer}
	#btn1 { position:absolute; top:5px; left:0px; }
	#btn2 { position:absolute; top:5px; left:823px; }

	ul#scrollerFrame { position:absolute; top:0px; left:27px; width:795px; padding:0;margin:0;list-style:none; overflow:hidden; }
	ul#scrollerFrame li {position:relative; float:left; height:215px; margin-right:10px; text-align:left; }

	/*설명 부분*/
	ul#scrollerFrame li p {margin:0;padding:0}
	ul#scrollerFrame li p.price{font-family:verdana;font-size:12px;font-weight:bold;margin-top:7px;text-align:center;color:#0a62cf}
	/* img width error ( temporary deleted - YJM ) */
	</style>

	<script type="text/javascript">
	$(function(){		     
		$("#scroller").imageScroller({
			next:"btn1",                   //다음 버튼 ID값
			prev:"btn2",                   //이전 버튼 ID값
			frame:"scrollerFrame",         //스크롤러 프레임 ID값  
			width:100,                     //이미지 가로 크기
			child:"li",                    //스크롤 지정 태그
			auto:true                      //오토 롤링 (해제는 false)
		});
	});
	</script>
	
	<?php
		$nBanner = new BannerClass(); //

		//======================== DB Module Freet ============================
		$Conn = new DBClass();
		
		// 이 값들이 뭔지 잘 모르겠음
		$search_key = '';
		$search_val = '';

		$nBanner->total_record = $Conn->PageListCount
		(
			$nBanner->table_name, $nBanner->where, $search_key, $search_val
		);

		$nBanner->page_result = $Conn->AllList
		(	
			$nBanner->table_name, $nBanner, "*", "where 1 order by seq desc limit ".$nBanner->total_record."", null, null
		);


		$Conn->DisConnect();
		//======================== DB Module End ===============================
	?>
	
	<!-- Partner -->
	<div class="prtner_scroll">
		<div class="partner"><img src="{{ url('imgs/CloverGardenPartner.png') }}" alt="CloverGardenPartner" /></div>
		
		<div class="scroll">
			<div id="scroller">
				<div class="title">
					<span id="btn1" style="float:left;"><img src="{{ url('imgs/banner_arrow_l.png') }}"></span>
					<span id="btn2" style="float:right;"><img src="{{ url('imgs/banner_arrow_r.png') }}"></span>
				</div>

				<ul id="scrollerFrame">

					<?php
						if(count($nBanner->page_result) > 0){
							for($i=0, $cnt_list=count($nBanner->page_result); $i < $cnt_list; $i++) {
								$nBanner->VarList($nBanner->page_result, $i,  array('comment'));
								$nBanner->url = str_replace("http://","",$nBanner->url);
					?>
						<li>
							<a href="http://{{ $nBanner->url }}" target="_blank">					
								<?php
										echo "<img src='/imgs/up_file/Banner/".$nBanner->file_edit[1]."' border='0' style='width: 100px;'>";
								?>
							</a>
						</li>

					<?php
							}
						}
					?>
					
				</ul>
			</div>
		</div>
	</div>
	<div class="xm_clr"></div>
	
	<!-- Footer -->
  <footer id="site-footer">
    <div class="wrap">
      <!-- Footer Logo -->
      <div id="footer_logo">
        <img src="{{ url('imgs/BottomLogo.png') }}" alt="clover garden" />
      </div>

			<script type="text/javascript">
				<!--
				function pri_pop(type){
				 window.open("{{ route('popup') }}?view="+type,"","'fullscreen=0,toolbar=0,scrollbars=0,status=0,menubar=0,width=499, height=554'");
				}	
				//-->
			</script>
            
      <div id="footer">
			  <ul class="footer_nav">
					<li><a href="{{ route('customer', array('cate' => 4, 'dep01' => 3)) }}">회사소개</a></li>
					<li><a href="javascript:pri_pop('use');">이용약관</a></li>
					<li><a href="javascript:pri_pop('pri');">개인정보취급방침</a></li>
					<li><a href="{{ route('customer', array('cate' => 4, 'dep01' => 1)) }}">관리자문의</a></li>
				</ul>
				<div class="xm_clr"></div>

				<address>
					<ul>
						<li>회사명 : (주)클로버가든</li>
						<li>주소 : 서울시 중구 남대문로 120 대일빌딩 3층</li>
						<li>대표 : 김완신</li>
						<li>이메일 : <a href="mailto:Director@clovergarden.co.kr">Director@clovergarden.co.kr</a></li>
					</ul>
					<div class="xm_clr"></div>

					<ul>
						<li>전화 : 02-720-3235</li>
						<li>팩스 : 02-720-1016</li>
						<li>업무시간 : 10:00 ~ 19:00</li>
						<li>사업자등록번호 : 110-86-14028</li>
						<li></li>
					</ul>
					<div class="xm_clr"></div>

					<ul>
						<li>개인정보책임자 이유진</li>
						<li>이메일 : <a href="mailto:manager@clovergarden.co.kr">manager@clovergarden.co.kr</a></li>
					</ul>
				</address>
				<div class="xm_clr"></div>

				<div class="copyright">본 홈페이지는 정부 중소기업청 "창업아이템사업화" 지원 하에 제작된 것입니다.<br/>COPYRIGHT @ Clover Garden. ALL RIGHT RESERVED</div>
      </div>
    </div>
  </footer>
</div>

</body>
</html>