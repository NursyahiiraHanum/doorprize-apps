<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PrizeSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan Foreign Key Check sementara
        Schema::disableForeignKeyConstraints();

        // Kosongkan tabel pemenang (jika ada) dan tabel prizes
        DB::table('doorprize_winners')->truncate();
        DB::table('prizes')->truncate();

        // Aktifkan kembali Foreign Key Check
        Schema::enableForeignKeyConstraints();

        $prizes = [
            // ================= SESI 1 (25 Item) =================
            ['name' => 'Kipas Angin Stand Fan Miyako', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Blender Philips Glass 2L', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Rice Cooker Yong Ma Digital 2L', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Setrika Uap Tefal', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Electric Kettle Oxone 1.7L', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Air Fryer LocknLock 3.5L', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Sandwich Maker Sharp', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Stand Mixer Cosmos 3L', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Speaker Bluetooth JBL GO 3', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Powerbank Anker 20.000mAh', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'TWS Earphones Redmi Buds 4', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Smartwatch Haylou Solar Plus', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Hair Dryer Panasonic Ionity', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Dispenser Hot & Cold Cosmos', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Kompor Gas Portable Rinnai', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Chopper Mitochiba CH-200', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Coffee Maker Drip Electrolux', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Toaster Roti Advance', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Slow Juicer Kirin', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Handy Vacuum Cleaner Deerma', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Lampu Meja LED Smart Xiaomi', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Electric Grill Pan Han River', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Humidifier Smart Bardi 4L', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Timbangan Badan Digital Omron', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],
            ['name' => 'Voucher Belanja Rp 500.000', 'quantity' => 1, 'sesi' => 1, 'employee_status' => 'ALL'],

            // ================= SESI 2 (25 Item) =================
            ['name' => 'Smart TV LG 32 Inch HD', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Kulkas 1 Pintu Sharp 166L', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Mesin Cuci Top Load Polytron 8kg', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Microwave Grill Samsung 23L', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Tablet Samsung Galaxy Tab A9', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Sepeda Lipat Polygon Urbano 3', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Soundbar Sony 2.1 Ch Subwoofer', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Air Purifier Xiaomi 4 Compact', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Robot Vacuum Cleaner Ecovacs', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Oven Listrik Kirin 33L', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Smart TV Coocaa 43 Inch 4K', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Apple Watch SE Gen 2', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Logam Mulia Antam 2 Gram', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Logam Mulia Antam 3 Gram', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Smart Monitor Samsung 27 Inch', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Kamera Pocket Canon Ixus', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Home Theater Polytron 5.1', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Water Heater Ariston 15L', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Espresso Machine Delonghi', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Sepeda Gunung Pacific 26 Inch', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Kulkas Portable Aqua 50L', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Mesin Cuci Front Load Sharp 7kg', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Proyektor Mini Portable Wanbo', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Garmin Instinct Smartwatch', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],
            ['name' => 'Nintendo Switch Lite', 'quantity' => 1, 'sesi' => 2, 'employee_status' => 'PERMANENT'],

            // ================= SESI 3 / GRANDPRIZE (25 Item) =================
            ['name' => 'Honda Vario 125 CBS ISS', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Yamaha NMAX 155 Connected', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Honda BeAT Street', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'iPhone 15 Pro 128GB', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Samsung Galaxy S24 Ultra', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'iPad Air M2 11 Inch 128GB', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Laptop Asus ROG Strix G16', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'MacBook Air M2 256GB', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Smart TV Samsung 65 Inch 4K', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Smart TV LG 55 Inch OLED', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Kulkas Side by Side LG 508L', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Logam Mulia Antam 10 Gram', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Logam Mulia Antam 25 Gram', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'PlayStation 5 Slim Disc Edition', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Sepeda Listrik Viar Uno', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Motor Listrik Gesits G1', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'AC Split Daikin 1.5 PK Inverter', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Drone DJI Mini 4 Pro Fly More', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Kamera Mirrorless Sony A6700', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Vespa LX 125 I-Get', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Laptop Lenovo Legion Slim 5', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Samsung Galaxy Z Fold 5', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Logam Mulia Antam 50 Gram', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Vespa Sprint S 150', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
            ['name' => 'Honda PCX 160 ABS', 'quantity' => 1, 'sesi' => 3, 'employee_status' => 'ALL'],
        ];

        foreach ($prizes as $prize) {
            DB::table('prizes')->insert([
                'name' => $prize['name'],
                'quantity' => $prize['quantity'],
                'sesi' => $prize['sesi'],
                'employee_status' => $prize['employee_status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}