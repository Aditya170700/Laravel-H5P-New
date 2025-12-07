# دليل الاستخدام الكامل - Laravel H5P Pro

## 📋 الفهرس

1. [المتطلبات](#المتطلبات)
2. [التنصيب](#التنصيب)
3. [الإعداد الأولي](#الإعداد-الأولي)
4. [إدارة المكتبات](#إدارة-المكتبات)
5. [إنشاء المحتوى](#إنشاء-المحتوى)
6. [عرض المحتوى للطلاب](#عرض-المحتوى-للطلاب)
7. [التقارير والإحصائيات](#التقارير-والإحصائيات)
8. [تكامل xAPI/LRS](#تكامل-xapi-lrs)
9. [الأوامر المتاحة](#الأوامر-المتاحة)
10. [حل المشاكل](#حل-المشاكل)

---

## المتطلبات

| المتطلب | الإصدار |
|---------|---------|
| PHP | 8.2+ |
| Laravel | 11 أو 12 |
| قاعدة البيانات | PostgreSQL أو MySQL |
| Composer | 2.x |

---

## التنصيب

### الخطوة 1: إضافة الحزمة

```bash
composer require djoudi/laravel-h5p
```

### الخطوة 2: التنصيب التلقائي

```bash
php artisan h5p:install
```

هذا الأمر سيقوم بـ:
- ✅ نشر ملفات الإعداد
- ✅ تشغيل الـ migrations
- ✅ إنشاء مجلدات التخزين
- ✅ ربط التخزين بالـ public

### الخطوة 3: التحقق من التنصيب

```bash
php artisan h5p:status
```

---

## الإعداد الأولي

### إعدادات البيئة (.env)

```env
# إعدادات H5P الأساسية
H5P_STORAGE_PATH=h5p
H5P_DEV_MODE=false

# إعدادات LRS (اختياري)
LRS_ENABLED=false
LRS_ENDPOINT=
LRS_USERNAME=
LRS_PASSWORD=
```

### تشغيل Queue Worker (مطلوب لـ xAPI)

```bash
php artisan queue:work
```

---

## إدارة المكتبات

### الوصول لصفحة المكتبات

```
/h5p/library
```

### تحميل مكتبة جديدة

1. اذهب إلى `/h5p/library`
2. اضغط "Upload Library"
3. اختر ملف `.h5p` من جهازك
4. اضغط "Upload"

### المكتبات الشائعة

| المكتبة | الوصف |
|---------|-------|
| Interactive Video | فيديو تفاعلي |
| Course Presentation | عرض تقديمي |
| Quiz | اختبار قصير |
| Drag and Drop | سحب وإفلات |
| Fill in the Blanks | ملء الفراغات |

---

## إنشاء المحتوى

### الوصول لصفحة المحتوى

```
/admin/h5p
```

### إنشاء محتوى جديد

1. اذهب إلى `/admin/h5p`
2. اضغط "Create New"
3. اختر نوع المحتوى
4. أضف العنوان والمحتوى
5. اضغط "Create"

### تعديل محتوى

1. اذهب إلى `/admin/h5p`
2. اضغط على المحتوى المراد تعديله
3. قم بالتعديلات
4. اضغط "Update"

---

## عرض المحتوى للطلاب

### رابط الدرس

```
/lesson/{content_id}
```

### الاستخدام البرمجي

```php
// في Controller
public function showLesson($id)
{
    $h5p = App::make('LaravelH5p');
    $content = $h5p->get_content($id);
    $embed = $h5p->get_embed($content, $settings);
    
    return view('lesson', [
        'embed_code' => $embed['embed'],
        'title' => $content['title'],
    ]);
}
```

### في Blade

```blade
<div class="lesson-container">
    <h1>{{ $title }}</h1>
    {!! $embed_code !!}
</div>

@push('h5p-header')
    {!! $settings['core']['styles'] !!}
    {!! $settings['loadedCss'] !!}
@endpush

@push('h5p-footer')
    {!! $settings['core']['scripts'] !!}
    {!! $settings['loadedJs'] !!}
@endpush
```

---

## التقارير والإحصائيات

### لوحة التحكم

```
/admin/reports
```

### التقارير المتاحة

| التقرير | الرابط |
|---------|--------|
| لوحة المعلومات | `/admin/reports` |
| تقارير الطلاب | `/admin/reports/students` |
| تقرير طالب | `/admin/reports/students/{id}` |
| تقارير المحتوى | `/admin/reports/contents` |
| تقرير محتوى | `/admin/reports/contents/{id}` |

### تصدير CSV

```
/admin/reports/export?type=student&id={user_id}
/admin/reports/export?type=content&id={content_id}
```

---

## تكامل xAPI LRS

### تفعيل LRS

في `.env`:

```env
LRS_ENABLED=true
LRS_ENDPOINT=https://your-lrs.com/data/xAPI
LRS_USERNAME=your-key
LRS_PASSWORD=your-secret
```

### تشغيل Queue

```bash
# للتطوير
php artisan queue:work

# للإنتاج (مع Supervisor)
[program:laravel-worker]
command=php /path/to/artisan queue:work
autostart=true
autorestart=true
```

### التحقق من الإرسال

```bash
tail -f storage/logs/laravel.log
```

ستظهر رسائل مثل:
```
[INFO] xAPI statement sent successfully
```

---

## الأوامر المتاحة

### h5p:install

```bash
# تنصيب كامل
php artisan h5p:install

# تنصيب مع استبدال الملفات
php artisan h5p:install --force

# تنصيب بدون migrations
php artisan h5p:install --skip-migrations
```

### h5p:publish

```bash
# نشر كل الملفات
php artisan h5p:publish

# نشر الإعدادات فقط
php artisan h5p:publish --config

# نشر الـ assets فقط
php artisan h5p:publish --assets

# نشر الـ views فقط
php artisan h5p:publish --views
```

### h5p:cleanup

```bash
# تنظيف الملفات المؤقتة
php artisan h5p:cleanup --temp

# حذف المكتبات غير المستخدمة
php artisan h5p:cleanup --unused

# معاينة بدون حذف
php artisan h5p:cleanup --dry-run
```

### h5p:status

```bash
php artisan h5p:status
```

يعرض:
- حالة جداول قاعدة البيانات
- إحصائيات (المكتبات، المحتوى، النتائج)
- حالة المجلدات
- حالة تكامل LRS

---

## حل المشاكل

### الـ Assets لا تعمل

```bash
php artisan h5p:publish --assets --force
php artisan storage:link
```

### خطأ في قاعدة البيانات

```bash
php artisan migrate:fresh
php artisan h5p:install
```

### مشاكل الصلاحيات

```bash
# على Linux/Mac
chmod -R 775 storage/app/public/h5p
chown -R www-data:www-data storage/app/public/h5p
```

### مسح الـ Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### LRS لا يعمل

1. تحقق من الإعدادات في `.env`
2. تأكد من تشغيل `queue:work`
3. راجع الـ logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

---

## الدعم

للمساعدة والدعم:
- GitHub Issues
- الوثائق: `/docs`

---

**إصدار الوثائق:** 1.0.0
**آخر تحديث:** 2025-12-07
