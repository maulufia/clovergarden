@extends('front.page.clovergarden')

@section('clovergarden')
<?php
	$page_no = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
	$search_key = isset($_REQUEST['search_key']) ? $_REQUEST['search_key'] : '';
	$search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : '';
?>
<script src="{{ url('js/pdf.js') }}"></script>
<script src="{{ url('js/pdfobject.min.js') }}"></script>

<section class="wrap">
	<header>
		<h2 class="ti">클로버가든 소식지</h2>
	</header>
	<article>
    <div id="pdfContainer">
      <div id="pdf"></div>
    </div>
	</article>
</section>


<script>
  var options = {
    pdfOpenParams: {
      navpanes: 0,
      toolbar: 0,
      statusbar: 0,
      view: "FitV",
      page: 1
    },
    forcePDFJS: true,
    PDFJS_URL: "{{ url('pdfjs') }}/web/viewer.html"
  };

  var myPDF = PDFObject.embed("{{ url('/') }}/imgs/up_file/clover/{{ $_GET['filename'] }}", "#pdfContainer", options);
</script>
@stop
