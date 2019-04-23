 <?php
  $datanew = file_get_contents( 
"https://api.openprocurement.org/api/2.5/tenders?opt_pretty=1&descending=1&limit=1" );
$encode = json_decode( $datanew, true);


foreach($encode['data'] as $item){
	
	$id_tender = $item['id'];
	$datanew_new = file_get_contents( "https://api.openprocurement.org/api/2.5/tenders/$id_tender" );
	$encode_new = json_decode($datanew_new, true);
	echo "<pre>";
		print_r($encode_new);
	echo "</pre>";  
	
}
echo "<pre>";
print_r($encode);
 echo "</pre>";  