<?php
header('Content-type: application/json');

$valid_exts = array('zip', 'rar'); // valid extensions
$max_size = 2000000 * 1024; // max file size (200kb)
$path = 'upload/'; // upload directory

if ( $_SERVER['REQUEST_METHOD'] === 'POST' )
{
  if( @is_uploaded_file($_FILES['image']['tmp_name']) )
  {
    // get uploaded file extension
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    // looking for format and size validity
    if (in_array($ext, $valid_exts) AND $_FILES['image']['size'] < $max_size)
    {
      // unique file path
      $path = $path . uniqid(). '.' .$ext;
      // move uploaded file from temp to uploads directory
      if (move_uploaded_file($_FILES['image']['tmp_name'], $path))
      {
        $status = 'Archivo subido con exito!';
        
			
      } else {
        $status = 'Upload Fail: Unknown error occurred!';
      }
    } else {
      $status = 'Upload Fail: Unsupported file format or It is too large to upload!';
    }
  }
  else {
    $status = 'Upload Fail: File not uploaded!';
  }
}
else {
  $status = 'Bad request!';
}

// echo out json encoded status
echo json_encode(array('status' => $status));
?>