<?php

header("Content-type: application/json");
header("Content-Disposition: attachment; filename=\"$namaFile.json\"");
header("Content-Length: " . strlen($jsonData));
echo $jsonData;
