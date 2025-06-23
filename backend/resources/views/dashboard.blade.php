<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ダッシュボード</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 2rem;
        }
        .dashboard-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e1e1e1;
        }
        .welcome-text {
            color: #333;
        }
        .logout-btn {
            padding: 0.5rem 1rem;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .logout-btn:hover {
            background: #c82333;
        }
        .content {
            line-height: 1.6;
            color: #555;
        }
        .user-info {
            background: #e9ecef;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="header">
            <div class="welcome-text">
                <h1>ダッシュボード</h1>
                <p>ようこそ、{{ Auth::user()->getUsername() }}さん</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">ログアウト</button>
            </form>
        </div>
        
        <div class="user-info">
            <h3>ユーザー情報</h3>
            <p><strong>ユーザー名:</strong> {{ Auth::user()->getUsername() }}</p>
            <p><strong>ログイン時刻:</strong> {{ now()->format('Y年m月d日 H:i:s') }}</p>
        </div>
        
        <div class="content">
            <h3>システム情報</h3>
            <p>ログインが正常に完了しました。このダッシュボードは認証が必要なページです。</p>
            <p>JSON ベースの認証システムを使用しています。</p>
        </div>
    </div>
</body>
</html>