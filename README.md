# copilot-test

## チャットアプリ

WEBブラウザでチャットができるアプリです。LaravelとReactを使用しています。

### 機能

- アカウント登録なしで利用可能
- 名前とメッセージを投稿
- リアルタイムでメッセージが更新される（2秒間隔でポーリング）

### 構成

- **フロントエンド**: React + TypeScript + Vite
- **バックエンド**: Laravel風のPHP API
- **データベース**: ファイルベース（JSON）

### 起動方法

#### 1. バックエンドAPI サーバーの起動

```bash
cd backend
php -S localhost:8000 index.php
```

#### 2. フロントエンド開発サーバーの起動

```bash
cd frontend
npm install
npm run dev
```

フロントエンドは http://localhost:5173 でアクセスできます。

### デモ

スタンドアロンでの動作確認用：
- `demo.html` - ローカルストレージを使用したフロントエンドのみのデモ

### API エンドポイント

- `GET /api/messages` - メッセージ一覧取得
- `POST /api/messages` - メッセージ投稿

#### メッセージ投稿の例

```bash
curl -X POST http://localhost:8000/api/messages \
  -H "Content-Type: application/json" \
  -d '{"name":"テストユーザー","message":"こんにちは！"}'
```

### ファイル構成

```
├── backend/           # Laravel風のPHPバックエンド
│   ├── index.php     # API エンドポイント
│   └── messages.json # メッセージデータファイル
├── frontend/         # React フロントエンド
│   ├── src/
│   │   ├── App.tsx   # メインチャットコンポーネント
│   │   └── App.css   # スタイル
│   └── package.json
├── demo.html         # スタンドアロンデモ
└── README.md         # このファイル
```