# API Documentation
# Domain local: http://127.0.0.1:8000
# Mọi request sau đó đính kèm header: Authorization: Bearer {token}

---

## 🔐 Auth

| Method | URL | Mô tả | Auth |
|--------|-----|-------|------|
| POST | `/api/auth/google/token` | Đăng nhập bằng Google ID Token | ❌ |
| POST | `/api/auth/logout` | Đăng xuất, xóa token hiện tại | ✅ |

**Body đăng nhập:**
```json
{ "id_token": "eyJhbGci..." }
```

---

## 👤 User

| Method | URL | Mô tả | Auth |
|--------|-----|-------|------|
| GET | `/api/user/profile` | Lấy profile của user đang đăng nhập | ✅ |
| PUT | `/api/user/profile` | Cập nhật profile | ✅ |

**Body update profile:**
```json
{
  "full_name": "Nguyễn Văn A",
  "username": "nguyenvana",
  "bio": "Giới thiệu bản thân",
  "avatar_url": "https://..."
}
```

---

## ⚙️ User Settings

| Method | URL | Mô tả | Auth |
|--------|-----|-------|------|
| GET | `/api/user/settings` | Lấy cài đặt của user | ✅ |
| PUT | `/api/user/settings` | Cập nhật cài đặt | ✅ |

**Body update settings:**
```json
{
  "language": "vi",
  "notify_like": true,
  "notify_comment": true,
  "notify_follow": true,
  "notify_mention": true
}
```

---

## 💰 Wallet

| Method | URL | Mô tả | Auth |
|--------|-----|-------|------|
| GET | `/api/wallet` | Lấy số dư ví | ✅ |
| GET | `/api/wallet/transactions` | Lịch sử giao dịch (có phân trang) | ✅ |

**Query params:**
- `per_page` — số bản ghi mỗi trang (mặc định 20)

---

## 📝 Posts

| Method | URL | Mô tả | Auth |
|--------|-----|-------|------|
| GET | `/api/posts` | Feed bài viết (mới nhất, có phân trang) | ✅ |
| POST | `/api/posts` | Tạo bài viết mới | ✅ |
| GET | `/api/posts/{id}` | Chi tiết bài viết | ✅ |
| PUT | `/api/posts/{id}` | Sửa bài viết (chỉ chủ bài) | ✅ |
| DELETE | `/api/posts/{id}` | Xóa bài viết (chỉ chủ bài) | ✅ |
| POST | `/api/posts/{id}/like` | Like / Unlike bài viết (toggle) | ✅ |
| POST | `/api/posts/{id}/save` | Save / Unsave bài viết (toggle) | ✅ |
| GET | `/api/users/{userId}/posts` | Danh sách bài viết của 1 user | ✅ |

**Body tạo/sửa bài viết:**
```json
{
  "caption": "Nội dung bài viết",
  "images": [
    "https://example.com/image1.jpg",
    "https://example.com/image2.jpg"
  ]
}
```

**Query params:**
- `per_page` — số bài mỗi trang (mặc định 20)

---

## 💬 Comments (TODO)

| Method | URL | Mô tả | Auth |
|--------|-----|-------|------|
| GET | `/api/posts/{id}/comments` | Danh sách comment | ✅ |
| POST | `/api/posts/{id}/comments` | Thêm comment | ✅ |
| DELETE | `/api/comments/{id}` | Xóa comment | ✅ |
| POST | `/api/comments/{id}/like` | Like / Unlike comment (toggle) | ✅ |

---

## 👥 Follows (TODO)

| Method | URL | Mô tả | Auth |
|--------|-----|-------|------|
| POST | `/api/users/{id}/follow` | Follow / Unfollow (toggle) | ✅ |
| GET | `/api/users/{id}/followers` | Danh sách followers | ✅ |
| GET | `/api/users/{id}/following` | Danh sách đang follow | ✅ |

---

## 🔔 Notifications (TODO)

| Method | URL | Mô tả | Auth |
|--------|-----|-------|------|
| GET | `/api/notifications` | Danh sách thông báo | ✅ |
| PUT | `/api/notifications/{id}/read` | Đánh dấu đã đọc | ✅ |
| PUT | `/api/notifications/read-all` | Đọc tất cả | ✅ |

---

## 📅 Check-in (TODO)

| Method | URL | Mô tả | Auth |
|--------|-----|-------|------|
| POST | `/api/checkin` | Điểm danh hằng ngày | ✅ |
| GET | `/api/checkin/status` | Trạng thái điểm danh hôm nay | ✅ |

---

## 🎯 Daily Tasks (TODO)

| Method | URL | Mô tả | Auth |
|--------|-----|-------|------|
| GET | `/api/daily-tasks` | Danh sách nhiệm vụ hôm nay | ✅ |
| POST | `/api/daily-tasks/{id}/claim` | Nhận thưởng nhiệm vụ | ✅ |
