<?php
/* version 0.6 — dynamic unit view (units + events) */

include("Configuration/Header.php");
?>
<style>
.au-card2{
	margin-left: 4%;
  margin-right: 4%;
}
.au-card2 a{
padding: 23px;  
 border-radius: 20px;
}
</style>
     <div class="main-content">
	            <div class="section__content section__content--p30">
                    <div class="container-fluid">
					<h1>البيانات المفتوحة</h1>
     <br>    
	<div class="row">
	
                            <div class="col-md-5 au-card m-b-30 au-card2">
							<div style="text-align: center; ">
					<div style="margin-top: 30px; text-align:center;">
					
    <a href="download_open_data.php?type=requests" class="btn btn-success">
	<div class="icon" style="font-size:xx-large;"><i class="zmdi zmdi-assignment"></i></div>
					
					
        تحميل بيانات الطلبات CSV
    </a>

</div>

					</div>
					  </div>
                            <div class="col-md-5 au-card m-b-30 au-card2">
							<div style="text-align: center; ">
					<div style="margin-top: 30px; text-align:center;">
   

    <a href="download_open_data.php?type=units" class="btn btn-primary" style="margin-right: 10px;">
		<div class="icon" style="font-size:xx-large;"><i class="fas fa-building"></i></div>
		
        تحميل بيانات الوحدات CSV
    </a>
</div>

					</div>
					  </div>
					  
					  
                            <div class="col-md-12 au-card m-b-30">
							<div style="width: 400px; text-align: center; margin-right: 38%;">
							<br>
                      <img src="https://rega.gov.sa/images/logos/REGA_LOGO.svg">
					<a href="https://rega.gov.sa/%D8%A7%D9%84%D8%A8%D9%8A%D8%A7%D9%86%D8%A7%D8%AA-%D8%A7%D9%84%D9%85%D9%81%D8%AA%D9%88%D8%AD%D8%A9/"
					>
					<hr>
			إفتراضياً تم إتاحة بعض البيانات للنظام ويمكن الاطلاع على المزيد من البيانات الخاصة بالعقار عبر موقع الهيئة		</a>
					</div>
					  </div>
					  </div>
					  </div>
					  </div>
					  </div>
					
					  

<?php include("Configuration/Footer.php"); ?>
