<?
/* version 0.1 */ 
?>

<?php
if(isset($_GET["go"]))
if($_GET["go"]== "logout")
{
   session_start();
	session_destroy();	
}
include("Configuration/Header.php");


?>
 	
      <!-- BREADCRUMB-->
            <section class="au-breadcrumb m-t-75">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="au-breadcrumb-content">
                                    <div class="au-breadcrumb-left">
                                        <span class="au-breadcrumb-span">إعلانات الوحدات</span>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- END BREADCRUMB-->

<div class="row g-4">

  <!-- تم إصلاح المصعد -->
  <div class="col-6">
    <div class="card h-100 shadow-sm">
      <img class="card-img-top" src="images/1.jpg" alt="إصلاح المصعد">
      <div class="card-body">
        <h4 class="card-title mb-3">تم إصلاح المصعد</h4>
        <p class="card-text">
          نود إعلام السادة الملاك بأنه تم إصلاح المصعد الرئيسي وإعادته للعمل بكفاءة لضمان راحة السكان وسلامتهم.
        </p>
      </div>
    </div>
  </div>

  <!-- التعاقد مع حضانة -->
  <div class="col-6">
    <div class="card h-100 shadow-sm">
      <img class="card-img-top" src="images/3.jpg" alt="التعاقد مع حضانة">
      <div class="card-body">
        <h4 class="card-title mb-3">تم التعاقد مع حضانة</h4>
        <p class="card-text">
          يسرنا الإعلان عن التعاقد مع حضانة متميزة لتوفير خدمات رعاية وتعليم للأطفال داخل نطاق المجمع السكني.
        </p>
      </div>
    </div>
  </div>

  <!-- التعاقد مع مشتل -->
  <div class="col-6">
    <div class="card h-100 shadow-sm">
      <img class="card-img-top" src="images/2.jpg" alt="التعاقد مع مشتل">
      <div class="card-body">
        <h4 class="card-title mb-3">تم التعاقد مع مشتل</h4>
        <p class="card-text">
          تم التعاقد مع مشتل متخصص لتوريد وصيانة النباتات والزهور لتحسين المظهر الجمالي للمرافق المشتركة.
        </p>
      </div>
    </div>
  </div>

  <!-- التعاقد مع شركة نظافة -->
  <div class="col-6">
    <div class="card h-100 shadow-sm">
      <img class="card-img-top" src="images/4.jpg" alt="التعاقد مع شركة نظافة">
      <div class="card-body">
        <h4 class="card-title mb-3">تم التعاقد مع شركة نظافة</h4>
        <p class="card-text">
          تم التعاقد مع شركة نظافة متخصصة لضمان نظافة الممرات والمداخل والمرافق بشكل يومي وبأعلى معايير الجودة.
        </p>
      </div>
    </div>
  </div>

</div>
<style>
.card-img, .card-img-top {
    height: 200px !important;
}
.col-6 {
    flex: 0 0 auto;
    width: 50%;
    padding: 0px 58px;
    direction: rtl;
}
</style>

<?php

include("Configuration/Footer.php");

?>
