#!/usr/bin/php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

// ==========================================
// РАСПРЕДЕЛЕНИЕ ФОТОГРАФИЙ ПО ОТЕЛЯМ И НОМЕРАМ
// ==========================================

$sourceDir = __DIR__ . '/photos';
$disk = Storage::disk('public');

echo "📸 РАСПРЕДЕЛЕНИЕ ФОТОГРАФИЙ\n";
echo str_repeat("=", 50) . "\n\n";

// Проверяем источник
if (!is_dir($sourceDir)) {
    echo "❌ Папка {$sourceDir} не найдена!\n";
    exit(1);
}

$sourcePhotos = glob("$sourceDir/*.jpg");
if (empty($sourcePhotos)) {
    echo "❌ Фотографии не найдены!\n";
    exit(1);
}

sort($sourcePhotos);
echo "✓ Найдено фотографий: " . count($sourcePhotos) . "\n\n";

// Получаем отели и типы номеров
$hotels = DB::table('hotels')->get();
$roomTypes = DB::table('room_types')->get();

echo "✓ Отелей: " . $hotels->count() . "\n";
echo "✓ Типов номеров: " . $roomTypes->count() . "\n\n";

$photoIndex = 0;
$totalInserted = 0;

// ==========================================
// 1. ФОТ НОМЕРОВ
// ==========================================
echo "🔄 Распределяю фото номеров...\n";

foreach ($roomTypes as $roomType) {
    for ($i = 1; $i <= 5; $i++) {
        if ($photoIndex >= count($sourcePhotos)) {
            $photoIndex = 0;
        }

        $sourceFile = $sourcePhotos[$photoIndex];
        $hotelId = $roomType->hotel_id;
        $destPath = "photos/hotel-{$hotelId}/room-type-{$roomType->id}/photo-{$i}.jpg";

        try {
            $content = file_get_contents($sourceFile);
            $disk->put($destPath, $content);
            
            DB::table('photos')->insert([
                'hotel_id' => $hotelId,
                'room_type_id' => $roomType->id,
                'file_path' => '/storage/' . $destPath,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $totalInserted++;
            $photoIndex++;
            
            if ($totalInserted % 20 == 0) {
                echo "✓ Обработано {$totalInserted} фото\n";
            }
        } catch (\Exception $e) {
            echo "⚠ Ошибка: " . $e->getMessage() . "\n";
        }
    }

    // Preview для номера
    $previewPath = "/storage/photos/hotel-{$roomType->hotel_id}/room-type-{$roomType->id}/photo-1.jpg";
    DB::table('room_types')->where('id', $roomType->id)->update(['preview_image' => $previewPath]);
}

echo "✓ Фото номеров: {$totalInserted}\n\n";

// ==========================================
// 2. ФОТО ОТЕЛЕЙ
// ==========================================
echo "🔄 Распределяю фото отелей...\n";
$hotelPhotosCount = 0;

foreach ($hotels as $hotel) {
    for ($i = 1; $i <= 5; $i++) {
        if ($photoIndex >= count($sourcePhotos)) {
            $photoIndex = 0;
        }

        $sourceFile = $sourcePhotos[$photoIndex];
        $destPath = "photos/hotel-{$hotel->id}/hotel-photo-{$i}.jpg";

        try {
            $content = file_get_contents($sourceFile);
            $disk->put($destPath, $content);
            
            DB::table('photos')->insert([
                'hotel_id' => $hotel->id,
                'room_type_id' => null,
                'file_path' => '/storage/' . $destPath,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $hotelPhotosCount++;
            $photoIndex++;

            if ($hotelPhotosCount % 20 == 0) {
                echo "✓ Обработано {$hotelPhotosCount} фото отелей\n";
            }
        } catch (\Exception $e) {
            echo "⚠ Ошибка: " . $e->getMessage() . "\n";
        }
    }

    // Preview для отеля
    $previewPath = "/storage/photos/hotel-{$hotel->id}/hotel-photo-1.jpg";
    DB::table('hotels')->where('id', $hotel->id)->update(['preview_image' => $previewPath]);
}

echo "✓ Фото отелей: {$hotelPhotosCount}\n\n";

// ==========================================
// ИТОГИ
// ==========================================
echo str_repeat("=", 50) . "\n";
echo "✅ ГОТОВО!\n";
echo "📊 Статистика:\n";
echo "  • Фото номеров: {$totalInserted}\n";
echo "  • Фото отелей: {$hotelPhotosCount}\n";
$totalPhotos = DB::table('photos')->count();
echo "  • Всего в БД: {$totalPhotos}\n";
echo "\n💾 Путь: storage/app/public/photos/\n";
echo "🌐 URL: /storage/photos/...\n\n";

// Очистка
echo "🧹 Удаляю исходные файлы...\n";
foreach ($sourcePhotos as $file) {
    @unlink($file);
}
@rmdir($sourceDir);
echo "✓ Очистка завершена\n";

echo "\n✨ Фотографии готовы к использованию!\n";
