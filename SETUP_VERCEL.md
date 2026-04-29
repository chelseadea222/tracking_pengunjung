# Setup Vercel untuk Track Wisatawan

## 📋 Penyebab 403 Forbidden

1. **Routing salah di vercel.json** ❌ - Sudah diperbaiki
2. **Session path tidak compatible dengan Vercel** ❌ - Sudah diperbaiki
3. **Database credentials belum di-set sebagai env variables** ❌ - Sudah siap
4. **SSL CA path tidak ada** ❌ - Sudah ditangani

---

## ✅ Langkah-langkah Fix:

### 1. **Update Environment Variables di Vercel Dashboard**

Buka [Vercel Dashboard](https://vercel.com), masuk ke project Anda, lalu:
- Buka tab **Settings** → **Environment Variables**
- Tambahkan variable berikut:

```
DB_HOST = gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com
DB_PORT = 4000
DB_NAME = Tracking
DB_USER = rYKFcN4zmjYBxLa.root
DB_PASS = h0UwkOyj9GVT7FpW
ENVIRONMENT = production
DEBUG = false
```

### 2. **Push Perubahan ke GitHub**

Setelah file-file sudah dikompilasi, lakukan:
```bash
git add .
git commit -m "Fix: Update vercel.json routing dan database configuration"
git push
```

### 3. **Vercel Akan Auto-Deploy**

Vercel akan otomatis detect push dan melakukan deployment ulang dengan routing yang benar.

---

## 🔍 Apa yang Sudah Diperbaiki:

### vercel.json
- ✅ Routing diperbaiki untuk menangani request ke `/login.php`, `/tiket.php`, dll
- ✅ Mendukung format dengan atau tanpa `.php` extension
- ✅ Mendukung redirect dengan hyphen (`tiket-harian.php`) maupun underscore (`tiket_harian.php`)

### koneksi.php
- ✅ Tidak lagi gunakan `session_save_path('/tmp')` yang tidak compatible dengan Vercel
- ✅ Database credentials sekarang dari environment variables
- ✅ SSL handling lebih baik (adaptive berdasarkan environment)

### .env.example
- ✅ Template untuk semua environment variables yang dibutuhkan

### .gitignore
- ✅ Pastikan `.env` tidak terakses di repository (keamanan)

---

## 🧪 Testing Setelah Deploy:

Setelah Vercel selesai deploy:

1. **Akses login page:**
   ```
   https://your-vercel-domain.com/login.php
   atau
   https://your-vercel-domain.com/login
   ```

2. **Cek error logs:**
   - Buka Vercel Dashboard → Deployments
   - Klik deployment terbaru
   - Klik "Functions" untuk lihat PHP logs

3. **Test database connection:**
   - Pastikan credentials di environment variables sesuai
   - Cek apakah IP Vercel sudah white-listed di TiDB Cloud

---

## ⚠️ Catatan Penting:

1. **Session di Vercel:** Vercel adalah serverless, jadi session cookies mungkin tidak persist antar request. Pertimbangkan untuk:
   - Menggunakan JWT tokens
   - Menggunakan database untuk session storage
   - Menggunakan Vercel KV (Redis) untuk session

2. **Credentials:** Jangan pernah hardcode password di file! Gunakan **Environment Variables** selalu.

3. **SSL untuk TiDB:** Vercel sudah support SSL ke TiDB, tapi pastikan koneksi aman.

---

## 💡 Advanced: Gunakan Database untuk Session (Opsional)

Jika session terus bermasalah, ubah `koneksi.php`:

```php
// Gunakan database untuk session storage
ini_set('session.serialize_handler', 'php');
session_set_save_handler(
    function($id) { /* open */ return true; },
    function() { /* close */ return true; },
    function($id) { /* read */ ... },
    function($id, $data) { /* write */ ... },
    function($id) { /* destroy */ ... },
    function($max_lifetime) { /* gc */ ... }
);
```

Atau gunakan library seperti `Slim Session Handler`.

---

## 📚 Resources:

- [Vercel PHP Runtime](https://vercel.com/docs/functions/serverless-functions/runtimes/php)
- [TiDB Cloud Connection String](https://docs.pingcap.com/tidbcloud/connect-via-standard-connection)
- [PHP Session di Serverless](https://www.php.net/manual/en/function.session-save-path.php)
