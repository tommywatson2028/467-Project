<?php
function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function money($value) {
    return '$' . number_format((float)$value, 2);
}
