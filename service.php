<?php

if (isset($_GET['feedback'])) {
    $path = 'Cinema/feedback.txt';

    $info = $_SERVER["QUERY_STRING"];
    $info = substr($info, 0, 200);

    echo file_put_contents($path, $info . "\n", FILE_APPEND) ? 1 : 0;
} else {
    echo 'Video-Spider Service!';
}
