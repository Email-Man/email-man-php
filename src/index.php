<?php

use EmailMan\EmailMan;

include "EmailMan.php";

$email = new EmailMan();

// add recipients
$email->addto("test1@email.com");
$email->addto("test2@email.com", "Test 2");
$email->addto("test3@email.com");

// add cc
$email->addCC("testCC1@email.com");
$email->addCC("testCC2@email.com");

// add bcc
$email->addBCC("testBCC@email.com");

// set sender email
$email->setFrom("sender@email.com");

// set sender name
$email->setFromName("Sender Name");

// set subject
$email->setSubject("THIS IS THE SUBJECT OF THE EMAIL");

// set message
$email->setMessage("This is just a test message");

// set reply to
$email->setReplyTo("reply_to_address@email.com");

// prepare the headers
$email->prepareHeaders();


// send the email
$email->send();

// dump errors if there are any
$errors = $email->dumpDebugger();

echo "<pre>";
var_dump($errors);
echo "</pre>";
