<script>
import axios from "axios";

export default {
    name: "Tours",
    data() {
        return {
            cities: [],
            countries: [],
            tours: [],
            citySearch: "",
            countrySearch: "",
            city_id: null,
            country_id: null,
            showCityDropdown: false,
            showCountryDropdown: false,

            stars: [],
            pricefrom: null,
            priceto: null,
            check_in: null,
            check_out: null,

            pagination: null,
        };
    },
    computed: {
        filteredCities() {
            if (!this.citySearch) return this.cities;
            return this.cities.filter(city =>
                city.name.toLowerCase().includes(this.citySearch.toLowerCase())
            );
        },
        filteredCountries() {
            if (!this.countrySearch) return this.countries;
            return this.countries.filter(country =>
                country.name.toLowerCase().includes(this.countrySearch.toLowerCase())
            );
        },
    },
    methods: {
        async getCities() {
            try {
                const res = await axios.get("http://localhost:8876/api/cities");
                this.cities = res.data.data;
            } catch (err) {
                console.error(err);
            }
        },
        async getCountries() {
            try {
                const res = await axios.get("http://localhost:8876/api/countries");
                this.countries = res.data.data;
            } catch (err) {
                console.error(err);
            }
        },
        async getTours(page = 1) {
            try {
                const res = await axios.post(`http://localhost:8876/api/tours?page=${page}`, {
                    city: this.city_id,
                    country: this.country_id,
                    stars: this.stars,
                    pricefrom: this.pricefrom,
                    priceto: this.priceto,
                    dates: {
                        check_in: this.check_in,
                        check_out: this.check_out
                    }
                });
                this.tours = res.data.data;
                this.pagination = res.data.meta;
                console.log(res);
            } catch (err) {
                console.error(err);
            }
        },
        applyFilters() {
            if (!this.check_in || !this.check_out) {
                alert("Выберите даты въезда и выезда");
                return;
            }

            this.getTours(1);
        },
        selectCity(city) {
            this.city_id = city.id;
            this.citySearch = city.name;
            this.showCityDropdown = false;
        },
        selectCountry(country) {
            this.country_id = country.id;
            this.countrySearch = country.name;
            this.showCountryDropdown = false;
        },
        handleClickOutside(event) {
            if (this.$refs.cityWrapper && !this.$refs.cityWrapper.contains(event.target)) {
                this.showCityDropdown = false;
            }
            if (this.$refs.countryWrapper && !this.$refs.countryWrapper.contains(event.target)) {
                this.showCountryDropdown = false;
            }
        },
        showPage(n) {
            const current = this.pagination.current_page;
            const last = this.pagination.last_page;

            if (n === 1 || n === last) return true;
            if (n >= current - 2 && n <= current + 2) return true;
            return false;
        },
    },
    mounted() {
        this.getCities();
        this.getCountries();
        this.getTours();
        this.clickOutsideHandler = this.handleClickOutside.bind(this);
        document.addEventListener("click", this.clickOutsideHandler);
    },
    beforeUnmount() {
        document.removeEventListener("click", this.clickOutsideHandler);
    }
};
</script>

<template>
    <!-- start banner Area -->
    <section class="about-banner relative">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="about-content col-lg-12">
                    <h1 class="text-white">
                        Туры
                    </h1>
                    <p class="text-white link-nav">
                        <router-link :to="{ name: 'index'}">Главная</router-link>
                        <span class="lnr lnr-arrow-right"></span>
                        <router-link to="/tours"> Туры</router-link>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start destinations Area -->
    <section class="destinations-area section-gap">
        <div class="container">
            <div class="row">
                <!-- Sidebar слева -->
                <div class="col-lg-4">
                    <div class="filter-section">
                        <h4 class="section-title">
                            <i class="lnr lnr-filter me-2"></i>Фильтры
                        </h4>

                        <!-- Searchable Select для страны -->
                        <div class="custom-select-wrapper mb-2" ref="countryWrapper">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Выберите страну..."
                                v-model="countrySearch"
                                @focus="showCountryDropdown = true"
                                @input="showCountryDropdown = true"
                            />
                            <ul v-if="showCountryDropdown" class="custom-select-dropdown">
                                <li
                                    v-for="country in filteredCountries"
                                    :key="country.id"
                                    @click="selectCountry(country)"
                                    :class="{ selected: country.id === country_id }"
                                >
                                    {{ country.name }}
                                </li>
                                <li v-if="filteredCountries.length === 0" class="no-results">Страна не найдена</li>
                            </ul>
                        </div>

                        <div class="custom-select-wrapper mb-2" ref="cityWrapper">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Выберите город..."
                                v-model="citySearch"
                                @focus="showCityDropdown = true"
                                @input="showCityDropdown = true"
                            />
                            <ul v-if="showCityDropdown" class="custom-select-dropdown">
                                <li
                                    v-for="city in filteredCities"
                                    :key="city.id"
                                    @click="selectCity(city)"
                                    :class="{ selected: city.id === city_id }"
                                >
                                    {{ city.name }}
                                </li>
                                <li v-if="filteredCities.length === 0" class="no-results">Город не найден</li>
                            </ul>
                        </div>

                        <div class="filter-group mb-3 stars-filter">
                            <label>Звезды</label>
                            <div>
                                <label class="me-2" v-for="n in 5" :key="n">
                                    <input type="checkbox" :value="n" v-model="stars"> {{ '★'.repeat(n) }}
                                </label>
                            </div>
                        </div>

                        <div class="filter-group mb-3">
                            <label>Макс. цена</label>
                            <input type="number" v-model="priceto" class="form-control">
                        </div>
                        <div class="filter-group mb-3">
                            <label>Мин. цена</label>
                            <input type="number" v-model="pricefrom" class="form-control">
                        </div>
                        <div class="filter-group mb-3">
                            <label>Дата заезда</label>
                            <input type="date" v-model="check_in" class="form-control">
                        </div>


                        <div class="filter-group mb-3">
                            <label>Дата выезда</label>
                            <input type="date" v-model="check_out" class="form-control">
                        </div>
                        <button class="primary-btn w-100 mt-3" @click="applyFilters">
                            Применить фильтры
                        </button>
                    </div>
                </div>

                <!-- Основной контент справа -->
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 mb-4 d-flex" v-for="tour in tours" :key="tour.id">
                            <div class="single-destinations d-flex flex-column w-100">
                                <div class="thumb">
                                    <img :src="`/storage/${tour.hotel.preview_image}`" alt="">
                                </div>
                                <div class="details d-flex flex-column flex-grow-1">
                                    <h4 class="tour-title d-flex justify-content-between align-items-center">
                                        <span>{{ tour.name }}</span>
                                        <div class="star">
                                            <span
                                                v-for="n in Math.min(tour.hotel.stars ?? 0, 5)"
                                                :key="'filled-'+n"
                                                class="fa fa-star checked"
                                            ></span>

                                            <span
                                                v-for="n in Math.max(5 - (tour.hotel.stars ?? 0), 0)"
                                                :key="'empty-'+n"
                                                class="fa fa-star"
                                            ></span>
                                        </div>
                                    </h4>
                                    <p>{{ tour.description }}</p>
                                    <ul class="package-list mt-auto">
                                        <li class="d-flex justify-content-between align-items-center">
                                            <span>Бассейн</span>
                                            <span v-if="tour.hotel.amenities.some(a => a.name === 'Бассейн')">Да</span>
                                            <span v-else>Нет</span>
                                        </li>
                                        <li class="d-flex justify-content-between align-items-center">
                                            <span>Wi-fi</span>
                                            <span v-if="tour.hotel.amenities.some(a => a.name === 'Wi-fi')">Да</span>
                                            <span v-else>Нет</span>
                                        </li>
                                        <li class="d-flex justify-content-between align-items-center">
                                            <span>Кондиционер</span>
                                            <span v-if="tour.hotel.amenities.some(a => a.name === 'Кондиционер')">Да</span>
                                            <span v-else>Нет</span>
                                        </li>
                                        <li class="d-flex justify-content-between align-items-center">
                                            <span>Ресторан</span>
                                            <span v-if="tour.hotel.amenities.some(a => a.name === 'Ресторан')">Да</span>
                                            <span v-else>Нет</span>
                                        </li>
                                        <li class="d-flex justify-content-between align-items-center">
                                            <span>Личный пляж</span>
                                            <span v-if="tour.hotel.amenities.some(a => a.name === 'Личный пляж')">Да</span>
                                            <span v-else>Нет</span>
                                        </li>
                                        <li class="d-flex justify-content-between align-items-center">
                                            <span>Цена за {{ tour.days }} ночей</span>
                                            <router-link :to="{name: 'tours.show', params: {id: tour.id}}"
                                                         class="price-btn">от {{ tour.price }} руб.
                                            </router-link>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- /карточки -->
                    </div>
                </div>
            </div>
            <div class="row mt-5" v-if="pagination && pagination.last_page > 1">
                <div class="col-12 d-flex justify-content-center">
                    <ul class="custom-pagination">
                        <!-- Назад -->
                        <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
                            <a @click.prevent="pagination.current_page > 1 && getTours  (pagination.current_page - 1)"
                               class="page-link">‹</a>
                        </li>

                        <!-- Числа страниц с обрезкой -->
                        <li v-for="n in pagination.last_page" :key="n" v-if="showPage(n)"
                            class="page-item" :class="{ active: n === pagination.current_page }">
                            <a @click.prevent="getTours (n)" class="page-link">{{ n }}</a>
                        </li>

                        <!-- Вперед -->
                        <li class="page-item" :class="{ disabled: pagination.current_page === pagination.last_page }">
                            <a @click.prevent="pagination.current_page < pagination.last_page && getTours   (pagination.current_page + 1)"
                               class="page-link">›</a>
                        </li>
                    </ul>
                </div>
            </div>


        </div>
    </section>
    <!-- End destinations Area -->


    <!-- Start home-about Area -->
    <section class="home-about-area">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-end">
                <div class="col-lg-6 col-md-12 home-about-left">
                    <h1>
                        Did not find your Package? <br>
                        Feel free to ask us. <br>
                        We‘ll make it for you
                    </h1>
                    <p>
                        inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct
                        standards especially in the workplace. That’s why it’s crucial that, as women, our behavior on
                        the job is beyond reproach. inappropriate behavior is often laughed.
                    </p>
                    <a href="#" class="primary-btn text-uppercase">request custom price</a>
                </div>
                <div class="col-lg-6 col-md-12 home-about-right no-padding">
                    <img class="img-fluid" :src="'img/tours/about-img'.jpg" alt="">
                </div>
            </div>
        </div>
    </section>
    <!-- End home-about Area -->
</template>

<style scoped>
.single-destinations {
    display: flex;
    flex-direction: column;
    height: 100%; /* растягиваем карточку по высоте колонки */
}

.single-destinations .details {
    display: flex;
    flex-direction: column;
    flex-grow: 1; /* заполняет пространство */
}

.package-list {
    margin-top: auto; /* футер всегда внизу */
}

.custom-pagination {
    display: flex;
    gap: 8px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.custom-pagination .page-item a {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 38px;
    height: 38px;
    padding: 0 12px;
    border-radius: 8px;
    background: #f8f9fa;
    color: #333;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid #e1e1e1;
}

.custom-pagination .page-item a:hover {
    background: #faab34;
    color: #fff;
    border-color: #faab34;
}

.custom-pagination .page-item.active a {
    background: #faab34;
    color: #fff;
    border-color: #faab34;
}

.custom-pagination .page-item.disabled a {
    opacity: 0.4;
    pointer-events: none;
}

/* --- Общие стили для фильтрации --- */
.filter-section {
    padding: 25px 20px;
    background: #f8f9fa;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
}

.tour-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-height: 40px;
}

.filter-section h4 {
    margin-bottom: 25px;
    font-weight: 700;
    color: #222;
    font-size: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #faab34;
}

.filter-group {
    margin-bottom: 25px;
}

.filter-group label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #333;
    font-size: 15px;
}

/* Стили для выпадающих списков */
.custom-select-wrapper {
    position: relative;
    margin-bottom: 20px;
}

.custom-select-wrapper .form-control {
    width: 100%;
    padding: 12px 15px;
    border-radius: 8px;
    border: 2px solid #e1e1e1;
    background: white;
    font-size: 14px;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.custom-select-wrapper .form-control:focus {
    border-color: #faab34;
    box-shadow: 0 0 0 0.2rem rgba(250, 171, 52, 0.25);
    outline: none;
}

.custom-select-dropdown {
    position: absolute;
    z-index: 1000;
    width: 100%;
    max-height: 250px;
    overflow-y: auto;
    background: white;
    border: 2px solid #faab34;
    border-top: none;
    border-radius: 0 0 8px 8px;
    list-style: none;
    margin: 0;
    padding: 0;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.custom-select-dropdown li {
    padding: 12px 15px;
    cursor: pointer;
    font-size: 14px;
    color: #333;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.2s ease;
}

.custom-select-dropdown li:last-child {
    border-bottom: none;
}

.custom-select-dropdown li:hover {
    background-color: #fff8ee;
    color: #faab34;
}

.custom-select-dropdown li.selected {
    background-color: #faab34;
    color: white;
    font-weight: 600;
}

.custom-select-dropdown .no-results {
    padding: 15px;
    color: #888;
    text-align: center;
    cursor: default;
    font-style: italic;
}

/* Стили для чекбоксов звезд */
.stars-filter {
    background: white;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e1e1e1;
}

.stars-filter label {
    display: inline-flex;
    align-items: center;
    margin-right: 15px;
    margin-bottom: 8px;
    font-size: 15px;
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 5px;
    transition: all 0.2s;
}

.stars-filter label:hover {
    background-color: #fff8ee;
}

.stars-filter input {
    margin-right: 8px;
    accent-color: #faab34;
    transform: scale(1.2);
}

/* Стили для слайдера цены */
.filter-group input[type="range"] {
    width: 100%;
    height: 8px;
    border-radius: 5px;
    background: #e1e1e1;
    outline: none;
    -webkit-appearance: none;
    margin-top: 10px;
}

.filter-group input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #faab34;
    cursor: pointer;
    border: 3px solid white;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.filter-group input[type="range"]::-moz-range-thumb {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #faab34;
    cursor: pointer;
    border: 3px solid white;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.single-destinations {
    height: 100%;
}

.single-destinations .details {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.single-destinations .package-list {
    margin-top: auto;
}

.single-destinations img {
    height: 220px;
    object-fit: cover;
}

.star {
    display: flex;
    align-items: center;
    gap: 2px;
    line-height: 1;
}

.star .fa-star {
    font-size: 12px; /* ← размер звезд */
    height: 12px;
    line-height: 12px;
}

.star .checked {
    color: #faab34;
}

/* Адаптивность */
@media (max-width: 992px) {
    .filter-section {
        margin-bottom: 20px;
    }

    .stars-filter label {
        margin-right: 10px;
        margin-bottom: 10px;
        font-size: 14px;
    }
}
</style>
