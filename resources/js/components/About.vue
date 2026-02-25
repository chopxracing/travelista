<script>
import {Swiper, SwiperSlide} from "swiper/vue";

export default {
    name: "About",
    components: {SwiperSlide, Swiper}
}
</script>

<template>
<div>
    <section class="about-banner relative">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="about-content col-lg-12">
                    <h1 class="text-white">
                        О нас
                    </h1>
                    <p class="text-white link-nav"><router-link to="/">Главная </router-link>  <span class="lnr lnr-arrow-right"></span>  <router-link :to="{name: 'about'}"> О нас</router-link></p>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start about-info Area -->
    <section class="about-info-area section-gap">
        <div class="container">
            <div class="row align-items-center">
                <!-- Левая часть: изображение -->
                <div class="col-lg-6 info-left">
                    <img class="img-fluid" :src="'img/about/info-img.jpg'" alt="О нас">
                </div>

                <!-- Правая часть: текст -->
                <div class="col-lg-6 info-right">
                    <h6>О компании</h6>
                    <h1>Кто мы такие?</h1>
                    <p>
                        Travelista — это команда профессионалов, создающих идеальные путешествия для каждого.
                        Мы подбираем лучшие маршруты, отели и туры, чтобы каждая поездка была уникальной и оставляла только приятные впечатления.
                        Независимо от того, ищете ли вы романтическое приключение, семейный отдых или экстремальные туры — мы поможем всё организовать.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- End about-info Area -->

    <!-- Start price Area -->
    <section class="price-area section-gap">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-9">
                    <div class="title text-center">
                        <h1 class="mb-10">Другие вопросы, с которыми мы можем вам помочь</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="single-other-issue">
                        <div class="thumb">
                            <img class="img-fluid" :src="'img/o1.jpg'" alt="">
                        </div>
                        <a href="#">
                            <h4>Аренда машин</h4>
                        </a>
                        <p>
                            Сохранение человеческой жизни — высшая ценность, столп этики и основа.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single-other-issue">
                        <div class="thumb">
                            <img class="img-fluid" :src="'img/o3.jpg'" alt="">
                        </div>
                        <a href="#">
                            <h4>Что посетить</h4>
                        </a>
                        <p>
                            Следующая статья посвящена теме, которая недавно вышла на передний план — по крайней мере, так кажется.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single-other-issue">
                        <div class="thumb">
                            <img class="img-fluid" :src="'img/o4.jpg'" alt="">
                        </div>
                        <a href="#">
                            <h4>Рекомендации по питанию</h4>
                        </a>
                        <p>
                            Существует множество видов повествований и принципов их организации. Наука движется доказательствами.
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="single-other-issue">
                        <div class="thumb">
                            <img class="img-fluid" :src="'img/o2.jpg'" alt="">
                        </div>
                        <a href="#">
                            <h4>Бронирование круизов</h4>
                        </a>
                        <p>
                            I was always somebody who felt quite sorry for myself, what I had not got compared.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Start testimonial Area -->
    <section class="testimonial-area section-gap">
        <div class="container">
            <div class="row justify-content-center">
                <swiper
                    :modules="swiperModules"
                    :loop="true"
                    :autoplay="{ delay: 4000, disableOnInteraction: false }"
                    :space-between="20"
                    :breakpoints="{
                            0: { slidesPerView: 1 },
                            768: { slidesPerView: 2 },
                            1200: { slidesPerView: 2 } /* две карточки на десктопе */
                        }"
                    class="modern-swiper"
                >
                    <swiper-slide v-for="tour in tours" :key="tour.id">
                        <div class="modern-card">
                            <div class="card-image">
                                <img :src="tour.photo" :alt="tour.name" />
                            </div>
                            <div class="card-body">
                                <h4>{{ tour.name }}</h4>
                                <p>{{ tour.description }}</p>
                                <button class="primary-btn" @click="applyTourTypeFilters(tour.type_id)">
                                    Подробнее
                                </button>
                            </div>
                        </div>
                    </swiper-slide>
                </swiper>

            </div>
        </div>
    </section>
</div>
</template>

<style scoped>/* ------------------ Общие секции ------------------ */
.section-gap {
    padding-top: 50px;
    padding-bottom: 50px;
}

/* ------------------ Популярные направления ------------------ */
.popular-destination-area .row {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}
.popular-destination-area .title h1 {
    white-space: nowrap;   /* запрещаем перенос на новую строку */
    overflow: hidden;      /* обрезаем, если не помещается */
    text-overflow: ellipsis; /* при необходимости добавляем троеточие */
}
.popular-destination-area .row > div {
    flex: 0 1 calc(33% - 20px); /* три карточки на десктопе */
}

@media (max-width: 1200px) {
    .popular-destination-area .row > div {
        flex: 0 1 calc(50% - 20px); /* две на планшете */
    }
}

@media (max-width: 768px) {
    .popular-destination-area .row > div {
        flex: 0 1 100%; /* одна на мобилке */
    }
}

.img-fluid {
    border-radius: 12px;
}

/* ------------------ Другие вопросы ------------------ */
.price-area .row {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    align-items: flex-start; /* ключевое — выравниваем по верхнему краю */
}

.price-area .row > div:nth-child(odd) {
    margin-bottom: 50px; /* добавляем отступ под первой карточкой */
}

/* или конкретно второму ряду */
.price-area .row > div:nth-child(n+3) {
    margin-top: 40px; /* сдвигаем вниз все карточки со второго ряда */
}

/* адаптив: на планшете и мобилке убираем, чтобы не было лишних отступов */
@media (max-width: 768px) {
    .price-area .row > div:nth-child(n+3) {
        margin-top: 0;
    }
}
/* динамика высоты карточек */
.price-area .row > div:nth-child(odd) .single-other-issue {
    height: 320px;
}

.price-area .row > div:nth-child(even) .single-other-issue {
    height: 280px;
}

/* адаптив */
@media (max-width: 1200px) {
    .price-area .row > div {
        flex: 0 1 calc(50% - 20px);
    }
}

@media (max-width: 768px) {
    .price-area .row > div {
        flex: 0 1 100%;
    }
    .price-area .row > div .single-other-issue {
        height: auto;
    }
}

/* ------------------ Слайдер ------------------ */
.modern-swiper {
    padding: 20px 0;
}

.modern-card {
    margin: 0 auto;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    transition: transform 0.3s, box-shadow 0.3s;
    display: flex;
    flex-direction: column;
}

.modern-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.12);
}

.card-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.card-body {
    padding: 15px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.card-body h4 {
    font-size: 18px;
    margin-bottom: 8px;
    color: #333;
}

.card-body p {
    font-size: 14px;
    color: #666;
    flex: 1;
}

.card-body .primary-btn {
    align-self: flex-start;
    padding: 8px 16px;
    font-size: 14px;
    background-color: #f8b600;
    color: #fff;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: background 0.3s;
}

.card-body .primary-btn:hover {
    background-color: #e0a800;
}

/* ------------------ Формы и выпадающие списки ------------------ */
.custom-select-wrapper {
    position: relative;
    width: 100%;
}

.custom-select-wrapper .form-control {
    height: 45px;
    border-radius: 6px;
    border: 1px solid #ddd;
    padding: 10px 12px;
    font-size: 14px;
    background-color: #fff;
}

.custom-select-dropdown {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    z-index: 1000;
    max-height: 220px;
    overflow-y: auto;
    background: #fff;
    border-radius: 6px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    padding: 5px 0;
}

.custom-select-dropdown li {
    padding: 10px 14px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.2s;
}

.custom-select-dropdown li:hover {
    background: #f5f5f5;
}

.custom-select-dropdown li.selected {
    background: #f0f0f0;
    font-weight: 600;
}

.custom-select-dropdown .no-results {
    padding: 10px 14px;
    color: #999;
    cursor: default;
}

.form-wrap .form-control {
    margin-bottom: 10px;
    height: 45px;
    border-radius: 6px;
    font-size: 14px;
    border: 1px solid #ddd;
}

.form-wrap .form-control:focus {
    border-color: #f8b600;
    box-shadow: none;
}

.form-wrap .primary-btn {
    width: 100%;
    text-align: center;
    height: 45px;
    line-height: 45px;
    border-radius: 6px;
    margin-top: 5px;
}

.banner-right {
    position: relative;
    z-index: 10;
}

.flatpickr-input {
    height: 45px;
    border-radius: 6px;
    border: 1px solid #ddd;
    padding: 0 12px;
    font-size: 14px;
}

.flatpickr-calendar {
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, .15);
}

.flatpickr-day.selected {
    background: #f8b600;
    border-color: #f8b600;
}

/* ------------------ Кнопки популярных направлений ------------------ */
.single-destination .price-btn {
    display: inline-block;
    padding: 8px 16px;
    background-color: #f8b600;
    border-radius: 5px;
    color: #fff;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s;
    cursor: pointer;
    border: none;
    outline: none;
}

.single-destination .price-btn:hover {
    background-color: #e0a800;
    transform: translateY(-2px);
}


</style>

