<?php
// config/app.php

define('APP_ENV',   $_ENV['APP_ENV']   ?? 'production');
define('APP_DEBUG', filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN));
