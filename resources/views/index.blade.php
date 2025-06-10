 @extends('layouts.app')

 @section('content')
     <div class="container">
         <div class="page-inner">
             <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                 <div>
                     <h3 class="fw-bold mb-3">Dashboard</h3>
                 </div>
             </div>
             <div class="row">
                 <div class="col-sm-6 col-md-3">
                     <div class="card card-stats card-round">
                         <div class="card-body">
                             <div class="row align-items-center">
                                 <div class="col-icon">
                                     <div class="icon-big text-center icon-primary bubble-shadow-small">
                                         <i class="fas fa-users"></i>
                                     </div>
                                 </div>
                                 <div class="col col-stats ms-3 ms-sm-0">
                                     <div class="numbers">
                                         <p class="card-category">Pengguna</p>
                                         <h4 class="card-title">{{ $userCount }}</h4>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

                 <div class="col-sm-6 col-md-3">
                     <div class="card card-stats card-round">
                         <div class="card-body">
                             <div class="row align-items-center">
                                 <div class="col-icon">
                                     <div class="icon-big text-center icon-success bubble-shadow-small">
                                         <i class="fas fa-luggage-cart"></i>
                                     </div>
                                 </div>
                                 <div class="col col-stats ms-3 ms-sm-0">
                                     <div class="numbers">
                                         <p class="card-category">Total Omset</p>
                                         <h4 class="card-title">{{ $totalOmset }}</h4>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="col-sm-6 col-md-3">
                     <div class="card card-stats card-round">
                         <div class="card-body">
                             <div class="row align-items-center">
                                 <div class="col-icon">
                                     <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                         <i class="far fa-check-circle"></i>
                                     </div>
                                 </div>
                                 <div class="col col-stats ms-3 ms-sm-0">
                                     <div class="numbers">
                                         <p class="card-category">Produk</p>
                                         <h4 class="card-title">{{ $productCount }}</h4>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="row">
                 <div class="col-md-12">
                     <div class="card card-round">
                         <div class="card-header">
                             <div class="card-head-row">
                                 <div class="card-title">Statistik Tahunan</div>
                                 <div class="card-tools">
                                     <a href="#" id="btnPrintChart" class="btn btn-label-info btn-round btn-sm">
                                         <span class="btn-label"><i class="fa fa-print"></i></span> Print
                                     </a>
                                 </div>
                             </div>
                         </div>
                         <div class="card-body">
                             <div class="chart-container" style="min-height: 375px">
                                 <canvas id="statistikTahunan"></canvas>
                             </div>
                             <div id="Chart"></div>
                         </div>
                     </div>
                 </div>
             </div>
             <script>
                 document.addEventListener('DOMContentLoaded', function() {

                     /* ---------- data dari controller ---------- */
                     const labels = @json($chartLabels);
                     const data = @json($chartValues);
                     const total = @json($yearlyOmzet);

                     /* ---------- util singkat angka ---------- */
                     function shortNum(n) {
                         if (n < 1_000) return n.toLocaleString('id-ID');
                         if (n < 1_000_000) return (n / 1_000).toFixed(1) + 'RB';
                         if (n < 1_000_000_000) return (n / 1_000_000).toFixed(1) + 'JT';
                         return (n / 1_000_000_000).toFixed(1) + 'M';
                     }

                     var ctx = document.getElementById('statistikTahunan').getContext('2d');

                     var statistikTahunan = new Chart(ctx, {
                         type: 'line',
                         data: {
                             labels: labels,
                             datasets: [{
                                 label: 'Omzet Tahun Ini : ' + shortNum(total),
                                 borderColor: '#177dff',
                                 pointBackgroundColor: 'rgba(23,125,255,0.6)',
                                 pointRadius: 0,
                                 backgroundColor: 'rgba(23,125,255,0.4)',
                                 fill: true,
                                 borderWidth: 2,
                                 data: data
                             }]
                         },
                         options: {
                             responsive: true,
                             maintainAspectRatio: false,
                             legend: {
                                 display: false
                             },

                             tooltips: {
                                 callbacks: {
                                     label: t => 'Rp ' + shortNum(t.yLabel)
                                 }
                             },

                             scales: {
                                 yAxes: [{
                                     ticks: {
                                         callback: v => 'Rp ' + shortNum(v),
                                         beginAtZero: true,
                                         padding: 10
                                     },
                                     gridLines: {
                                         drawTicks: false,
                                         display: false
                                     }
                                 }],
                                 xAxes: [{
                                     gridLines: {
                                         zeroLineColor: 'transparent'
                                     },
                                     ticks: {
                                         padding: 10
                                     }
                                 }]
                             }
                         }
                     });

                     /* --- (legend opsional) --- */
                     document.getElementById('Chart').innerHTML =
                         statistikTahunan.generateLegend();
                 });
             </script>

             <script>
                 document.addEventListener('DOMContentLoaded', function() {

                     /* helper singkat angka */
                     function shortNum(n) {
                         if (n < 1_000) return n.toLocaleString('id-ID');
                         if (n < 1_000_000) return (n / 1_000).toFixed(1) + 'RB';
                         if (n < 1_000_000_000) return (n / 1_000_000).toFixed(1) + 'JT';
                         return (n / 1_000_000_000).toFixed(1) + 'M';
                     }

                     const yearlyOmzet = @json($yearlyOmzet); // angka full

                     document.getElementById('btnPrintChart').addEventListener('click', function(e) {
                         e.preventDefault();

                         const canvas = document.getElementById('statistikTahunan');
                         const imgSrc = canvas.toDataURL('image/png', 1.0);

                         /* buat halaman print */
                         const w = window.open('', '_blank');
                         w.document.write(`
                                <html>
                                <head>
                                    <title>Cetak Grafik</title>
                                    <style>
                                        body{margin:0; font-family:Arial,Helvetica,sans-serif; text-align:center}
                                        h2{margin:10px 0 5px}
                                        h4{margin:0 0 15px}
                                    </style>
                                </head>
                                <body>
                                    <h2>Statistik Tahun ${new Date().getFullYear()}</h2>
                                    <img src="${imgSrc}" style="max-width:100%;"/>
                                    <h4>Total Omzet: Rp ${shortNum(yearlyOmzet)}</h4>
                                    <script>
                                        window.onload = function(){
                                            window.focus(); window.print(); window.close();
                                        };
                                    <\/script>
                                </body>
                                </html>
                            `);
                         w.document.close();
                     });
                 });
             </script>

         </div>
     </div>
 @endsection
