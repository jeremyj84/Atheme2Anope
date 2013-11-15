<?php
if ($argc <= 1) {
	die("Usage:\n\t{$argv[0]} Nickname\n\t\t-OR-\n\t{$argv[0]} user@email.address\nInfo:\n\tReturns the generated password for the specified user.\n");
}
if (!file_exists("anope_list.txt")) {
	die("The file 'anope_list.txt' does NOT exist!\n");
}
$term = trim($argv[1]);
$users = explode("\n",file_get_contents("anope_list.txt"));
echo "Loaded ".count($users)." users..\n";
$debug = false;
$found = array();

foreach ($users as $id => $u) {
	$dat = explode(chr(32),$u);
	if (preg_match("/@/",$term)) {
		// Searching for Email.
		if ($debug) { echo "Checking Email for user ".($id+1)."/".count($users)."..."; }
		if (strtolower($term) === strtolower($dat[0])) {
			if ($debug) { echo "\tMatch!\n"; }
			$found[] = $dat;
		} else {
			if ($debug) { echo "\tNo match!\n"; }
		}
	} else {
		if ($debug) { echo "Checking Nickname for user ".($id+1)."/".count($users)."..."; }
		if (strtolower($term) === strtolower($dat[1])) {
			if ($debug) { echo "\tMatch!\n"; }
			$found[] = $dat;
		} else {
			if ($debug) { echo "\tNo match!\n"; }
		}
	}
}
if (count($found) === 0) {
	echo "No users were found for the search term '{$term}'\n";
} else if (count($found) > 15) {
	echo "We found ".count($found)." users. Displaying the first 15.\n";
	for ($i = 0; $i < 15; $i++) {
		echo "\tThe new password for {$found[$i][1]} ({$found[$i][0]}) is now '{$found[$i][2]}'.\n";
	}
} else {
	echo "We found ".count($found)." displaying all records:\n";
	foreach ($found as $u) {
		echo "\tThe new password for {$u[1]} ({$u[0]}) is now '{$u[2]}'.\n";
	}
}
?>