# ✅ Checklist Setup Vercel - JANGAN LEWATKAN!

## 📝 Langkah 1: Push Perubahan ke GitHub

```bash
cd c:\Users\Hp\Downloads\track_wisatawan-main\track_wisatawan-main
git add .
git commit -m "Fix: Update routing dan URLs untuk Vercel deployment"
git push origin main
```

✅ Pastikan push berhasil sebelum ke langkah berikutnya!

---

## 🔧 Langkah 2: Set Environment Variables di Vercel Dashboard

1. Buka [Vercel Dashboard](https://vercel.com)
2. Masuk ke project **tracking-pengunjung-hgpytpbfi-chelseadea222s-projects**
3. Klik **Settings** (di bagian atas)
4. Pilih **Environment Variables** (di sidebar kiri)
5. **Tambahkan variable berikut SATU PER SATU:**

| Variable | Value |
|----------|-------|
| `DB_HOST` | `gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com` |
| `DB_PORT` | `4000` |
| `DB_NAME` | `Tracking` |
| `DB_USER` | `rYKFcN4zmjYBxLa.root` |
| `DB_PASS` | `h0UwkOyj9GVT7FpW` |
| `ENVIRONMENT` | `production` |
| `DEBUG` | `false` |

⚠️ **Pastikan SEMUA 7 variable sudah ter-set dengan benar!**

---

## 🚀 Langkah 3: Trigger Redeploy di Vercel

Setelah environment variables ter-set:

1. Buka tab **Deployments**
2. Cari deployment terbaru (status "Ready")
3. Klik 3 titik (...) pada deployment itu
4. Pilih **Redeploy**
5. Tunggu deployment selesai (status jadi "Ready" dengan centang hijau)

---

## 🧪 Langkah 4: Test Website

Setelah deployment selesai, buka:

```
https://tracking-pengunjung-hgpytpbfi-chelseadea222s-projects.vercel.app
```

atau

```
https://tracking-pengunjung-hgpytpbfi-chelseadea222s-projects.vercel.app/login
```

### Test Cases:
- [ ] Akses login page tanpa error 403
- [ ] Bisa masuk dengan akun yang terdaftar
- [ ] Redirect ke dashboard sesuai role (admin → tiket-harian, user → tiket)
- [ ] Logout berfungsi dengan baik
- [ ] Form tiket bisa di-submit

---

## 🔍 Debugging Jika Masih Error

### Error 403 Masih Muncul?

**A. Cek apakah ENV variables sudah di-set:**
- Buka Settings → Environment Variables
- Pastikan 7 variables sudah ada (jangan kurang!)

**B. Cek database connection:**
1. Di Vercel Dashboard, buka tab **Deployments**
2. Klik deployment terbaru
3. Buka tab **Functions** → klik `login.php`
4. Lihat logs - ada error apa?

**C. Common Error & Solution:**

| Error | Penyebab | Solusi |
|-------|---------|--------|
| `Connection timeout` | Firewall TiDB Cloud | Whitelist IP Vercel di [TiDB Cloud](https://tidbcloud.com) |
| `Access denied` | Password salah | Double-check password di env variables |
| `No such file` | Path routing salah | Cek vercel.json sudah ter-update |

### Akses TiDB Cloud untuk whitelist IP:

1. Buka [TiDB Cloud Console](https://tidbcloud.com/console)
2. Pilih cluster **Tracking**
3. Buka tab **Network Access**
4. Klik **Add IP Access List**
5. Gunakan `0.0.0.0/0` untuk allow semua IP (aman di test)
6. Klik **Confirm**

---

## 📋 Perubahan yang Sudah Dilakukan

File yang sudah di-update:
- ✅ **vercel.json** - Routing sekarang bisa handle `/login`, `/register`, dll
- ✅ **index.html** - Link sekarang ke `/login` bukan `/api/login.php`
- ✅ **api/koneksi.php** - Database config sekarang dari ENV variables
- ✅ **api/login.php** - Link dan error handling sudah fixed
- ✅ **api/register.php** - Link dan redirect sudah fixed
- ✅ **api/logout.php** - Redirect sekarang ke `/` (home)
- ✅ **api/tiket.php** - Logout link sudah fixed
- ✅ **api/tiket_harian.php** - Semua link dan redirect sudah fixed
- ✅ **api/dashboard.php** - Link sudah fixed
- ✅ **api/backup_tiket.php** - Link dan redirect sudah fixed
- ✅ **api/proses_login.php** - Redirect ke halaman yang benar
- ✅ **api/proses_register.php** - Redirect URL sudah diperbaiki
- ✅ **api/proses_tiket_harian.php** - Semua redirect sudah diperbaiki
- ✅ **.gitignore** - Pastikan `.env` tidak ter-track
- ✅ **.env.example** - Template untuk environment variables

---

## 💡 Tips & Trik

1. **Jangan hardcode password di file!** - Selalu gunakan environment variables
2. **Jangan commit `.env` file!** - Itu file rahasia, hanya `.env.example` yang di-commit
3. **Session Cookies**: Vercel serverless kadang ada issue dengan session. Jika terjadi, pertimbangkan gunakan JWT tokens
4. **Testing lokal sebelum push** - Jika ada vercel CLI, bisa test dengan `vercel dev`

---

## 📞 Jika Masih Stuck

1. **Cek Vercel Logs:**
   - Dashboard → Deployments → Buka deployment terbaru
   - Tab **Functions** → Pilih file PHP
   - Lihat console output untuk error details

2. **Clear Browser Cache:**
   - Tekan `Ctrl + Shift + Delete`
   - Clear cached images and files

3. **Check TiDB Connection:**
   - Akses [TiDB Cloud Console](https://tidbcloud.com)
   - Pastikan cluster status: "Available"
   - Pastikan username/password benar

---

**Siap? Let's go! 🚀**
