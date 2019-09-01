<?php
// https://www.utf8-chartable.de/unicode-utf8-table.pl?unicodeinhtml=hex
$debug = false;
require_once("bnet_oauth.php");
function make_unixtime($l) { return substr($l, 0, 10); }

$wow = new bnet_oauth();

$link = mysqli_connect('127.0.0.1', 'root', 'password', 'wow');
if (!$link) { die('cannot connect to database'); }
mysqli_query($link, "set character_set_results='utf8'"); /* Specific to getting utf8 results */

$query = "SELECT * FROM `id2char`";
$res = mysqli_query($link, $query);

while ($r = mysqli_fetch_assoc($res))
{
	sleep(1);
	$pk = $r['pk'];

	$server = $r['toon_server'];
	$toon = $r['toon_char'];
	$data = $wow->getCharacterFeed($server, $toon);
	if ($debug) { echo "$server/$toon\n"; }
	$feed = json_decode($data);
	if ($debug) { echo "data=" . strlen($data). "\n"; }
	if (($debug) && strlen($data) < 100) { echo "json:$data\n"; }
	if (!is_object($feed)) { continue; }

	// get the lastModified and put it into the DB
	if (property_exists($feed, 'lastModified'))
	{
		if ($debug) { echo "found lastModified\n"; }
		$lastmod = make_unixtime($feed->lastModified);
		$lastmodval = gmstrftime("%c", $lastmod) . " GMT";
		$subquery = "SELECT * FROM `last-online` WHERE `pk_char`='$pk' AND `ts_timestamp`='$lastmod' LIMIT 1";
		$subres = mysqli_query($link, $subquery);
		if (mysqli_num_rows($subres) == 0)
		{
			$subquery = "INSERT INTO `last-online` (`pk_char`, `ts_timestamp`, `ts_text`) VALUES ('$pk', '$lastmod', '$lastmodval')";
			mysqli_query($link, $subquery);
		}
	}

	if (property_exists($feed, 'feed'))
	{
		$feed_obj = $feed->feed;
		foreach ($feed_obj as $obj)
		{
			$feed_timestamp = make_unixtime($obj->timestamp);
			$feed_date = gmstrftime("%c", $feed_timestamp) . " GMT";
			$feed_type = $obj->type;
			$feed_message = "unknown";
			if ($feed_type == "LOOT")
			{
				$feed_message = "LOOT of Item ID#" . $obj->itemId;
			}
			if ($feed_type == "CRITERIA")
			{
				$feed_message = $obj->achievement->title . " (" . $obj->achievement->description . ")";
			}
			if ($feed_type == "BOSSKILL")
			{
				$feed_message = $obj->achievement->title . " = " . $obj->quantity;
			}
			if ($feed_type == "ACHIEVEMENT")
			{
				$feed_message = "Achievement: " . $obj->achievement->title . " (" . $obj->achievement->description . ")";
			}
			$feed_message_escaped = mysqli_real_escape_string($link, $feed_message);
			$subquery = "SELECT * FROM `char-feed` WHERE `pk_char`='$pk' AND `ts_timestamp`='$feed_timestamp' AND `ts_text`='$feed_date' AND `feed_data`=\"$feed_message_escaped\" LIMIT 1";
			$subres = mysqli_query($link, $subquery);
			if (mysqli_num_rows($subres) == 0)
			{
				$subquery = "INSERT INTO `char-feed` (`pk_char`, `ts_timestamp`, `ts_text`, `feed_data`) VALUES ('$pk', '$feed_timestamp', '$feed_date', \"$feed_message_escaped\")";
				mysqli_query($link, $subquery);
			}
		}
	}
}

mysqli_close($link);
