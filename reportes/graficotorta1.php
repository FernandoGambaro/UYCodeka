<?php // content="text/plain; charset=utf-8"

include ('jpgraph/src/jpgraph.php');
include ('jpgraph/src/jpgraph_pie.php');
include ('jpgraph/src/jpgraph_pie3d.php');


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

// Create the Pie Graph.
$graph = new PieGraph(300,250);
$graph->SetShadow();

$theme_class= new VividTheme;
$graph->SetTheme($theme_class);
 
// Set A title for the plot
$graph->title->Set($titulo);
$graph->title->SetFont(FF_FONT1,FS_BOLD,12);
$graph->title->SetColor('black');
$graph->legend->Pos(0.1,0.2); 

$p1 = new PiePlot3D($datay);
$p1->SetAngle(50);
//$p1->SetCenter(0.5,0.5);
$p1->SetSize(0.4);
 
// Enable and set policy for guide-lines. Make labels line up vertically
//$p1->SetGuideLines(true,false);
//$p1->SetGuideLinesAdjust(1.1);
// Setup the labels to be displayed
$p1->SetLabels($datax);
 
// This method adjust the position of the labels. This is given as fractions
// of the radius of the Pie. A value < 1 will put the center of the label
// inside the Pie and a value >= 1 will pout the center of the label outside the
// Pie. By default the label is positioned at 0.5, in the middle of each slice.
$p1->SetLabelPos(1);
 
// Setup the label formats and what value we want to be shown (The absolute)
// or the percentage.
$p1->SetLabelType(PIE_VALUE_ABS);
$p1->value->Show();
$p1->value->SetFont(FF_FONT1,FS_NORMAL,9);
$p1->value->SetFormat('%2.1f%%'); 
$p1->value->SetColor('darkgray');
 
// Add and stroke
$graph->Add($p1);
$graph->Stroke();


/*

// Create the Pie Graph. 
$graph = new PieGraph(350,300, "auto");
//$graph = new Graph(400,200,"auto");	
$graph->img->SetMargin(40,40,55,80);
$graph->SetScale("textlin");
$graph->SetMarginColor("silver");

$theme_class= new VividTheme;
$graph->SetTheme($theme_class);

// Set A title for the plot
$graph->title->Set($titulo);
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->title->SetMargin(8);

// Create
$p1 = new PiePlotC($datay);

//$p1 = new PiePlot3D($datay);
//$p1->SetAngle(30);
$p1->SetSize(0.35);
//$p1->SetCenter(0.45);
//$p1->SetLegends($datax);


// Label font and color setup
$p1->value->SetFont(FF_FONT1,FS_BOLD,12);
$p1->value->SetColor('black');

$p1->value->Show();

// Add drop shadow to slices
$p1->SetShadow();

// Explode all slices 15 pixels
$p1->ExplodeAll(15);
$p1->SetLabels($datax);


$graph->Add($p1);

$p1->ShowBorder();
$p1->SetColor('black');
//$p1->ExplodeSlice(1);
$graph->Stroke();

*/
?>