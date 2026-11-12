<?php
// The public base URL is explicit so emails never depend on a request Host header.
$APP_URL = rtrim(getenv('APP_URL') ?: 'http://localhost:8080', '/');
