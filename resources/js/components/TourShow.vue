<script>
import axios from "axios";
import { inject, computed } from "vue";
export default {
    name: "HotelShow",
    setup() {
        const currentUser = inject('currentUser'); // получаем то, что provide дал в App.vue

        // Если в provide мы сделали reactive объект:
        const user = computed(() => currentUser.user);

        return { user };
    },
    data() {
        return {
            hotel: null,
            currentPhoto: 0,
            reviews: [],
            tour: null,
        };
    },

    methods: {
        getHotel(id) {
            axios.get(`http://localhost:8876/api/hotels/${this.$route.params.id}`)
                .then(res => {
                    this.hotel = res.data.data
                    console.log(res);
                })
        },
        getTour(id) {
            axios.get(`http://localhost:8876/api/tours/${this.$route.params.id}`)
                .then(res => {
                    this.tour = res.data.data
                    console.log(res);
                })
        },
        getReviews(page = 1) {
            axios
                .get(`http://localhost:8876/api/reviews/${this.$route.params.id}?page=${page}`)
                .then((res) => {
                    // предполагаем, что API возвращает Laravel пагинацию: data + meta
                    this.reviews.data = res.data.data;
                    this.reviews.meta = res.data.meta || {};
                });
        },
        nextPhoto() {
            if (!this.hotel || !this.hotel.photos.length) return;
            this.currentPhoto = (this.currentPhoto + 1) % this.hotel.photos.length;
        },
        prevPhoto() {
            if (!this.hotel || !this.hotel.photos.length) return;
            this.currentPhoto =
                (this.currentPhoto - 1 + this.hotel.photos.length) % this.hotel.photos.length;
        },
        scrollThumbnails(direction) {
            const container = this.$refs.thumbsContainer;
            const scrollAmount = 150;
            container.scrollBy({
                left: scrollAmount * direction,
                behavior: "smooth",
            });
        },
        nextRoomPhoto(room) {
            if (!room.photos || !room.photos.length) return;
            room.currentPhoto = ((room.currentPhoto || 0) + 1) % room.photos.length;
        },
        prevRoomPhoto(room) {
            if (!room.photos || !room.photos.length) return;
            room.currentPhoto = ((room.currentPhoto || 0) - 1 + room.photos.length) % room.photos.length;
        },
        scrollRoomThumbnails(room, direction) {
            const container = this.$refs['thumbs_' + room.id][0];
            const scrollAmount = 100;
            container.scrollBy({left: scrollAmount * direction, behavior: 'smooth'});
        },
    },

    mounted() {
        this.getHotel();
        this.getReviews();
        this.getTour()
    },
    computed: {
        rating() {
            if (!this.reviews.data || !this.reviews.data.length) return null;
            const sum = this.reviews.data.reduce((acc, r) => acc + r.rating, 0);
            return (sum / this.reviews.data.length).toFixed(1);
        }
    }
};
</script>

<template>
    <!-- Баннер -->
    <section
        class="about-banner relative"
        v-if="hotel"
        :style="{
      backgroundImage: `linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('/storage/${hotel.preview_image}')`
    }"
    >
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="about-content col-lg-12 text-center">
                    <h1 class="text-white">{{ hotel.name }}</h1>
                    <p class="text-white link-nav">
                        <router-link :to="{ name: 'index'}">Главная</router-link>
                        <span class="lnr lnr-arrow-right"></span>
                        <router-link to="/hotels"> Отели</router-link>
                        <span class="lnr lnr-arrow-right"></span>
                        <span>{{ hotel.name }}</span>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Основной блок: Галерея + Информация -->
    <section class="hotel-info section-gap" v-if="hotel">
        <div class="container">
            <div class="row align-items-start">
                <!-- Галерея -->
                <div class="col-lg-6 mb-4 mb-lg-0" v-if="hotel.photos && hotel.photos.length">
                    <div class="hotel-gallery">
                        <div class="main-photo">
                            <img
                                :src="`/storage/${hotel.photos[currentPhoto].file_path}`"
                                alt="hotel photo"
                            />
                            <button class="prev" @click="prevPhoto">&#10094;</button>
                            <button class="next" @click="nextPhoto">&#10095;</button>
                        </div>

                        <div class="thumbnails-wrapper" v-if="hotel.photos && hotel.photos.length">
                            <button class="scroll-btn left" @click="scrollThumbnails(-1)">&#10094;</button>
                            <div class="thumbnails" ref="thumbsContainer">
                                <img
                                    v-for="(photo, index) in hotel.photos"
                                    :key="photo.id"
                                    :src="`/storage/${photo.file_path}`"
                                    :class="{ active: currentPhoto === index }"
                                    @click="currentPhoto = index"
                                />
                            </div>
                            <button class="scroll-btn right" @click="scrollThumbnails(1)">&#10095;</button>
                        </div>
                    </div>
                </div>

                <!-- Информация -->
                <div class="col-lg-6">
                    <h2 class="hotel-title">{{ hotel.name }} <div class="hotel-rating mb-2">
                        <span v-for="n in hotel.stars" :key="'filled-'+n" class="fa fa-star checked"></span>
                        <span v-for="n in 5 - hotel.stars" :key="'empty-'+n" class="fa fa-star"></span>
                    </div></h2>
                    <div class="hotel-average-rating" v-if="rating">
                        <strong>Средний рейтинг:</strong> {{ rating }}/5
                    </div>
                    <p class="hotel-address">
                        <strong>Адрес:</strong> {{ hotel.address }}, {{ hotel.city.name }}, {{ hotel.country.name }}
                    </p>

                    <p class="hotel-price"><strong>Цена за ночь:</strong> от {{ hotel.min_price }} руб.</p>

                    <div class="hotel-amenities mb-3" v-if="hotel.amenities && hotel.amenities.length">
                        <strong>Удобства:</strong>
                        <div class="amenities-list">
              <span v-for="amenity in hotel.amenities" :key="amenity.id" class="amenity-item">
                {{ amenity.name }}
              </span>
                        </div>
                    </div>

                    <p class="hotel-description"><strong>Описание:</strong> {{ hotel.description }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Номера гостиницы -->
    <section class="hotel-rooms section-gap" v-if="hotel && hotel.room_types && hotel.room_types.length">
        <div class="container">
            <h3 class="rooms-title">Номера</h3>
            <div class="rooms-list">
                <div class="room-card" v-for="room in hotel.room_types" :key="room.id">
                    <!-- Галерея номера -->
                    <div class="room-gallery">
                        <div class="main-photo">
                            <img
                                :src="`/storage/${room.photos && room.photos.length ? room.photos[room.currentPhoto || 0].file_path : room.preview_image}`"
                                :alt="room.name"
                            />
                            <button class="prev" @click="prevRoomPhoto(room)">&#10094;</button>
                            <button class="next" @click="nextRoomPhoto(room)">&#10095;</button>
                        </div>
                        <div class="thumbnails-wrapper" v-if="room.photos && room.photos.length">
                            <div class="thumbnails" :ref="'thumbs_' + room.id">
                                <img
                                    v-for="(photo, index) in room.photos"
                                    :key="photo.id"
                                    :src="`/storage/${photo.file_path}`"
                                    :class="{ active: (room.currentPhoto || 0) === index }"
                                    @click="room.currentPhoto = index"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Информация о номере -->
                    <div class="room-info">
                        <h4 class="room-name">{{ room.name }}</h4>
                        <p v-if="room.price === hotel.min_price" class="room-price">
                            <strong>Цена:</strong> {{ tour ? tour.price : '-' }} руб. {{ tour ? tour.days : '-' }} ночей
                        </p>
                        <p v-else class="room-price">
                            <strong>Цена:</strong> {{ tour ? tour.price + (room.price - hotel.min_price) * tour.days : '-' }} руб. за {{ tour ? tour.days : '-' }} ночей
                        </p>
                        <p class="room-detail"><strong>Кровать:</strong> {{ room.bed_type }}</p>
                        <p class="room-detail"><strong>Вместимость:</strong> {{ room.capacity }} чел.</p>
                        <p class="room-detail"><strong>Площадь:</strong> {{ room.size_sqm }} кв.м.</p>
                        <p class="room-description" v-if="room.description">{{ room.description }}</p>
                        <div v-if="user" class="room-buttons">
                            <a class="primary-btn">Забронировать</a>
                        </div>
                        <div v-else>
                            <p>Для бронирования войдите в аккаунт</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Отзывы -->
    <section class="hotel-reviews section-gap" v-if="reviews.data && reviews.data.length">
        <div class="container">
            <h3 class="reviews-title">Отзывы гостей</h3>

            <div class="reviews-list">
                <div class="review-card" v-for="review in reviews.data" :key="review.id">
                    <div class="review-header">
                        <div class="review-user">
                            <strong>{{ review.user?.name }} {{ review.user?.surname }}</strong>
                        </div>
                        <div class="review-rating">
                            <span v-for="n in review.rating" :key="n" class="fa fa-star checked"></span>
                            <span v-for="n in 5 - review.rating" :key="'empty-' + n" class="fa fa-star"></span>
                        </div>
                    </div>
                    <h4 class="review-title">{{ review.title }}</h4>
                    <p class="review-comment">{{ review.comment }}</p>
                </div>
            </div>

            <!-- Пагинация -->
            <div class="reviews-pagination" v-if="reviews.meta && reviews.meta.last_page > 1">
                <button
                    class="page-btn"
                    :disabled="reviews.meta.current_page === 1"
                    @click="getReviews(reviews.meta.current_page - 1)"
                >
                    &#10094; Назад
                </button>
                <span>Страница {{ reviews.meta.current_page }} из {{ reviews.meta.last_page }}</span>
                <button
                    class="page-btn"
                    :disabled="reviews.meta.current_page === reviews.meta.last_page"
                    @click="getReviews(reviews.meta.current_page + 1)"
                >
                    Вперед &#10095;
                </button>
            </div>
        </div>
    </section>
</template>

<style scoped>
/*-------- Отзывы ---- */
.hotel-reviews {
    padding: 50px 0;
    background-color: #f7f7f7;
}

.reviews-title {
    font-size: 32px;
    font-weight: 700;
    text-align: center;
    margin-bottom: 40px;
    color: #333;
}

.reviews-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.review-card {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s, box-shadow 0.3s;
}

.review-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.review-user {
    font-weight: 600;
    color: #333;
}

.review-rating .fa-star {
    font-size: 14px;
    margin-right: 2px;
}

.checked {
    color: #faab34;
}

.review-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #444;
}

.review-comment {
    font-size: 15px;
    color: #555;
    line-height: 1.6;
}

.reviews-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
    margin-top: 30px;
}

.page-btn {
    background-color: #faab34;
    color: #fff;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
}

.page-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.page-btn:hover:not(:disabled) {
    background-color: #e6992e;
}

/* ------------------ Баннер ------------------ */
.about-banner {
    height: 350px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    background-size: cover;
    background-position: center;
}

.overlay-bg {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
}

/* ------------------ Основная информация ------------------ */
.hotel-info {
    padding-top: 50px;
}

.hotel-title {
    font-size: 30px;
    font-weight: 700;
    margin-bottom: 15px;
}

.hotel-address,
.hotel-price {
    font-size: 16px;
    color: #555;
    margin-bottom: 10px;
}

.hotel-rating .fa-star {
    font-size: 16px;
    margin-right: 2px;
}

.checked {
    color: #faab34;
}

.hotel-amenities {
    margin-top: 15px;
}

.amenities-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 5px;
}

.amenity-item {
    background-color: #f3f3f3;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 14px;
    color: #333;
    transition: all 0.3s;
}

.amenity-item:hover {
    background-color: #faab34;
    color: #fff;
}

.hotel-description {
    margin-top: 20px;
    color: #444;
    line-height: 1.6;
}

/* ------------------ Галерея ------------------ */
.hotel-gallery, .room-gallery {
    border-radius: 12px;
    overflow: hidden;
}

.main-photo {
    position: relative;
}

.main-photo img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 12px;
    transition: transform 0.3s;
}

.main-photo img:hover {
    transform: scale(1.05);
}

.main-photo button.prev,
.main-photo button.next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(0, 0, 0, 0.4);
    color: white;
    border: none;
    font-size: 28px;
    padding: 8px 14px;
    cursor: pointer;
    border-radius: 8px;
}

.main-photo button.prev {
    left: 5px;
}

.main-photo button.next {
    right: 5px;
}

.thumbnails-wrapper {
    display: flex;
    align-items: center;
    margin-top: 10px;
}

.thumbnails {
    display: flex;
    overflow-x: auto;
    gap: 8px;
    scroll-behavior: smooth;
    flex: 1;
    padding: 5px 0;
}

.thumbnails::-webkit-scrollbar {
    height: 8px;
}

.thumbnails::-webkit-scrollbar-thumb {
    background-color: #faab34;
    border-radius: 4px;
}

.thumbnails img {
    width: 70px;
    height: 55px;
    object-fit: cover;
    border-radius: 6px;
    opacity: 0.7;
    cursor: pointer;
    transition: all 0.3s;
}

.thumbnails img.active,
.thumbnails img:hover {
    opacity: 1;
    border: 2px solid #faab34;
}

.scroll-btn {
    background: rgba(0, 0, 0, 0.3);
    border: none;
    color: white;
    font-size: 22px;
    cursor: pointer;
    border-radius: 6px;
    padding: 5px 10px;
    flex-shrink: 0;
}

.scroll-btn:hover {
    background: rgba(0, 0, 0, 0.6);
}

/* ------------------ Карточки номеров ------------------ */
.hotel-rooms {
    background-color: #f7f7f7;
    padding: 50px 0;
}

.rooms-title {
    font-size: 32px;
    font-weight: 700;
    text-align: center;
    margin-bottom: 40px;
    color: #333;
}

.rooms-list {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.room-card {
    display: flex;
    flex-wrap: wrap;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s, box-shadow 0.3s;
}

.room-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
}

.room-gallery {
    flex: 1 1 250px;
    min-width: 250px;
    position: relative;
}

.room-gallery .main-photo img {
    height: 200px;
}

.room-info {
    flex: 2 1 300px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.room-name {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
}

.room-price,
.room-detail {
    font-size: 16px;
    color: #555;
    margin-bottom: 6px;
}

.room-description {
    font-size: 14px;
    color: #666;
    line-height: 1.5;
    margin-top: 10px;
}

.room-buttons {
    margin-top: 15px;
    display: flex;
    gap: 10px;
}

.btn-details, .btn-book {
    display: inline-block;
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-details {
    background-color: #faab34;
    color: #fff;
}

.btn-details:hover {
    background-color: #e6992e;
}

.btn-book {
    background-color: #28a745;
    color: #fff;
}

.btn-book:hover {
    background-color: #218838;
}

/* ------------------ Адаптив ------------------ */
@media (max-width: 992px) {
    .room-card {
        flex-direction: column;
    }

    .room-gallery, .room-info {
        flex: 1 1 100%;
    }

    .room-gallery .main-photo img {
        height: 200px;
    }
}

@media (max-width: 576px) {
    .room-gallery .main-photo img {
        height: 160px;
    }

    .thumbnails img {
        width: 45px;
        height: 35px;
    }
}
.hotel-average-rating {
    margin-bottom: 15px;
    font-size: 16px;
    color: #333;
}
.hotel-average-rating .fa-star,
.hotel-average-rating .fa-star-half-alt {
    color: #faab34;
    margin-left: 2px;
}
</style>

