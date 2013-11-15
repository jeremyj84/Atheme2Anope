<?php
echo "Atheme2Anope Mail Tool v0.1\n";
if ($argc <= 2) {
	die("Usage:\n\t{$argv[0]} NetworkName NetworkEmail\n\nInfo:\n\tEMails all users their new password.\n");
}
$net_name = $argv[1];
$net_email = $argv[2];
echo "Please note. This PHP Script mails all users with their new password.\nThis can be spammy!\n";
echo "The script will wait 5 seconds incase you change your mind!\n";
sleep(5);
echo "Ready. Reading password list.\n";
if (!file_exists("anope_list.txt")) {
	die("The file 'anope_list.txt' does NOT exist!\n");
}
$users = explode("\n",file_get_contents("anope_list.txt"));
if (count($users) == 0) {
	die("No users found! Exiting!\n");
}
echo "Found ".count($users)." users.\n";

function email($data) {
	$header = "From: {$data['network']} <{$data['from']}>\r\nContent-Type:text/html; charset=iso-8859-1";
	$html = "<h2>{$data['network']} IRC Network</h2><hr></hr>";
	$html .= "Hi {$data['nick']},<p/>Recently our network has migrated services package from Atheme to Anope 1.9<br/>";
	$html .= "Due to Password Encryption Methods that differ between packages we've had to reset all passwords.<br/>";
	$html .= "Don't worry though, all other data remains unchanged.<br/>";
	$html .= "The new password for the Nickserv account '{$data['network']}' is now:<p/>";
	$html .= "<h1><strong>{$data['password']}</strong></h1>";
	$html .= "You can now log into NickServ via '/ns identify {$data['password']}' then reset your password<br/>";
	$html .= "via '/ns set password newpasswordhere', if you require support ask in our Support channel.";
	$html .= "</p>Thanks,<br/>{$data['network']} IRC Network";
	return mail("{$data['nick']} <{$data['to']}>","{$data['network']} - Password reset for {$data['nick']}",$html,$header);
}
$count = 0;
$err = array();
foreach ($users as $line => $u) {
	if ($u != "") {
		$u = explode(chr(32),$u);
		$data = array();
		$data['to'] = $u[0];
		$data['nick'] = $u[1];
		$data['password'] = $u[2];
		$data['from'] = $net_email;
		$data['network'] = $net_name;
		$res = email($data);
		if ($res) {
			$count++;
		} else {
			$err[] = "Error sending E-Mail to {$u[1]} ({$u[0]}): Mail Function couldn't send Email!";
		}
	} else {
		$err[] = "Line {$line} was blank";
	}
}
if (count($err) > 0) {
	echo "The script encountered ".count($err)." Errors!\n\nSee 'error.log'\n";
	file_put_contents("error.log",implode("\n",$err));
}
echo $count."/".count($users)." Emails have been sent. They may take a few mins to actually get there!\n";
echo "Users may have to check their spam!\n";
?>