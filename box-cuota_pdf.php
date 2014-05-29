<style type="text/css">
    @media print 
    {
        .dontprint{display:none} 
    }
</style>
<script type="text/javascript" language="javascript" src="jquery/jquery-1.6.2.min.js"></script>
<script type="text/javascript">
    function printIframePdf(){
        window.frames["printf"].focus();
        try {
            window.frames["printf"].print();
        }
        catch(e){
            window.print();
            console.log(e);
        }
    }
    function printObjectPdf() {
        try{            
            document.getElementById('idPdf').Print();
        }
        catch(e){
            printIframePdf();
            console.log(e);
        }
    }

    function idPdf_onreadystatechange() {
		console.log('a');
        if (idPdf.readyState === 4)
            setTimeout(printObjectPdf, 1000);
    }
	// setTimeout(printObjectPdf, 3000);
	var flag = true;
	$('#printf, #idPdf').ready(function(){
		if (flag) {
			flag = false;
			printObjectPdf();
		}
	});
	
</script>
<div class="dontprint" >
    <form><input type="button" onClick="printObjectPdf()" class="btn" value="Print"/></form>
</div>

<iframe id="printf" name="printf" src="print-cuota.php?print&id=<?=$_GET['id']?>" frameborder="0" width="440" height="580" style="width: 440px; height: 580px;display: none;"></iframe>
<object id="idPdf" onreadystatechange="idPdf_onreadystatechange()"
    width="440" height="580" style="width: 440px; height: 580px;" type="application/pdf"
    data="print-cuota.php?print&id=<?=$_GET['id']?>">
    <embed src="print-cuota.php?print&id=<?=$_GET['id']?>" width="440" height="580" style="width: 440px; height: 580px;" type="application/pdf">
    </embed>
    <span>PDF plugin is not available.</span>
</object>
