<html>
<head>
<title>Mapa de clientes</title>
<!-- Mobile viewport optimized -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link media="all" type="text/css" href="assets/dashicons.css" rel="stylesheet">
<link media="all" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic" rel="stylesheet">
<link rel='stylesheet' id='style-css'  href='style.css' type='text/css' media='all' />
<script type='text/javascript' src='assets/jquery.js'></script>
<script type='text/javascript' src='assets/jquery-migrate.js'></script>

<?php /* === GOOGLE MAP JAVASCRIPT NEEDED (JQUERY) ==== */ ?>
<script src="https://maps.google.com/maps/api/js?sensor=true" type="text/javascript"></script>
<script type='text/javascript' src='assets/gmaps.js'></script>

</head>

<body>
	<div id="container">

		<article class="entry">
			<div class="entry-content">

				<?php /* === THIS IS WHERE WE WILL ADD OUR MAP USING JS ==== */ ?>
				<div class="google-map-wrap" itemscope itemprop="hasMap" itemtype="https://schema.org/Map">
					<div id="google-map" class="google-map">
					</div><!-- #google-map -->
				</div>

				<?php /* === MAP DATA === */ ?>
<?php
			//global	$locations;
			$locations = array();
// function to get  the address
function get_lat_long($address, $nombre, $image) {
$region = "Uruguay";
	
$url = "https://maps.google.com/maps/api/geocode/json?address=".urlencode($address)."&sensor=false&region=".$region;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$response = curl_exec($ch);
curl_close($ch);
$response_a = json_decode($response);
$latitude = $response_a->results[0]->geometry->location->lat;
 $longitude = $response_a->results[0]->geometry->location->lng;	
				/* Marker #1 */
				$locations[] = array(
				    'image'=>$image,
					'google_map' => array(
						'lat' =>$latitude,
						'lng' => $longitude,
					),
					'location_address' =>$address,
					'location_name'    =>$nombre,
				);   
   return $locations;
}

// We define our address
$address = 'Av Pedro de Mendoza 7052';
$nombre="Perrin S.A.";
$image="../clientes/logos/perrinchico.png";
$locations[] =get_lat_long($address, $nombre,$image);

$address = 'Juan Ramón Goméz 2671';
$nombre="Casa.";
$image="../img/google_map_mark.png";
$locations[] =get_lat_long($address, $nombre,$image);

$address = 'Av. 8 de Octubre 2355';
$nombre="CMU.";
$image="../clientes/logos/cmuchico.png";
$locations[] =get_lat_long($address, $nombre,$image);

$address = 'Av. Brasil 2391, Montevideo, Uruguay';
$nombre="Improfit";
$image="../clientes/logos/impeofirchico.png";
$locations[] =get_lat_long($address, $nombre,$image);

$address = 'Burgues 2881 esq. Enrique Martinez, 11800 Montevideo, Departamento de Montevideo, Uruguay';
$nombre="Liderblex S.A.";
$image="../clientes/logos/dapiechico.png";
$locations[] =get_lat_long($address, $nombre,$image);

$address = 'Carabelas 3412,, Departamenteo de Montevideo , Uruguay ';
$nombre="Sanitaria Gyalog S.A.";
$image="../img/google_map_mark.png";
$locations[] =get_lat_long($address, $nombre,$image);

$address = '8 de Octubre 2662,, Departamenteo de Montevideo , Uruguay ';
$nombre="Fundación Logosófica del Uruguay.";
$image="../clientes/logos/logosofiachico.png";
$locations[] =get_lat_long($address, $nombre,$image);

$address = 'Miraflores 1443 esq. Ferrari, Montevideo 11500, Departamenteo de Montevideo , Uruguay ';
$nombre="Biblioteca Nuestros Hijos.";
$image="../clientes/logos/nuestroshojischico.png";
$locations[] =get_lat_long($address, $nombre,$image);

$address = 'Miguel Angel 3740, 11420 Montevideo, Departamento de Montevideo, Uruguay ';
$nombre="Tramil S.A.";
$image="../clientes/logos/tramilchico.png";
$locations[] =get_lat_long($address, $nombre,$image);

$address = 'Costa Rica 1589, 11600 Montevideo, Departamento de Montevideo, Uruguay ';
$nombre="Beatriz Martinez.";
$image="../clientes/logos/bmartinezchico.png";
$locations[] =get_lat_long($address, $nombre,$image);



?>


				<?php /* === PRINT THE JAVASCRIPT === */ ?>

				<?php
				/* Set Default Map Area Using First Location */
				$map_area_lat = isset( $locations[0][0]['google_map']['lat'] ) ? $locations[0][0]['google_map']['lat'] : '';
				$map_area_lng = isset( $locations[0][0]['google_map']['lng'] ) ? $locations[0][0]['google_map']['lng'] : '';
				?>

				<script>
				jQuery( document ).ready( function($) {

					/* Do not drag on mobile. */
					var is_touch_device = 'ontouchstart' in document.documentElement;

					var map = new GMaps({
						el: '#google-map',
						lat: '<?php echo $map_area_lat; ?>',
						lng: '<?php echo $map_area_lng; ?>',
						scrollwheel: false,
						draggable: ! is_touch_device
					});

					/* Map Bound */
					var bounds = [];

					<?php /* For Each Location Create a Marker. */
					foreach( $locations as $location ){
						$image = $location[0]['image'];
						$name = $location[0]['location_name'];
						$addr = $location[0]['location_address'];
						$map_lat = $location[0]['google_map']['lat'];
						$map_lng = $location[0]['google_map']['lng'];
						?>
						/* Set Bound Marker */
						var latlng = new google.maps.LatLng(<?php echo $map_lat; ?>, <?php echo $map_lng; ?>);
						bounds.push(latlng);
						/* Add Marker */
						map.addMarker({
							lat: <?php echo $map_lat; ?>,
							lng: <?php echo $map_lng; ?>,
							title: '<?php echo $name; ?>',
							infoWindow: {
								content: '<p><?php echo $name; ?></p>'
							},
							icon:' <?php echo $image; ?>',
						});
						
					<?php } //end foreach locations ?>

					/* Fit All Marker to map */
					map.fitLatLngBounds(bounds);

					/* Make Map Responsive */
					var $window = $(window);
					function mapWidth() {
						var size = $('.google-map-wrap').width();
						$('.google-map').css({width: size + 'px', height: (size/2) + 'px'});
					}
					mapWidth();
					$(window).resize(mapWidth);

				});
				</script>

				<div class="map-list">
					<ul class="google-map-list" itemscope itemprop="hasMap" itemtype="https://schema.org/Map">

						<?php foreach( $locations as $location ){
							$image = $location[0]['image'];
							$name = $location[0]['location_name'];
							$addr = $location[0]['location_address'];
							$map_lat = $location[0]['google_map']['lat'];
							$map_lng = $location[0]['google_map']['lng'];
							?>
							<li><img src="<?php echo $image;?>" alt="" />
								<a target="_blank" itemprop="url" href="<?php echo 'https://www.google.com/maps/place/' . $map_lat . ',' . $map_lng;?>"><?php echo $name; ?></a>
								<span itemprop="address" itemscope itemtype="https://schema.org/PostalAddress"><?php echo $addr; ?></span>
							</li>
						
						<?php } //end foreach ?>
					</ul><!-- .google-map-list -->
				</div>
			</div><!-- .entry-content -->
		</article>
	</div><!-- #container -->
</body>

</html>