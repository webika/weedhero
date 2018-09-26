<table border="0" width="100%">
    <tr>
        <td>
            <div id="placeholder" style="width:100%; height:400px;"></div>
        </td>
        <td width="400">
            <div id="piechart" style="width:100%; height:400px;"></div>
        </td>
    </tr>
</table>
<?php
//$sql = "SELECT COUNT(*) , DATE(date) FROM table WHERE DATE(dtCreatedAt) >= DATE('2009-03-01') AND DATE(dtCreatedAt) <= DATE('2009-03-10') GROUP BY DATE(date)";
if (empty($daterange)) {
    $daterange = date('m').'/01/'.date('Y').' - '.date('m').'/'.date("t").'/'.date('Y');
}
$pos = strpos($daterange, '-');
$tempcfrom = $daterange;
$cto = date('Y-m-d', strtotime(substr($tempcfrom, $pos + 1)));
$cfrom = date('Y-m-d', strtotime(substr($tempcfrom, 0, $pos - 1)));
if ($location == 0) {
    $sql = "SELECT DATE(time_ordered) as cdate, count(time_ordered) as num, sum(total_sum) as tsum  FROM ".MMSettingsForm::getTableNames('orders')." WHERE DATE(time_ordered) >= DATE('$cfrom') AND DATE(time_ordered) <= DATE('$cto') GROUP BY cdate";
    $data = Yii::app()->db->createCommand($sql)->queryAll();
    $sql = "SELECT count(id) as count, payment_type FROM ".MMSettingsForm::getTableNames('orders')." WHERE DATE(time_ordered) >= DATE('$cfrom') AND DATE(time_ordered) <= DATE('$cto') GROUP BY payment_type";
    $datapie = Yii::app()->db->createCommand($sql)->queryAll();
} else {
    $sql = "SELECT DATE(time_ordered) as cdate, count(time_ordered) as num, sum(total_sum) as tsum  FROM ".MMSettingsForm::getTableNames('orders')." WHERE DATE(time_ordered) >= DATE('$cfrom') AND DATE(time_ordered) <= DATE('$cto') AND location_id = '" . $location . "' GROUP BY cdate";
    $data = Yii::app()->db->createCommand($sql)->queryAll();
    $sql = "SELECT count(id) as count, payment_type FROM ".MMSettingsForm::getTableNames('orders')." WHERE DATE(time_ordered) >= DATE('$cfrom') AND DATE(time_ordered) <= DATE('$cto') AND location_id = '" . $location . "' GROUP BY payment_type";
    $datapie = Yii::app()->db->createCommand($sql)->queryAll();
}
foreach ($data as $key => $day) {
    $data[$key]['cdate'] = strtotime($day['cdate']) * 1000;
}
?>
<script>
    function currencyFormatter(v, axis) {
        return "$ " + v.toFixed(axis.tickDecimals);
    }

    var plot = $.plot($("#placeholder"),
            [{data: <?php echo '[ ';
foreach ($data as $day) {
    echo '[ ' . $day['cdate'] . ' , ' . $day['num'] . ' ], ';
} echo ' ]'; ?>, label: "<?php echo Yii::t('_', 'Sales') ?>"},
                {data: <?php echo '[ ';
foreach ($data as $day) {
    echo '[ ' . $day['cdate'] . ' , ' . $day['tsum'] . ' ], ';
} echo ' ]'; ?>, label: "<?php echo Yii::t('_', 'Income') ?>", yaxis: 2}], {
        series: {
            lines: {show: true},
            points: {show: true}
        },
        grid: {hoverable: true, clickable: true},
        xaxes: [{mode: 'time', minTickSize: [1, "day"], timeformat: "%d/%m/%y"}],
        yaxes: [{min: 0},
            {
                // align if we are to the right
                min: 0,
                alignTicksWithAxis: null,
                position: 'right',
                tickFormatter: currencyFormatter
            }],
        legend: {position: 'nw'}
    });

    function showTooltip(x, y, contents) {
        $('<div id="tooltip" class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + contents + '</div></div>').css({
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
        ;
    }

    $("#placeholder").bind("plothover", function(event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));
        if (item) {
            if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;

                $("#tooltip").remove();
                var x = new Date(parseInt(item.datapoint[0].toFixed(2))),
                        y = parseInt(item.datapoint[1].toFixed(2));

                var curr_date = x.getDate();
                var curr_month = x.getMonth() + 1; //Months are zero based
                var curr_year = x.getFullYear();

                showTooltip(item.pageX, item.pageY,
                        item.series.label + ": " + curr_date + "/" + curr_month + "/" + curr_year + " : " + y);
            }
        }
        else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });

    $.plot($("#piechart"),<?php echo '[ ';
foreach ($datapie as $pie) {
    $label_pie='Credit';
    if($pie['payment_type'] == 'cash') {
        $label_pie='Cash';
    }
    echo '{ data:' . $pie['count'] . ' , label: "' . $label_pie . '" }, ';
} echo ' ]'; ?>,
            {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        innerRadius: 0.5,
                        label: {
                            show: true,
                            radius: 1,
                            formatter: function(label, series) {
                                return '<div style="font-size:12pt;text-align:center;padding:3px;color:black;border: 2px solid #FFF;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                            },
                            background: {opacity: 0.8}
                        },
                        combine: {
                            color: '#999',
                            threshold: 0.1
                        }
                    }
                },
                legend: {
                    show: false
                }
            });
</script>