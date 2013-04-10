<?php
global $output_path;
$out  = '';
$out .= dfm::construct()->epoch('Y-m-d H:i:s')->apply(1365540913);

file_put_contents($output_path,$out);

?>