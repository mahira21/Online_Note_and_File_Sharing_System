<?php
    session_start();
    //preventing access from view route
    if (strpos($_SERVER['REQUEST_URI'], '/views/') !== false) {
        exit();
    }
    if(!isset($_SESSION['admin']))
    {
        header("Location:login");
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Document</title>
        <link rel="stylesheet" href="views/styles/all.css" />
        <link rel="stylesheet" href="views/styles/adbase.css" />
        <link rel="stylesheet" href="views/styles/dashboard.css" />
        <link rel="stylesheet" href="views/styles/throwlert.css" />

        <script src="views/js/jquery341.js"></script>
        <script
            type="text/javascript"
            src="https://www.gstatic.com/charts/loader.js"
        ></script>
        <script></script>
    </head>
    <body>
        <section class="ad-holder">
            <?php require "adsidebar.php"; ?>
            <section class="ad-container">
                <div class="counts">
                    <div class="inner-row">
                        <div class="count-card note-count-card">
                            <div class="thumb note-thumb">
                                <a href=""><i class="fas fa-file-alt"></i></a>
                            </div>
                            <div class="summ">
                                <div class="small-summ-value">
                                    0
                                </div>
                                <div class="small-summ-title">
                                    Total Notes
                                </div>
                            </div>
                            <div class="details note-details">
                                <div class="small-details" id="guestNoteCount">
                                    Guest Notes: 0
                                </div>
                                <div class="small-details" id="userNoteCount">
                                    User Notes: 0
                                </div>
                                <div class="small-details" id="publicNoteCount">
                                    Public Notes: 0
                                </div>
                                <div
                                    class="small-details"
                                    id="privateNoteCount"
                                >
                                    Private Notes: 0
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="inner-row">
                        <div class="count-card file-count-card">
                            <div class="thumb file-thumb">
                                <a href=""
                                    ><i class="fas fa-file-archive"></i
                                ></a>
                            </div>
                            <div class="summ">
                                <div class="small-summ-value">
                                    0
                                </div>
                                <div class="small-summ-title">
                                    Space Used
                                </div>
                            </div>
                            <div class="details file-details">
                                <div class="small-details" id="totalFileCount">
                                    Total Files: 0
                                </div>
                                <div
                                    class="small-details"
                                    id="privateFileCount"
                                >
                                    Private Files: 0
                                </div>
                                <div class="small-details">
                                    &nbsp;
                                </div>
                                <div class="small-details">
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="inner-row">
                        <div class="count-card url-count-card">
                            <div class="thumb url-thumb">
                                <a href=""><i class="fas fa-link"></i></a>
                            </div>
                            <div class="summ">
                                <div class="small-summ-value">
                                    0
                                </div>
                                <div class="small-summ-title">
                                    Total URLs
                                </div>
                            </div>
                            <div class="details url-details">
                                <div class="small-details">
                                    &nbsp;
                                </div>
                                <div class="small-details">
                                    &nbsp;
                                </div>
                                <div class="small-details">
                                    &nbsp;
                                </div>
                                <div class="small-details">
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overall-graph-holder">
                    <div class="overall-graph-card">
                        <div class="overall-graph-title">
                            Sitewide Activity Statistics For last 15 Days
                        </div>
                        <div id="areachart" class="areachart"></div>
                    </div>
                </div>
                <div class="graphs">
                    <div class="graph-card-wrapper">
                        <div class="note-graph graph-card">
                            <div class="inner-note-graph inner-graph-card">
                                <div
                                    class="note-graph-header graph-card-header"
                                >
                                    <div
                                        id="linechart_material"
                                        class="line-chart-graph"
                                    ></div>
                                </div>
                                <div
                                    class="inner-note-graph-title graph-card-title"
                                >
                                    Note Activity For last 7 Days
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="graph-card-wrapper">
                        <div class="file-graph graph-card">
                            <div class="inner-file-graph inner-graph-card">
                                <div
                                    class="file-graph-header graph-card-header"
                                >
                                    <div
                                        id="linechart_material2"
                                        class="line-chart-graph"
                                    ></div>
                                </div>
                                <div
                                    class="inner-file-graph-title graph-card-title"
                                >
                                    File Activity For last 7 Days
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="graph-card-wrapper">
                        <div class="url-graph graph-card">
                            <div class="inner-url-graph inner-graph-card">
                                <div class="url-graph-header graph-card-header">
                                    <div
                                        id="linechart_material3"
                                        class="line-chart-graph"
                                    ></div>
                                </div>
                                <div
                                    class="inner-url-graph-title graph-card-title"
                                >
                                    url Activity For last 7 Days
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
        <div></div>

        <div class="throwlert">
            <div class="alert-box">
                <div class="alert-close-button">
                    <button><i class="fas fa-times"></i></button>
                </div>
                <div class="alert-type type-success">
                    <i class="far fa-check-circle"></i>
                </div>
                <div class="alert-type type-error">
                    <i class="far fa-times-circle"></i>
                </div>
                <div class="alert-dialog"></div>
            </div>
        </div>
        <script>
            $('.ad-side a').eq(0).css('background-color', '#555');
            $('.sidebar-toggler').click(function () {
                $('.ad-sidebar').toggleClass('ad-sidebar-active');
            });
            $('.thumb a').click(function (e) {
                e.preventDefault();
            });
            let obj0;
            $.ajax({
                url: 'controllers/dashboardhandler.php',
                method: 'POST',
                dataType: 'JSON',
                data: {
                    getStat: 1,
                },
                success: function (data) {
                    // console.log(data);
                    obj0 = data;
                    google.charts.setOnLoadCallback(drawChart0);
                },
            });
            let obj;
            $.ajax({
                url: 'controllers/dashboardhandler.php',
                method: 'POST',
                dataType: 'JSON',
                data: {
                    getRec: 1,
                },
                success: function (data) {
                    // console.log(data);
                    obj = data;
                    google.charts.setOnLoadCallback(drawChart1);
                },
            });
            let obj2;
            $.ajax({
                url: 'controllers/dashboardhandler.php',
                method: 'POST',
                dataType: 'JSON',
                data: {
                    getRec2: 1,
                },
                success: function (data) {
                    // console.log(data);
                    obj2 = data;
                    google.charts.setOnLoadCallback(drawChart2);
                },
            });
            let obj3;
            $.ajax({
                url: 'controllers/dashboardhandler.php',
                method: 'POST',
                dataType: 'JSON',
                data: {
                    getRec3: 1,
                },
                success: function (data) {
                    // console.log(data);
                    obj3 = data;
                    google.charts.setOnLoadCallback(drawChart3);
                },
            });
            google.charts.load('current', { packages: ['corechart'] });

            function drawChart0() {
                var data = google.visualization.arrayToDataTable(obj0);

                var options = {
                    animation: {
                        startup: 'true',
                        duration: 1000,
                    },
                    legend: { position: 'bottom', alignment: 'center' },

                    is3D: true,
                    vAxis: {
                        gridlines: { color: 'transparent' },
                        format: '0',
                    },
                    chartArea: {
                        left: '7%',
                        top: '12%',
                        width: '90%',
                        height: '70%',
                    },
                    colors: ['#F5A067', '#3CAFA4', '#914DF3'],
                    lineWidth: 1,
                    curveType: 'function',
                    pointSize: 7,
                    crosshair: { trigger: 'both' },
                    focusTarget: 'category',
                };

                var chart = new google.visualization.AreaChart(
                    document.getElementById('areachart')
                );

                chart.draw(data, options);
            }
            function drawChart1() {
                var data = google.visualization.arrayToDataTable(obj);

                var options = {
                    curveType: 'none',
                    animation: {
                        startup: 'true',
                        duration: 1000,
                        easing: 'in',
                    },
                    legend: { position: 'none' },
                    backgroundColor: { fill: 'transparent' },
                    is3D: true,
                    vAxis: {
                        gridlines: { color: '#fff' },
                        textStyle: { color: '#fff' },
                        baseline: { color: '#fff' },
                    },
                    colors: ['#fff'],
                    lineWidth: 4,
                    curveType: 'function',
                    pointSize: 7,
                    crosshair: { trigger: 'both' },
                    hAxis: {
                        textStyle: { color: '#fff' },
                        gridlines: { color: '#fff', count: 4 },
                    },
                };

                var chart = new google.visualization.LineChart(
                    document.getElementById('linechart_material')
                );

                chart.draw(data, options);
            }
            function drawChart2() {
                var data = google.visualization.arrayToDataTable(obj2);

                var options = {
                    curveType: 'none',
                    animation: {
                        startup: 'true',
                        duration: 1000,
                        easing: 'in',
                    },
                    legend: { position: 'none' },
                    backgroundColor: { fill: 'transparent' },
                    is3D: true,
                    vAxis: {
                        gridlines: { color: '#fff' },
                        textStyle: { color: '#fff' },
                        baseline: { color: '#fff' },
                    },
                    colors: ['#fff'],
                    lineWidth: 4,
                    curveType: 'function',
                    pointSize: 7,
                    crosshair: { trigger: 'both' },
                    hAxis: {
                        textStyle: { color: '#fff' },
                        gridlines: { color: '#fff', count: 4 },
                    },
                };

                var chart = new google.visualization.LineChart(
                    document.getElementById('linechart_material2')
                );

                chart.draw(data, options);
            }
            function drawChart3() {
                var data = google.visualization.arrayToDataTable(obj3);

                var options = {
                    curveType: 'none',
                    animation: {
                        startup: 'true',
                        duration: 1000,
                        easing: 'in',
                    },
                    legend: { position: 'none' },
                    backgroundColor: { fill: 'transparent' },
                    is3D: true,
                    vAxis: {
                        gridlines: { color: '#fff' },
                        textStyle: { color: '#fff' },
                        baseline: { color: '#fff' },
                    },
                    colors: ['#fff'],
                    lineWidth: 4,
                    curveType: 'function',
                    pointSize: 7,
                    crosshair: { trigger: 'both' },
                    hAxis: {
                        textStyle: { color: '#fff' },
                        gridlines: { color: '#fff', count: 4 },
                    },
                };

                var chart = new google.visualization.LineChart(
                    document.getElementById('linechart_material3')
                );

                chart.draw(data, options);
            }

            $(window).resize(function () {
                let win = $(this);
                if (win.width() > 450) {
                    if (this.resizeTO) clearTimeout(this.resizeTO);
                    this.resizeTO = setTimeout(function () {
                        $(this).trigger('resizeEnd');
                    }, 500);
                }
            });

            //redraw graph when window resize is completed
            $(window).on('resizeEnd', function () {
                drawChart0();
                drawChart1();
                drawChart2();
                drawChart3();
            });

            $.ajax({
                url: 'controllers/dashboardhandler.php',
                method: 'POST',
                dataType: 'JSON',
                data: {
                    getCounts: 1,
                },
                success: function (data) {
                    if ('notecount' in data) {
                        $('.note-count-card .small-summ-value').text(
                            data.notecount
                        );
                    }
                    if ('totalfilesize' in data) {
                        $('.file-count-card .small-summ-value').text(
                            data.totalfilesize + ' KB'
                        );
                    }
                    if ('urlcount' in data) {
                        $('.url-count-card .small-summ-value').text(
                            data.urlcount
                        );
                    }
                    if ('gnotecount' in data) {
                        $('#guestNoteCount').text(
                            'Guest Notes: ' + data.gnotecount
                        );
                    }
                    if ('gnotecount' in data) {
                        $('#userNoteCount').text(
                            'User Notes: ' + (data.notecount - data.gnotecount)
                        );
                    }
                    if ('pnotecount' in data) {
                        $('#privateNoteCount').text(
                            'Private Notes: ' + data.pnotecount
                        );
                    }
                    if ('pnotecount' in data) {
                        $('#publicNoteCount').text(
                            'Public Notes: ' +
                                (data.notecount - data.pnotecount)
                        );
                    }

                    //file
                    if ('filecount' in data) {
                        $('#totalFileCount').text(
                            'Total Files: ' + data.filecount
                        );
                    }
                    if ('pfilecount' in data) {
                        $('#privateFileCount').text(
                            'Private Files: ' + data.pfilecount
                        );
                    }
                },
            });
        </script>
    </body>
</html>
