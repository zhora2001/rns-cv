<?php

function csv_to_array($filename='', $delimiter=',')
{
    if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = ($row);
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}

$result = csv_to_array(__DIR__.'/diln.csv',';');
$kus = array();
$tmp_kus = array();
$temp_a = array();
$dil = array();
$rayon = array();
$tmp_rayon = array();
//echo print_r($result);
foreach($result as $var11)
  {
//echo ($var11[1]); //
    array_push($tmp_kus, $var11['kusch']);
    array_push($tmp_rayon, $var11['rayon']);
    $temp_a[0] = ['diln'=>$var11['diln'], 'n_diln'=>$var11['n_diln']];
    //$new_kusch[$i] = ['kusch'=>$var1, 'n_diln'=>$q];
    array_push($dil,$temp_a[0]);
  }
$tmp_kus = array_unique($tmp_kus);
$tmp_rayon = array_unique($tmp_rayon);
$temp_a = array();

foreach($tmp_kus as $var1)
    {
      $q = ''; $i = 0;
          foreach ($result as $var11) {
          if ($var11['kusch'] == $var1)
          $q .= $var11['n_diln'].", ";
      }
$temp_a[0] = ['kusch'=>$var1, 'n_diln'=>$q];
//$new_kusch[$i] = ['kusch'=>$var1, 'n_diln'=>$q];
array_push($kus,$temp_a[0]);
$i += 1;
}

foreach($tmp_rayon as $var1)
    {
      $q = ''; $i = 0;
          foreach ($result as $var11) {
          if ($var11['rayon'] == $var1)
          $q .= $var11['n_diln'].", ";
      }
$temp_a[0] = ['rayon'=>$var1, 'n_diln'=>$q];
//$new_kusch[$i] = ['kusch'=>$var1, 'n_diln'=>$q];
array_push($rayon,$temp_a[0]);
$i += 1;
}


echo "Дільниці <br />";
echo print_r($dil);
echo "<br />";
echo "Кущі <br />";
echo print_r($kus);
echo "<br />";
echo "Райони <br />";
echo print_r($rayon);
echo "<br />";

?>
