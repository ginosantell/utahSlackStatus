#!/usr/local/bin
<?php

http_response_code(200);
print "Test\n";

define('TOKEN', getenv('TOKEN'));
define('CHANNEL', getenv('CHANNEL'));

# http_response_code(301);

 // Grab event data from the request
# $input = $_POST['body'];
$input = file_get_contents('php://input');
$json = json_decode($input, FALSE);
$type = $json->type;

print "Here 10\n";
print "json->type:" . $json->type;

print "\nHere 20\n";
print "json->token:" . $json->token;

print "\nHere 30\n";
print "json->challenge:" . $json->challenge;

print "\nHere 40\n";
#print "json->event:" . $json->event;

print "\n";
print "json->event->type:" . $json["event"]["type"];
print "\nHere 50\n";

print "$json->event[type]:" . $json->event[type];
print "\nHere 60.";

switch ($type) {

  case "url_verification":

    $challenge = isset($json->challenge) ? $json->challenge : null;
    $response = array(
      'challenge' => $challenge,
    );
    header('Content-type: application/json');
    print $response;
    break;

  case "event_callback":

    switch ($json->event->type) {

      case "user_change":

       $message = [
         "text" => "Hello world"
       ];

       $attachments = [
          $message
        ];

        $payload = [
          #'token' => TOKEN,
          'token' => "lI6wukbUxKGwQavMYSdIIXtX",
          #'channel' => CHANNEL,
          'channel' => "C021A8J853L",
          'attachments' => $attachments
        ];

        $args = http_build_query($payload);
        $callurl = "https://slack.com/api/chat.postMessage" . "?" . $args;


        $ch = curl_init();
        curl_exec($ch);
        curl_close();

      break;

    }

}

?>
