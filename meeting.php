<?php
/* النسخة 0.1 */ 
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
 	
<!-- المسار (Breadcrumb) -->
<section class="au-breadcrumb m-t-75">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="au-breadcrumb-content">
                        <div class="au-breadcrumb-left">
                            <span class="au-breadcrumb-span">الاجتماعات</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- نهاية المسار -->

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">الاجتماعات | قريباً</h2>
                        <button class="au-btn au-btn-icon au-btn--blue" onclick="addNewEvent()">
                            <i class="zmdi zmdi-plus"></i> إضافة اجتماع
                        </button>
                    </div>
                </div>
            </div>

            <div class="row m-t-25">
                <div class="col-lg-9">
                    <div class="au-card">
                        <div class="au-card-inner">
                            <h3 class="title-2 m-b-40">التقويم الزمني</h3>
                            <div id="calendar" class="calendar-container"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <!-- الأحداث القادمة -->
                    <div class="au-card m-b-30">
                        <div class="au-card-inner">
                            <h3 class="title-2 m-b-30">الأحداث القادمة</h3>
                            <div class="upcoming-events">

                                <div class="event-item d-flex align-items-start mb-3 p-3 border rounded">
                                    <div class="event-date text-center me-3">
                                        <div class="fs-6 fw-bold text-primary">يناير</div>
                                        <div class="fs-4 fw-bold">25</div>
                                    </div>
                                    <div class="event-details flex-grow-1">
                                        <h6 class="mb-1">اجتماع الفريق</h6>
                                        <small class="text-muted">9:00 ص - 10:30 ص</small>
                                        <div class="mt-1">
                                            <span class="badge bg-primary">اجتماع</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="event-item d-flex align-items-start mb-3 p-3 border rounded">
                                    <div class="event-date text-center me-3">
                                        <div class="fs-6 fw-bold text-success">يناير</div>
                                        <div class="fs-4 fw-bold">28</div>
                                    </div>
                                    <div class="event-details flex-grow-1">
                                        <h6 class="mb-1">الموعد النهائي للمشروع</h6>
                                        <small class="text-muted">طوال اليوم</small>
                                        <div class="mt-1">
                                            <span class="badge bg-danger">موعد نهائي</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="event-item d-flex align-items-start mb-3 p-3 border rounded">
                                    <div class="event-date text-center me-3">
                                        <div class="fs-6 fw-bold text-info">فبراير</div>
                                        <div class="fs-4 fw-bold">02</div>
                                    </div>
                                    <div class="event-details flex-grow-1">
                                        <h6 class="mb-1">عرض العميل</h6>
                                        <small class="text-muted">2:00 م - 3:30 م</small>
                                        <div class="mt-1">
                                            <span class="badge bg-info">عرض تقديمي</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- أنواع الأحداث -->
                    <div class="au-card">
                        <div class="au-card-inner">
                            <h3 class="title-2 m-b-30">أنواع الأحداث</h3>
                            <div class="calendar-legend">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="legend-color bg-primary rounded me-2" style="width: 16px; height: 16px;"></div>
                                    <span class="fs-6">الاجتماعات</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="legend-color bg-success rounded me-2" style="width: 16px; height: 16px;"></div>
                                    <span class="fs-6">المهام</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="legend-color bg-warning rounded me-2" style="width: 16px; height: 16px;"></div>
                                    <span class="fs-6">المواعيد</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="legend-color bg-danger rounded me-2" style="width: 16px; height: 16px;"></div>
                                    <span class="fs-6">المواعيد النهائية</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="legend-color bg-info rounded me-2" style="width: 16px; height: 16px;"></div>
                                    <span class="fs-6">العروض التقديمية</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       
        </div>
    </div>
</div>

<!-- ✅ تحميل السكربتات بدون تعارض -->
<script>
function loadScriptOnce(src) {
    return new Promise((resolve, reject) => {
        if (document.querySelector(`script[src="${src}"]`)) {
            resolve('already_loaded');
            return;
        }
        const script = document.createElement('script');
        script.src = src;
        script.onload = resolve;
        script.onerror = reject;
        document.body.appendChild(script);
    });
}

(async () => {
    const scripts = [
        "js/vanilla-utils.js",
        "vendor/bootstrap-5.3.8.bundle.min.js",
        "vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.min.js",
        "vendor/chartjs/chart.umd.js-4.5.0.min.js",
        "https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js",
        "js/bootstrap5-init.js",
        "js/main-vanilla.js",
        "js/swiper-bundle-11.2.10.min.js",
        "js/aos.js",
        "js/modern-plugins.js"
    ];
    for (const src of scripts) await loadScriptOnce(src);
})();
</script>

<!-- ✅ تهيئة التقويم -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    
    if (calendarEl) {
        const events = [
            {
                title: 'اجتماع الفريق',
                start: '2025-01-25T09:00:00',
                end: '2025-01-25T10:30:00',
                backgroundColor: '#007bff',
                borderColor: '#007bff',
                textColor: '#ffffff'
            },
            {
                title: 'الموعد النهائي للمشروع',
                start: '2025-01-28',
                allDay: true,
                backgroundColor: '#dc3545',
                borderColor: '#dc3545',
                textColor: '#ffffff'
            },
            {
                title: 'عرض العميل',
                start: '2025-02-02T14:00:00',
                end: '2025-02-02T15:30:00',
                backgroundColor: '#17a2b8',
                borderColor: '#17a2b8',
                textColor: '#ffffff'
            }
        ];

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            height: 'auto',
            events: events,
            locale: 'ar',
            dayMaxEvents: 3
        });

        calendar.render();
        window.calendarInstance = calendar;
    }
});

function addNewEvent() {
    const title = prompt('أدخل عنوان الحدث:');
    if (title && window.calendarInstance) {
        const today = new Date();
        window.calendarInstance.addEvent({
            title: title,
            start: today,
            backgroundColor: '#6c757d',
            borderColor: '#6c757d',
            textColor: '#ffffff'
        });
    }
}
</script>

<?php include("Configuration/Footer.php"); ?>
