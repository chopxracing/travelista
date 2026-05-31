#!/usr/bin/php
<?php

// Простое распределение фото БЕЗ Laravel
// Подключаемся напрямую к БД

$dbHost = 'db';
$dbUser = 'root';
$dbPass = 'root';
$dbName = 'travelista';

echo "📸 РАСПРЕДЕЛЕНИЕ ФОТОГРАФИЙ\n";
echo str_repeat("=", 50) . "\n\n";

// Подключение к БД
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Подключение к БД успешно\n";
} catch (PDOException $e) {
    echo "❌ Ошибка подключения: " . $e->getMessage() . "\n";
    exit(1);
}

// Проверяем исходную папку
$sourceDir = __DIR__ . '/photos';
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

// Получаем данные из БД
$hotels = $pdo->query("SELECT * FROM hotels ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
$roomTypes = $pdo->query("SELECT * FROM room_types ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);

echo "✓ Отелей: " . count($hotels) . "\n";
echo "✓ Типов номеров: " . count($roomTypes) . "\n\n";

$photoIndex = 0;
$totalInserted = 0;

// Папка для хранения
$storagePath = __DIR__ . '/storage/app/public/photos';

// ==========================================
// 1. ФОТО НОМЕРОВ
// ==========================================
echo "🔄 Распределяю фото номеров...\n";

foreach ($roomTypes as $roomType) {
    for ($i = 1; $i <= 5; $i++) {
        if ($photoIndex >= count($sourcePhotos)) {
            $photoIndex = 0;
        }

        $sourceFile = $sourcePhotos[$photoIndex];
        $hotelId = $roomType['hotel_id'];
        $roomTypeId = $roomType['id'];
        
        $destDir = "$storagePath/hotel-{$hotelId}/room-type-{$roomTypeId}";
        $destFile = "$destDir/photo-{$i}.jpg";

        try {
            // Создаем директорию
            @mkdir($destDir, 0755, true);
            
            // Копируем файл
            if (copy($sourceFile, $destFile)) {
                $destPath = "/storage/photos/hotel-{$hotelId}/room-type-{$roomTypeId}/photo-{$i}.jpg";
                
                $stmt = $pdo->prepare("
                    INSERT INTO photos (hotel_id, room_type_id, file_path, created_at, updated_at)
                    VALUES (?, ?, ?, NOW(), NOW())
                ");
                $stmt->execute([$hotelId, $roomTypeId, $destPath]);
                
                $totalInserted++;
                $photoIndex++;
                
                if ($totalInserted % 20 == 0) {
                    echo "✓ Обработано {$totalInserted} фото\n";
                }
            }
        } catch (\Exception $e) {
            echo "⚠ Ошибка: " . $e->getMessage() . "\n";
        }
    }

    // Preview для номера
    $previewPath = "/storage/photos/hotel-{$roomType['hotel_id']}/room-type-{$roomType['id']}/photo-1.jpg";
    $stmt = $pdo->prepare("UPDATE room_types SET preview_image = ? WHERE id = ?");
    $stmt->execute([$previewPath, $roomType['id']]);
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
        $hotelId = $hotel['id'];
        
        $destDir = "$storagePath/hotel-{$hotelId}";
        $destFile = "$destDir/hotel-photo-{$i}.jpg";

        try {
            @mkdir($destDir, 0755, true);
            
            if (copy($sourceFile, $destFile)) {
                $destPath = "/storage/photos/hotel-{$hotelId}/hotel-photo-{$i}.jpg";
                
                $stmt = $pdo->prepare("
                    INSERT INTO photos (hotel_id, room_type_id, file_path, created_at, updated_at)
                    VALUES (?, NULL, ?, NOW(), NOW())
                ");
                $stmt->execute([$hotelId, $destPath]);
                
                $hotelPhotosCount++;
                $photoIndex++;

                if ($hotelPhotosCount % 20 == 0) {
                    echo "✓ Обработано {$hotelPhotosCount} фото отелей\n";
                }
            }
        } catch (\Exception $e) {
            echo "⚠ Ошибка: " . $e->getMessage() . "\n";
        }
    }

    // Preview для отеля
    $previewPath = "/storage/photos/hotel-{$hotelId}/hotel-photo-1.jpg";
    $stmt = $pdo->prepare("UPDATE hotels SET preview_image = ? WHERE id = ?");
    $stmt->execute([$previewPath, $hotelId]);
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

$result = $pdo->query("SELECT COUNT(*) as count FROM photos")->fetch(PDO::FETCH_ASSOC);
echo "  • Всего в БД: " . $result['count'] . "\n";

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
