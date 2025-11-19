<?php
/* version 0.2 — Fixed JS loading & Chart.js initialization */
if (isset($_GET["go"]) && $_GET["go"] == "logout") {
    session_start();
    session_destroy();
}
include("Configuration/Header.php");
?>

<!-- CSS Dependencies -->
<link href="css/font-face.css" rel="stylesheet" media="all">
<link href="vendor/fontawesome-7.0.1/css/all.min.css" rel="stylesheet" media="all">
<link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
<link href="vendor/bootstrap-5.3.8.min.css" rel="stylesheet" media="all">
<link href="css/aos.css" rel="stylesheet" media="all">
<link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
<link href="css/swiper-bundle-11.2.10.min.css" rel="stylesheet" media="all">
<link href="vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.css" rel="stylesheet" media="all">
<link href="css/theme.css" rel="stylesheet" media="all">

<!-- Breadcrumb -->
<section class="au-breadcrumb m-t-75">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="au-breadcrumb-content">
                        <div class="au-breadcrumb-left">
                            <span class="au-breadcrumb-span">التقارير</span> | Coming Soon
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<br>
<h2>| Coming Soon</h2>

<!-- MAIN CONTENT -->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <?php
                // chart IDs for reusability
                $charts = [
                    "sales-chart" => "Yearly Sales",
                    "team-chart" => "Team Commits",
                    "barChart" => "Bar Chart",
                    "radarChart" => "Radar Chart",
                    "lineChart" => "Line Chart",
                    "doughutChart" => "Doughnut Chart",
                    "pieChart" => "Pie Chart",
                    "polarChart" => "Polar Chart",
                    "singelBarChart" => "Single Bar Chart"
                ];

                foreach ($charts as $id => $title) {
                    echo "
                    <div class='col-lg-6'>
                        <div class='au-card m-b-30'>
                            <div class='au-card-inner'>
                                <h3 class='title-2 m-b-40'>{$title}</h3>
                                <canvas id='{$id}'></canvas>
                            </div>
                        </div>
                    </div>";
                }
                ?>
            </div>

          
        </div>
    </div>
</div>

<!-- JS Dependencies -->
<script src="js/vanilla-utils.js"></script>
<script src="vendor/bootstrap-5.3.8.bundle.min.js"></script>
<script src="vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.min.js"></script>

<!-- ✅ Correct Chart.js file -->
<script src="vendor/chartjs/chart.umd.min.js"></script>

<script src="js/bootstrap5-init.js"></script>
<script src="js/main-vanilla.js"></script>
<script src="js/swiper-bundle-11.2.10.min.js"></script>
<script src="js/aos.js"></script>
<script src="js/modern-plugins.js"></script>

<!-- ✅ Charts Initialization -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js not loaded.');
        return;
    }

    const chartConfigs = [
        {
            id: 'sales-chart',
            type: 'line',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                datasets: [{
                    label: 'Sales',
                    data: [12,19,15,25,22,30,28,35,30,40,35,45],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0,123,255,0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            }
        },
        {
            id: 'team-chart',
            type: 'doughnut',
            data: {
                labels: ['Frontend','Backend','DevOps','Design','QA'],
                datasets: [{
                    data: [35,25,15,15,10],
                    backgroundColor: ['#007bff','#28a745','#ffc107','#dc3545','#6f42c1']
                }]
            }
        },
        {
            id: 'barChart',
            type: 'bar',
            data: {
                labels: ['Q1','Q2','Q3','Q4'],
                datasets: [{
                    label: 'Revenue',
                    data: [65,59,80,81],
                    backgroundColor: [
                        'rgba(54,162,235,0.8)',
                        'rgba(255,99,132,0.8)',
                        'rgba(255,205,86,0.8)',
                        'rgba(75,192,192,0.8)'
                    ]
                }]
            }
        },
        {
            id: 'radarChart',
            type: 'radar',
            data: {
                labels: ['Performance','Scalability','Security','Usability','Reliability','Maintainability'],
                datasets: [{
                    label: 'Current System',
                    data: [80,70,85,75,90,65],
                    backgroundColor: 'rgba(54,162,235,0.2)',
                    borderColor: 'rgba(54,162,235,1)',
                    borderWidth: 2
                },{
                    label: 'Target System',
                    data: [90,85,95,90,95,85],
                    backgroundColor: 'rgba(255,99,132,0.2)',
                    borderColor: 'rgba(255,99,132,1)',
                    borderWidth: 2
                }]
            }
        },
        {
            id: 'lineChart',
            type: 'line',
            data: {
                labels: ['Week 1','Week 2','Week 3','Week 4','Week 5','Week 6'],
                datasets: [{
                    label: 'Website Visits',
                    data: [1200,1900,1500,2500,2200,3000],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40,167,69,0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                },{
                    label: 'App Downloads',
                    data: [800,1400,1100,1800,1600,2200],
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255,193,7,0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            }
        },
        {
            id: 'doughutChart',
            type: 'doughnut',
            data: {
                labels: ['Mobile','Desktop','Tablet'],
                datasets: [{
                    data: [55,35,10],
                    backgroundColor: ['#007bff','#28a745','#ffc107']
                }]
            }
        },
        {
            id: 'pieChart',
            type: 'pie',
            data: {
                labels: ['Chrome','Firefox','Safari','Edge','Others'],
                datasets: [{
                    data: [45,25,15,10,5],
                    backgroundColor: ['#007bff','#28a745','#ffc107','#dc3545','#6c757d']
                }]
            }
        },
        {
            id: 'polarChart',
            type: 'polarArea',
            data: {
                labels: ['Product A','Product B','Product C','Product D','Product E'],
                datasets: [{
                    data: [30,45,25,35,40],
                    backgroundColor: [
                        'rgba(255,99,132,0.7)',
                        'rgba(54,162,235,0.7)',
                        'rgba(255,205,86,0.7)',
                        'rgba(75,192,192,0.7)',
                        'rgba(153,102,255,0.7)'
                    ]
                }]
            }
        },
        {
            id: 'singelBarChart',
            type: 'bar',
            data: {
                labels: ['HTML','CSS','JavaScript','React','Vue','Angular'],
                datasets: [{
                    label: 'Skills Proficiency (%)',
                    data: [95,90,85,80,70,75],
                    backgroundColor: 'rgba(54,162,235,0.8)'
                }]
            },
            options: { indexAxis: 'y' }
        }
    ];

    chartConfigs.forEach(cfg => {
        const ctx = document.getElementById(cfg.id);
        if (ctx) new Chart(ctx, cfg);
    });

    console.log('✅ All Chart.js charts initialized successfully.');
});
</script>

<?php include("Configuration/Footer.php"); ?>
