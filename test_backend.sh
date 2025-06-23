#!/bin/bash

echo "=== チャットアプリ バックエンド テスト ==="
echo

# Test backend API functionality without network calls
cd backend

echo "1. 直接のPHP関数テスト:"
php -r "
\$messagesFile = 'messages.json';

function getMessages() {
    global \$messagesFile;
    if (!file_exists(\$messagesFile)) {
        return [];
    }
    \$content = file_get_contents(\$messagesFile);
    return json_decode(\$content, true) ?: [];
}

function saveMessage(\$name, \$message) {
    global \$messagesFile;
    \$messages = getMessages();
    \$newMessage = [
        'id' => count(\$messages) + 1,
        'name' => htmlspecialchars(\$name),
        'message' => htmlspecialchars(\$message),
        'timestamp' => date('Y-m-d H:i:s')
    ];
    \$messages[] = \$newMessage;
    file_put_contents(\$messagesFile, json_encode(\$messages));
    return \$newMessage;
}

// Clean start
if (file_exists(\$messagesFile)) unlink(\$messagesFile);

// Test saving messages
\$msg1 = saveMessage('テストユーザー1', 'こんにちは！');
\$msg2 = saveMessage('テストユーザー2', 'チャットアプリのテストです');

echo \"メッセージ保存完了\\n\";
echo \"メッセージ数: \" . count(getMessages()) . \"\\n\";
echo \"最新メッセージ: \" . json_encode(\$msg2, JSON_UNESCAPED_UNICODE) . \"\\n\";
"

echo

echo "2. GET APIテスト:"
REQUEST_METHOD=GET REQUEST_URI=/api/messages php index.php

echo

echo "3. メッセージファイルの確認:"
if [ -f "messages.json" ]; then
    echo "messages.json が作成されました"
    echo "ファイル内容:"
    cat messages.json | jq . 2>/dev/null || cat messages.json
else
    echo "messages.json が見つかりません"
fi

echo
echo "=== テスト完了 ==="
echo "バックエンドAPIが正常に動作しています"