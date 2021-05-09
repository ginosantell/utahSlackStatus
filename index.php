#!/usr/local/bin
<?php

http_response_code(200);

define('TOKEN', getenv('TOKEN'));
define('CHANNEL', getenv('CHANNEL'));

#http_response_code(301);

// Grab event data from the request
#$input = $_POST['body'];
$input = file_get_contents('php://input');
$json = json_decode($input, false);
$type = $json->type;

print "Here 10.118\n";
#print_r($json);
#print "var_dump:\n";
#print var_dump($json);
print "type: " . $type;
print "\nHere 50\n";
print "json->event->type: " . $json->event->type . "\n";

switch ($type) {

  case 'url_verification':

    $challenge = isset($json->challenge) ? $json->challenge : null;
    $response = array(
      'challenge' => $challenge,
    );
    header('Content-type: application/json');
    print $response;

  break;

  case 'event_callback':

    switch ($json->event->type) {

      case 'user_change':

        // Grab some data about the user;
        $userid = $json->event->user->id;
        $username = $json->event->user->real_name_normalized;
        $status_text = $json->event->user->profile->status_text;
        $status_emoji = $json->event->user->profile->status_emoji;

        // Build the message payload

        // If their status contains some text
        /*
        if (isset($status_text) && strlen($status_text) == 0) {
          $message = [
            "text" => $username . " cleared their status.",
          ];
          ];
           }
        */

        // send the message!
        print "writing message...\n";
        $message = [
         "pretext" => "pretext string.",
         "text" => "Hello world"
        ];

        print "writing attachments...\n";
        $message_json = json_encode($message);

        /*
        $attachments = [
          $message,
        ];
        */

        print "writing payload...\n";

        $payload = [
          #'token' => TOKEN,
          'token' => "lI6wukbUxKGwQavMYSdIIXtX",
          #'channel' => CHANNEL,
          'channel' => "C021A8J853L",
          'attachments' => $message_json
        ];

        print "before postMessage.\n";
        postMessage($payload);

      break;

    }

}

function postMessage($payload) {

    // Make a cURL call

    // add our payload passed through the function.
#    $attachment_json = json_encode($payload);
#    $args = http_build_query($attachment_json);
    $args = http_build_query($payload);

    print "before callurl...\n";

    // Build the full URL call to the API.
    $callurl = "https://slack.com/api/chat.postMessage" . "?" . $args;

    print "callurl: " . $callurl . "\n";

    // Let's build a cURL query.
          $ch = curl_init($callurl);
        curl_setopt($ch, CURLOPT_USERAGENT, "Slack Technical Exercise");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

    print "before array_key_exists\n";

    if (array_key_exists("filename", $payload)) {
      print "inside array_key_exists\n";
      $callurl = $url . $method;
      $headers = array("Content-Type: multipart/form-data"); // cURL headers for file uploading
      curl_setopt($ch, CURLOPT_HEADER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    }

    print "before curl_exec\n";

    $ch_response = json_decode(curl_exec($ch));
    if ($ch_response->ok == FALSE) {
      error_log($ch_response->error);
      print "There was an error: \n";
      print_r($ch_response);
    } else {
       print "after curl_exec\n";
    }

 }

?>
