<?php
$data = $_GET['data'];
$msg=$_GET['msg'];
$msg2=$_GET['msg2'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Flot Examples: Categories</title>
	<link href="../common/flot/examples/examples.css" rel="stylesheet" type="text/css">
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../common/flot/excanvas.min.js"></script><![endif]-->
	<script language="javascript" type="text/javascript" src="../common/flot/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="../common/flot/jquery.flot.js"></script>
	<script language="javascript" type="text/javascript" src="../common/flot/jquery.flot.categories.js"></script>
	<script type="text/javascript" src="../common/flot/jquery.flot.axislabels.js"></script>
	<link rel="stylesheet" href="../css3/styles.css">
	<style type="text/css">
	.flot-x-axis .flot-tick-label {
    white-space: nowrap;
    transform: translate(-9px, 0) rotate(-60deg);
    text-indent: -100%;
    transform-origin: top right;
    text-align: right !important;
}
	</style>
	<script type="text/javascript">
var data = <?php echo $data;?>;
alert(data);
var dataset = [ { label: "<?php echo $msg;?>", data: data, color: "#5482FF" } ] ;
	$(function() {
		$.plot("#placeholder", dataset, {
			series: {
				bars: {
					show: true,
					barWidth: 0.5,
					align: "center"
				}
			},
			xaxis: {
				mode: "categories",
				tickLength: 0
			},
            yaxis: {
                axisLabel: "Compras totales",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial',
                axisLabelPadding: 3,
                tickFormatter: function (v, axis) {
                    return v ;
                }
            },			
            legend: {
                noColumns: 0,
                labelBoxBorderColor: "#000000",
                position: "nw"
            },
            grid: {
                hoverable: true,
                borderWidth: 2,
                backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
            }		
		});
 		$("#placeholder").UseTooltip();
	});
	
        var previousPoint = null, previousLabel = null;
 
        $.fn.UseTooltip = function () {
            $(this).bind("plothover", function (event, pos, item) {
                if (item) {
                    if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                        previousPoint = item.dataIndex;
                        previousLabel = item.series.label;
                        $("#tooltip").remove();
 
                        var x = item.datapoint[0];
                        var y = item.datapoint[1];
 
                        var color = item.series.color;
 
                        //console.log(item.series.xaxis.ticks[x].label);                
 
                        showTooltip(item.pageX,
                        item.pageY,
                        color,
                        "<strong>" + item.series.label + "</strong><br>" + item.series.xaxis.ticks[x].label + " : <strong>" + y + "</strong>");
                    }
                } else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });
        };
 
        function showTooltip(x, y, color, contents) {
            $('<div id="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                top: y - 40,
                left: x - 120,
                border: '2px solid ' + color,
                padding: '3px',
                'font-size': '9px',
                'border-radius': '5px',
                'background-color': '#fff',
                'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                opacity: 0.9
            }).appendTo("body").fadeIn(200);
        }
	</script>
</head>
<body>
<table summary="Estadísticas" class="noborder boxtable nohover" width="100%">
<tbody>
<tr class="liste_titre"><th class="liste_titre"><?php echo $msg2;?></th></tr>
<tr class="impair"><td class="tdboxstats nohover flexcontainer">

<div id="content">
		<div class="demo-container">
			<div id="placeholder"  style="width:400px;height:200px; margin-left: 10px; top:5px; padding: 5px 5px 5px 5px;"></div>
		</div>
	</div>

</td>
</tr></tbody></table> 
</body>
</html>