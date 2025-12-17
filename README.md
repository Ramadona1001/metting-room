# نظام حجز قاعات الاجتماعات بواسطة QR Code

## وصف النظام
نظام إدارة حجز قاعات الاجتماعات باستخدام رموز QR. يتيح للموظفين حجز القاعات عبر مسح رمز QR دون الحاجة لتسجيل حساب.

## المتطلبات
- PHP 8.2+
- MySQL 8.0+
- Composer

## التثبيت

```bash
# نسخ ملف البيئة
cp .env.example .env

# تعديل إعدادات قاعدة البيانات في .env
DB_DATABASE=meeting_room_system
DB_USERNAME=root
DB_PASSWORD=

# تثبيت الحزم
composer install

# توليد مفتاح التطبيق
php artisan key:generate

# تشغيل الترحيلات
php artisan migrate

# تشغيل البذور (بيانات تجريبية)
php artisan db:seed

# تشغيل السيرفر
php artisan serve
```

## بيانات الدخول الافتراضية
- **البريد الإلكتروني:** admin@example.com
- **كلمة المرور:** password

---

## الصفحات والمسارات

### صفحات المدير (تتطلب تسجيل دخول)

| المسار | الوصف |
|--------|-------|
| `/login` | صفحة تسجيل الدخول |
| `/admin` | لوحة التحكم الرئيسية |
| `/admin/companies` | إدارة الشركات |
| `/admin/departments` | إدارة الأقسام |
| `/admin/meeting-rooms` | إدارة غرف الاجتماعات |
| `/admin/meeting-rooms/{id}/qr-code` | عرض رمز QR للغرفة |
| `/admin/bookings` | إدارة الحجوزات |

### صفحات الحجز العام (بدون تسجيل دخول)

| المسار | الوصف |
|--------|-------|
| `/book/{qr_token}` | صفحة حجز الغرفة (يصل إليها الموظف عبر مسح QR) |

---

## البنية المعمارية

```
app/
├── Console/Commands/
│   └── ExpireFinishedBookings.php    # أمر لتحديث الحجوزات المنتهية
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── CompanyController.php
│   │   │   ├── DepartmentController.php
│   │   │   ├── MeetingRoomController.php
│   │   │   └── BookingController.php
│   │   └── PublicBookingController.php
├── Models/
│   ├── Admin.php
│   ├── Company.php
│   ├── Department.php
│   ├── MeetingRoom.php
│   └── Booking.php
└── Services/
    ├── BookingService.php            # منطق الحجز
    └── QrCodeService.php             # توليد رموز QR

resources/views/
├── layouts/
│   ├── admin.blade.php               # تخطيط لوحة التحكم
│   └── public.blade.php              # تخطيط الصفحات العامة
├── admin/
│   ├── auth/login.blade.php
│   ├── dashboard.blade.php
│   ├── companies/
│   ├── departments/
│   ├── meeting-rooms/
│   └── bookings/
└── public/
    ├── booking.blade.php
    └── booking-unavailable.blade.php
```

## قواعد الحجز
- الحجز متاح فقط لنفس اليوم
- لا يمكن حجز وقت متداخل مع حجز آخر
- مدة الحجز القصوى قابلة للتخصيص لكل غرفة
- يجب أن تكون الغرفة والشركة نشطة
- ساعات العمل اختيارية لكل غرفة

## الأمان
- رمز QR يستخدم UUID آمن غير قابل للتخمين
- لا يتم كشف معرفات الغرف في الروابط العامة
- تحديد معدل الطلبات على نقاط الحجز العامة (10 طلبات/دقيقة)
- التحقق من المدخلات ضد الإساءة

## الأوامر المجدولة
```bash
# تحديث الحجوزات المنتهية يدوياً
php artisan bookings:expire

# تشغيل المجدول
php artisan schedule:work
```

## الاختبارات
```bash
php artisan test
```
