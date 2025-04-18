<?php
// diet_plan.php

// 1. Setup
$apiKey = ""; // Replace with your actual API key

// 2. Collect POST data
$goal = $_POST["goal"] ?? '';
$frequency = $_POST["frequency"] ?? '';
$preference = $_POST["preference"] ?? '';

// 3. Compose the message
$messages = [
    [
        "role" => "system",
        "content" => "You are a helpful fitness and nutrition assistant. Based on user preferences, create a detailed 1-day diet plan."
    ],
    [
        "role" => "user",
        "content" => "Generate a diet plan for someone who wants to $goal, trains $frequency times a week, and prefers a $preference diet."
    ]
];

// 4. Prepare CURL request
$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "model" => "gpt-3.5-turbo",
    "messages" => $messages
]));

// 5. Execute and respond
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode(["error" => curl_error($ch)]);
} else {
    echo $response;
}
curl_close($ch);
