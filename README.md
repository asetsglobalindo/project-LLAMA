# ASETS API Data Fetcher

Script Python untuk mengambil data dari API ASETS dan menyimpannya ke database SQLite.

## Fitur

- Mengambil data dari kedua API endpoint:
  - `https://api.asets.id/api/listings` (data listings)
  - `https://service.asets.id/api/space-available` (data spaces)
- Menyimpan data ke database SQLite dengan struktur yang terorganisir
- Mengambil semua halaman data secara otomatis
- Export data ke format JSON
- Logging untuk monitoring proses
- Statistik data yang tersimpan

## Instalasi

1. Install dependencies:
```bash
pip install -r requirements.txt
```

## Penggunaan

### Menjalankan Script Lengkap
```bash
python api_data_fetcher.py
```

### Menggunakan Class Secara Terpisah
```python
from api_data_fetcher import AsetsDataFetcher

# Initialize fetcher
fetcher = AsetsDataFetcher("my_database.db")

# Fetch semua data
fetcher.fetch_all_data()

# Lihat statistik
stats = fetcher.get_database_stats()
print(f"Total listings: {stats['listings_count']}")
print(f"Total spaces: {stats['spaces_count']}")

# Export ke JSON
fetcher.export_to_json("export.json")
```

## Struktur Database

### Tabel `listings`
- `id`: ID unik listing
- `name`: Nama listing
- `slug`: URL slug
- `spaces`: Jumlah spaces
- `address`: Alamat lengkap
- `city_id`, `city_name`: Data kota
- `area_id`, `area_name`: Data area
- `source`: Sumber data (pms/private_sector)
- `created_at`, `updated_at`: Timestamp

### Tabel `spaces`
- `id`: ID unik space
- `code`: Kode space
- `name`: Nama space
- `type`: Tipe space (RUANGAN, LOT OUTDOOR, dll)
- `price`: Harga
- `price_type`: Tipe harga (sqm)
- `size_sqm`: Ukuran dalam meter persegi
- `min_period`: Periode minimum
- `listing_id`: ID listing terkait
- `listing_address`, `listing_city`: Data listing
- `source`: Sumber data
- `created_at`, `updated_at`: Timestamp

### Tabel `media`
- `id`: ID unik media
- `file`: Nama file
- `url`: URL media
- `listing_id`: ID listing (nullable)
- `space_id`: ID space (nullable)
- `created_at`: Timestamp

## Output

Script akan menghasilkan:
1. Database SQLite (`asets_data.db`)
2. File log (`api_fetch.log`)
3. File export JSON (`asets_data_export.json`)
4. Statistik data di console

## Catatan

- Script akan mengambil semua halaman data secara otomatis
- Ada delay 0.5 detik antar request untuk menghormati API
- Data akan di-update jika sudah ada (INSERT OR REPLACE)
- Logging akan mencatat semua aktivitas dan error