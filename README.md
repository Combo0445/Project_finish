# ระบบประเมินความสามารถในการดำเนินกิจวัตรประจำวันของผู้สูงอายุ
## ADL Assessment System

![Laravel](https://img.shields.io/badge/Laravel-10-red?logo=laravel)
![MySQL](https://img.shields.io/badge/MySQL-8.0-blue?logo=mysql)
![PHP](https://img.shields.io/badge/PHP-8.1+-purple?logo=php)
![License](https://img.shields.io/badge/License-MIT-green)

## 📋 ภาพรวมโปรเจกต์

ระบบประเมินความสามารถในการดำเนินชีวิตประจำวันของผู้สูงอายุ (ADL Barthel Index) พร้อมการประเมิน Care Giver และ TAI (Thai Able Index) สำหรับสนับสนุนการดูแลผู้สูงอายุในสถานสงเคราะห์และโรงพยาบาล

**เทคโนโลยี:** Laravel 10, MySQL, jQuery, Bootstrap 4, Quill Editor

---

## ✨ ฟีเจอร์หลัก

### 👥 ระบบแบ่งบทบาท (Role-Based Access)
- **Admin** - จัดการระบบ, ผู้ใช้, ข่าวสาร, Sliders
- **Staff** - บันทึก ADL Assessment, Care Giver, TAI, Activity, Performance Report
- **Doctor** - จัดการคำสั่งดูแล (Care Instructions)

### 📊 ระบบประเมินครอบคลุม
- **Barthel ADL Index** - ประเมิน 10 ด้าน (Self-care, Mobility, Continence)
- **Care Giver Assessment** - บันทึก caregiver และสภาพสุขภาพผู้สูงอายุ (37+ ข้อมูลด้าน)
- **TAI (Thai Able Index)** - ประเมิน Mobility, Confusion, Feeding, Toileting
- **Performance Report** - รวบรวมและรายงานผลประเมิน

### 📰 ระบบข่าวสาร
- สร้าง/แก้ไข/ลบข่าว พร้อม Quill Editor
- อัพโหลดหลายรูป (ตารางแยก news_images)
- Avatar ตามบทบาท (Admin/Staff/Doctor)

### 📁 การจัดการไฟล์
- Upload Profile Picture, Caregiver Photos, Sliders
- Storage symlink สำหรับเก็บไฟล์สาธารณะ
- ลบไฟล์อัตโนมัติเมื่อลบบันทึก

### 📑 ระบบรายงาน & Export
- Export Excel (ADL, Care Giver, TAI)
- PDF Report สำหรับแต่ละประเมิน
- ค้นหาและกรองข้อมูล

### 🔔 ระบบแจ้งเตือน
- Notification สำหรับใบสั่ง Care Instruction ที่รอยืนยัน
- Bell Icon มี shake animation เมื่อมีการแจ้งเตือน

---

## 🛠️ การติดตั้ง

### ข้อกำหนด
- PHP >= 8.1
- Composer
- MySQL / MariaDB 8.0
- Node.js & npm (ตามต้องการ)

### ขั้นตอนการติดตั้ง

**1. Clone Repository**
```bash
git clone https://github.com/Combo0445/Project_finish.git
cd Project_finish
```

**2. ติดตั้ง PHP Dependencies**
```bash
composer install
```

**3. สร้าง .env File**
```bash
cp .env.example .env
php artisan key:generate
```

**4. ตั้งค่า Database ใน .env**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_finish
DB_USERNAME=root
DB_PASSWORD=
```

**5. รัน Migration**
```bash
php artisan migrate
```

**6. สร้าง Storage Symlink**
```bash
php artisan storage:link
```

**7. สตาร์ท Development Server**
```bash
php artisan serve
```

เปิดเบราว์เซอร์ไปที่: `http://localhost:8000`

---

## 📖 การใช้งาน

### สำหรับ Admin
- ไปที่ `/admin-dashboard`
- สร้าง/ลบผู้ใช้งาน (Staff/Doctor)
- จัดการข่าวสาร และ Sliders

### สำหรับ Staff
- ไปที่ `/staff-dashboard`
- บันทึก ADL Assessment สำหรับผู้สูงอายุ
- บันทึก Care Giver และ Activity
- ประเมิน TAI scores
- บันทึก Performance Report

### สำหรับ Doctor
- ไปที่ `/doctor-dashboard`
- ออกคำสั่งดูแล (Care Instructions)
- ดูรายงานประเมิน

---

## 📊 โครงสร้าง Database

| ตาราง | รายละเอียด |
|------|----------|
| `users` | ผู้ใช้งาน พร้อม Image_User & Type_Personnel |
| `elderlys` | ข้อมูลผู้สูงอายุ (ชื่อ, วันเกิด, ที่อยู่) |
| `barthel_adls` | ผลการประเมิน ADL (10 ด้าน) |
| `care_givers` | ข้อมูล Caregiver และสุขภาพผู้สูงอายุ (37+ ฟิลด์, nullable) |
| `score_t_a_i_s` | ผลการประเมิน TAI พร้อม timestamps |
| `care_instructions` | คำสั่งดูแลจากแพทย์ |
| `activity_caregivers` | กิจกรรมรายวันของ Caregiver |
| `news` | บทความข่าวสาร (title, content) |
| `news_images` | รูปภาพข่าวสาร (JSON paths) |
| `performance_report` | รวมผลการประเมินทั้งหมด |
| `sliders` | ภาพบนหน้าแรก |

---

## 🔐 ความปลอดภัย

- ✅ Laravel Authentication + Session
- ✅ Middleware Role-based Access Control
- ✅ CSRF Token Protection
- ✅ Password Hashing (Bcrypt)
- ✅ SQL Injection Prevention (Eloquent ORM)
- ✅ .env ไม่ Commit ลง Git

---

## 📝 API Routes

ดูรายละเอียด:
```bash
php artisan route:list
```

---

## 🚀 Deployment

### การ Deploy ไปยัง Production

1. ตั้งค่า `.env`:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

2. ปรับ Composer:
   ```bash
   composer install --no-dev
   ```

3. Cache Configuration:
   ```bash
   php artisan config:cache
   php artisan route:cache
   ```

4. ตั้งค่า Web Server (Nginx/Apache) ให้ Document Root = `public/`

5. ติดตั้ง SSL Certificate

---

## 🤝 ผู้ร่วมสร้าง

- **พัฒนาโดย:** Combo (Combo0445)

---

## 📧 ติดต่อ

- **GitHub:** [https://github.com/Combo0445](https://github.com/Combo0445)

---

## 📜 เวอร์ชั่นและอัปเดต

**v1.1 (Feb 2026 - Security & Refactoring Audit)**
- ✅ **Security Hardening**: เพิ่ม Soft Deletes ป้องกันข้อมูลลบถาวร
- ✅ **Role-Based Access Control (RBAC)**: ควบรวม `Care Instruction` (หมอและสตาฟฟ์) ใช้ Controller ส่วนกลาง
- ✅ **Performance**: แก้ไข Memory Leaks จากการเรียก `Model::all()` ใน Dashboard และ การปริ้น PDF
- ✅ **File Upload Security**: เพิ่มระบบคัดกรองขนาดและประเภทไฟล์ (MIME types) แบบเข้มงวดให้ Slider และระบบอื่นๆ

**v1.0 (Feb 2026)**
- ✅ แก้ไข UrlGenerator error ใน Avatar
- ✅ เพิ่มระบบข่าวสาร & Quill Editor
- ✅ ทำให้ care_givers columns nullable
- ✅ สร้างตาราง performance_report  
- ✅ แก้ไข JavaScript onclick errors
- ✅ Role-based Avatar Selection

---

**ขอบคุณที่ใช้ระบบนี้ 🙏**

