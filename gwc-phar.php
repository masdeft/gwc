<?php

$phar = new Phar("./gwc.phar", FilesystemIterator::CURRENT_AS_FILEINFO |FilesystemIterator::KEY_AS_FILENAME, "gwc.phar");
$phar->buildFromDirectory('./');
$phar->setStub($phar->createDefaultStub("public/make.php"));

 