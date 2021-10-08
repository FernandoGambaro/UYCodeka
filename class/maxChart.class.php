<?php
class maxChart {
   var $data;         // The data array to display
   var $type = 1;     // Vertical:1 or Horizontal:0 chart
   var $title;        // The title of the chart
   var $width = 300;  // The chart box width 
   var $height = 200; // The chart box height
   var $metaSpaceHorizontal = 60; // Total space needed for chart title + bar title + bar value
   var $metaSpaceVertical = 60; // Total space needed for chart title + bar title + bar value
   var $variousColors = false;

   function maxChart($data){
      $this->data = $data;
   }

   function displayChart($title='', $type, $width=300, $height=200, $variousColor=false){
      $this->type   = $type;
      $this->title  = $title;
      $this->width  = $width;
      $this->height = $height;
      $this->variousColors = $variousColor;

      echo '<div style="width:'.$this->width.'px; height:'.$this->height.'px; 	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px; color:#333; text-align: center; margin:auto; border:1px solid #333; padding:5px; padding-top:0px; overflow:hidden; 	background-color:#fff;">
                <h2>'.$this->title.'</h2>'."\r\n";
      if ($this->type == 1)  $this->drawVertical();
      else $this->drawHorizontal();
      echo '    </div>';
   }

   function getMaxDataValue(){
      $max = 0;
      foreach ($this->data as $key=>$value) {
         if ($value > $max) $max = $value;	
      }
      return $max;
   }

   function getElementNumber(){
      return sizeof($this->data);
   }

   function drawVertical(){
      $multi = ($this->height -$this->metaSpaceHorizontal) / $this->getMaxDataValue();
      $max   = $multi * $this->getMaxDataValue();
      $barw  = floor($this->width / $this->getElementNumber()) - 6;
      $i = 1;
      foreach ($this->data as $key=>$value) {
         $b = floor($max - ($value*$multi));
         $a = $max - $b;
         if ($this->variousColors) $color = ($i % 5) + 1;
         else $color = 1;
         $i++;
         echo '  <div style="	margin: 2px; float:left;">'."\r\n";
         echo '    <div style="margin-top:'.$b.'px;width:'.$barw.'px;	padding:0px; margin:0px; font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 12px; text-align: center;">'.$value.'</div>'."\r\n";
	 echo '    <div style=" height:'.$max.'; padding:1 px; margin:1px;"><img src="css/images/bar'.$color.'.png" style="width:'.$barw.'px; height:'.$a.'px;" /></div>'."\r\n";
         echo '    <div style="width:'.$barw.'px;	padding:0px; margin:0px; font-family: Geneva, Arial, Helvetica, sans-serif; 	font-size: 12px; text-align: center;">'.$key.'</div>'."\r\n";
         echo '  </div>'."\r\n";

      }
   }

   function drawHorizontal(){
      $multi = ($this->width-170) / $this->getMaxDataValue();
      $max   = $multi * $this->getMaxDataValue();
      $barh  = floor(($this->height - 35) / $this->getElementNumber());
      $i = 1;
      foreach ($this->data as $key=>$value) {
         $b = floor($value*$multi);

         if ($this->variousColors) $color = ($i % 5) + 1;
         else $color = 1;
         $i++;
         echo '  <div style="height:'.$barh.'px;	margin: 4px; text-align:left; clear:both;">'."\r\n";
         echo '    <div style="line-height:'.$barh.'px; width:90px;	padding:0px; margin:0px 5px; font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 12px; text-align: right; overflow:hidden; float:left;">'.$key.'</div>'."\r\n";
         echo '    <div style="    float:left;"><img src="css/images/barh'.$color.'.png" style="width:'.$b.'px; height:'.$barh.'px;" /></div>'."\r\n";
         echo '    <div style="line-height:'.$barh.'px; width:30px;	padding:0px; margin:0px; font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 12px; text-align: center;">'.$value.'</div>'."\r\n";
         echo '  </div>';
      }
   }
}


?>
