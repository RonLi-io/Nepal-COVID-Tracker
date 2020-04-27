<?php
header('Content-Type: application/json');
define("DATA_SOURCE_URL", "https://en.wikipedia.org/w/api.php?action=parse&page=2020_coronavirus_pandemic_in_Nepal&prop=text&format=json&section=4");

$config['useragent'] = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';
$options  = array('http' => array('user_agent' => $config['useragent']));
$context  = stream_context_create($options);
$outputFile = 'assets/response.json';

abstract class HeadIndex
{
    const District = 0;
    const Cases = 1;
    const Recovered = 2;
    const Deaths = 3 + 1;
    // const Active = 0; This we can calculate based on Total-(Death+Recovered)
}

try {
    $jsonResponse = json_decode(file_get_contents(DATA_SOURCE_URL, false, $GLOBALS['context']));

    $htmlData = $jsonResponse->parse->text->{"*"};
    $doc = new DOMDocument();
    @$doc->loadHTML($htmlData);
    $doc->preserveWhiteSpace = false;
    $tables = $doc->getElementsByTagName('table');
    $table = $tables->item(0);
    $rows = $table->getElementsByTagName('tr');
    $tableArray = array();
    $headArray = array();
    $finalarray = array();
    foreach ($rows as $key => $element) {
        array_push($tableArray, array_values(array_filter(array_map("trim", explode("<br />", nl2br($rows[$key]->nodeValue))), "strlen")));
    }

    $headArray = $tableArray[0]; //Table column names;

    if(count($headArray)!=5) //If table column names are changed;
        echo "Number of column is changed\n";
        
    $currentState = array();
    $selfCounter = -1;
    $totalCases = 0;
    $totalRecovered = 0;
    $totalDeaths = 0;
    $morrisDists = array();
    for ($i = 2; $i < count($tableArray) - 1; $i++) {
        $morrisDists[++$selfCounter]['data'] = "XX"; //We require this for JS library to work;
        $morrisDists[$selfCounter]['value'] = $tableArray[$i][HeadIndex::District];

        $currentState[$selfCounter][$headArray[0]] = $tableArray[$i][HeadIndex::District];
        $currentState[$selfCounter][$headArray[1]] = $tableArray[$i][HeadIndex::Cases];
        $totalCases += $tableArray[$i][HeadIndex::Cases];

        $currentState[$selfCounter][$headArray[2]] = $tableArray[$i][HeadIndex::Recovered];
        $totalRecovered += $tableArray[$i][HeadIndex::Recovered];

        $currentState[$selfCounter][$headArray[3]] = $tableArray[$i][HeadIndex::Deaths];
        $totalDeaths += $tableArray[$i][HeadIndex::Deaths];
    }
    $finalarray['totalCases'] = $totalCases;
    $finalarray['activeCases'] = $totalCases - ($totalRecovered + $totalDeaths);
    $finalarray['curedCases'] = $totalRecovered;
    $finalarray['deathCases'] = $totalDeaths;
    $finalarray['districts'] = $currentState;
    $finalarray['morris'] = $morrisDists;

    $fp = fopen($outputFile, 'w');
    fwrite($fp, json_encode($finalarray, JSON_PRETTY_PRINT));
    fclose($fp);
    echo "Saved JSON!";
} catch (Exception $e) {
    echo $e->getMessage();
}