# 🏥 ระบบประเมินความสามารถในการดำเนินกิจวัตรประจำวันของผู้สูงอายุ (Elderly Care Assessment System)
[![Laravel](https://img.shields.io/badge/Laravel-10-red?logo=laravel&style=for-the-badge)](https://laravel.com/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-blue?logo=mysql&style=for-the-badge)](https://www.mysql.com/)
[![PHP](https://img.shields.io/badge/PHP-8.1+-purple?logo=php&style=for-the-badge)](https://www.php.net/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-4-563D7C?logo=bootstrap&style=for-the-badge)](https://getbootstrap.com/)

> **Portfolio Project:** Web Application สำหรับจัดการและติดตามผลการประเมินสุขภาพผู้สูงอายุในระดับชุมชน ออกแบบมาเพื่อเพิ่มประสิทธิภาพการทำงานของบุคลากรทางการแพทย์ (หมอ และ เจ้าหน้าที่สาธารณสุข)

## 📋 ภาพรวมระบบ (Project Overview)
ระบบถูกพัฒนาขึ้นเพื่อเปลี่ยนผ่านการจัดเก็บข้อมูลสุขภาพผู้สูงอายุจากรูปแบบกระดาษสู่ระบบดิจิทัล (Digital Transformation) โดยรองรับแบบประเมินมาตรฐานทางการแพทย์ถึง 3 รูปแบบ ได้แก่ **Barthel ADL Index**, **Care Giver Assessment**, และ **TAI (Thai Able Index)** ระบบมาพร้อมกับกระบวนการทำงานที่ไร้รอยต่อ (Seamless Workflow) ตั้งแต่การประเมินผลโดยเจ้าหน้าที่ ไปจนถึงการสั่งการดูแล (Care Instructions) โดยแพทย์ผู้เชี่ยวชาญ

---

## ✨ ฟีเจอร์เด่น (Highlight Features)

### 👥 1. Role-Based Access Control (RBAC)
ออกแบบระบบจำกัดสิทธิ์การเข้าถึงข้อมูลและการใช้งานฟังก์ชันต่างๆ อย่างเด็ดขาดตามบทบาทของผู้ใช้งาน:
*   **Admin:** ควบคุมผู้ใช้งานระบบ, อัปเดตข่าวสาร/ประกาศ, จัดการแบนเนอร์
*   **Staff (เจ้าหน้าที่สาธารณสุข):** ลงพื้นที่ประเมินสุขภาพผู้สูงอายุ, เก็บข้อมูล ADL/CG/TAI, และติดตามผล
*   **Doctor (แพทย์):** ดูประวัติสุขภาพรายบุคคล และออกคำสั่งการดูแล (Care Instructions) ให้เจ้าหน้าที่นำไปปฏิบัติ

### 📊 2. Comprehensive Health Assessment System
รองรับมาตรวัดสุขภาพผู้สูงอายุที่เชื่อมโยงกัน:
*   **Barthel ADL Index:** ประเมินความสามารถในการดำเนินชีวิต 10 ด้าน พร้อมจำแนกกลุ่ม (ติดสังคม, ติดบ้าน, ติดเตียง) อัตโนมัติ
*   **Care Giver Assessment:** เก็บข้อมูลผู้ดูแลและสภาพแวดล้อมอย่างละเอียดกว่า 37 มิติ
*   **TAI (Thai Able Index):** ประเมินประสิทธิภาพการกลืน, การขับถ่าย, และการเคลื่อนไหว

### 📝 3. Doctor's Care Instruction Workflow
*   ระบบสั่งการดูแลจากแพทย์ พร้อม Status Tracking (รอยืนยัน -> ยืนยันรับทราบ)
*   ระบบออกรายงาน PDF แบบ Real-time ครบถ้วนบนหน้าเดียว สำหรับพิมพ์แนบแฟ้มประวัติ

### 📈 4. Dashboard & Analytics
*   สรุปข้อมูลสถิติประชากรผู้สูงอายุตามช่วงวัย และกลุ่มสุขภาพ (ADL Groups)
*   **Performance Report:** สรุปผลประเมินทั้งหมดในรูปแบบตารางและ Export เป็น PDF ได้

---

## 💻 เทคโนโลยีและสถาปัตยกรรม (Tech Stack & Architecture)

*   **Backend:** PHP 8.1, Laravel 10 (MVC Architecture)
*   **Frontend:** Blade Templates, Bootstrap 4, jQuery, DataTables
*   **Database:** MySQL 8.0 (Relational Database Design)
*   **Other Libraries:** mPDF (สำหรับสร้าง PDF), Quill Editor (สำหรับจัดการบทความ)

---

## 🛠 ทักษะทางวิศวกรรมซอฟต์แวร์ที่นำมาใช้ (Engineering Practices)

1.  **Clean Architecture:**
    *   ใช้ `DB::transaction()` ในการบันทึกข้อมูลแบบหลายตาราง ป้องกันปัญหาข้อมูลตกหล่นหากเกิด Error กลางคัน
2.  **Security & Data Integrity:**
    *   Implement **Soft Deletes** (`deleted_at`) สำหรับข้อมูลสำคัญ ป้องกันการสูญหายของประวัติทางการแพทย์
    *   ป้องกันการโจมตีแบบ SQL Injection ด้วย Eloquent ORM อย่างเคร่งครัด
    *   ป้องกัน Cross-Site Scripting (XSS) ผ่านระบบ Blade Templating ของ Laravel
3.  **Performance Optimization:**
    *   ใช้วิธี **Eager Loading** (`with()`) แก้ปัญหา **N+1 Query Problem** ทำให้ระบบแสดงผลได้รวดเร็วขึ้น
    *   ลดการเรียกใช้ `Model::all()` เปลี่ยนมาใช้ Query กรองและ Paginate ข้อมูลเสมอ
4.  **Google Maps Integration:**
    *   บูรณาการแผนที่เพื่อระบุตำแหน่งพิกัดบ้านผู้สูงอายุ ผ่านพิกัด Latitude/Longitude

---

## 🚀 การติดตั้งสำหรับ Development (Installation)

```bash
# 1. Clone repository
git clone https://github.com/Combo0445/Project_finish.git
cd Project_finish

# 2. Install dependencies
composer install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Database Setup (ตั้งค่า DB_DATABASE ใน .env ให้เรียบร้อย)
php artisan migrate --seed

# 5. Storage Link (สำหรับดึงรูปภาพ)
php artisan storage:link

# 6. Run Server
php artisan serve
```

---

## 👨‍💻 เกี่ยวกับผู้พัฒนา (About Developer)

**Combo (Combo0445)**
*Software Developer / Web Programmer*

ระบบนี้ถูกพัฒนาขึ้นด้วยความตั้งใจที่จะนำเทคโนโลยีมาประยุกต์ใช้กับระบบสาธารณสุขชุมชน โดยเน้นที่การใช้งานได้จริง โค้ดที่เป็นระเบียบ และพร้อมสำหรับการสเกลในอนาคต

*   🌍 **GitHub:** [https://github.com/Combo0445](https://github.com/Combo0445)