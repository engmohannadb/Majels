<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userMessage = $_POST['message'];
    $apiKey = 'sk-0dgggggggggggggggggc976f7'; // Replace with your actual API key
    $apiUrl = 'https://api.deepseek.com/chat/completions';

    $postData = [
        "model" => "deepseek-chat",
        "messages" => [
            ["role" => "system", "content" => "You are مجلس, a Saudi Real Estate Assistant specializing in property regulations and market information within Saudi Arabia. Follow the laws and policies of the Real Estate General Authority (REGA – https://rega.gov.sa/). 
Explain real estate concepts clearly in Arabic, maintain professionalism, and avoid giving legal, financial, or religious opinions. 
If a question is outside Saudi real estate scope, politely refuse and remind the user that you only handle REGA-related real estate topics.
"],
            ["role" => "user", "content" => $userMessage]
        ],
        "stream" => false
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

    $response = curl_exec($ch);
    curl_close($ch);

    $responseMessage = "No reply received.";
    if ($response) {
        $responseData = json_decode($response, true);
        $responseMessage = $responseData['choices'][0]['message']['content'] ?? $responseMessage;
    }

    echo $responseMessage;
    exit;
}
include("Configuration/Header.php");


?>

<style>
body {
    background-image: url('background.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center;
    font-family: "Cairo", Arial, sans-serif;
    direction: rtl;
    color: #333;
}

.video-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 118%;
    object-fit: cover;
    z-index: -1;
}

.chat-container {
    background-color: rgba(255,255,255,0.9);
    border-radius: 15px;
    margin: 80px auto;
    max-width: 900px;
    padding: 20px;
    box-shadow: 0 0 10px #ccc;
    display: flex;
    flex-direction: column;
    height: 75vh;
}

#chatBody {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #fafafa;
}

#chatInputContainer {
    display: flex;
    margin-top: 10px;
    border-top: 1px solid #ddd;
}

#chatInput {
    flex: 1;
    padding: 10px;
    border: none;
    outline: none;
    font-size: 16px;
}

#sendButton {
    background-color: #215365;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 0 8px 8px 0;
}

#sendButton:hover {
    background-color: #0e7dfc;
}

.response {
    color: blue;
    white-space: pre-line;
}

pre {
    border: 1px solid gray;
    display: inline-block;
    width: 80%;
    margin: 10px;
    padding: 10px;
    direction: ltr;
    color: gray;
    border-radius: 10px;
    font-family: monospace;
}

.typing {
    font-family: monospace;
    border-right: 3px solid;
    animation: blink 0.8s step-end infinite alternate;
}
@keyframes blink {
    50% {
        border-color: transparent;
    }
}
</style>

<video class="video-background" autoplay loop muted>
    <source src="back.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>

<section class="au-breadcrumb m-t-75">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <h5 >مساعد الذكاء الاصطناعي العقاري</h3>
        </div>
    </div>
</section>

<div class="chat-container">يمكنك  طرح الاستفسارات العقارية فقط و مايتعلق بذلك من سياسات و أنظمة وغيرها
    <div id="chatBody"></div>
    <div id="chatInputContainer">
        <input type="text" id="chatInput" placeholder="الرجاء كتابة سؤالك هنا..." />
        <button id="sendButton">إرسال</button>
    </div>
</div>

<script>
const chatInput = document.getElementById('chatInput');
const chatBody = document.getElementById('chatBody');
const sendButton = document.getElementById('sendButton');

sendButton.addEventListener('click', sendMessage);
chatInput.addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
        event.preventDefault();
        sendMessage();
    }
});

function sendMessage() {
    const message = chatInput.value.trim();
    if (message === '') return;

    chatBody.innerHTML += `<div><strong>السؤال:</strong> ${message}</div>`;
    chatInput.value = '';
    chatBody.innerHTML += '<div>جاري المعالجة...</div>';
    chatBody.scrollTop = chatBody.scrollHeight;

    fetch('', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({ message })
    })
    .then(response => response.text())
    .then(reply => {
        reply = reply.replace(/###/g, '⚪️');
        reply = reply.replace(/(؛:؟!)/g, '$1<br>');
        reply = reply.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        reply = reply.replace(/\\/g, '');
        const responseDiv = document.createElement('div');
        responseDiv.className = 'response';
        responseDiv.innerHTML = '<strong>مساعد الذكاء الاصطناعي:</strong> ';
        chatBody.appendChild(responseDiv);
        typeWriter(responseDiv, reply);
    })
    .catch(() => {
        chatBody.innerHTML += `<div><strong>مساعد الذكاء الاصطناعي:</strong> حدث خطأ في الاتصال.</div>`;
    });
}

function typeWriter(element, text, speed = 40) {
    let i = 0;
    function typingEffect() {
        if (i < text.length) {
            element.innerHTML += text.charAt(i);
            i++;
            element.parentElement.scrollTop = element.parentElement.scrollHeight;
            setTimeout(typingEffect, speed);
        }
    }
    typingEffect();
}
</script>

<?php include("Configuration/Footer.php"); ?>
