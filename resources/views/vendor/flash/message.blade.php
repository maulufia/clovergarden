<?php
    if(isset($_GET['flash'])) {
        if($_GET['flash'] == 'need_login') {
            Session::flash('flash_notification.level', 'warning');
            Session::flash('flash_notification.message', '로그인 후 이용해주세요.');
        }
    }
?>

@if (Session::has('flash_notification.message'))
    @if (Session::has('flash_notification.overlay'))
        @include('flash::modal', ['modalClass' => 'flash-modal', 'title' => Session::get('flash_notification.title'), 'body' => Session::get('flash_notification.message')])
    @else
        <div class="alert alert-{{ Session::get('flash_notification.level') }}">
            <button type="button" class="close" onclick="closeAlert(this);">&times;</button>

            {!! Session::get('flash_notification.message') !!}
        </div>
        
        <script type="text/javascript">
        	function closeAlert(object) {
        		$(object).parent().hide();
        	}
        </script>
    @endif
@endif

<?php
    Session::pull('flash_notification.message'); // 플래시 삭제 확인
?>