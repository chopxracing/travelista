#!/bin/bash

DEST="/home/chopx/projects/travelista/storage/app/public/photos"
SOURCE="/home/chopx/projects/travelista/photos"

mkdir -p "$DEST"

echo "📸 Копирую фотографии..."
echo "Источник: $SOURCE"
echo "Назначение: $DEST"
echo ""

# Счетчик
PHOTO_NUM=0

# Копируем в папки отелей и номеров
# По 5 фото на каждый тип номера, циклим если не хватает

TOTAL_PHOTOS=$(ls "$SOURCE"/*.jpg | wc -l)
echo "Всего фото: $TOTAL_PHOTOS"

# Для каждого отеля (1-11)
for HOTEL_ID in {1..11}; do
    # Для каждого типа номера в отеле (предположим, по 2-3 на отель)
    for ROOM_TYPE_ID in {1..3}; do
        ROOM_DIR="$DEST/hotel-$HOTEL_ID/room-type-$ROOM_TYPE_ID"
        mkdir -p "$ROOM_DIR"
        
        # По 5 фото на каждый тип номера
        for i in {1..5}; do
            SRC_FILE=$(ls "$SOURCE"/*.jpg | head -n $((PHOTO_NUM % TOTAL_PHOTOS + 1)) | tail -n 1)
            if [ -f "$SRC_FILE" ]; then
                cp "$SRC_FILE" "$ROOM_DIR/photo-$i.jpg" 2>/dev/null
                echo -ne "\r✓ Обработано: $((PHOTO_NUM + 1)) фото"
                PHOTO_NUM=$((PHOTO_NUM + 1))
            fi
        done
    done
    
    # По 5 фото на каждый отель
    HOTEL_DIR="$DEST/hotel-$HOTEL_ID"
    mkdir -p "$HOTEL_DIR"
    for i in {1..5}; do
        SRC_FILE=$(ls "$SOURCE"/*.jpg | head -n $((PHOTO_NUM % TOTAL_PHOTOS + 1)) | tail -n 1)
        if [ -f "$SRC_FILE" ]; then
            cp "$SRC_FILE" "$HOTEL_DIR/hotel-photo-$i.jpg" 2>/dev/null
            echo -ne "\r✓ Обработано: $((PHOTO_NUM + 1)) фото"
            PHOTO_NUM=$((PHOTO_NUM + 1))
        fi
    done
done

echo ""
echo ""
echo "✅ ГОТОВО!"
echo "✓ Скопировано $PHOTO_NUM фото"
echo ""
