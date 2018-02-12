<?php

header("Content-type: {$contentType}");
header("Content-Disposition: attachment; filename=\"$namaFile\"");
header("Cache-Control: no-cache");
header("Expires: 0");
echo $text;
