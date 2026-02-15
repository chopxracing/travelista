<script>
import axios from "axios";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";
import {Russian} from "flatpickr/dist/l10n/ru.js";
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/autoplay';

import { Autoplay } from 'swiper/modules';
export default {
    name: "Home",
    components: {
        Swiper,
        SwiperSlide,
    },
    inject: ['currentUser' , 'fetchUser'],
    data() {
        return {
            cities: [],
            countries: [],
            filters: {
                city_id: null,
                date_from: null,
                date_to: null,
            },

            countrySearch: '',
            citySearch: '',
            showCountryDropdown: false,
            showCityDropdown: false,

            swiperModules: [Autoplay],
            tours: [
                {
                    id: 1,
                    type_id: 2,
                    name: "Пляжный отдых",
                    description: "Идеальный вариант, если хочется солнца, моря и полного релакса. Лучшие курорты Турции, Египта, Мальдив и Таиланда.",
                    photo: "/img/tours/beach.jpg",
                },
                {
                    id: 2,
                    type_id: 4,
                    name: "Романтические туры",
                    description: "Медовый месяц, годовщина или просто побег вдвоём. Париж, Венеция, Бали и другие места для любви.",
                    photo: "/img/tours/romantic.jpeg",
                },
                {
                    id: 3,
                    type_id: 6,
                    name: "Круизы",
                    description: "Путешествие без чемоданов — каждый день новый город. Морские и речные круизы по Европе и миру.",
                    photo: "/img/tours/cruise.jpg",
                },
                {
                    id: 4,
                    type_id: 7,
                    name: "Горнолыжные курорты",
                    description: "Альпы, Кавказ, Скандинавия — трассы для новичков и профи. Зима, горы и адреналин.",
                    photo: "/img/tours/ski.jpg",
                },
                {
                    id: 5,
                    type_id: 8,
                    name: "Корпоративные туры",
                    description: "Тимбилдинг, конференции и выезды для компаний. Организуем всё под ключ.",
                    photo: "/img/tours/corporate.jpg",
                },
                {
                    id: 6,
                    type_id: 1,
                    name: "Экскурсионные туры",
                    description: "История, культура и новые впечатления. Европа, Азия и авторские маршруты.",
                    photo: "/img/tours/excursion.jpg",
                },
            ]
        };
    },
    computed: {
        filteredCities() {
            if (!this.citySearch) return this.cities;
            return this.cities.filter(c =>
                c.name.toLowerCase().includes(this.citySearch.toLowerCase())
            );
        },
    },
    methods: {
        async getCities() {
            try {
                const res = await axios.get("/api/cities");
                this.cities = res.data.data;
            } catch (err) {
                console.error(err);
            }
        },
        selectCity(city) {
            this.filters.city_id = city.id;
            this.citySearch = city.name;
            this.showCityDropdown = false;
        },
        handleClickOutside(event) {
            const hotel = this.$refs.hotelCityWrapper;
            const holiday = this.$refs.holidayCityWrapper;

            if (
                hotel && !hotel.contains(event.target) &&
                holiday && !holiday.contains(event.target)
            ) {
                this.showCityDropdown = false;
            }
        },
        initFlatpickr(el, modelKey) {
            if (!el || el._flatpickr) return;

            flatpickr(el, {
                locale: Russian,
                dateFormat: "d.m.Y",
                minDate: "today",
                onChange: ([date]) => {
                    if (date) {
                        this.filters[modelKey] = date.toISOString().split('T')[0]; // yyyy-mm-dd для URL
                    } else {
                        this.filters[modelKey] = null;
                    }
                },
            });
        },
        initHolidayFlatpickr() {
            // Проверяем, есть ли refs
            if (!this.$refs.tourDateFrom || !this.$refs.tourDateTo) return;
            this.initFlatpickr(this.$refs.tourDateFrom, "date_from");
            this.initFlatpickr(this.$refs.tourDateTo, "date_to");
        },
        applyHotelFilters() {
            if (!this.filters.date_from || !this.filters.date_to) {
                alert("Выберите даты въезда и выезда");
                return;
            }

            this.$router.push({
                name: 'hotels',
                query: {
                    city: this.filters.city_id,
                    check_in: this.filters.date_from,
                    check_out: this.filters.date_to,
                }
            });
        },
        applyTourFilters() {
            if (!this.filters.date_from || !this.filters.date_to) {
                alert("Выберите даты въезда и выезда");
                return;
            }
            const [dayIn, monthIn, yearIn] = this.check_in.split('.');
            const [dayOut, monthOut, yearOut] = this.check_out.split('.');
            this.$router.push({
                name: 'tours',
                query: {
                    city: this.filters.city_id,
                    dates: JSON.stringify({
                        check_in: this.filters.date_from,
                        check_out: this.filters.date_to
                    })
                }
            });
        },
        applyPopularFilters(cityName) {
            const city = this.cities.find(c => c.name === cityName);
            if (!city) return;

            this.filters.city_id = city.id;

            this.$router.push({
                name: 'tours',
                query: {
                    city: this.filters.city_id
                }
            });
        },
        applyTourTypeFilters(typeId) {
            if (!typeId) return;
            this.$router.push({ name: "tours", query: { tour_type_id: typeId } });
        },
    },
    mounted() {
        $('.fullscreen').css('height', $(window).height());
        this.getCities();

        this.clickOutsideHandler = this.handleClickOutside.bind(this);
        document.addEventListener("click", this.clickOutsideHandler);

        // Инициализация flatpickr на отелях сразу
        this.$nextTick(() => {
            this.initFlatpickr(this.$refs.hotelDateFrom, "date_from");
            this.initFlatpickr(this.$refs.hotelDateTo, "date_to");
        });

        // Инициализация flatpickr для туров при переключении таба
        const holidayTab = document.getElementById("holiday-tab");
        if (holidayTab) {
            $(holidayTab).on("shown.bs.tab", () => {
                this.$nextTick(() => {
                    this.initHolidayFlatpickr();
                });
            });
        }
    },
    beforeUnmount() {
        document.removeEventListener("click", this.clickOutsideHandler);
    },
};
</script>

<template>
    <div>
        <main>
            <!-- start banner Area -->
            <section class="banner-area relative">
                <div class="overlay overlay-bg"></div>
                <div class="container">
                    <div class="row fullscreen align-items-center justify-content-between">
                        <div class="col-lg-6 col-md-6 banner-left">
                            <h6 class="text-white">Побег из серых будней</h6>
                            <h1 class="text-white">Ваше идеальное путешествие</h1>
                            <p class="text-white">Заселись — новые места, новые эмоции, новые воспоминания</p>
                            <router-link v-if="currentUser.user" :to="{ name: 'profile' }" class="primary-btn text-uppercase">Начать</router-link>
                            <router-link v-else :to="{ name: 'login' }" class="primary-btn text-uppercase">Начать</router-link>
                        </div>
                        <div class="col-lg-4 col-md-6 banner-right">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="hotel-tab" data-toggle="tab" href="#hotel" role="tab"
                                       aria-controls="hotel" aria-selected="false">ОТЕЛИ</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="holiday-tab" data-toggle="tab" href="#holiday" role="tab"
                                       aria-controls="holiday" aria-selected="false">ТУРЫ</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="hotel" role="tabpanel"
                                     aria-labelledby="hotel-tab">
                                    <form class="form-wrap">
                                        <div class="custom-select-wrapper mb-2" ref="hotelCityWrapper">
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="Город назначения..."
                                                v-model="citySearch"
                                                @focus="showCityDropdown = true"
                                                @input="showCityDropdown = true"
                                            />
                                            <ul v-if="showCityDropdown" class="custom-select-dropdown">
                                                <li
                                                    v-for="city in filteredCities"
                                                    :key="city.id"
                                                    @click="selectCity(city)"
                                                    :class="{ selected: city.id === filters.city_id }"
                                                >
                                                    {{ city.name }}
                                                </li>
                                                <li v-if="filteredCities.length === 0" class="no-results">Город не
                                                    найден
                                                </li>
                                            </ul>
                                        </div>
                                        <input ref="hotelDateFrom" v-model="check_in" type="text" class="form-control"
                                               placeholder="Дата заезда">
                                        <input ref="hotelDateTo" v-model="check_out" type="text" class="form-control"
                                               placeholder="Дата выезда">
                                        <input type="number" min="1" max="20" class="form-control" name="adults"
                                               placeholder="Количество гостей " onfocus="this.placeholder = ''"
                                               onblur="this.placeholder = 'Adults '">
                                        <button type="button" @click="applyHotelFilters"
                                                class="primary-btn text-uppercase">Искать отели
                                        </button>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="holiday" role="tabpanel" aria-labelledby="holiday-tab">
                                    <form class="form-wrap">
                                        <div class="custom-select-wrapper mb-2" ref="holidayCityWrapper">
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="Город назначения..."
                                                v-model="citySearch"
                                                @focus="showCityDropdown = true"
                                                @input="showCityDropdown = true"
                                            />
                                            <ul v-if="showCityDropdown" class="custom-select-dropdown">
                                                <li
                                                    v-for="city in filteredCities"
                                                    :key="city.id"
                                                    @click="selectCity(city)"
                                                    :class="{ selected: city.id === filters.city_id }"
                                                >
                                                    {{ city.name }}
                                                </li>
                                                <li v-if="filteredCities.length === 0" class="no-results">Город не
                                                    найден
                                                </li>
                                            </ul>
                                        </div>
                                        <input ref="tourDateFrom" v-model="check_in" type="text" class="form-control"
                                               placeholder="Дата заезда">
                                        <input ref="tourDateTo" v-model="check_out" type="text" class="form-control"
                                               placeholder="Дата выезда">
                                        <input type="number" min="1" max="20" class="form-control" name="adults"
                                               placeholder="Количество гостей " onfocus="this.placeholder = ''"
                                               onblur="this.placeholder = 'Adults '">
                                        <button type="button" @click="applyTourFilters"
                                                class="primary-btn text-uppercase">Искать туры
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End banner Area -->

            <!-- Start popular-destination Area -->
            <section class="popular-destination-area section-gap">
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        <div class="menu-content pb-70 col-lg-8">
                            <div class="title text-center">
                                <h1 class="mb-10">Популярные направления</h1>
                                <p>Ваши мечты — наш маршрут</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="single-destination relative">
                                <div class="thumb relative">
                                    <div class="overlay overlay-bg"></div>
                                    <img class="img-fluid" :src="'img/d1.jpg'" alt="">
                                </div>
                                <div class="desc">

                                        <button type="button" @click="applyPopularFilters('Асуньсон')" class="price-btn">от 160.000</button>

                                    <h4>Горные реки Асуньсона</h4>
                                    <p>Парагвай</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="single-destination relative">
                                <div class="thumb relative">
                                    <div class="overlay overlay-bg"></div>
                                    <img class="img-fluid" :src="'img/d2.jpg'" alt="">
                                </div>
                                <div class="desc">

                                        <button type="button" @click="applyPopularFilters('Париж')" class="price-btn">от 90.000</button>

                                    <h4>Город мечты</h4>
                                    <p>Париж</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="single-destination relative">
                                <div class="thumb relative">
                                    <div class="overlay overlay-bg"></div>
                                    <img class="img-fluid" :src="'img/d3.jpg'" alt="">
                                </div>
                                <div class="desc">

                                        <button type="button" @click="applyPopularFilters('Коломбо')" class="price-btn">от 120.000</button>

                                    <h4>Облачные горы Коломбо</h4>
                                    <p>Шри-Ланка</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End popular-destination Area -->


            <!-- Start price Area -->
            <!-- End price Area -->


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



            <!-- End home-about Area -->


            <!-- Start blog Area -->
        </main>
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
        margin-top: 80px;
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
