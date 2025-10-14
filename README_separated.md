# ASETS API Data Fetcher - Separated Files

Script Python untuk mengambil data dari API ASETS, sekarang dipisah menjadi 2 file terpisah untuk kemudahan penggunaan.

## 📁 File yang Tersedia

### 1. `listings_fetcher.py` - Data Listings
- Mengambil data dari: `https://api.asets.id/api/listings`
- Database: `listings_data.db`
- Export: `listings_export.json`
- Log: `listings_fetch.log`

### 2. `spaces_fetcher.py` - Data Spaces  
- Mengambil data dari: `https://service.asets.id/api/space-available`
- Database: `spaces_data.db`
- Export: `spaces_export.json`
- Log: `spaces_fetch.log`

## 🚀 Cara Penggunaan

### Instalasi
```bash
pip install -r requirements.txt
```

### Menjalankan Script Listings
```bash
python listings_fetcher.py
```

### Menjalankan Script Spaces
```bash
python spaces_fetcher.py
```

### Menggunakan Class Secara Terpisah

#### Untuk Listings:
```python
from listings_fetcher import ListingsDataFetcher

fetcher = ListingsDataFetcher("my_listings.db")
fetcher.fetch_all_data()
stats = fetcher.get_database_stats()
fetcher.export_to_json("my_listings.json")
```

#### Untuk Spaces:
```python
from spaces_fetcher import SpacesDataFetcher

fetcher = SpacesDataFetcher("my_spaces.db")
fetcher.fetch_all_data()
stats = fetcher.get_database_stats()
fetcher.export_to_json("my_spaces.json")
```

## 📊 Fitur Masing-masing File

### Listings Fetcher:
- Mengambil semua data listings (SPBU, properti, dll)
- Statistik berdasarkan source dan kota
- Database terpisah untuk listings
- Export JSON khusus listings

### Spaces Fetcher:
- Mengambil semua data spaces (ruangan, lot, dll)
- Statistik berdasarkan tipe dan harga
- Analisis harga dan ukuran
- Database terpisah untuk spaces

## 🗄️ Struktur Database

### Listings Database (`listings_data.db`):
- `listings` - Data properti/SPBU
- `media` - Media untuk listings

### Spaces Database (`spaces_data.db`):
- `spaces` - Data ruangan/lot
- `media` - Media untuk spaces

## ✅ Keuntungan Pemisahan:

1. **Fleksibilitas** - Bisa menjalankan hanya salah satu
2. **Performance** - Database lebih kecil dan cepat
3. **Maintenance** - Lebih mudah di-maintain
4. **Resource** - Menggunakan resource lebih efisien
5. **Debugging** - Lebih mudah debug masalah spesifik

## 📝 Log Files:
- `listings_fetch.log` - Log untuk proses listings
- `spaces_fetch.log` - Log untuk proses spaces




