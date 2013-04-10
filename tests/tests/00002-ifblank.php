<?php
global $output_path;
$out  = '';
$out .= dfm::construct()->ifblank('TEST1!')->apply(1);
$out .= "\n";
$out .= dfm::construct()->ifblank('TEST2!')->apply(null);

file_put_contents($output_path,$out);

?>