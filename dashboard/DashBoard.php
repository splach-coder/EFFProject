<?php 
    session_start();
    require_once '../app/db.php';
    include '../assets/php/functions.php';

    if(isset($_SESSION['user_id'])  &&  isset($_SESSION['user_id_infos'])  &&  isset($_SESSION['user_email']) && $_SESSION['role'] == 'admin'){

        $adminName = getSingleValue($conn, "SELECT full_name FROM `informations` WHERE `id` = " 
        . $_SESSION['user_id_infos']);
        
        $users = getSingleValue($conn, "SELECT COUNT(*) FROM `users` WHERE `role` = 'user'");
        $visitors = getSingleValue($conn, "SELECT COUNT(*) FROM `visitors-counter` ");
        $anounces = getSingleValue($conn, "SELECT COUNT(*) FROM `announcer` where status != 'rejected'");
        $active = getSingleValue($conn, "SELECT COUNT(*) FROM `announcer` WHERE `status` = 'active'");
        $pending = getSingleValue($conn, "SELECT COUNT(*) FROM `announcer` WHERE `status` = 'moderation'");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/man-style.css" rel="stylesheet">
    <link href="css/dss-bd.css" rel="stylesheet">

    <?php include 'links.php';?>

    <style>
    .jren {
        border-radius: 6px;
        background: #E4E9F7;
        box-shadow: 7px 7px 16px #d4d9e6,
            -7px -7px 16px #f4f9ff;
        font-size: 22px;
        margin-right: 30px;
        color: #0191D8;
        padding: 5px 15px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 200ms;
    }

    .jren.active {
        color: #f5f5f5;
        border-radius: 6px;
        background: #0191D8;
        box-shadow: inset 7px 7px 16px #0187c9,
            inset -7px -7px 16px #019be7;
    }

    </style>
</head>

<body>
    <?php include 'sidemenu.php';?>

    <section class="home" style="margin-bottom: 50px;">
        <div class="text">Welcome <?=$adminName?></div>
        <div class="con">
            <div class="states">
                <div class="state" style="cursor: pointer;" onclick="$(location).prop('href', 'users.php')">
                    <div class="data">
                        <span class="nbr">
                            <?=$users?>
                        </span>
                        <p>users</p>
                    </div>
                    <div class="icon">
                        <i class='bx bx-user'></i>
                    </div>
                </div>
                <div class="state" style="cursor: pointer;" onclick="$(location).prop('href', 'visitors.php')">
                    <div class="data">
                        <span class="nbr">
                            <?=$visitors?>
                        </span>
                        <p>Visitors</p>
                    </div>
                    <div class="icon">
                        <i class='bx bx-user'></i>
                    </div>
                </div>
                <div class="state" style="cursor: pointer;" onclick="$(location).prop('href', 'announces.php')">
                    <div class="data">
                        <span class="nbr">
                            <?php echo $anounces?>
                        </span>
                        <p>announces</p>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>
                </div>
                <div class="state" style="cursor: pointer;" onclick="$(location).prop('href', 'first.php?cat=-1')">
                    <div class="data">
                        <span class="nbr">
                            <?php echo $pending?>
                        </span>
                        <p>pending</p>
                    </div>
                    <div class="icon">
                        <i class="bx bxs-time"></i>
                    </div>
                </div>
                <div class="state" style="cursor: pointer;" onclick="$(location).prop('href', 'announces.php')">
                    <div class="data">
                        <span class="nbr">
                            <?php echo $active?>
                        </span>
                        <p>active</p>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>
            <div style="margin-left:50px ;margin-bottom: 25px;">
                <span class="jren active vst">visitors</span> <span class="jren glb">announces</span>
            </div>
            <div id="firstcharts" class="" style="display: flex;  width: 100%;">
                <div id="chartdiv" style=" width: 50%;height: 350px;"></div>
                <div id="chartdiv2" style=" width: 50%;height: 350px;"></div>
            </div>

            <div id="secondcharts" class="" style="display: none;  width: 100%;">
                <div id="chartdiv3" style=" width: 40%;height: 350px;"></div>
                <div id="chartdiv4" style=" width: 60%;height: 350px;"></div>
            </div>

            <div class="tables">
                <div class="left-table">
                    <div class="sub-table">
                        <h3 class="text">recent announces</h3>
                        <a href="announces.php">view All</a>
                    </div>
                    <div class="table">
                        <div class="header">
                            <span>Id</span>
                            <span>Title</span>
                            <span>Price</span>
                            <span>Views</span>
                            <span>From</span>
                        </div>
                        <?php  $data = $conn->query("SELECT a.id_announcer, inf.titre_annonce, inf.prix, i.full_name FROM `announcer` a, `infos_generales` inf, `users` u, `informations` i 
                        WHERE a.id_infos_Generales = inf.id_infos_Generales 
                        AND a.id_user = u.id
                        AND u.id_infos = i.id
                        AND a.status = 'active'
                        ORDER BY a.id_announcer DESC
                        LIMIT 7"); ?>
                        <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
                        <div class="info">
                            <?php $ance =  $dt['id_announcer'];
                            $viewss = getSingleValue($conn, "SELECT count(*) FROM `announce_views` WHERE id_announce = $ance GROUP BY id_announce");
                            if(empty($viewss)){
                                $viewss = 0;
                            }
                            ?>
                            <span>
                                <?php echo $ance?>
                            </span>
                            <span><?php echo $dt['titre_annonce']?></span>
                            <span><?php echo $dt['prix']?></span>
                            <span><?php echo $viewss ?>
                            </span>
                            <span><?php echo $dt['full_name']?></span>
                        </div>
                        <?php }?>
                    </div>
                </div>
                <div class="right-table">
                    <div class="sub-table">
                        <h3 class="text">recent users</h3>
                        <a href="users.php">view All</a>
                    </div>
                    <div class="table">
                        <div class="header">
                            <span>Id</span>
                            <span>Name</span>
                        </div>
                        <?php  $data = $conn->query("SELECT u.id, i.full_name FROM `users` u, `informations` i 
                        WHERE u.id_infos = i.id
                        AND u.role = 'user'
                        ORDER BY u.id DESC
                        LIMIT 7"); ?>
                        <?php  while($dt = $data->fetch(PDO::FETCH_ASSOC)){?>
                        <div class="info">
                            <span><?php echo $dt['id']?></span>
                            <span><?php echo $dt['full_name']?></span>
                        </div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
    //states

    //chart 1
    am5.ready(function() {

        // Create root element
        // https://www.amcharts.com/docs/v5/getting-started/#Root_element
        var root = am5.Root.new("chartdiv");


        // Set themesDecember
        // https://www.amcharts.com/docs/v5/concepts/themes/
        root.setThemes([
            am5themes_Animated.new(root)
        ]);


        // Create chart
        // https://www.amcharts.com/docs/v5/charts/xy-chart/
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
            panX: true,
            panY: true,
            wheelX: "panX",
            wheelY: "zoomX",
            pinchZoomX: true
        }));

        // Add cursor
        // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
        var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
        cursor.lineY.set("visible", false);


        // Create axes
        // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
        var xRenderer = am5xy.AxisRendererX.new(root, {
            minGridDistance: 30
        });
        xRenderer.labels.template.setAll({
            rotation: -90,
            centerY: am5.p50,
            centerX: am5.p100,
            paddingRight: 15
        });

        var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
            maxDeviation: 0.3,
            categoryField: "date",
            renderer: xRenderer,
            tooltip: am5.Tooltip.new(root, {})
        }));

        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            maxDeviation: 0.3,
            renderer: am5xy.AxisRendererY.new(root, {})
        }));


        // Create series
        // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
        var series = chart.series.push(am5xy.ColumnSeries.new(root, {
            name: "Views/day",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "views",
            sequencedInterpolation: true,
            categoryXField: "date",
            tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}"
            })
        }));

        series.columns.template.setAll({
            cornerRadiusTL: 5,
            cornerRadiusTR: 5
        });

        series.columns.template.adapters.add("fill", function(fill, target) {
            return chart.get("colors").getIndex(series.columns.indexOf(target));
        });

        series.columns.template.adapters.add("stroke", function(stroke, target) {
            return chart.get("colors").getIndex(series.columns.indexOf(target));
        });

        <?php $json_format = visitorsGraphs($conn)?>
        var data = <?=$json_format?>;

        xAxis.data.setAll(data);
        series.data.setAll(data);


        // Make stuff animate on load
        // https://www.amcharts.com/docs/v5/concepts/animations/
        series.appear(1000);
        chart.appear(1000, 100);

    });
    // end am5.ready()

    //chart 2
    am5.ready(function() {

        // Create root element
        // https://www.amcharts.com/docs/v5/getting-started/#Root_element
        var root = am5.Root.new("chartdiv2");

        // Set themes
        // https://www.amcharts.com/docs/v5/concepts/themes/
        root.setThemes([
            am5themes_Animated.new(root)
        ]);

        // Create chart
        // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/
        var chart = root.container.children.push(
            am5percent.PieChart.new(root, {
                endAngle: 270
            })
        );

        // Create series
        // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
        var series = chart.series.push(
            am5percent.PieSeries.new(root, {
                valueField: "views",
                categoryField: "city",
                endAngle: 270
            })
        );

        series.states.create("hidden", {
            endAngle: -90
        });

        // Set data
        // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
        <?php $json_format = visitorsGraphsPie($conn); ?>
        var data = <?=$json_format?>;

        series.data.setAll(data);

        series.appear(1000, 100);

    }); // end am5.ready()


    //chart 3
    am5.ready(function() {

        // Create root element
        // https://www.amcharts.com/docs/v5/getting-started/#Root_element
        var root = am5.Root.new("chartdiv3");


        // Set themesDecember
        // https://www.amcharts.com/docs/v5/concepts/themes/
        root.setThemes([
            am5themes_Animated.new(root)
        ]);


        // Create chart
        // https://www.amcharts.com/docs/v5/charts/xy-chart/
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
            panX: true,
            panY: true,
            wheelX: "panX",
            wheelY: "zoomX",
            pinchZoomX: true
        }));

        // Add cursor
        // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
        var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
        cursor.lineY.set("visible", false);


        // Create axes
        // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
        var xRenderer = am5xy.AxisRendererX.new(root, {
            minGridDistance: 30
        });
        xRenderer.labels.template.setAll({
            rotation: -90,
            centerY: am5.p50,
            centerX: am5.p100,
            paddingRight: 15
        });

        var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
            maxDeviation: 0.3,
            categoryField: "date",
            renderer: xRenderer,
            tooltip: am5.Tooltip.new(root, {})
        }));

        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            maxDeviation: 0.3,
            renderer: am5xy.AxisRendererY.new(root, {})
        }));


        // Create series
        // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
        var series = chart.series.push(am5xy.ColumnSeries.new(root, {
            name: "Views/day",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "announces",
            sequencedInterpolation: true,
            categoryXField: "date",
            tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}"
            })
        }));

        series.columns.template.setAll({
            cornerRadiusTL: 5,
            cornerRadiusTR: 5
        });

        series.columns.template.adapters.add("fill", function(fill, target) {
            return chart.get("colors").getIndex(series.columns.indexOf(target));
        });

        series.columns.template.adapters.add("stroke", function(stroke, target) {
            return chart.get("colors").getIndex(series.columns.indexOf(target));
        });

        <?php $json_format = graphance($conn)?>
        var data = <?=$json_format?>;

        xAxis.data.setAll(data);
        series.data.setAll(data);


        // Make stuff animate on load
        // https://www.amcharts.com/docs/v5/concepts/animations/
        series.appear(1000);
        chart.appear(1000, 100);

    });

    //chart 4
    am5.ready(function() {

        // Create root element
        // https://www.amcharts.com/docs/v5/getting-started/#Root_element
        var root = am5.Root.new("chartdiv4");

        // Set themes
        // https://www.amcharts.com/docs/v5/concepts/themes/
        root.setThemes([
            am5themes_Animated.new(root)
        ]);

        // Create chart
        // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/
        var chart = root.container.children.push(
            am5percent.PieChart.new(root, {
                endAngle: 270
            })
        );

        // Create series
        // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
        var series = chart.series.push(
            am5percent.PieSeries.new(root, {
                valueField: "announces",
                categoryField: "city",
                endAngle: 270
            })
        );

        series.states.create("hidden", {
            endAngle: -90
        });

        // Set data
        // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
        <?php $json_format = anceGraphsPie($conn); ?>
        var data = <?=$json_format?>;

        series.data.setAll(data);

        series.appear(1000, 100);

    }); // end am5.ready()


    $(".jren").click(function() {
        $(".jren").each(function() {
            $(this).removeClass("active");
        })

        $(this).addClass("active");
    })

    $(".glb").click(function() {
        $("#secondcharts").css("display", "flex");
        $("#firstcharts").css("display", "none");
    })

    $(".vst").click(function() {
        $("#secondcharts").css("display", "none");
        $("#firstcharts").css("display", "flex");
    })
    </script>
</body>

</html>
<?php }else{
    header("Location: ../firstpage.php");
}?>
