#!/bin/bash

# Оптимизация размеров фотографий
echo "🖼️  Оптимизирую фотографии..."
echo "Исходный размер: $(du -sh storage/app/public/photos | cut -f1)"

# Проверяем наличие imagemagick
if ! command -v convert &> /dev/null; then
    echo "❌ ImageMagick не установлен"
    echo "Установка ImageMagick..."
    sudo apt-get update && sudo apt-get install -y imagemagick
fi

PHOTOS_DIR="storage/app/public/photos"
PROCESSED=0

# Обрабатываем все JPG/JPEG файлы
find "$PHOTOS_DIR" -type f \( -iname "*.jpg" -o -iname "*.jpeg" \) | while read FILE; do
    # Сжимаем и переконвертируем в WebP для лучшего сжатия
    # Опции:
    # -quality 75 = качество 75% (хорошее соотношение размер/качество)
    # -resize 1200x800 = максимальный размер 1200x800
    # -strip = удаляем метаданные EXIF (экономим место)
    
    TEMP_FILE="${FILE}.tmp"
    
    # Конвертируем и оптимизируем
    convert "$FILE" \
        -quality 75 \
        -resize 1200x800\> \
        -strip \
        "$TEMP_FILE"
    
    if [ -f "$TEMP_FILE" ]; then
        mv "$TEMP_FILE" "$FILE"
        PROCESSED=$((PROCESSED + 1))
        
        if [ $((PROCESSED % 20)) -eq 0 ]; then
            echo "✓ Обработано $PROCESSED фото"
        fi
    fi
done

echo ""
echo "✅ Оптимизация завершена!"
echo "Конечный размер: $(du -sh storage/app/public/photos | cut -f1)"
