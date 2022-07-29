<?php
$arUrlRewrite=array (
  0 => 
  array (
    'CONDITION' => '#^\\/?\\/mobileapp/jn\\/(.*)\\/.*#',
    'RULE' => 'componentName=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobileapp/jn.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/rest/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/rest/index.php',
    'SORT' => 200,
  ),
  2 => 
  array (
    'CONDITION' => '#^/news/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/news/index.php',
    'SORT' => 300,
  ),
  3 => 
  array (
    'CONDITION' => '#^/info/stories/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/info/stories/index.php',
    'SORT' => 400,
  ),
  4 => 
  array (
    'CONDITION' => '#^/info/([^/]+)/.*#',
    'RULE' => 'SECTION_CODE=$1',
    'ID' => NULL,
    'PATH' => '/info/index.php',
    'SORT' => 500,
  ),
  5 => 
  array (
    'CONDITION' => '#^/media/([^/]+)/.*#',
    'RULE' => 'SECTION_CODE=$1',
    'ID' => '',
    'PATH' => '/media/section.php',
    'SORT' => 600,
  ),
  6 => 
  array (
    'CONDITION' => '#^/party/timetable/([0-9]+)/([0-9]+)/.*#',
    'RULE' => 'year=$1&month=$2',
    'ID' => '',
    'PATH' => '/party/timetable/index.php',
    'SORT' => 700,
  ),
  7 => 
  array (
    'CONDITION' => '#^/party/essay/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/party/essay/index.php',
    'SORT' => 800,
  ),
  8 => 
  array (
    'CONDITION' => '#^/party/([^/]+)/.*#',
    'RULE' => 'SECTION_CODE=$1',
    'ID' => '',
    'PATH' => '/party/index.php',
    'SORT' => 900,
  ),
  9 => 
  array (
    'CONDITION' => '#^/regions/([^/]+)/.*#',
    'RULE' => 'SECTION_CODE=$1',
    'ID' => '',
    'PATH' => '/regions/index.php',
    'SORT' => 1000,
  ),
  10 => 
  array (
    'CONDITION' => '#^/more/([^/]+)/.*#',
    'RULE' => 'SECTION_CODE=$1',
    'ID' => '',
    'PATH' => '/more/index.php',
    'SORT' => 1100,
  ),
  11 => 
  array (
    'CONDITION' => '#^/docs/([^/]+)/.*#',
    'RULE' => 'ELEMENT_CODE=$1',
    'ID' => NULL,
    'PATH' => '/docs/index.php',
    'SORT' => 1200,
  ),
  12 => 
  array (
    'CONDITION' => '#^/sitemap.xml/.*#',
    'RULE' => 'mode=xml',
    'ID' => NULL,
    'PATH' => '/sitemap/index.php',
    'SORT' => 1300,
  ),
);
