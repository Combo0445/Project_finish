# คู่มือ Deploy (Deployment Guide)

โปรเจกต์นี้เป็น Laravel 10 (PHP 8.1+, MySQL 8) พร้อม asset build ด้วย Vite รองรับการ deploy ได้ 4 แนวทาง เลือกตามสภาพแวดล้อมที่มี:

| แนวทาง | เหมาะกับ | ระดับความยาก |
|---|---|---|
| [Railway](#option-d-railway-เร็วที่สุด-ฟรี) | ทำ live demo/portfolio แบบไม่มีค่าใช้จ่าย ไม่ต้องมี VPS | ง่ายที่สุด, ~5 นาที |
| [Docker](#option-a-docker-แนะนำ) | VPS/Cloud ใดก็ได้ที่รัน Docker ได้ (Render, DigitalOcean, self-hosted) | ง่าย, ทำซ้ำได้แน่นอน |
| [VPS แบบ manual](#option-b-vps-manual-nginx--php-fpm) | VPS ที่มี SSH เต็มรูปแบบ | ปานกลาง |
| [Shared hosting (cPanel)](#option-c-shared-hosting-cpanel) | โฮสต์ราคาถูก/ฟรีที่ไม่มี SSH หรือ Docker | ยุ่งยากสุด, มีข้อจำกัด |

ไฟล์ที่เตรียมไว้ให้แล้วในโปรเจกต์:
- `Dockerfile`, `docker-compose.yml`, `.dockerignore`, `docker/entrypoint.sh` — สำหรับแนวทาง Docker
- `/up` route — health check endpoint สำหรับ load balancer / uptime monitor

---

## ⚠️ ข้อควรรู้ก่อน deploy: การอัปโหลดรูปภาพ

โค้ดปัจจุบันเขียนไฟล์ที่อัปโหลด (รูปโปรไฟล์ผู้ใช้, โลโก้) ลงตรงใน `public/images/` และ `public/images-user/` โดยตรง (ไม่ได้ผ่าน Laravel `Storage` disk + `storage:link`) ผลคือ:

- **Docker**: ถ้าไม่ mount volume ให้สองโฟลเดอร์นี้ ไฟล์ที่อัปโหลดจะหายทุกครั้งที่ build image ใหม่/redeploy — `docker-compose.yml` ที่เตรียมไว้ mount volume ให้แล้ว (`uploads-images`, `uploads-images-user`) แต่ถ้าย้ายไปแพลตฟอร์มอื่น (Railway/Render) ต้องตรวจสอบว่ารองรับ persistent volume หรือไม่ ถ้าไม่รองรับ ไฟล์อัปโหลดจะหายเมื่อ container restart
- **Shared hosting/VPS**: ไม่มีปัญหานี้เพราะ filesystem อยู่ถาวร แต่ต้อง backup โฟลเดอร์นี้เองคู่กับฐานข้อมูล

---

## Pre-deploy checklist (ทุกแนวทาง)

1. ตั้งค่า `.env` สำหรับ production:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.tld
   LOG_LEVEL=error
   ```
2. สร้าง `APP_KEY` ใหม่เสมอสำหรับ production (ห้ามใช้ค่าเดียวกับ dev): `php artisan key:generate --force`
3. ตั้งค่า `DB_*` ให้ตรงกับฐานข้อมูลจริง
4. รัน `php artisan migrate --force` (ต้องมี `--force` เพราะ production จะไม่ถาม prompt)
5. รัน `php artisan storage:link` (สำหรับไฟล์ที่ผ่าน Storage disk เช่น export/report)
6. cache ให้ครบเพื่อ performance: `php artisan config:cache && php artisan route:cache && php artisan view:cache`
   (โปรเจกต์นี้แก้ route closures ทั้งหมดเป็น controller แล้ว ทำให้ `route:cache` ใช้งานได้)
7. **ต้อง seed ข้อมูล personnel ก่อนสร้าง admin เสมอ**: `php artisan db:seed --class=PersonnelSeeder --force` — `make:admin` อ้างอิง `ID_Personnel=1` ที่มาจาก seeder นี้ ถ้าข้ามขั้นตอนนี้จะเจอ foreign key constraint error
8. สร้างผู้ดูแลระบบคนแรก: `php artisan make:admin <username> <password>`
9. ตรวจสอบสิทธิ์ไฟล์: `storage/` และ `bootstrap/cache/` ต้องเขียนได้โดย web server user (`chmod -R 775` + owner ที่ถูกต้อง)
10. ถ้าอยู่หลัง TLS-terminating proxy ที่ **ไม่** forward header `X-Forwarded-Proto` (ปกติ Nginx/PaaS ส่วนใหญ่ forward ให้อยู่แล้วและไม่ต้องทำอะไรเพิ่ม) ให้ตั้ง `FORCE_HTTPS_SCHEME=true` ใน `.env` — **อย่าตั้งค่านี้ถ้ายังไม่มี TLS จริงอยู่หน้าแอป** (เช่น `docker compose up` แบบ plain HTTP โดยไม่มี reverse proxy) เพราะจะทำให้ redirect ไปเป็น `https://` ที่แอปไม่มีทางเสิร์ฟได้ ผู้ใช้จะเข้าเว็บไม่ได้เลย

---

## Option A: Docker (แนะนำ)

ใช้ไฟล์ `Dockerfile` + `docker-compose.yml` ที่เตรียมไว้ให้ (multi-stage build: composer → npm build → php-apache runtime)

```bash
# 1. สร้าง .env สำหรับ compose (ใช้ตัวแปรแทนค่าจริง อย่า commit)
cp .env.example .env.docker
php artisan key:generate --show   # เอาค่ามาใส่ APP_KEY ใน .env.docker

# 2. Build และรัน
docker compose --env-file .env.docker up -d --build

# 3. รอ container พร้อม แล้ว seed personnel ก่อน (make:admin ต้องการ ID_Personnel=1 จาก seeder นี้)
docker compose exec app php artisan db:seed --class=PersonnelSeeder --force
docker compose exec app php artisan make:admin admin "รหัสผ่านที่ปลอดภัย"
```

`docker/entrypoint.sh` จะรัน migrate + cache config/route/view + storage:link ให้อัตโนมัติทุกครั้งที่ container start

**สิ่งที่ต้องตั้งค่าผ่าน environment variables** (ใน `.env.docker` หรือ platform's env settings): `APP_KEY`, `APP_URL`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `DB_ROOT_PASSWORD`

**Deploy ไปแพลตฟอร์มอื่น (Railway/Render/VPS+Docker)**: ใช้ `Dockerfile` ตัวเดียวกันได้เลย เพียงตั้งค่า environment variables ให้ครบตาม `.env.example` และเชื่อมต่อฐานข้อมูล MySQL ที่แพลตฟอร์มนั้นจัดให้ (หรือใช้ container `db` ใน compose ถ้า self-host) — อย่าลืมตรวจสอบเรื่อง persistent volume ตามหัวข้อ "ข้อควรรู้" ด้านบน

---

## Option B: VPS manual (Nginx + PHP-FPM)

ข้อกำหนด: Ubuntu/Debian VPS, PHP 8.1+ (พร้อม extensions: `mbstring`, `pdo_mysql`, `gd`, `zip`, `dom`, `bcmath`, `exif`), MySQL 8, Nginx, Composer, Node.js (สำหรับ build assets), Certbot (HTTPS)

```bash
# 1. Clone และติดตั้ง dependencies
git clone <repo-url> /var/www/project_finish
cd /var/www/project_finish
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# 2. ตั้งค่า environment
cp .env.example .env
php artisan key:generate --force
# แก้ .env: APP_ENV=production, APP_DEBUG=false, APP_URL, DB_*

# 3. ฐานข้อมูลและ cache
php artisan migrate --force
php artisan storage:link
php artisan config:cache && php artisan route:cache && php artisan view:cache
php artisan db:seed --class=PersonnelSeeder --force
php artisan make:admin admin "รหัสผ่านที่ปลอดภัย"

# 4. สิทธิ์ไฟล์ (ปรับ www-data ตาม user ที่ Nginx/PHP-FPM รันอยู่)
chown -R www-data:www-data storage bootstrap/cache public/images public/images-user
chmod -R 775 storage bootstrap/cache
```

ตัวอย่าง Nginx server block (document root ต้องชี้ที่ `public/` เท่านั้น ห้ามชี้ที่ root โปรเจกต์):

```nginx
server {
    listen 80;
    server_name your-domain.tld;
    root /var/www/project_finish/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

จากนั้นออก HTTPS certificate ด้วย `certbot --nginx -d your-domain.tld`

เนื่องจากระบบนี้ไม่มี queue worker หรือ scheduled job ที่ต้อง run ตลอดเวลา (`QUEUE_CONNECTION=sync`) จึงไม่จำเป็นต้องตั้ง Supervisor/systemd service เพิ่มเติมสำหรับ queue

---

## Option C: Shared hosting (cPanel)

ใช้เมื่อโฮสต์ไม่มี SSH/Docker (เช่น hosting ฟรี/ราคาถูกที่ให้แค่ File Manager + phpMyAdmin)

1. **เตรียมไฟล์ในเครื่องก่อนอัปโหลด** (เพราะโฮสต์ส่วนใหญ่ไม่มี Composer/Node):
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci && npm run build
   ```
2. **โครงสร้างบนโฮสต์**: อัปโหลดทุกโฟลเดอร์ยกเว้น `public/` ไปไว้ "เหนือ" `public_html` (เช่น `~/project_finish/`) แล้วอัปโหลดเนื้อหาข้างใน `public/` เข้าไปใน `public_html/` โดยตรง
3. **แก้ `public_html/index.php`** ให้ path ชี้ไปยังตำแหน่งจริงของโปรเจกต์ (เพราะย้าย public ออกมาแล้ว):
   ```php
   require __DIR__.'/../project_finish/vendor/autoload.php';
   $app = require_once __DIR__.'/../project_finish/bootstrap/app.php';
   ```
   (ปรับ path ตามโครงสร้างจริงบนโฮสต์)
4. ตั้งค่า `.env` ในโฟลเดอร์ `project_finish/` (นอก public_html เพื่อไม่ให้เข้าถึงผ่านเว็บได้)
5. รัน migration ผ่าน cPanel Terminal ถ้ามี (`php artisan migrate --force`) หรือ import schema ผ่าน phpMyAdmin ถ้าไม่มี terminal
6. ตั้งค่าสิทธิ์โฟลเดอร์ `storage/`, `bootstrap/cache/`, `public_html/images/`, `public_html/images-user/` ให้เขียนได้ (มักใช้ 755/775 ผ่าน File Manager)
7. ถ้า cPanel รองรับ "Setup Node.js/PHP App" ให้ตั้ง PHP version เป็น 8.1+ และ document root เป็นโฟลเดอร์ที่มีไฟล์จาก `public/`

**ข้อจำกัด**: หลาย shared host ไม่มี cron จริง (ต้องใช้ cPanel Cron Jobs แทน `queue:work`/scheduler) และบาง host block `exec()`/`shell_exec()` ที่ mPDF อาจเรียกใช้ภายใน ถ้าเจอปัญหาสร้าง PDF ไม่ได้ ให้ตรวจสอบ PHP disabled functions ในหน้า cPanel ก่อน

---

## Option D: Railway (เร็วที่สุด, ฟรี)

เหมาะที่สุดสำหรับทำ **live demo ใส่พอร์ตโฟลิโอ** — ไม่ต้องมี VPS ไม่ต้องผูกบัตรเครดิต ใช้ `Dockerfile` ตัวเดียวกับ Option A ได้เลยโดยไม่ต้องแก้อะไร (entrypoint รองรับ `$PORT` ที่ Railway inject ให้อัตโนมัติอยู่แล้ว)

1. **Push โค้ดขึ้น GitHub** (ถ้ายังไม่ได้ push) — Railway deploy จาก GitHub repo โดยตรง
2. สมัคร/ล็อกอิน **[railway.app](https://railway.app)** ด้วยบัญชี GitHub
3. **New Project → Deploy from GitHub repo** → เลือก repo นี้ Railway จะตรวจเจอ `Dockerfile` และ build ให้อัตโนมัติ
4. **เพิ่มฐานข้อมูล**: ในโปรเจกต์เดียวกัน กด **+ New → Database → Add MySQL** (มี free tier ในตัว)
5. **ตั้งค่า Environment Variables** ที่ service ของแอป (แท็บ Variables) — อ้างอิงค่าจาก MySQL service ที่เพิ่งสร้างด้วย syntax `${{ ServiceName.VAR }}`:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://<ชื่อโปรเจกต์>.up.railway.app   # แก้เป็น domain จริงที่ Railway ออกให้หลัง deploy ครั้งแรก
   APP_KEY=                                          # รันคำสั่งด้านล่างเพื่อสร้าง แล้วเอามาใส่
   DB_CONNECTION=mysql
   DB_HOST=${{MySQL.MYSQLHOST}}
   DB_PORT=${{MySQL.MYSQLPORT}}
   DB_DATABASE=${{MySQL.MYSQLDATABASE}}
   DB_USERNAME=${{MySQL.MYSQLUSER}}
   DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
   ```
   สร้างค่า `APP_KEY` จากเครื่องตัวเอง (ไม่ต้องมี DB ต่อก็รันได้): `php artisan key:generate --show`
6. Railway จะ redeploy อัตโนมัติเมื่อบันทึก environment variables — `docker/entrypoint.sh` จะรัน migrate ให้เองตอน container start
7. หลัง deploy สำเร็จ เข้า Settings ของ service เพื่อดู public domain (`https://<ชื่อโปรเจกต์>.up.railway.app`) แล้วย้อนกลับไปแก้ `APP_URL` ให้ตรงกับ domain จริงตามข้อ 5
8. สร้างข้อมูล demo (ปลอดภัย ไม่ใช่ข้อมูลจริง) ผ่าน Railway's web shell หรือ CLI:
   ```bash
   railway run php artisan db:seed --class=DemoSeeder --force
   ```
   หรือถ้าไม่ได้ติดตั้ง Railway CLI ให้เปิด Terminal ในหน้า service (Railway มี built-in shell ให้ใช้ได้จาก dashboard)

**ค่าใช้จ่าย**: free tier ของ Railway มี usage credit ให้ทุกเดือน เพียงพอสำหรับ demo พอร์ตโฟลิโอที่มีคนเข้าดูไม่เยอะ — ถ้า credit หมดแอปจะ sleep ไม่ได้ถูกลบ เปิดเข้าอีกครั้งจะ wake ขึ้นมาเอง

---

## Post-deploy verification

- เปิด `https://your-domain.tld/up` ควรได้ `OK` (200)
- ล็อกอินด้วย admin ที่สร้างไว้ ตรวจสอบว่า dashboard, การประเมิน ADL/CG/TAI, และ export PDF ทำงานปกติ
- ตรวจสอบว่า `APP_DEBUG=false` จริง (ลองเข้า URL ที่ไม่มีอยู่ ควรเห็นหน้า error ทั่วไป ไม่ใช่ stack trace)
- ตั้ง backup อัตโนมัติสำหรับฐานข้อมูล + โฟลเดอร์ `public/images`, `public/images-user`, `storage/app/public`
