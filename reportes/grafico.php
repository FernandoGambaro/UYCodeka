<?php // content="text/plain; charset=utf-8"

require_once ('../jpgraph/jpgraph.php');
require_once ('../jpgraph/jpgraph_bar.php');
require_once ('../jpgraph/jpgraph_spider.php');


function array_recibe($url_array) {
    $tmp = stripslashes($url_array);
    $tmp = urldecode($tmp);
    $tmp = unserialize($tmp);
   return $tmp;
}

$a=$_GET['Data'];
$titulo=$_GET['t'];
//echo $a;

$a=array_recibe($a);

$total = 0;
	foreach ($a as $key => $item)
	{
//echo $key." .. ".$item."<br>";
$datay[$total]=$item;
$datax[$total]=$key;
$total++;
	}


// Setup the graph. 
$graph = new Graph(400,200,"auto");	
$graph->img->SetMargin(40,20,25,80);
$graph->SetScale("textlin");
$graph->SetMarginColor("silver");
//$graph->SetShadow();

$graph->SetBackgroundImage("images/tiger_bkg.png",3);

$graph->img->SetAntiAliasing("white");
$graph->SetScale("textlin");
$graph->SetShadow();
$graph->title->Set("Background image");

// Set up the title for the graph
$graph->title->Set($titulo);
$graph->title->SetFont(FF_VERDANA,FS_NORMAL,16);
$graph->title->SetColor("darkred");

// Setup font for axis
$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,10);
$graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,10);

// Show 0 label on Y-axis (default is not to show)
$graph->yscale->ticks->SupressZeroLabel(false);

// Setup X-axis labels
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(50);

// Set X-axis at the minimum value of Y-axis (default will be at 0)
$graph->xaxis->SetPos("min");	// "min" will position the x-axis at the minimum value of the Y-axis

// Create the bar pot
$bplot = new BarPlot($datay);
$bplot->SetWidth(0.6);

// Setup color for gradient fill style 
$bplot->SetFillGradient("navy","steelblue",GRAD_MIDVER);

// Set color for the frame of each bar
$bplot->SetColor("navy");
$graph->Add($bplot);

// Finally send the graph to the browser
$graph->Stroke();
?>