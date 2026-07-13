# 📚 图书管理系统（Library Management System）

## 项目简介

本项目是一个基于 **Vue 3 + Laravel 12** 开发的前后端分离图书管理系统。

系统包含用户端和管理员端，支持图书管理、借阅管理、公告管理、用户管理等功能，适合作为学习项目、毕业设计及中小型图书管理系统参考。

---

## 技术栈

### 前端

- Vue 3
- Vue Router
- Axios
- Element Plus
- Vite

### 后端

- Laravel 12
- Laravel Sanctum
- MySQL

### 开发工具

- Git
- Composer
- npm

---

## 项目结构

```
book-management-system
├── frontend      # Vue3 前端
└── backend       # Laravel 后端
```

---

## 功能介绍

### 用户端

- 用户注册
- 用户登录
- 邮箱验证码
- 图书浏览
- 图书搜索
- 图书借阅
- 图书归还
- 查看公告
- 修改个人信息

### 管理员端

- 用户管理
- 图书管理
- 分类管理
- 借阅管理
- 公告管理
- 封禁用户
- 邮件通知

---

## 运行方式

### 后端

```bash
cd backend

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan serve
```

### 前端

```bash
cd frontend

npm install

npm run dev
```

---

## 后续开发计划

- [x] 用户系统
- [x] 图书管理
- [x] 借阅管理
- [x] 公告系统
- [ ] Redis 缓存
- [ ] Docker 部署
- [ ] 操作日志
- [ ] RBAC 权限管理

---

## 当前版本

**v0.8.0 Beta**

---

## 作者


# 📚 図書管理システム（Library Management System）

## 概要

本システムは **Vue 3** と **Laravel 12** を用いて開発したフロントエンド・バックエンド分離型の図書管理システムです。

利用者向け機能と管理者向け機能を備えており、図書館や小規模な書籍管理システムを想定して開発しました。

---

## 使用技術

### フロントエンド

- Vue 3
- Vue Router
- Axios
- Element Plus
- Vite

### バックエンド

- Laravel 12
- Laravel Sanctum
- MySQL

### 開発環境

- Git
- Composer
- npm

---

## ディレクトリ構成

```
book-management-system
├── frontend
└── backend
```

---

## 主な機能

### 利用者

- ユーザー登録
- ログイン
- メール認証
- 図書検索
- 図書一覧
- 図書貸出
- 図書返却
- お知らせ閲覧
- プロフィール管理

### 管理者

- ユーザー管理
- 図書管理
- カテゴリ管理
- 貸出管理
- お知らせ管理
- ユーザー停止
- メール通知

---

## 実行方法

### Backend

```bash
cd backend

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan serve
```

### Frontend

```bash
cd frontend

npm install

npm run dev
```

---

## 今後の開発予定

- [x] ユーザー管理
- [x] 図書管理
- [x] 貸出管理
- [x] お知らせ管理
- [ ] Redis キャッシュ
- [ ] Docker 対応
- [ ] 操作ログ
- [ ] RBAC 権限管理

---

## バージョン

**v0.8.0 Beta**

---

## GitHub

https://github.com/sjssjsjssj95-svg
GitHub：

https://github.com/sjssjsjssj95-svg
