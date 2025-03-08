<?php

// Code by @AzR_Projects

header('Content-Type: application/json');


if (!isset($_GET['text']) || empty(trim($_GET['text']))) {
    echo json_encode(['error' => '⚠️ Missing text input. Provide a valid query.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$text = trim($_GET['text']);


$url = 'https://api.deepinfra.com/v1/openai/chat/completions';


$data = [
    'model' => 'deepseek-ai/DeepSeek-R1',
    'messages' => [
        ['role' => 'system', 'content' => 'Be a helpful assistant'],
        ['role' => 'user', 'content' => $text]
    ],
    'stream' => false
];


$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'User-Agent: Mozilla/5.0'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));


$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);


if ($response === false) {
    echo json_encode(['error' => '🚨 API is unreachable. Try again later.'], JSON_UNESCAPED_UNICODE);
    exit;
}


$json = json_decode($response, true);


if ($httpCode !== 200) {
    echo json_encode(['error' => "❌ HTTP Error: $httpCode - API issue detected."], JSON_UNESCAPED_UNICODE);
    exit;
}


if (!isset($json['choices'][0]['message']['content'])) {
    echo json_encode(['error' => '⚠️ Unexpected response format from API.'], JSON_UNESCAPED_UNICODE);
    exit;
}


$result = [
    'credit' => '@AzR_projects',
    'response' => $json['choices'][0]['message']['content']
];

echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>
