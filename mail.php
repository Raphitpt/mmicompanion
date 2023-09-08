<?php
namespace PhpImap;
require 'src/PhpImap/__autoload.php';
?>
<!DOCTYPE html>
<html>
<head>
<style>
    div#wrap {width:860px; margin:20px auto;}
</style>
</head>
<body>
<div id="wrap">
<?php

// Configuration for the Mailbox class
$hoststring = '{mail.univ-poitiers.fr:993/imap/ssl/novalidate-cert}INBOX';
$username   = 'rtiphone';
$password   = '69Y7t5ps';
$attachdir  = './attachments/';

// Construct the $mailbox handle
$mailbox = new Mailbox($hoststring, $username, $password, $attachdir);

// Get INBOX emails after date 2017-01-01
$mailsIds = $mailbox->searchMailbox('SINCE "20230801"');
if(!$mailsIds) exit('Mailbox is empty');

// Show the total number of emails loaded
echo 'n= '.count($mailsIds).'<br>';

// Put the latest email on top of listing
rsort($mailsIds);

// Get the last 15 emails only
array_splice($mailsIds, 15);

// Loop through emails one by one
foreach($mailsIds as $num) {
    
    // Show header with subject and data on this email
    $head = $mailbox->getMailHeader($num);
    echo '<div style="text-align:center"><b>';
    echo $head->subject.'</b>&nbsp;&nbsp;(';
    if     (isset($head->fromName))    echo 'by '.$head->fromName.' on ';
    elseif (isset($head->fromAddress)) echo 'by '.$head->fromAddress.' on ';
    echo $head->date.')';
    echo '</div>';
    
    // Show the main body message text
    // Do not mark email as seen
    $markAsSeen = false;
    $mail = $mailbox->getMail($num, $markAsSeen);
    if ($mail->textHtml)
        echo $mail->textHtml;
    else
        echo $mail->textPlain;
    echo '<br><br>';
    
    // Load eventual attachment into attachments directory
    $mail->getAttachments();

}

$mailbox->disconnect();
?>
</div>
</body>
</html>