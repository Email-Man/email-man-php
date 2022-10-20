# EmailMan ![wakatime](https://wakatime.com/badge/user/9657174f-2430-4dfd-aaef-2b316eb71a36/project/73368ee8-a27a-42fc-b4f2-4a0095198c94.svg)
A fully featured PHP Email Library for sending emails 
especially via smtp

## Features
- Add multiple recipients
- Add multiple CC
- Add multiple BCC
- Add Reply To
- Add HTML Emails
- Add Raw Text Email
- Dump Errors

### Sample Usage
```PHP
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

```

