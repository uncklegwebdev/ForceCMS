<?php
exit; // upload demo is disabled

if ( !empty( $_FILES ) ) {
    $tempPath = $_FILES[ 'file' ][ 'tmp_name' ];
    $uploadPath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $_FILES[ 'file' ][ 'name' ];

    move_uploaded_file( $tempPath, $uploadPath );

    $answer = ['answer' => 'File transfer completed'];
    $json = json_encode( $answer );

    echo $json;

} else {
    echo 'No files';
}
?>