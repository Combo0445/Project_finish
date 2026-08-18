# 🏥 ระบบประเมินความสามารถในการดำเนินกิจวัตรประจำวันของผู้สูงอายุ (Elderly Care Assessment System)
[![Laravel](https://img.shields.io/badge/Laravel-10-red?logo=laravel&style=for-the-badge)](https://laravel.com/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-blue?logo=mysql&style=for-the-badge)](https://www.mysql.com/)
[![PHP](https://img.shields.io/badge/PHP-8.1+-purple?logo=php&style=for-the-badge)](https://www.php.net/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-4-563D7C?logo=bootstrap&style=for-the-badge)](https://getbootstrap.com/)

> **Portfolio Project:** Web Application สำหรับจัดการและติดตามผลการประเมินสุขภาพผู้สูงอายุในระดับชุมชน ออกแบบมาเพื่อเพิ่มประสิทธิภาพการทำงานของบุคลากรทางการแพทย์ (หมอ และ เจ้าหน้าที่สาธารณสุข)

## 🎮 Live Demo

🔗 **Demo URL:** _[เติมลิงก์หลัง deploy — ดูวิธี deploy ฟรีด้วย Railway ใน 5 นาทีที่ [DEPLOYMENT.md](DEPLOYMENT.md#option-d-railway-เร็วที่สุด-ฟรี)]_

ทดลองเข้าใช้งานได้ทันทีด้วยบัญชีตัวอย่าง (ข้อมูลทั้งหมดเป็นข้อมูลสมมติ ไม่ใช่ข้อมูลจริง):

| บทบาท | Username | Password |
|---|---|---|
| ผู้ดูแลระบบ (Admin) | `admin` | `Admin@12345` |
| เจ้าหน้าที่ (Staff) | `demo_staff` | `Demo@2026` |
| แพทย์ (Doctor) | `demo_doctor` | `Demo@2026` |

> หมายเหตุ: บัญชี Admin สามารถสลับไปทดลองมุมมองของ Staff/Doctor ได้จากเมนูโปรไฟล์ โดยไม่ต้อง logout

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

## 🐛 ปัญหาที่เจอและแก้ไขระหว่างพัฒนา (Problems Found & Fixed)

ช่วงเตรียมระบบให้พร้อม deploy จริง ได้ไล่ตรวจโค้ดทั้งระบบอย่างเป็นระบบ (compile Blade views ทั้งหมด, ทดสอบทุก flow ผ่าน HTTP จริงใน Docker ก่อน/หลังแก้ทุกครั้ง) และเจอบั๊กที่ซ่อนอยู่หลายจุดซึ่งน่าสนใจในแง่วิศวกรรม:

*   **ฟอร์มที่กดบันทึกไม่ได้เลยจากหน้าตา UI ปกติ:** ฟอร์มแบบ multi-step wizard 2 หน้า (Care Giver, TAI) มีปุ่ม "ถัดไป"/"บันทึก" ปรากฏครบ แต่ตัว JavaScript ที่ควบคุม step ขาดหายไป หรืออ้างอิง element ID ที่ไม่มีอยู่จริง ทำให้ผู้ใช้ไม่สามารถผ่าน step แรกได้เลย — เจอจากการอ่านโค้ด JS เทียบกับ DOM จริง ไม่ใช่แค่ดู error message
*   **บั๊กที่โผล่เฉพาะตอน deploy บน Linux เท่านั้น:** ระบบไฟล์ Windows (dev machine) ไม่สนใจตัวพิมพ์เล็ก-ใหญ่ของชื่อไฟล์ แต่ Linux (Docker/production) สนใจ ทำให้ `view('admin.register-user')` หาไฟล์ `Register-user.blade.php` ไม่เจอและ error 500 — เขียนสคริปต์ไล่เทียบชื่อไฟล์ `view()` ทุกจุดในระบบกับชื่อไฟล์จริงแบบ case-sensitive เพื่อจับบั๊กประเภทนี้ทั้งหมดในครั้งเดียว
*   **Session หมดอายุแล้ว 500 แทนที่จะ redirect ไป login:** Laravel middleware เรียก `route('login')` ตอน redirect ผู้ใช้ที่ยังไม่ login แต่ route `GET /login` ไม่เคยตั้งชื่อ (`->name('login')`) ไว้ ทำให้ทุกครั้งที่ session หมดอายุ ผู้ใช้เจอหน้า 500 แทนหน้า login ปกติ
*   **Dropdown ที่ required แต่ submit ไม่ได้เพราะ validation ฝั่ง browser บล็อกเงียบๆ:** field เชื่อมโยงข้อมูล `ID_Adl`/`ID_Tai` ในหน้าแก้ไข ใช้ชื่อ attribute ผิด casing เทียบกับคอลัมน์ฐานข้อมูลจริง (`ID_Adl` vs `ID_ADL`) ทำให้ dropdown เลือกค่าว่างเสมอ และ browser บล็อก submit แบบเงียบๆ (ไม่มี network request ส่งออกเลย) — ต้อง diagnose จาก access log ที่ไม่มี POST/PUT ตามหลัง GET เพื่อยืนยันว่าเป็นปัญหาฝั่ง client ไม่ใช่ server
*   **ฟีเจอร์ที่มีโค้ดสมบูรณ์แต่ไม่เคยทำงานได้เลย:** ระบบให้ Admin สลับมุมมองเป็น Staff/Doctor มี UI และ route ครบ แต่ flag `is_admin_permanent` ที่ใช้เช็คสิทธิ์ไม่เคยถูกตั้งเป็น `true` ที่ไหนในโค้ดเลยตั้งแต่ต้น ทำให้เมนูนี้ไม่เคยแสดงผลสำหรับบัญชีใดเลย

**แนวทางการแก้ทุกจุด:** ยืนยันด้วย `php -l` + `php artisan view:cache` (compile ทั้งระบบ) หลังแก้ทุกไฟล์ แล้วทดสอบซ้ำผ่าน HTTP จริงในสภาพแวดล้อม Docker (ใกล้เคียง production) ก่อนถือว่าจบงาน ไม่ใช่แค่ทดสอบบน dev server ในเครื่อง

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

# 4.1 (ทางเลือก) สร้างข้อมูลตัวอย่าง + บัญชี demo_staff/demo_doctor สำหรับทดลองระบบ
php artisan db:seed --class=DemoSeeder

# 5. Storage Link (สำหรับดึงรูปภาพ)
php artisan storage:link

# 6. Run Server
php artisan serve
```

---

## 🚀 การ Deploy ขึ้น Production

รองรับการ deploy 4 แนวทาง (Railway, Docker, VPS แบบ manual, Shared hosting cPanel) พร้อม `Dockerfile`/`docker-compose.yml` สำเร็จรูป — ดูรายละเอียดทั้งหมดที่ [DEPLOYMENT.md](DEPLOYMENT.md)

```bash
docker compose up -d --build
```

---

## 👨‍💻 เกี่ยวกับผู้พัฒนา (About Developer)

**Combo (Combo0445)**
*Software Developer / Web Programmer*

ระบบนี้ถูกพัฒนาขึ้นด้วยความตั้งใจที่จะนำเทคโนโลยีมาประยุกต์ใช้กับระบบสาธารณสุขชุมชน โดยเน้นที่การใช้งานได้จริง โค้ดที่เป็นระเบียบ และพร้อมสำหรับการสเกลในอนาคต

*   🌍 **GitHub:** [https://github.com/Combo0445](https://github.com/Combo0445)