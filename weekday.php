<?php

/**
 * Wochentagsberechnung nach https://de.wikipedia.org/wiki/Wochentagsberechnung
 */

/**
 * @param $day
 * @param $month
 * @param $year
 */
function printEingabe($day, $month, $year): void
{
    echo "Eingabe: {$day}.{$month}.{$year}\n";
}

/**
 * @param $year
 * @param $month
 * @param $day
 * @param $weekDay
 */
function printAusgabe($year, $month, $day, $weekDay): void
{
    echo strftime("Berechnung PHP: Wochentag='%A'\n", strtotime("$year-$month-$day"));
    echo "Berechnung Algorithmus: Wochentag='{$weekDay}'\n";
}

/**
 * @param int $m
 * @param $y
 * @param $c
 */
function printDebugOutput(int $m, $y, $c): void
{
    global $argc;
    global $argv;

    if ($argc > 4 && ($argv[4] == '-d' || $argv[4] == '--debug')) {
        echo "DEBUG: m={$m} y={$y} c={$c}\n";
    }
}

/**
 * @param int $w
 * @return mixed
 */
function getWeekDayName(int $w)
{
    $weekDayNames = [
        "sonntag",
        "Montag",
        "Dienstag",
        "Mittwoch",
        "Donnerstag",
        "Freitag",
        "Samstag",
    ];

    $weekDay = $weekDayNames[$w];
    return $weekDay;
}

/**
 * @param int $argc
 * @param array $argv
 * @return array
 */
function handleCommandLine(int $argc, array $argv): array
{
    $day = $argv[1];
    $month = $argv[2];
    $year = $argv[3]; /* muss vierstellig sein */

    if ($argc < 4 || $argc > 5) {
        echo "Wrong number of arguments.";
        exit(1);
    }
    return [$day, $month, $year];
}

/**
 * @param $month
 * @param $year
 * @param $day
 * @return array
 */
function calculateWeekDayNumber($month, $year, $day): array
{
    $julianMonth = (($month - 2 - 1) + 12) % 12 + 1; // this is because of the modulo

    if ($julianMonth >= 11) {
        $c = substr($year - 1, 0, 2);
        $y = substr($year - 1, 2, 2);
    } else {
        $c = substr($year, 0, 2);
        $y = substr($year, 2, 2);
    }

    $w = ($day + intval(2.6 * $julianMonth - 0.2) + $y + intval($y / 4) + intval($c / 4) - 2 * $c) % 7;
    return array($julianMonth, $c, $y, $w);
}

function main(int $argc, array $argv): void
{
    setlocale(LC_TIME, 'de_AT.utf-8');

    list($day, $month, $year) = handleCommandLine($argc, $argv);

    list($julianMonth, $c, $y, $w) = calculateWeekDayNumber($month, $year, $day);

    $weekDay = getWeekDayName($w);

    printEingabe($day, $month, $year);
    printAusgabe($year, $month, $day, $weekDay);
    printDebugOutput($julianMonth, $y, $c);
}

main($argc, $argv);
