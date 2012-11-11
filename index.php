<?php

header('content-type: text/html');

function getResultsData($data, $begintext, $endtext)
{
        $foundLocBegin = strpos($data, $begintext) + strlen($begintext);
        $foundLocEnd = strpos($data, $endtext, $foundLocBegin);
        $foundData = substr($data, $foundLocBegin, $foundLocEnd - $foundLocBegin);

        return $foundData;
}

if(!isset($_GET['term']))
{
        mt_srand();

        $max = 1249179;
        $rand = mt_rand(0, $max);

        $file = fopen('urbandom.lst', 'r');
        $line = '';

        $i = 0;

        while(!feof($file))
        {
                if($i++ == $rand)
                {
                        $line = fgets($file);
                        break;
                }
                else fgets($file);
        }
}
else
{
        $line = strip_tags($_GET['term']);
}

$query = urlencode(trim($line));

$data = file_get_contents('http://www.urbandictionary.com/define.php?term='.$query);

$term = rawurldecode($query);

$definition = getResultsData($data, '<div class="definition">', '</div>');
$example = getResultsData($data, '<div class="example">', '</div>');

$definition = str_replace($term, '<b>'.$term.'</b>', $definition);
$example = str_replace($term, '<b>'.$term.'</b>', $example);

echo 'Term: <b><a href="?term='.urlencode($term).'">'.$term.'</a></b><br>';
echo 'Definition: '.$definition.'<br>';
echo 'Example: '.$example.'';
