<script>
import axios from "axios";

export default {
    data() {
        return {
            cities: [],
            countries: [],
            city_id: null,
            country_id: null,
            citySearch: "",
            countrySearch: "",
            showCityDropdown: false,
            showCountryDropdown: false,

            phone: "",
            email: "",
            name: "",
            surname: "",
            last_name: "",
            address: "",
            password: "",
            error: "",
            gender: null,
            showGenderDropdown: false,
            genders: [
                { id: 1, name: "Мужской" },
                { id: 2, name: "Женский" },
            ],
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
    mounted() {
        this.getCities();
        this.getCountries();
        $('.fullscreen').css('height', $(window).height());
        // Закрытие dropdown при клике вне компонента
        document.addEventListener("click", this.handleClickOutside);
    },
    beforeUnmount() {
        document.removeEventListener("click", this.handleClickOutside);
    },
    methods: {
        async register() {
            console.log("Форма:", {
                phone: this.phone,
                email: this.email,
                name: this.name,
                surname: this.surname,
                last_name: this.last_name,
                address: this.address,
                city_id: this.city_id,
                country_id: this.country_id,
                password: this.password,
                gender: this.gender,
            });
            this.error = null;

            try {
                const response = await axios.post(
                    'http://localhost:8876/api/register',
                    {
                        phone: this.phone,
                        email: this.email,
                        name: this.name,
                        surname: this.surname,
                        last_name: this.last_name,
                        address: this.address,
                        city_id: this.city_id,
                        country_id: this.country_id,
                        password: this.password,
                        gender: this.gender,
                    }
                );

                const token = response.data.token;
                localStorage.setItem('api_token', token); // Сохраняем токен

                // Настраиваем axios для будущих запросов
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

                this.$router.push('/login');
            } catch (e) {
                this.error = 'Ошибка регистрации';
                console.error(e.response?.data);
            }
        },
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
        selectGender(gender) {
            this.gender = gender.id;
            this.showGenderDropdown = false;
        },
        handleClickOutside(event) {
            if (!this.$refs.cityWrapper.contains(event.target)) {
                this.showCityDropdown = false;
            }
            if (!this.$refs.countryWrapper.contains(event.target)) {
                this.showCountryDropdown = false;
            }
            if (!this.$refs.genderWrapper.contains(event.target)) {
                this.showGenderDropdown = false;
            }
        },

    },
};
</script>

<template>
    <section class="banner-area relative">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row fullscreen align-items-center justify-content-between">
                <div class="col-lg-6 col-md-6 banner-left">
                    <h6 class="text-white">В дали от монотонной жизни</h6>
                    <h1 class="text-white">Магическое путешествие</h1>
                    <p class="text-white">
                        Забронируйте путешествие мечты вместе с Travelista
                    </p>
                </div>

                <div class="col-lg-4 col-md-6 banner-right">
                    <form class="form-wrap" @submit.prevent="register">
                        <!-- Основные поля -->
                        <input type="text" v-model="phone" placeholder="Номер телефона +7..."
                               class="form-control mb-2"/>
                        <input type="text" v-model="email" placeholder="Email" class="form-control mb-2"/>
                        <input type="text" v-model="name" placeholder="Имя" class="form-control mb-2"/>
                        <input type="text" v-model="surname" placeholder="Фамилия" class="form-control mb-2"/>
                        <input type="text" v-model="last_name" placeholder="Отчество" class="form-control mb-2"/>
                        <input type="text" v-model="address" placeholder="Полный адрес" class="form-control mb-2"/>

                        <!-- Searchable Select для города -->
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

                        <div class="custom-select-wrapper mb-2" ref="genderWrapper">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Выберите пол..."
                                :value="genders.find(g => g.id === gender)?.name || ''"
                                @focus="showGenderDropdown = true"
                                readonly
                            />
                            <ul v-if="showGenderDropdown" class="custom-select-dropdown">
                                <li
                                    v-for="g in genders"
                                    :key="g.id"
                                    @click="selectGender(g)"
                                    :class="{ selected: g.id === gender }"
                                >
                                    {{ g.name }}
                                </li>
                            </ul>
                        </div>
                        <input type="password" v-model="password" placeholder="Пароль" class="form-control mb-2"/>
                        <button type="submit" class="primary-btn text-uppercase">Зарегистрироваться</button>
                    </form>

                    <router-link :to="{ name: 'login' }" class="d-flex justify-content-center mt-2 primary-btn">
                        Есть аккаунт? Войти
                    </router-link>
                    <p v-if="error" class="text-danger">{{ error }}</p>
                </div>
            </div>
        </div>
    </section>
</template>


<style scoped>
.custom-select-wrapper {
    position: relative;
    width: 100%;
}

.custom-select-dropdown {
    position: absolute;
    z-index: 10;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    background: #fff;
    border: 1px solid #ccc;
    border-top: none;
    list-style: none;
    margin: 0;
    padding: 0;
}

.custom-select-dropdown li {
    padding: 8px 12px;
    cursor: pointer;
}

.custom-select-dropdown li:hover,
.custom-select-dropdown li.selected {
    background-color: #f0f0f0;
}

.custom-select-dropdown .no-results {
    padding: 8px 12px;
    color: #888;
    cursor: default;
}
</style>
