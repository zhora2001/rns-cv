<?php

$nonce = $_REQUEST['nonce'];
//$nonce = isset($_POST['nonce']) ? $_POST['nonce'] : ''; // сначала возьмем скрытое поле nonce

$title_p = isset($_POST['title_p']) ? $_POST['title_p'] : '';
$opys_p = isset($_POST['opys_p']) ? $_POST['opys_p'] : '';
$vyd_problemy = isset($_POST['vyd_problemy']) ? $_POST['vyd_problemy'] : '';
$status_p = isset($_POST['status_p']) ? $_POST['status_p'] : '';
$kontakt = isset($_POST['kontakt']) ? $_POST['kontakt'] : '';
$smi = isset($_POST['smi']) ? $_POST['smi '] : '';
$propos_v = isset($_POST['propos_v']) ? $_POST['propos_v'] : '';
$n_diln = isset($_POST['n_diln']) ? $_POST['n_diln']:'';


if (!wp_verify_nonce($nonce, 'problema'))
wp_send_json_error(array('message' => 'Данні відправлені з стороньої адреси', 'redirect' => false));

if (!$n_diln )
wp_send_json_error(array('message' => 'Не вибрано номер округу', 'redirect' => false));

if ( !trim($title_p) || !trim($opys_p)  )
wp_send_json_error(array('message' => 'Не заповнені обовязкові поля', 'redirect' => false));


$pod = pods( 'problemy');

$data = array(
                'post_title' => $title_p,
                'post_content' => $opys_p,
                'kontakt' => $kontakt,
                'smi' => $smi,
                'propos_v' => $propos_v,
                'n_diln' => $n_diln,
                );

if ($pod != '')
{
//                  $post_id = $pod->id();
//                if( $post_id != $n_prih)
                $post_id = $pod->add( $data );
//                else {
//                  $pod->save( $data );
                }




if ($post_id)
{
wp_set_object_terms( $post_id, intval($status_p), 'status_p' , false );
wp_set_object_terms( $post_id, intval($vyd_problemy), 'vyd_problemy' , false );
wp_set_object_terms( $post_id, $n_diln, 'dvk', false );

if ($_FILES['declar']) { // если дополнительные фото были загружены
  $imgs = array(); // из-за того, что дефолтный массив с загруженными файлами в пхп выглядит не так как нужно, а именно вся инфа о файлах лежит в разных массивах но с одинаковыми ключами, нам нужно создать свой массив с блэкджеком, где у каждого файла будет свой массив со всеми данными
  foreach ($_FILES['declar']['name'] as $key => $array) { // пробежим по массиву с именами загруженных файлов
    $file = array( // пишем новый массив
      'name' => $_FILES['declar']['name'][$key],
      'type' => $_FILES['declar']['type'][$key],
      'tmp_name' => $_FILES['declar']['tmp_name'][$key],
      'error' => $_FILES['declar']['error'][$key],
      'size' => $_FILES['declar']['size'][$key]
    );
    $_FILES['declar'.$key] = $file; // записываем новый массив с данными в глобальный массив с файлами
    $imgs[] = media_handle_upload( 'declar'.$key, $post_id ); // добавляем текущий файл в медиабиблиотека, а id картинки суем в другой массив
  }
  update_post_meta($post_id,'foto_p',$imgs); // привязываем все картинки к посту
}



wp_send_json_success(array('message' => 'Записано.'.$post_id, 'redirect' => false));
//wp_send_json_success($data);


}
else {
  wp_send_json_success(array('message' => 'Трапилась помилка.', 'redirect' => false));
}
die();
 // говорим что все прошло ок, если нужен редирект то вместо false поставьте $redirect_to

?>
