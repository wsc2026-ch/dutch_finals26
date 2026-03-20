<?php
$start_date = new DateTime("2024-01-15");
$end_date = new DateTime("2025-04-19");

// 1. Calculate the amount of days (Tip: diff)
echo "Days between dates: " . date_diff($start_date, $end_date)->days;
echo "<br/>";
// 2. Calculate the amount of weeks, round down, no decimals
echo "Weeks between dates: " . floor(date_diff($start_date, $end_date)->days / 7);
?>
