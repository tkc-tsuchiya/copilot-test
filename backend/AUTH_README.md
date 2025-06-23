# Laravel JSON認証システム

このプロジェクトは、データベースを使用せずJSONファイルベースの認証システムを実装したLaravelアプリケーションです。

## 機能

- **JSONベース認証**: データベースなしでユーザー認証
- **ログイン画面**: 日本語UIでの認証画面
- **パスワード暗号化**: Laravel Hashファサードによる安全なパスワード保存
- **パスワード暗号化API**: 平文パスワードを暗号化するAPIエンドポイント
- **ダッシュボード**: 認証後のユーザー画面

## セットアップ

1. 依存関係のインストール:
```bash
composer install
```

2. 環境設定:
```bash
cp .env.example .env
php artisan key:generate
```

3. データベース準備（セッション用）:
```bash
touch database/database.sqlite
php artisan migrate
```

4. サーバー起動:
```bash
php artisan serve
```

## テストアカウント

- **管理者**: `admin` / `admin123`
- **一般ユーザー**: `user1` / `password`

## API

### パスワード暗号化
```bash
POST /api/encrypt-password
Content-Type: application/json

{
  "password": "your_password"
}
```

レスポンス:
```json
{
  "plaintext": "your_password",
  "encrypted": "$2y$12$..."
}
```

## ファイル構成

- `storage/app/users.json` - ユーザー認証情報
- `app/Providers/JsonUserProvider.php` - カスタム認証プロバイダー
- `app/Models/JsonUser.php` - JSONユーザーモデル
- `app/Http/Controllers/AuthController.php` - 認証コントローラー
- `resources/views/auth/login.blade.php` - ログイン画面
- `resources/views/dashboard.blade.php` - ダッシュボード画面

## テスト

```bash
vendor/bin/phpunit
```

全7つの認証テストが含まれています。