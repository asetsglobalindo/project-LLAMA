# AVIS (Asets Virtual Intelligence)

AVIS adalah asisten pencarian lokasi usaha dan manajemen properti berbasis LLaMA yang menggabungkan data internal (lokasi, ruangan, harga sewa, tenant) dengan sumber data publik/API gratis untuk membangun platform pencarian dan manajemen commercial spaces satu pintu.


## Cara Menjalankan

```bash
npm install
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

## Demo Video

Link demo: https://drive.google.com/drive/folders/1g_tJyotmw9nViW1kfJjY8HXAucOnIT5i?usp=sharing

