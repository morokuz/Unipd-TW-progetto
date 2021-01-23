<?php
session_start();
require_once (__DIR__ . "/../../scripts/php/useful_functions.php");


$page = file_get_contents(__DIR__ . "/../html/calcolatore.html");
$links = array();
$header = file_get_contents(__DIR__ . "/../html/components/header.html");
$current = '<li class="current"><a href="calcolatore">Calcola Pizza</a></li>';
$header = str_replace('<li><a href="calcolatore">Calcola Pizza</a></li>', $current, $header);
$links = checkSession();

$replacements = [
  "<placeholder_head_default_tags />" => file_get_contents(__DIR__ . "/../html/components/head_default_tags.html"),
  "<placeholder_header />" => $header,
  "<placeholder_footer />" => file_get_contents(__DIR__ . "/../html/components/footer.html"),
  "<placeholder_breadcrumbs />" => file_get_contents(__DIR__ . "/../html/components/breadcrumbs.html")
];
$replacements = addReplacements($replacements, $links);

echo replace($page, $replacements);
?>
