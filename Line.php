<?php
$datas = file_get_contents('php://input');
$deCode = json_decode($datas, true);
$replyToken = $deCode['events'][0]['replyToken'];
$userId = $deCode['events'][0]['source']['userId'];
$text = $deCode['events'][0]['message']['text'];
$Headers['url'] = "https://api.line.me/v2/bot/message/reply";
$Headers['Authorization'] = "uj2H+VicCE6ny9NztnV8bVJZUCYihnikKvphWUaNc6yyu/wwYoGnm8QBaI/Ssq8+EE/7QG1TLRdhZo28I5ie9iIuYsbxiJ0QOEjipLZiBPZGRgaRtNwq7iGWMUJ+Ytrq8JZ+6l1JA9LQUSD5dvK3cgdB04t89/1O/w1cDnyilFU=";
$messages = [];
$messages['replyToken'] = $replyToken;
if ($text == "halp"){
    $messages['messages'][0] = getFormatTextMessage("https://liff.line.me/1655723118-nvz8lEvv","true");
}else{
    $messages['messages'][0] = getFormatTextMessage($text,"0");
}
$Body = json_encode($messages);
$sendre = responsemanage($Headers, $Body);
function getFormatTextMessage($text,$quickReply)
{
    $datas = [];
    $datas['type'] = 'text';
    $datas['text'] = $text;
    if($quickReply == "true"){
        $datas['quickReply'] = QuickReply();
    }
    return $datas;
}
function QuickReply()
{
    $datas = [];
    $datas['items'][0]['type'] ="action";
    $datas['items'][0]['imageUrl'] ="https://cdn1.iconfinder.com/data/icons/mix-color-3/502/Untitled-1-512.png";
    $datas['items'][0]['action'] =[
        "type"=>"message",
        "label"=>"Message",
        "text"=>"ทดสอบQuickReply"
    ];
    return $datas;
}
function responsemanage($Headers, $Body)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $Headers['url'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $Body,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "cache-control: no-cache",
            'Authorization: Bearer '.$Headers['Authorization']
        ),
    ));

    $response = curl_exec($curl);
    file_put_contents('log.txt', $response . PHP_EOL, FILE_APPEND);
    if (curl_error($curl)) {
        file_put_contents('log.txt', curl_error($curl) . PHP_EOL, FILE_APPEND);
    } else {
        // $result_ = json_decode($response, true);
        // file_put_contents('log.txt', $result_ . PHP_EOL, FILE_APPEND);
    }
    curl_close($curl);

}
