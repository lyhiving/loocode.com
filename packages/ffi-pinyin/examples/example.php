<?php

include __DIR__ . '/../src/Pinyin.php';


$py = FastFFI\Pinyin::new();

echo "无音调: ", $py->plain("中国人..."), "\n";
echo "首字母: ", $py->letter("中国人"), "\n";
echo "音调: ", $py->tone("中国人"), "\n";
echo "音调多音:", $py->tone_multi("中国人"), "\n";
