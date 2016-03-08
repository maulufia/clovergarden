<?php
    /*------------------------------------------------------------------------------------------------------*
     * @copyright   : grandsurgery
     * @description : 페이징처리 클래스
     * @author      : 김동영 kdy0602@nate.com
     * @created     : 2012.03
     *------------------------------------------------------------------------------------------------------*/

    settype($nPage, 'object');

    class PageOut
    {
        var $start_page;
        var $last_page;
        var $pages_number;
        var $end_pages_number;
        var $pre_page;
        var $next_page;

        var $start_page_img;
        var $pre_page_img;
        var $next_page_img;
        var $last_page_img;

        function PageOut()
        {
            settype($this->start_page, 'int');
            settype($this->last_page, 'int');
            settype($this->pages_number, 'int');
            settype($this->end_pages_number, 'int');
            settype($this->pre_page, 'int');
            settype($this->next_page, 'int');

            settype($this->start_page_img[10], 'array');
            settype($this->pre_page_img[10], 'array');
            settype($this->next_page_img[10], 'array');
            settype($this->last_page_img[10], 'array');

            $this->start_page_img[0] = "<img src='/imgs/new_images/bt_prev02.gif' alt='처음' />";
            $this->pre_page_img[0]   = "<img src='/imgs/new_images/bt_prev01.gif' alt='이전' />";
            $this->next_page_img[0]  = "<img src='/imgs/new_images/bt_next01.gif' alt='다음' />";
            $this->last_page_img[0]  = "<img src='/imgs/new_images/bt_next02.gif' alt='끝' />";

            $this->start_page_img[1] = "&nbsp;";
            $this->pre_page_img[1]   = "&nbsp;";
            $this->next_page_img[1]  = "&nbsp;";
            $this->last_page_img[1]  = "&nbsp;";
        }

        /*
        function __destruct()
        {
            $this->start_page      = '';
            $this->last_page       = '';
            $this->pages_number    = '';
            $this->endPages_number = '';
            $this->pre_page        = '';
            $this->next_page       = '';
        }
        */

        /*------------------------------------------------------------------------------------------------------*
         * 페이지
         *------------------------------------------------------------------------------------------------------*/
        function PageOutSet($pTotalRecord, $pPage, $pPageView, $pPageSet, $pPageWhere)
        {
            $this->start_page = ((int)(($pPage - 1) / $pPageSet) * $pPageSet) + 1;
            $this->end_pages_number = $this->start_page + $pPageSet - 1;
            $this->last_page = (int)(($pTotalRecord + $pPageView - 1) / $pPageView);
            if($this->end_pages_number >= $this->last_page) $this->end_pages_number = $this->last_page;
            if($this->start_page != 1) $this->pre_page = $this->start_page - $pPageWhere;
            if(($this->start_page + $pPageWhere) <= $this->last_page) $this->next_page = $this->start_page + $pPageWhere;
        }

        /*------------------------------------------------------------------------------------------------------*
         * 관리자 페이지번호
         *------------------------------------------------------------------------------------------------------*/
        function AdminPageList($pTotalRecord, $pPage=1, $pPageView=10, $pPageSet=10, $pPageWhere, $pScript, $pNum=0)
        {
            if($pNum == "" || $pNum == null) $pNum = 0;
            $this->PageOutSet($pTotalRecord, $pPage, $pPageView, $pPageSet, $pPageWhere);

            echo "<a href=".Chr(34)."javascript:".$pScript."('1');".Chr(34).">".$this->start_page_img[$pNum]."</a>".Chr(10);
            if($this->pre_page != 0){
                echo "<a href=".Chr(34)."javascript:".$pScript."('".$this->pre_page."');".Chr(34).">".$this->pre_page_img[$pNum]."</a>".Chr(10);
            }else{
                echo $this->pre_page_img[$pNum].Chr(10);
            }
            for($this->pages_number=$this->start_page; $this->pages_number <= $this->end_pages_number; $this->pages_number++) {
                if((int)($this->pages_number == $pPage)){
                    echo "<b>".$this->pages_number."</b>".Chr(10);
                }else{
                    echo "<a href=".Chr(34)."javascript:".$pScript."('".$this->pages_number."');".Chr(34).">".$this->pages_number."</a>".Chr(10);
                }
            }
            if($this->next_page != 0){
                echo "<a href=".Chr(34)."javascript:".$pScript."('".$this->next_page."');".Chr(34).">".$this->next_page_img[$pNum]."</a>".Chr(10);
            }else{
                echo $this->next_page_img[$pNum].Chr(10);
            }
            echo "<a href=".Chr(34)."javascript:".$pScript."('".$this->last_page."');".Chr(34).">".$this->last_page_img[$pNum]."</a>";
        }

		function CustomPageList($pTotalRecord, $pPage=1, $pPageView=10, $pPageSet=10, $pPageWhere, $pScript, $pNum=0)
        {
            if($pNum == "" || $pNum == null) $pNum = 0;

            $this->PageOutSet($pTotalRecord, $pPage, $pPageView, $pPageSet, $pPageWhere);

			if($pPage==1){
				echo "<a href='javascript:alert(\"첫페이지 입니다.\")'; class='nor'>&lt;&lt; 맨처음</a>";				
			}else{
				echo "<a href=".Chr(34)."javascript:".$pScript."('1');".Chr(34)." class='nor'>&lt;&lt; 맨처음</a>".Chr(10);
			}
            
            if($this->pre_page != 0){
                echo "<a href=".Chr(34)."javascript:".$pScript."('".$this->pre_page."');".Chr(34)." class='nor'>&lt; 이전</a>".Chr(10);
            }else{
                echo "<a href='javascript:alert(\"첫페이지 입니다.\")'; class='nor'>&lt; 이전</a>";
            }

            for($this->pages_number=$this->start_page; $this->pages_number <= $this->end_pages_number; $this->pages_number++) {
                if((int)($this->pages_number == $pPage)){
                    echo "<a href=".Chr(34)."javascript:".$pScript."('".$this->pages_number."');".Chr(34)." class='on'>".$this->pages_number."</a>".Chr(10);
                }else{
                    echo "<a href=".Chr(34)."javascript:".$pScript."('".$this->pages_number."');".Chr(34).">".$this->pages_number."</a>".Chr(10);
                }
            }

            if($this->next_page != 0){
                echo "<a href=".Chr(34)."javascript:".$pScript."('".$this->next_page."');".Chr(34)." class='nor'>다음 &gt;</a>".Chr(10);
            }else{
                echo "<a href='javascript:alert(\"마지막페이지 입니다.\")'; class='nor'>다음 &gt;</a>";
            }

			if($this->last_page){
				echo "<a href='javascript:alert(\"마지막페이지 입니다.\")'; class='nor'>마지막 &gt;&gt;</a>";				
			}else{
				echo "<a href=".Chr(34)."javascript:".$pScript."('".$this->last_page."');".Chr(34)." class='nor'>마지막 &gt;&gt;</a>";
			}
        }

		function ChinaCustomPageList($pTotalRecord, $pPage=1, $pPageView=10, $pPageSet=10, $pPageWhere, $pScript, $pNum=0)
        {
            if($pNum == "" || $pNum == null) $pNum = 0;
            $this->PageOutSet($pTotalRecord, $pPage, $pPageView, $pPageSet, $pPageWhere);

            echo "<a href=".Chr(34)."javascript:".$pScript."('1');".Chr(34)." style='padding:0 5px;'><img src='http://eightps.com/new_images/cu_prev.gif' alt='처음' style='vertical-align: middle;'/></a>".Chr(10);
            //if($this->pre_page != 0){
            //    echo "<a href=".Chr(34)."javascript:".$pScript."('".$pre_page."');".Chr(34).">".$this->pre_page_img[$pNum]."</a>".Chr(10);
            //}else{
            //    echo $this->pre_page_img[$pNum].Chr(10);
            //}
            for($this->pages_number=$this->start_page; $this->pages_number <= $this->end_pages_number; $this->pages_number++) {
                if((int)($this->pages_number == $pPage)){
                    echo "<b style='color:#ff2e90; padding:0 5px; font-size: 13px;'>".$this->pages_number."</b>".Chr(10);
                }else{
                    echo "<a href=".Chr(34)."javascript:".$pScript."('".$this->pages_number."');".Chr(34)." style='color: #7f7f7f; padding:0 5px; font-weight:bold; font-size: 13px;'>".$this->pages_number."</a>".Chr(10);
                }
            }
            //if($this->next_page != 0){
            //    echo "<a href=".Chr(34)."javascript:".$pScript."('".$this->next_page."');".Chr(34).">".$this->next_page_img[$pNum]."</a>".Chr(10);
            //}else{
            //    echo $this->next_page_img[$pNum].Chr(10);
            //}
            echo "<a href=".Chr(34)."javascript:".$pScript."('".$this->last_page."');".Chr(34)." style='padding:0 5px;'><img src='http://eightps.com/new_images/cu_next.gif' alt='처음' style='vertical-align: middle;'/></a>";
        }
    }//end class
?>
