<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Photo;
use App\Models\RoomType;
use App\Models\Hotel;

class DistributePhotos extends Command
{
    protected $signature = 'photos:distribute';
    protected $description = 'Распределить фотографии по отелям и номерам';

    public function handle()
    {
        $sourceDir = base_path('photos');
        $disk = Storage::disk('public');
        
        if (!is_dir($sourceDir)) {
            $this->error("❌ Папка {$sourceDir} не найдена!");
            return 1;
        }

        // Получаем все JPEG файлы
        $sourcePhotos = glob("$sourceDir/*.jpg");
        if (empty($sourcePhotos)) {
            $this->error("❌ Фотографии не найдены в папке!");
            return 1;
        }

        $this->info("📸 Найдено фотографий: " . count($sourcePhotos));

        // Получаем все отели и номера
        $hotels = Hotel::all();
        $roomTypes = RoomType::all();
        
        if ($hotels->isEmpty()) {
            $this->error("❌ Отелей не найдено в БД!");
            return 1;
        }

        if ($roomTypes->isEmpty()) {
            $this->error("❌ Типы номеров не найдены в БД!");
            return 1;
        }

        $this->info("🏨 Отелей: " . $hotels->count());
        $this->info("🚪 Типов номеров: " . $roomTypes->count());

        $photoIndex = 0;
        $totalCopied = 0;

        // ==========================================
        // 1. Раскладываем фото НОМЕРОВ
        // ==========================================
        $this->info("\n🔄 Распределяю фото номеров...");

        foreach ($roomTypes as $roomType) {
            // По 5 фото на каждый тип номера
            for ($i = 1; $i <= 5; $i++) {
                if ($photoIndex >= count($sourcePhotos)) {
                    $photoIndex = 0; // Циклим фото если не хватает
                }

                $sourceFile = $sourcePhotos[$photoIndex];
                $hotelId = $roomType->hotel_id;
                $destPath = "photos/hotel-{$hotelId}/room-type-{$roomType->id}/photo-{$i}.jpg";

                try {
                    // Читаем исходный файл
                    $content = file_get_contents($sourceFile);
                    
                    // Сохраняем в public storage
                    $disk->put($destPath, $content);
                    
                    // Создаем запись в БД
                    Photo::create([
                        'hotel_id' => $hotelId,
                        'room_type_id' => $roomType->id,
                        'file_path' => '/storage/' . $destPath,
                    ]);

                    $totalCopied++;
                    $photoIndex++;
                    
                    if ($totalCopied % 10 == 0) {
                        $this->line("✓ Обработано {$totalCopied} фото номеров");
                    }
                } catch (\Exception $e) {
                    $this->warn("⚠ Ошибка при копировании: {$e->getMessage()}");
                }
            }

            // Обновляем preview_image для типа номера
            $previewPath = "/storage/photos/hotel-{$roomType->hotel_id}/room-type-{$roomType->id}/photo-1.jpg";
            $roomType->update(['preview_image' => $previewPath]);
        }

        $this->info("✓ Фото номеров распределены: {$totalCopied}");

        // ==========================================
        // 2. Раскладываем фото ОТЕЛЕЙ
        // ==========================================
        $this->info("\n🔄 Распределяю фото отелей...");
        $hotelPhotosCount = 0;

        foreach ($hotels as $hotel) {
            // По 5 фото на каждый отель
            for ($i = 1; $i <= 5; $i++) {
                if ($photoIndex >= count($sourcePhotos)) {
                    $photoIndex = 0;
                }

                $sourceFile = $sourcePhotos[$photoIndex];
                $destPath = "photos/hotel-{$hotel->id}/hotel-photo-{$i}.jpg";

                try {
                    $content = file_get_contents($sourceFile);
                    $disk->put($destPath, $content);
                    
                    Photo::create([
                        'hotel_id' => $hotel->id,
                        'room_type_id' => null,
                        'file_path' => '/storage/' . $destPath,
                    ]);

                    $hotelPhotosCount++;
                    $photoIndex++;

                    if ($hotelPhotosCount % 10 == 0) {
                        $this->line("✓ Обработано {$hotelPhotosCount} фото отелей");
                    }
                } catch (\Exception $e) {
                    $this->warn("⚠ Ошибка: {$e->getMessage()}");
                }
            }

            // Обновляем preview_image для отеля
            $previewPath = "/storage/photos/hotel-{$hotel->id}/hotel-photo-1.jpg";
            $hotel->update(['preview_image' => $previewPath]);
        }

        $this->info("✓ Фото отелей распределены: {$hotelPhotosCount}");

        // ==========================================
        // 3. Итоги
        // ==========================================
        $this->info("\n" . str_repeat("=", 50));
        $this->info("✅ ГОТОВО!");
        $this->info("📊 Статистика:");
        $this->info("  • Фото номеров: {$totalCopied}");
        $this->info("  • Фото отелей: {$hotelPhotosCount}");
        $this->info("  • Всего фото в БД: " . Photo::count());
        $this->info("\n💾 Фото хранятся в: storage/app/public/photos/");
        $this->info("🌐 Доступ из браузера: /storage/photos/...");

        // Очистка исходной папки
        $this->info("\n🧹 Очищаю исходную папку photos/...");
        foreach ($sourcePhotos as $file) {
            @unlink($file);
        }
        @rmdir($sourceDir);
        $this->info("✓ Исходные файлы удалены");

        return 0;
    }
}
