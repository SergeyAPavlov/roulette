<?php

chdir(__DIR__ . '/../../');
if (!file_exists('files')) mkdir('files');
if (!file_exists('testfiles')) mkdir('testfiles');
if (!file_exists('log')) mkdir('log');
chmod('files', 0777);
chmod('testfiles', 0777);
chmod('log', 0777);