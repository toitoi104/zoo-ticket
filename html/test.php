<?php declare(strict_types=1);

# [SUCCESS] [通常チケット]大人1人
exec('php index.php --adult=1', $output);
print_r($output);
