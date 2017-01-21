<?php

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=\"$namaFile.csv\"");
header("Pragma: no-cache");
header("Expires: 0");
echo $csv;
