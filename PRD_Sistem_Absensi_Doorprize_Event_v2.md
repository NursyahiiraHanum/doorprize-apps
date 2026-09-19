# PRD — Sistem Absensi & Doorprize Event

## 1. Informasi Produk

| Item | Detail |
|---|---|
| Nama Produk | Sistem Absensi & Doorprize Event |
| Platform | Web |
| Teknologi | Laravel, MySQL |
| Pengguna | Admin/Panitia |
| Scope | Satu event |
| Tujuan | Mengelola data master karyawan, absensi menggunakan QR Code, serta pengundian doorprize berdasarkan karyawan yang hadir dan memenuhi aturan eligibility |

---

## 2. Latar Belakang

Sistem dibuat untuk membantu panitia mengelola kehadiran karyawan pada acara secara digital menggunakan QR Code dan menentukan karyawan yang berhak mengikuti pengundian doorprize.

Data karyawan berasal dari data master perusahaan. Setiap karyawan memiliki NPK sebagai identitas karyawan dan QR Code yang digunakan saat proses absensi.

Ketika karyawan hadir di acara, QR Code miliknya akan dipindai oleh panitia. Sistem memvalidasi QR Code dan mencatat waktu kehadiran.

Data karyawan yang sudah hadir kemudian digunakan sebagai dasar pembentukan eligible pool doorprize.

Eligibility juga mempertimbangkan status karyawan, session doorprize, dan tahun terakhir karyawan memenangkan doorprize. Karyawan yang masih berada dalam periode larangan kemenangan tidak dapat masuk pool meskipun sudah hadir.

Sistem ini dibuat khusus untuk satu event sehingga tidak membutuhkan tabel `events`.

---

## 3. Tujuan

1. Menyimpan data master karyawan.
2. Menyimpan NPK sebagai identitas karyawan dari perusahaan.
3. Menyimpan jumlah tanggungan dan total tiket.
4. Menyimpan status karyawan.
5. Menyediakan QR Code unik untuk setiap karyawan.
6. Mencatat kehadiran berdasarkan scan QR Code.
7. Mencegah absensi ganda.
8. Mengelola daftar hadiah dan session doorprize.
9. Membentuk eligible pool secara otomatis.
10. Menerapkan aturan tahun terakhir kemenangan.
11. Melakukan random draw.
12. Menyimpan hasil pengundian.

---

# 4. Scope

## 4.1 In Scope

- Import/input data master karyawan.
- Pengelolaan data NPK.
- Pengelolaan nama karyawan.
- Pengelolaan jumlah tanggungan.
- Pengelolaan total tiket.
- Pengelolaan status karyawan.
- Generate QR Code.
- Scan QR Code untuk absensi.
- Validasi QR Code.
- Pencegahan scan ganda.
- Dashboard kehadiran.
- Pengelolaan hadiah.
- Pengelolaan session.
- Pengaturan target status karyawan untuk hadiah/session.
- Pembentukan eligible pool.
- Random draw doorprize.
- Penyimpanan pemenang.
- Tampilan hasil pemenang.
- Rekap data attendance dan pemenang.

## 4.2 Out of Scope

- Sistem HR perusahaan secara keseluruhan.
- Payroll.
- Pengelolaan keluarga/tanggungan secara detail per individu.
- Sistem pembayaran.
- Sistem registrasi peserta publik.
- Multi-event management.

---

# 5. Aktor Sistem

## 5.1 Admin/Panitia

Admin/panitia dapat:

- Mengelola data karyawan.
- Generate QR Code.
- Melakukan scan QR Code.
- Melihat data kehadiran.
- Mengelola hadiah.
- Mengatur session.
- Melihat jumlah eligible pool.
- Menjalankan random draw.
- Melihat hasil pemenang.
- Melihat riwayat pemenang pada event berjalan.
- Melakukan koreksi data jika diperlukan.

## 5.2 Karyawan

Karyawan tidak memerlukan akun.

Karyawan hanya:

1. Datang ke lokasi acara.
2. Menunjukkan QR Code.
3. QR Code dipindai panitia.
4. Sistem mencatat kehadiran.

---

# 6. Alur Utama Sistem

```text
DATA MASTER KARYAWAN
        |
        v
    QR CODE
        |
        v
KARYAWAN DATANG
        |
        v
    SCAN QR
        |
        v
  VALIDASI QR
        |
        +---- Tidak Valid ----> Tolak
        |
        v
 CEK SUDAH ABSEN?
        |
        +---- Ya -------------> Tolak / Sudah Absen
        |
        v
 SIMPAN KEHADIRAN
        |
        v
ELIGIBILITY FILTER
        |
        +---- Cek Kehadiran
        +---- Cek Status
        +---- Cek Last Winner Year
        +---- Cek Session
        +---- Cek Sudah Menang
        |
        v
  ELIGIBLE POOL
        |
        v
   RANDOM DRAW
        |
        v
    PEMENANG
        |
        v
SIMPAN HASIL DOORPRIZE
```

---

# 7. Struktur Database

Sistem menggunakan **4 tabel utama**:

```text
employees
    |
    +---- attendances
    |
    +---- doorprize_winners
              |
              +---- prizes
```

Relasi:

```text
employees 1:N attendances

employees 1:N doorprize_winners

prizes 1:N doorprize_winners
```

Tidak terdapat tabel `events` karena sistem hanya digunakan untuk satu event.

Tidak terdapat tabel `pool_prizes` karena eligible pool dibentuk secara dinamis dari data attendance dan aturan eligibility.

---

# 8. Tabel `employees` — Master Karyawan

Tabel ini merupakan sumber data utama karyawan dari perusahaan.

| Field | Type | Required | Description |
|---|---|---|---|
| id | BIGINT | Yes | Primary key internal database |
| npk | VARCHAR | Yes | ID/NPK karyawan dari perusahaan |
| name | VARCHAR | Yes | Nama karyawan |
| tanggungan | INT | Yes | Jumlah tanggungan/keluarga yang ikut |
| tiket | INT | Yes | Total tiket karyawan + tanggungan |
| status_karyawan | VARCHAR | Yes | Status karyawan, misalnya Kartap/Kontrak |
| qr_code | VARCHAR | Yes | Identifier unik untuk QR Code |
| last_winner_year | INT | No | Tahun terakhir karyawan memenangkan doorprize |
| created_at | TIMESTAMP | Yes | Waktu pembuatan |
| updated_at | TIMESTAMP | Yes | Waktu perubahan |

### Constraint

- `id` menjadi primary key.
- `npk` harus unique.
- `qr_code` harus unique.
- `last_winner_year` boleh `NULL` apabila karyawan belum pernah menang.

### Contoh Data

| NPK | Nama | Tanggungan | Tiket | Status | Last Winner Year |
|---|---|---:|---:|---|---:|
| 001 | Budi Santoso | 2 | 3 | Kartap | 2025 |
| 002 | Siti Aminah | 1 | 2 | Kontrak | 2023 |
| 003 | Andi Wijaya | 0 | 1 | Kartap | NULL |

---

# 9. Tabel `attendances` — Kehadiran

Tabel ini mencatat karyawan yang melakukan scan QR Code pada event.

| Field | Type | Required | Description |
|---|---|---|---|
| id | BIGINT | Yes | Primary key |
| employee_id | BIGINT | Yes | Foreign key ke `employees` |
| scanned_at | TIMESTAMP | Yes | Waktu QR Code dipindai |
| scan_type | VARCHAR | Yes | Metode absensi, default `qr` |
| created_at | TIMESTAMP | Yes | Waktu pembuatan |
| updated_at | TIMESTAMP | Yes | Waktu perubahan |

### Constraint

`employee_id` harus unique karena satu karyawan hanya boleh tercatat satu kali sebagai hadir.

Contoh:

```text
employee_id = 1
scanned_at = 2026-10-31 08:15:23
scan_type = qr
```

---

# 10. Tabel `prizes` — Hadiah

Tabel ini menyimpan daftar hadiah yang tersedia dalam event.

| Field | Type | Required | Description |
|---|---|---|---|
| id | BIGINT | Yes | Primary key |
| name | VARCHAR | Yes | Nama hadiah |
| description | TEXT | No | Deskripsi hadiah |
| quantity | INT | Yes | Jumlah hadiah |
| session | INT | Yes | Session pengundian |
| employee_type | VARCHAR | No | Target peserta, misalnya `all` atau `kartap` |
| is_active | BOOLEAN | Yes | Status hadiah |
| created_at | TIMESTAMP | Yes | Waktu pembuatan |
| updated_at | TIMESTAMP | Yes | Waktu perubahan |

### Contoh

| Hadiah | Quantity | Session | Target |
|---|---:|---:|---|
| Voucher 100K | 20 | 1 | Semua |
| Speaker | 5 | 2 | Semua |
| Smart TV | 2 | 3 | Kartap |
| Hadiah Utama | 1 | 4 | Kartap |

---

# 11. Tabel `doorprize_winners` — Pemenang

Tabel ini menyimpan hasil pengundian doorprize pada event.

| Field | Type | Required | Description |
|---|---|---|---|
| id | BIGINT | Yes | Primary key |
| prize_id | BIGINT | Yes | Foreign key ke `prizes` |
| participant_id | BIGINT | Yes | Foreign key ke `participants` |
| won_at | TIMESTAMP | Yes | Waktu kemenangan |
| created_at | TIMESTAMP | Yes | Waktu pembuatan |
| updated_at | TIMESTAMP | Yes | Waktu perubahan |

Tabel ini digunakan untuk mengetahui siapa saja yang sudah memenangkan hadiah selama event berjalan.

---

# 12. QR Code

Setiap karyawan memiliki QR Code unik.

QR Code dapat berisi:

```text
NPK
```

atau identifier/token unik.

Contoh:

```text
EMP-001234
```

QR Code tidak perlu menyimpan seluruh informasi pribadi karyawan.

Sistem menggunakan nilai QR Code untuk mencari data karyawan pada tabel `employees`.

---

# 13. Proses Absensi

```text
Scan QR
   |
   v
Cari employee berdasarkan QR
   |
   v
QR ditemukan?
   |
   +-- Tidak --> Tampilkan "QR tidak valid"
   |
   v
Cek attendance
   |
   +-- Sudah ada --> Tampilkan "Sudah absen"
   |
   v
Simpan attendance
   |
   v
Tampilkan "Absensi berhasil"
```

### Validasi

#### QR Valid

```text
QR ditemukan
→ employee ditemukan
→ belum absen
→ attendance disimpan
```

#### QR Tidak Valid

```text
QR tidak ditemukan
→ attendance tidak disimpan
```

#### Scan Ganda

```text
Employee sudah memiliki attendance
→ attendance baru tidak dibuat
```

---

# 14. Eligible Pool Doorprize

Eligible pool **bukan tabel database**.

Eligible pool dibuat secara dinamis berdasarkan data attendance dan aturan doorprize.

```text
ATTENDANCES
     |
     v
Karyawan yang hadir
     |
     v
Cek Status Karyawan
     |
     v
Cek Last Winner Year
     |
     v
Cek Pemenang Event Berjalan
     |
     v
Cek Session
     |
     v
ELIGIBLE POOL
```

---

# 15. Aturan Eligibility

Karyawan dapat masuk pool apabila:

1. Sudah melakukan absensi.
2. QR Code valid.
3. Status karyawan sesuai dengan aturan session/hadiah.
4. `last_winner_year` tidak membuat karyawan terkena batasan kemenangan.
5. Belum memenangkan doorprize pada event berjalan apabila aturan mengharuskan satu karyawan hanya boleh menang satu kali.

### Contoh Aturan Cooldown

Jika aturan event menetapkan bahwa karyawan yang menang dalam 2 tahun terakhir tidak boleh mengikuti doorprize:

```text
Event tahun 2026

Last Winner Year 2025 → Tidak eligible
Last Winner Year 2024 → Tidak eligible
Last Winner Year 2023 → Eligible
Last Winner Year NULL → Eligible
```

Implementasi final batas tahun mengikuti aturan bisnis yang disepakati panitia.

---

# 16. Session Doorprize

Sistem mendukung beberapa session doorprize.

Contoh:

## Session 1

```text
Target: Semua karyawan
```

## Session 2

```text
Target: Semua karyawan
```

## Session 3

```text
Target: Kartap
Hadiah: Hadiah WOW
```

## Session 4

```text
Target: Kartap
Hadiah: Hadiah WOW
```

Aturan session dapat disesuaikan dengan kebutuhan event.

---

# 17. Aturan Pemenang

Ketika random draw dijalankan:

1. Sistem mengambil karyawan yang hadir.
2. Sistem menerapkan filter eligibility.
3. Sistem membentuk eligible pool.
4. Sistem memilih karyawan secara random.
5. Sistem menampilkan pemenang.
6. Sistem menyimpan pemenang ke `doorprize_winners`.
7. Jika aturan event melarang pemenang mendapatkan hadiah lagi, karyawan tersebut dikeluarkan dari pool berikutnya.

### Prinsip

```text
HADIR
+
ELIGIBLE
+
SESUAI SESSION
+
BELUM MENANG
=
CALON PEMENANG
```

---

# 18. Tiket dan Tanggungan

Data `tanggungan` dan `tiket` digunakan untuk kebutuhan data peserta acara.

Contoh:

```text
Nama        : Budi
Tanggungan  : 3
Tiket       : 4
```

Artinya:

```text
1 karyawan + 3 tanggungan = 4 tiket
```

Untuk doorprize, aturan default:

```text
1 karyawan = 1 entry pool
```

Jumlah tiket tidak otomatis memberikan beberapa kesempatan menang.

---

# 19. Dashboard Admin

Dashboard minimal menampilkan:

- Total karyawan.
- Total Kartap.
- Total Kontrak.
- Total tanggungan.
- Total tiket.
- Total sudah hadir.
- Total belum hadir.
- Persentase kehadiran.
- Total hadiah.
- Hadiah yang sudah diundi.
- Total pemenang.
- Jumlah eligible pool berdasarkan session.

---

# 20. Halaman Admin

## 20.1 Data Karyawan

Fitur:

- Menampilkan data karyawan.
- Tambah data.
- Edit data.
- Hapus data.
- Import Excel.
- Search berdasarkan NPK/nama.
- Filter status karyawan.
- Generate QR Code.

## 20.2 Attendance

Fitur:

- Scan QR Code.
- Menampilkan hasil scan.
- Menampilkan daftar hadir.
- Search NPK/nama.
- Filter status karyawan.
- Melihat waktu scan.

## 20.3 Hadiah

Fitur:

- Tambah hadiah.
- Edit hadiah.
- Hapus/nonaktifkan hadiah.
- Menentukan quantity.
- Menentukan session.
- Menentukan target status karyawan.

## 20.4 Doorprize

Fitur:

- Memilih session.
- Memilih hadiah.
- Menampilkan jumlah eligible pool.
- Menjalankan random draw.
- Menampilkan animasi/hasil random.
- Menampilkan pemenang.
- Menyimpan pemenang.
- Melihat pemenang yang sudah terpilih.

---

# 21. Struktur Relasi

```text
┌──────────────────┐
│    EMPLOYEES     │
├──────────────────┤
│ id               │
│ npk              │
│ name             │
│ tanggungan       │
│ tiket            │
│ status_karyawan  │
│ qr_code          │
│ last_winner_year │
└───────┬──────────┘
        │
        ├─────────────────────┐
        │                     │
        │                     │
        ▼                     ▼
┌──────────────────┐   ┌─────────────────────┐
│   ATTENDANCES    │   │ DOORPRIZE_WINNERS   │
├──────────────────┤   ├─────────────────────┤
│ id               │   │ id                  │
│ employee_id      │   │ employee_id         │
│ scanned_at       │   │ prize_id            │
│ scan_type        │   │ session             │
└──────────────────┘   │ won_at              │
                       └──────────┬──────────┘
                                  │
                                  ▼
                         ┌──────────────────┐
                         │      PRIZES      │
                         ├──────────────────┤
                         │ id               │
                         │ name             │
                         │ description      │
                         │ quantity         │
                         │ session          │
                         │ employee_type    │
                         │ is_active        │
                         └──────────────────┘
```

---

# 22. Migration Laravel

Urutan migration:

```text
1. create_employees_table
2. create_attendances_table
3. create_prizes_table
4. create_doorprize_winners_table
```

Foreign key:

```text
attendances.employee_id
        ↓
employees.id

doorprize_winners.employee_id
        ↓
employees.id

doorprize_winners.prize_id
        ↓
prizes.id
```

---

# 23. Acceptance Criteria

## Master Karyawan

- [ ] NPK wajib unik.
- [ ] Nama karyawan tersimpan.
- [ ] Data tanggungan tersimpan.
- [ ] Data tiket tersimpan.
- [ ] Status karyawan tersimpan.
- [ ] QR Code unik.
- [ ] Tahun terakhir menang dapat disimpan.
- [ ] `last_winner_year` dapat kosong untuk karyawan yang belum pernah menang.

## Attendance

- [ ] QR valid dapat melakukan absensi.
- [ ] QR invalid ditolak.
- [ ] Karyawan tidak dapat absen dua kali.
- [ ] Waktu scan tersimpan.
- [ ] Data attendance terhubung dengan karyawan.

## Hadiah

- [ ] Hadiah dapat ditambahkan.
- [ ] Quantity dapat ditentukan.
- [ ] Session dapat ditentukan.
- [ ] Target status karyawan dapat ditentukan.
- [ ] Hadiah dapat diaktifkan/nonaktifkan.

## Doorprize

- [ ] Hanya karyawan yang hadir yang dapat masuk pool.
- [ ] Karyawan yang terkena cooldown tidak masuk pool.
- [ ] Filter status karyawan berjalan sesuai session.
- [ ] Karyawan yang sudah menang dapat dikeluarkan dari pool berikutnya sesuai aturan.
- [ ] Random draw menghasilkan pemenang.
- [ ] Pemenang tersimpan di database.
- [ ] Hadiah yang sudah diberikan tercatat.
- [ ] Quantity hadiah tidak boleh melebihi jumlah yang tersedia.

---

# 24. Ringkasan Database Final

| No | Table | Fungsi |
|---:|---|---|
| 1 | `employees` | Master data karyawan + QR + tahun terakhir menang |
| 2 | `attendances` | Mencatat kehadiran hasil scan QR |
| 3 | `prizes` | Menyimpan daftar hadiah dan aturan session |
| 4 | `doorprize_winners` | Menyimpan hasil pengundian |

## Prinsip Sistem

```text
MASTER KARYAWAN
       ↓
    QR CODE
       ↓
    ABSENSI
       ↓
ELIGIBILITY FILTER
       ↓
 ELIGIBLE POOL
       ↓
  RANDOM DRAW
       ↓
   PEMENANG
```

Sistem dibuat sederhana untuk satu event dan tidak menggunakan tabel `events` maupun tabel khusus `pool_prizes`.
