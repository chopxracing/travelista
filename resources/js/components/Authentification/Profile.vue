<script>
import axios from 'axios';
import airlinesList from "../../airlinesList.js";

export default {
    name: 'UserProfile',
    inject: ['currentUser', 'fetchUser'],
    data() {
        return {
            activeTab: 'profile',
            bookings: [],
            loading: false,
            error: null,
            showTouristModal: false,
            showBookingModal: false,
            selectedBooking: null,
            selectedTourists: [],
            surname: '', name: '', last_name: '',
            passport_series: '', passport_number: '',
            passport_date: '', passport_org: '',
            passportDate: {day: '', month: '', year: ''},
            birthDate: {day: '', month: '', year: ''},
            birth_date: '',
            origin: null,
            flights: [], backflights: [],
            selectedFlight: null, selectedBackFlight: null,
            loadingFlights: false,
            flightCombos: [], selectedFlightCombo: null,
            favorites: [],

            // Отзыв
            showReviewModal: false,
            reviewBooking: null,
            reviewRating: 5,
            reviewTitle: '',
            reviewComment: '',
            reviewSubmitting: false,
        };
    },
    mounted() {
        this.fetchBookings();
        this.$watch(() => this.currentUser.user, (user) => {
            if (user) this.getFavorites();
        }, {immediate: true});
    },
    computed: {
        currentYear() {
            return new Date().getFullYear();
        },
        passportYears() {
            return Array.from({length: this.currentYear - 1899}, (_, i) => this.currentYear - i);
        },
        birthYears() {
            return Array.from({length: this.currentYear - 1899}, (_, i) => this.currentYear - i);
        },
        passportDays() {
            const {month, year} = this.passportDate;
            if (!month || !year || this.isFutureDate(year, month)) return [];
            return Array.from({length: new Date(year, month, 0).getDate()}, (_, i) => i + 1);
        },
        birthDays() {
            const {month, year} = this.birthDate;
            if (!month || !year || this.isFutureDate(year, month)) return [];
            return Array.from({length: new Date(year, month, 0).getDate()}, (_, i) => i + 1);
        },
        activeBookings() {
            return this.bookings.filter(b => b.status.id !== 7);
        },
        historyBookings() {
            return this.bookings.filter(b => b.status.id === 7);
        },
    },
    watch: {
        passportDate: {
            deep: true, handler() {
                const {day, month, year} = this.passportDate;
                if (!day || !month || !year) return;
                const date = new Date(year, month - 1, day);
                this.passport_date = date > new Date() ? '' : `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            }
        },
        birthDate: {
            deep: true, handler() {
                const {day, month, year} = this.birthDate;
                if (!day || !month || !year) return;
                const date = new Date(year, month - 1, day);
                this.birth_date = date > new Date() ? '' : `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            }
        },
    },
    methods: {
        async fetchBookings() {
            this.loading = true;
            this.error = null;
            try {
                const res = await axios.get('/api/bookings');
                this.bookings = res.data.data;
            } catch (e) {
                console.error(e);
                this.error = 'Не удалось загрузить бронирования';
            } finally {
                this.loading = false;
            }
        },
        async logout() {
            try {
                await axios.post('/api/logout');
            } catch (err) {
                console.error(err);
            }
            this.currentUser.user = null;
            localStorage.removeItem('api_token');
            delete axios.defaults.headers.common['Authorization'];
            this.$router.push('/');
        },
        openTouristModal() {
            this.showTouristModal = true;
        },
        isFutureDate(year, month) {
            return new Date(year, month - 1, 1) > new Date();
        },
        closeTouristModal() {
            this.showTouristModal = false;
            this.resetTouristForm();
        },
        resetTouristForm() {
            this.surname = '';
            this.name = '';
            this.last_name = '';
            this.passport_series = '';
            this.passport_number = '';
            this.passport_date = '';
            this.passport_org = '';
            this.birth_date = '';
            this.passportDate = {day: '', month: '', year: ''};
            this.birthDate = {day: '', month: '', year: ''};
        },
        async saveTourist() {
            try {
                await axios.post('/api/tourist', {
                    surname: this.surname,
                    name: this.name,
                    last_name: this.last_name,
                    passport_series: this.passport_series,
                    passport_number: this.passport_number,
                    passport_date: this.passport_date,
                    passport_org: this.passport_org,
                    birth_date: this.birth_date
                });
                const res = await axios.get('/api/user');
                this.currentUser.user = res.data.data;
                this.closeTouristModal();
            } catch (e) {
                console.error(e);
                alert('Ошибка при сохранении данных');
            }
        },
        async deleteTourist(id) {
            try {
                await axios.delete(`/api/tourist/${id}`);
                const res = await axios.get('/api/user');
                this.currentUser.user = res.data.data;
            } catch (error) {
                console.error(error);
                alert('Не удалось удалить туриста');
            }
        },
        async deleteBooking(id) {
            try {
                await axios.delete(`/api/bookings/${id}`);
                const res = await axios.get('/api/bookings');
                this.bookings = res.data.data;
            } catch (error) {
                console.error(error);
                alert('Не удалось удалить');
            }
        },
        openBookingModal(booking) {
            this.selectedBooking = booking;
            this.showBookingModal = true;
            this.selectedTourists = [];
            this.selectedFlightCombo = null;
            this.flightCombos = [];
            if (booking.tour?.id) {
                this.loadingFlights = true;
                this.getFlights(booking);
            } else {
                this.loadingFlights = false;
            }
        },
        closeBookingModal() {
            this.showBookingModal = false;
            this.selectedBooking = null;
            this.selectedTourists = [];
            this.selectedFlightCombo = null;
        },
        async confirmBooking() {
            if (!this.selectedBooking) return;
            if (this.selectedBooking.tour?.id && !this.selectedFlightCombo) {
                alert('Пожалуйста, выберите рейс');
                return;
            }
            if (this.selectedTourists.length === 0) {
                alert('Пожалуйста, выберите туристов');
                return;
            }
            const priceAddition = this.selectedFlightCombo ? this.selectedFlightCombo.priceAddition : 0;
            const totalAmount = (this.selectedBooking.payment.amount + priceAddition) * this.selectedTourists.length;
            try {
                const res = await axios.post('/api/payments/create', {
                    booking_id: this.selectedBooking.id,
                    amount: totalAmount
                });
                await axios.post('/api/bookings/confirm', {
                    booking_id: this.selectedBooking.id,
                    tourist_ids: this.selectedTourists,
                    flight_price: this.selectedFlightCombo?.totalPrice ?? null,
                    flight_origin: this.selectedFlightCombo?.to.origin ?? null,
                    flight_destination: this.selectedFlightCombo?.to.destination ?? null,
                    flight_airline: this.selectedFlightCombo?.to.airline_name ?? null,
                    flight_number: this.selectedFlightCombo?.to.flight_number ?? null
                });
                window.location.href = res.data.url;
            } catch (error) {
                console.error(error);
                alert('Ошибка при создании платежа');
            }
        },
        async getFlights(booking) {
            if (!booking.tour) {
                this.flights = [];
                this.backflights = [];
                this.loadingFlights = false;
                return;
            }
            const origin = this.currentUser.user.city?.iata_code;
            const destination = booking.tour.city?.iata_code;
            const depart_at = booking.tour.date_from;
            const return_at = booking.tour.date_to;
            if (!origin || !destination || !depart_at || !return_at) return;
            this.loadingFlights = true;
            try {
                const resTo = await axios.get('/api/flights', {
                    params: {
                        origin,
                        destination,
                        depart_at,
                        return_at: null
                    }
                });
                const resBack = await axios.get('/api/flights', {
                    params: {
                        origin: destination,
                        destination: origin,
                        depart_at: return_at
                    }
                });
                const flightsTo = resTo.data.map(f => ({
                    ...f,
                    airline_name: airlinesList[f.airline] || f.airline,
                    arrival_at: this.computeArrival(f.departure_at, f.duration_to)
                }));
                const flightsBack = resBack.data.map(f => ({
                    ...f,
                    airline_name: airlinesList[f.airline] || f.airline,
                    arrival_at: this.computeArrival(f.departure_at, f.duration_back)
                }));
                this.flightCombos = [];
                flightsTo.forEach(to => {
                    flightsBack.forEach(back => {
                        this.flightCombos.push({
                            to,
                            back,
                            totalPrice: (to.price || 0) + (back.price || 0),
                            transfers: to.transfers || 0,
                            return_transfers: back.transfers || 0
                        });
                    });
                });
                if (this.flightCombos.length > 0) {
                    const minPrice = Math.min(...this.flightCombos.map(c => c.totalPrice));
                    this.flightCombos.forEach(combo => {
                        combo.basePrice = minPrice;
                        combo.priceAddition = combo.totalPrice - minPrice;
                    });
                }
            } catch (e) {
                console.error(e);
            } finally {
                this.loadingFlights = false;
            }
        },
        computeArrival(departure, durationMinutes) {
            if (!departure || !durationMinutes) return '-';
            const dep = new Date(departure);
            const arr = new Date(dep.getTime() + durationMinutes * 60 * 1000);
            return `${arr.getHours().toString().padStart(2, '0')}:${arr.getMinutes().toString().padStart(2, '0')}`;
        },
        async getFavorites() {
            if (!this.currentUser.user) return;
            try {
                const res = await axios.post('/api/favorites/get', {user_id: this.currentUser.user.id});
                this.favorites = res.data.data;
            } catch (err) {
                console.error(err);
            }
        },
        getImage(favorite) {
            if (favorite.hotel?.preview_image) return `/storage/${favorite.hotel.preview_image}`;
            if (favorite.tour?.hotel?.preview_image) return `/storage/${favorite.tour.hotel.preview_image}`;
            return '/img/no-image.png';
        },
        async removeFavorite(favorite) {
            try {
                await axios.delete('/api/favorites/delete', {
                    data: {
                        user_id: this.currentUser.user.id,
                        hotel_id: favorite.hotel?.id || null,
                        tour_id: favorite.tour?.id || null
                    }
                });
                this.favorites = this.favorites.filter(f => f.id !== favorite.id);
                this.getFavorites();
            } catch (e) {
                console.error(e);
            }
        },

        // ── ОТЗЫВ ──
        openReviewModal(booking) {
            this.reviewBooking = booking;
            this.reviewRating = 5;
            this.reviewTitle = '';
            this.reviewComment = '';
            this.showReviewModal = true;
        },
        closeReviewModal() {
            this.showReviewModal = false;
            this.reviewBooking = null;
        },
        setRating(val) {
            this.reviewRating = val;
        },
        async submitReview() {
            if (!this.reviewTitle.trim() || !this.reviewComment.trim()) {
                alert('Заполните заголовок и комментарий');
                return;
            }
            this.reviewSubmitting = true;
            try {
                await axios.post('/api/reviews/store', {
                    rating: this.reviewRating,
                    title: this.reviewTitle,
                    comment: this.reviewComment,
                    user_id: this.currentUser.user.id,
                    booking_id: this.reviewBooking.id,
                    hotel_id: this.reviewBooking.hotel?.id ?? null,
                    tour_id: this.reviewBooking.tour?.id ?? null,
                });
                this.closeReviewModal();
                alert('Отзыв успешно отправлен!');
            } catch (e) {
                console.error(e);
                alert('Ошибка при отправке отзыва');
            } finally {
                this.reviewSubmitting = false;
            }
        },
    },
};
</script>

<template>
    <section class="about-banner relative">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="about-content col-lg-12">
                    <h1 class="text-white">Привет, {{ currentUser.user?.name }}!</h1>
                    <p class="text-white link-nav" style="margin-top:12px">
                        <router-link :to="{ name: 'index' }" class="banner-home-btn">← На главную</router-link>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="profile-section">
        <div class="container">

            <div class="tabs">
                <button :class="{ active: activeTab === 'profile' }" @click="activeTab = 'profile'">
                    <span class="tab-icon"><i class="fas fa-user"></i></span>
                    <span>Профиль</span>
                </button>
                <button :class="{ active: activeTab === 'bookings' }" @click="activeTab = 'bookings'">
                    <span class="tab-icon"><i class="fa-regular fa-calendar"></i></span>
                    <span>Брони</span>
                </button>
                <button :class="{ active: activeTab === 'tourists' }" @click="activeTab = 'tourists'">
                    <span class="tab-icon"><i class="fa-regular fa-address-card"></i></span>
                    <span>Данные</span>
                </button>
                <button :class="{ active: activeTab === 'favorites' }" @click="activeTab = 'favorites'">
                    <span class="tab-icon"><i class="fa-regular fa-heart"></i></span>
                    <span>Избранное</span>
                </button>
            </div>

            <!-- ── ПРОФИЛЬ ── -->
            <div v-if="activeTab === 'profile'" class="tab-pane">
                <div class="info-card">
                    <div class="info-card__header">
                        <div class="info-card__avatar">{{ (currentUser.user?.name || 'U')[0].toUpperCase() }}</div>
                        <div>
                            <div class="info-card__name">{{ currentUser.user?.name || 'Пользователь' }}</div>
                            <div class="info-card__role">Путешественник</div>
                        </div>
                    </div>
                    <div class="info-card__body" v-if="currentUser.user">
                        <div class="info-row">
                            <span class="info-row__icon">✉️</span>
                            <div>
                                <div class="info-row__label">Email</div>
                                <div class="info-row__value">{{ currentUser.user.email }}</div>
                            </div>
                        </div>
                        <div class="info-row">
                            <span class="info-row__icon">📱</span>
                            <div>
                                <div class="info-row__label">Телефон</div>
                                <div class="info-row__value">{{ currentUser.user.phone || 'Не указан' }}</div>
                            </div>
                        </div>
                    </div>
                    <button class="btn-logout" @click="logout">Выйти из аккаунта</button>
                </div>
            </div>

            <!-- ── БРОНИРОВАНИЯ ── -->
            <div v-if="activeTab === 'bookings'" class="tab-pane">
                <div v-if="loading" class="loading-state">
                    <div class="spinner"></div>
                    <p>Загрузка бронирований...</p>
                </div>

                <template v-else>
                    <!-- АКТИВНЫЕ БРОНИ — мобиль -->
                    <div v-if="activeBookings.length === 0 && historyBookings.length === 0" class="empty-state">
                        <div class="empty-state__icon">🗓</div>
                        <p>У вас пока нет бронирований</p>
                    </div>

                    <div v-if="activeBookings.length" class="bookings-list">
                        <div class="booking-item" v-for="booking in activeBookings" :key="booking.id">
                            <div class="booking-item__top">
                                <div class="booking-item__name">{{ booking.hotel?.name || booking.tour?.name }}</div>
                                <span class="status-badge" :class="booking.is_paid ? 'status-paid' : 'status-unpaid'">
                                    {{ booking.is_paid ? 'Оплачен' : 'Не оплачен' }}
                                </span>
                            </div>
                            <div class="booking-item__meta">
                                <div class="meta-chip">📅 {{ booking.date_from }} — {{ booking.date_to }}</div>
                                <div class="meta-chip">{{ booking.status.name }}</div>
                            </div>
                            <div class="booking-item__price">{{ booking.payment.amount }} ₽</div>
                            <div class="booking-item__actions">
                                <button class="btn-primary" @click="openBookingModal(booking)">Оформить</button>
                                <button class="btn-danger" @click="deleteBooking(booking.id)">Удалить</button>
                            </div>
                        </div>
                    </div>

                    <!-- АКТИВНЫЕ БРОНИ — десктоп -->
                    <div class="desktop-table-wrap">
                        <div class="section-header">
                            <h2 class="section-title">Мои бронирования</h2>
                        </div>
                        <table class="nice-table" v-if="activeBookings.length">
                            <thead>
                            <tr>
                                <th>Отель / Тур</th>
                                <th>Заезд</th>
                                <th>Выезд</th>
                                <th>Статус</th>
                                <th>Сумма</th>
                                <th>Оплата</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="booking in activeBookings" :key="booking.id">
                                <td><strong>{{ booking.hotel?.name || booking.tour?.name }}</strong></td>
                                <td>{{ booking.date_from }}</td>
                                <td>{{ booking.date_to }}</td>
                                <td>{{ booking.status.name }}</td>
                                <td><strong>{{ booking.payment.amount }} ₽</strong></td>
                                <td><span class="status-badge"
                                          :class="booking.is_paid ? 'status-paid' : 'status-unpaid'">{{
                                        booking.is_paid ? 'Оплачен' : 'Не оплачен'
                                    }}</span></td>
                                <td>
                                    <button class="btn-primary btn-sm" @click="openBookingModal(booking)">Оформить
                                    </button>
                                </td>
                                <td>
                                    <button class="btn-danger btn-sm" @click="deleteBooking(booking.id)">Удалить
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div v-else-if="!loading" class="table-empty-block">Нет активных бронирований</div>
                    </div>

                    <!-- ИСТОРИЯ БРОНЕЙ -->
                    <div v-if="historyBookings.length" class="history-section">
                        <div class="history-header">
                            <span class="history-icon">🕓</span>
                            <h3 class="history-title">История поездок</h3>
                        </div>

                        <!-- мобиль -->
                        <div class="bookings-list history-list">
                            <div class="booking-item booking-item--history" v-for="booking in historyBookings"
                                 :key="'h'+booking.id">
                                <div class="booking-item__top">
                                    <div class="booking-item__name">{{
                                            booking.hotel?.name || booking.tour?.name
                                        }}
                                    </div>
                                    <span class="status-badge status-history">Завершён</span>
                                </div>
                                <div class="booking-item__meta">
                                    <div class="meta-chip">📅 {{ booking.date_from }} — {{ booking.date_to }}</div>
                                    <div class="meta-chip">{{ booking.payment.amount }} ₽</div>
                                </div>
                                <button class="btn-review w-full" @click="openReviewModal(booking)">
                                    ⭐ Оставить отзыв
                                </button>
                            </div>
                        </div>

                        <!-- десктоп -->
                        <div class="desktop-table-wrap">
                            <table class="nice-table nice-table--history">
                                <thead>
                                <tr>
                                    <th>Отель / Тур</th>
                                    <th>Заезд</th>
                                    <th>Выезд</th>
                                    <th>Сумма</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="booking in historyBookings" :key="'hd'+booking.id">
                                    <td><strong>{{ booking.hotel?.name || booking.tour?.name }}</strong></td>
                                    <td>{{ booking.date_from }}</td>
                                    <td>{{ booking.date_to }}</td>
                                    <td><strong>{{ booking.payment.amount }} ₽</strong></td>
                                    <td>
                                        <button class="btn-review btn-sm" @click="openReviewModal(booking)">
                                            ⭐ Отзыв
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </template>
            </div>

            <!-- ── ДАННЫЕ / ТУРИСТЫ ── -->
            <div v-if="activeTab === 'tourists'" class="tab-pane">
                <div class="section-header">
                    <h2 class="section-title">Мои данные</h2>
                    <button class="btn-primary" @click="openTouristModal">+ Добавить</button>
                </div>
                <div v-if="currentUser.user?.tourists?.length === 0" class="empty-state">
                    <div class="empty-state__icon">🪪</div>
                    <p>Добавьте данные путешественников</p>
                </div>
                <div class="tourist-list mobile-only">
                    <div class="tourist-item" v-for="tourist in currentUser.user.tourists" :key="tourist.id">
                        <div class="tourist-item__name">{{ tourist.surname }} {{ tourist.name }} {{
                                tourist.last_name
                            }}
                        </div>
                        <div class="tourist-item__details">
                            <span>🪪 {{ tourist.passport_series }} {{ tourist.passport_number }}</span>
                            <span>📅 {{ tourist.birth_date }}</span>
                        </div>
                        <div class="tourist-item__org">{{ tourist.passport_org }}, {{ tourist.passport_date }}</div>
                        <button class="btn-danger btn-sm w-full mt-2" @click="deleteTourist(tourist.id)">Удалить
                        </button>
                    </div>
                </div>
                <div class="desktop-table-wrap">
                    <table class="nice-table">
                        <thead>
                        <tr>
                            <th>Фамилия</th>
                            <th>Имя</th>
                            <th>Отчество</th>
                            <th>Серия</th>
                            <th>Номер</th>
                            <th>Выдан</th>
                            <th>Организация</th>
                            <th>Дата рождения</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="tourist in currentUser.user.tourists" :key="tourist.id">
                            <td>{{ tourist.surname }}</td>
                            <td>{{ tourist.name }}</td>
                            <td>{{ tourist.last_name }}</td>
                            <td>{{ tourist.passport_series }}</td>
                            <td>{{ tourist.passport_number }}</td>
                            <td>{{ tourist.passport_date }}</td>
                            <td>{{ tourist.passport_org }}</td>
                            <td>{{ tourist.birth_date }}</td>
                            <td>
                                <button class="btn-danger btn-sm" @click="deleteTourist(tourist.id)">Удалить</button>
                            </td>
                        </tr>
                        <tr v-if="!currentUser.user?.tourists?.length">
                            <td colspan="9" class="table-empty">Здесь пока пусто</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ── ИЗБРАННОЕ ── -->
            <div v-if="activeTab === 'favorites'" class="tab-pane">
                <div v-if="!favorites.length" class="empty-state">
                    <p>В избранном пока ничего нет</p>
                </div>
                <div class="favorites-grid">
                    <div class="fav-card" v-for="favorite in favorites" :key="favorite.id">
                        <router-link
                            :to="favorite.hotel ? { name: 'hotels.show', params: { id: favorite.hotel.id } } : { name: 'tours.show', params: { id: favorite.tour.id } }"
                            class="fav-card__link"
                        >
                            <div class="fav-card__img-wrap">
                                <img :src="getImage(favorite)" alt=""/>
                                <span class="fav-badge" :class="{ 'fav-badge--tour': favorite.tour }">
                                    {{ favorite.hotel ? 'Отель' : 'Тур' }}
                                </span>
                            </div>
                            <div class="fav-card__body">
                                <div class="fav-card__name">{{ favorite.hotel?.name || favorite.tour?.name }}</div>
                                <div class="fav-card__price">от {{ favorite.hotel?.min_price || favorite.tour?.price }}
                                    ₽
                                </div>
                            </div>
                        </router-link>
                        <button class="fav-remove" @click.prevent="removeFavorite(favorite)"
                                title="Удалить из избранного">✕
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="error" class="error-msg">⚠️ {{ error }}</div>
        </div>
    </section>

    <!-- ══ МОДАЛ: Добавить туриста ══ -->
    <div v-show="showTouristModal" class="modal-overlay" @click.self="closeTouristModal">
        <div class="modal-box">
            <div class="modal-top">
                <h3>Добавить туриста</h3>
                <button class="modal-x" @click="closeTouristModal">✕</button>
            </div>
            <div class="form-grid">
                <input type="text" v-model="surname" placeholder="Фамилия" class="f-input">
                <input type="text" v-model="name" placeholder="Имя" class="f-input">
                <input type="text" v-model="last_name" placeholder="Отчество" class="f-input" style="grid-column:1/-1">
                <input type="text" v-model="passport_series" placeholder="Серия паспорта" class="f-input">
                <input type="text" v-model="passport_number" placeholder="Номер паспорта" class="f-input">
                <div class="f-group" style="grid-column:1/-1">
                    <label class="f-label">Дата выдачи паспорта</label>
                    <div class="date-row">
                        <select v-model="passportDate.day" class="f-input">
                            <option value="">День</option>
                            <option v-for="d in passportDays" :key="d" :value="d">{{ d }}</option>
                        </select>
                        <select v-model="passportDate.month" class="f-input">
                            <option value="">Месяц</option>
                            <option v-for="m in 12" :key="m" :value="m">
                                {{ new Date(0, m - 1).toLocaleString('ru', {month: 'long'}) }}
                            </option>
                        </select>
                        <select v-model="passportDate.year" class="f-input">
                            <option value="">Год</option>
                            <option v-for="y in passportYears" :key="y" :value="y">{{ y }}</option>
                        </select>
                    </div>
                </div>
                <input type="text" v-model="passport_org" placeholder="Кем выдан" class="f-input"
                       style="grid-column:1/-1">
                <div class="f-group" style="grid-column:1/-1">
                    <label class="f-label">Дата рождения</label>
                    <div class="date-row">
                        <select v-model="birthDate.day" class="f-input">
                            <option value="">День</option>
                            <option v-for="d in birthDays" :key="d" :value="d">{{ d }}</option>
                        </select>
                        <select v-model="birthDate.month" class="f-input">
                            <option value="">Месяц</option>
                            <option v-for="m in 12" :key="m" :value="m">
                                {{ new Date(0, m - 1).toLocaleString('ru', {month: 'long'}) }}
                            </option>
                        </select>
                        <select v-model="birthDate.year" class="f-input">
                            <option value="">Год</option>
                            <option v-for="y in birthYears" :key="y" :value="y">{{ y }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-primary w-full" @click="saveTourist">Сохранить</button>
                <button class="btn-ghost w-full" @click="closeTouristModal">Отмена</button>
            </div>
        </div>
    </div>

    <!-- ══ МОДАЛ: Оформление бронирования ══ -->
    <div v-if="showBookingModal" class="modal-overlay" @click.self="closeBookingModal">
        <div class="modal-box modal-box--lg">
            <div class="modal-top">
                <h3>Оформление бронирования</h3>
                <button class="modal-x" @click="closeBookingModal">✕</button>
            </div>
            <div class="booking-summary">
                <div class="bs-row"><span class="bs-label">Объект</span><span
                    class="bs-val">{{ selectedBooking.hotel?.name || selectedBooking.tour?.name }}</span></div>
                <div class="bs-row"><span class="bs-label">Заезд</span><span class="bs-val">{{
                        selectedBooking.date_from
                    }}</span></div>
                <div class="bs-row"><span class="bs-label">Выезд</span><span class="bs-val">{{
                        selectedBooking.date_to
                    }}</span></div>
                <div class="bs-row"><span class="bs-label">Стоимость</span><span
                    class="bs-val bs-val--price">{{ selectedBooking.payment.amount }} ₽</span></div>
            </div>
            <div class="modal-section">
                <div class="modal-section__title">Выберите туристов</div>
                <div v-if="currentUser.user?.tourists?.length">
                    <label class="check-row" v-for="tourist in currentUser.user.tourists" :key="tourist.id">
                        <input type="checkbox" :value="tourist.id" v-model="selectedTourists">
                        <span class="check-name">{{ tourist.surname }} {{ tourist.name }} {{ tourist.last_name }}</span>
                    </label>
                </div>
                <p v-else class="hint-text">Нет туристов. Добавьте их в разделе «Данные».</p>
            </div>
            <div v-if="loadingFlights" class="spinner-wrap">
                <div class="spinner"></div>
                <p>Поиск рейсов...</p>
            </div>
            <div v-if="!loadingFlights && flightCombos.length" class="modal-section">
                <div class="modal-section__title">Выберите рейс</div>
                <div class="flights-scroll">
                    <div v-for="(combo, i) in flightCombos" :key="i" class="flight-card"
                         :class="{ 'flight-card--selected': selectedFlightCombo === combo }">
                        <label class="flight-card__label">
                            <input type="radio" :value="combo" v-model="selectedFlightCombo" class="flight-radio"/>
                            <div class="flight-legs">
                                <div class="flight-leg">
                                    <div class="leg-dir">✈️ Туда</div>
                                    <div class="leg-route">{{ combo.to.origin }} → {{ combo.to.destination }}</div>
                                    <div class="leg-time">{{ combo.to.departure_at.substring(11, 16) }} –
                                        {{ combo.to.arrival_at }}
                                    </div>
                                    <div class="leg-info">{{ combo.to.airline_name }} ·
                                        {{ Math.floor(combo.to.duration_to / 60) }}ч {{ combo.to.duration_to % 60 }}м
                                    </div>
                                </div>
                                <div class="flight-leg">
                                    <div class="leg-dir">✈️ Обратно</div>
                                    <div class="leg-route">{{ combo.back.origin }} → {{ combo.back.destination }}</div>
                                    <div class="leg-time">{{ combo.back.departure_at.substring(11, 16) }} –
                                        {{ combo.back.arrival_at }}
                                    </div>
                                    <div class="leg-info">{{ combo.back.airline_name }} ·
                                        {{ Math.floor(combo.back.duration_back / 60) }}ч
                                        {{ combo.back.duration_back % 60 }}м
                                    </div>
                                </div>
                            </div>
                            <div class="flight-price-tag">
                                <span v-if="combo.priceAddition > 0" class="price-plus">+{{
                                        combo.priceAddition
                                    }} ₽</span>
                                <span v-else class="price-base">Базовый</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <div v-if="!loadingFlights && flightCombos.length === 0 && selectedBooking.tour?.id"
                 class="hint-text text-center">Рейсы не найдены
            </div>
            <div class="modal-footer modal-footer--booking">
                <div class="total-row">
                    <span>Итого</span>
                    <strong class="total-amount">{{
                            (selectedBooking.payment.amount + (selectedFlightCombo ? selectedFlightCombo.priceAddition : 0)) * selectedTourists.length
                        }} ₽</strong>
                </div>
                <button class="btn-primary w-full" @click="confirmBooking"
                        :disabled="(selectedBooking.tour?.id && !selectedFlightCombo) || selectedTourists.length === 0">
                    Перейти к оплате →
                </button>
                <button class="btn-ghost w-full" @click="closeBookingModal">Отмена</button>
            </div>
        </div>
    </div>

    <!-- ══ МОДАЛ: Отзыв ══ -->
    <div v-if="showReviewModal" class="modal-overlay" @click.self="closeReviewModal">
        <div class="modal-box">
            <div class="modal-top">
                <h3>Оставить отзыв</h3>
                <button class="modal-x" @click="closeReviewModal">✕</button>
            </div>

            <!-- Объект -->
            <div class="review-object">
                <span class="review-object__icon">🏨</span>
                <span class="review-object__name">{{ reviewBooking?.hotel?.name || reviewBooking?.tour?.name }}</span>
            </div>

            <!-- Звёзды -->
            <div class="review-rating-section">
                <div class="f-label" style="margin-bottom:10px">Ваша оценка</div>
                <div class="star-row">
                    <button
                        v-for="n in 5" :key="n"
                        class="star-btn"
                        :class="{ active: n <= reviewRating }"
                        @click="setRating(n)"
                        type="button"
                    >★
                    </button>
                    <span class="star-label">{{ reviewRating }} / 5</span>
                </div>
            </div>

            <!-- Форма -->
            <div class="form-grid" style="grid-template-columns:1fr; gap:12px; margin-top:4px">
                <div class="f-group">
                    <label class="f-label">Заголовок</label>
                    <input type="text" v-model="reviewTitle" placeholder="Кратко о впечатлении" class="f-input">
                </div>
                <div class="f-group">
                    <label class="f-label">Комментарий</label>
                    <textarea v-model="reviewComment" placeholder="Поделитесь своим опытом..."
                              class="f-input f-textarea" rows="4"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-primary w-full" @click="submitReview" :disabled="reviewSubmitting">
                    {{ reviewSubmitting ? 'Отправка...' : 'Отправить отзыв' }}
                </button>
                <button class="btn-ghost w-full" @click="closeReviewModal">Отмена</button>
            </div>
        </div>
    </div>
</template>

<style scoped>
:root {
    --c-accent: #faab34;
    --c-accent-dark: #e8960f;
    --c-accent-light: #fff8ec;
    --c-on-accent: #1f1f1f;
    --c-danger: #ef4444;
    --c-danger-bg: #fef2f2;
    --c-success: #22c55e;
    --c-success-bg: #f0fdf4;
    --c-text: #1f1f1f;
    --c-text-muted: #6b7280;
    --c-border: #e5e7eb;
    --c-bg: #f8f9fb;
    --c-white: #ffffff;
    --radius: 14px;
    --shadow-sm: 0 1px 4px rgba(0, 0, 0, 0.06);
    --shadow-md: 0 4px 20px rgba(0, 0, 0, 0.10);
    --shadow-lg: 0 8px 40px rgba(0, 0, 0, 0.14);
    --glass-blur: 18px;
}

/* ── БАННЕР ── */
.banner-home-btn {
    display: inline-block;
    padding: 10px 22px;
    color: #fff2f2 !important;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.2s;
}

.banner-home-btn:hover {
    background: #e8960f;
    color: #1f1f1f !important;
}

/* ── СЕКЦИЯ ── */
.profile-section {
    background: var(--c-bg);
    min-height: calc(100vh - 220px);
    padding: 24px 0 60px;
}

/* ── ВКЛАДКИ ── */
.tabs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-bottom: 20px;
}

.tabs button {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    padding: 12px 8px;
    background: #fff;
    border: 2px solid transparent;
    border-radius: var(--radius);
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    color: #6b7280;
    transition: all 0.2s;
    min-height: 60px;
    outline: none;
}

.tabs button:hover {
    border-color: #faab34;
    color: #e8960f;
    background: #fff8ec;
}

.tabs button.active {
    background: #faab34;
    border-color: #faab34;
    color: #1f1f1f;
    box-shadow: 0 4px 14px rgba(250, 171, 52, 0.4);
    outline: none;
}

.tab-icon {
    font-size: 18px;
    line-height: 1;
}

/* ── ПРОФИЛЬ ── */
.info-card {
    background: #fff;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow-md);
}

.info-card__header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 24px;
    background: linear-gradient(135deg, #faab34, #fff );
}

.info-card__avatar {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.35);
    color: #1f1f1f;
    font-size: 20px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 2px solid rgba(255, 255, 255, 0.6);
}

.info-card__name {
    font-size: 18px;
    font-weight: 700;
    color: #1f1f1f;
}

.info-card__role {
    font-size: 13px;
    color: rgba(30, 20, 0, 0.6);
    margin-top: 2px;
}

.info-card__body {
    padding: 16px 24px;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid #e5e7eb;
}

.info-row:last-child {
    border-bottom: none;
}

.info-row__icon {
    font-size: 20px;
}

.info-row__label {
    font-size: 12px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.info-row__value {
    font-size: 15px;
    font-weight: 500;
    color: #1f1f1f;
    margin-top: 2px;
}

.btn-logout {
    display: block;
    width: calc(100% - 48px);
    margin: 0 24px 24px;
    padding: 13px;
    background: transparent;
    border: 2px solid #ef4444;
    border-radius: 10px;
    color: #ef4444;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-logout:hover {
    background: #ef4444;
    color: #fff;
}

/* ── АКТИВНЫЕ БРОНИ ── */
.bookings-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.booking-item {
    background: #fff;
    border-radius: var(--radius);
    padding: 16px;
    box-shadow: var(--shadow-sm);
    border: 1px solid #e5e7eb;
    transition: box-shadow 0.2s;
}

.booking-item:hover {
    box-shadow: var(--shadow-md);
}

.booking-item__top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 8px;
    margin-bottom: 10px;
}

.booking-item__name {
    font-size: 16px;
    font-weight: 700;
    color: #1f1f1f;
    line-height: 1.3;
}

.booking-item__meta {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 12px;
}

.meta-chip {
    background: var(--c-bg);
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    padding: 4px 10px;
    font-size: 12px;
    color: #6b7280;
}

.booking-item__price {
    font-size: 20px;
    font-weight: 800;
    color: #e8960f;
    margin-bottom: 14px;
}

.booking-item__actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}

/* ── ИСТОРИЯ ── */
.history-section {
    margin-top: 32px;
}

.history-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
}

.history-icon {
    font-size: 22px;
}

.history-title {
    font-size: 18px;
    font-weight: 700;
    color: #1f1f1f;
    margin: 0;
}

.history-list {
    display: flex;
}

/* показывается только мобильно */

.booking-item--history {
    background: #fafafa;
    border-color: #e5e7eb;
    opacity: 0.92;
}

.booking-item--history .booking-item__name {
    color: #374151;
}

.status-history {
    background: #f3f4f6;
    color: #6b7280;
}

.table-empty-block {
    padding: 20px;
    text-align: center;
    color: #6b7280;
    font-size: 14px;
}

/* ── КНОПКА ОТЗЫВ ── */
.btn-review {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 16px;
    background: linear-gradient(135deg, #fff8ec, #ffedc0);
    color: #1f1f1f;
    border: 1.5px solid #faab34;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    min-height: 40px;
}

.btn-review:hover {
    background: #faab34;
    color: #1f1f1f;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(250, 171, 52, 0.35);
}

/* ── МОДАЛ ОТЗЫВ ── */
.review-object {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    margin-bottom: 16px;
    background: rgba(250, 171, 52, 0.1);
    border: 1px solid rgba(250, 171, 52, 0.3);
    border-radius: 12px;
}

.review-object__icon {
    font-size: 20px;
}

.review-object__name {
    font-size: 15px;
    font-weight: 700;
    color: #1f1f1f;
}

.review-rating-section {
    margin-bottom: 16px;
}

.star-row {
    display: flex;
    align-items: center;
    gap: 6px;
}

.star-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 32px;
    line-height: 1;
    color: #d1d5db;
    transition: color 0.15s, transform 0.15s;
    padding: 0;
}

.star-btn.active {
    color: #faab34;
}

.star-btn:hover {
    color: #faab34;
    transform: scale(1.15);
}

.star-label {
    font-size: 14px;
    color: #6b7280;
    margin-left: 8px;
    font-weight: 600;
}

.f-textarea {
    resize: vertical;
    min-height: 100px;
    line-height: 1.5;
}

/* ── ТУРИСТЫ ── */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 10px;
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    color: #1f1f1f;
    margin: 0;
}

.tourist-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.tourist-item {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: var(--radius);
    padding: 16px;
    box-shadow: var(--shadow-sm);
}

.tourist-item__name {
    font-size: 16px;
    font-weight: 700;
    color: #1f1f1f;
    margin-bottom: 8px;
}

.tourist-item__details {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 4px;
    font-size: 13px;
    color: #6b7280;
}

.tourist-item__org {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
}

/* ── ИЗБРАННОЕ ── */
.favorites-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 16px;
}

.fav-card {
    background: #fff;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    border: 1px solid #e5e7eb;
    transition: all 0.25s;
    position: relative;
}

.fav-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.fav-card__link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.fav-card__img-wrap {
    position: relative;
    height: 175px;
    overflow: hidden;
}

.fav-card__img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.fav-card:hover .fav-card__img-wrap img {
    transform: scale(1.04);
}

.fav-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #22c55e;
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.fav-badge--tour {
    background: #f97316;
}

.fav-remove {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 30px;
    height: 30px;
    background: rgba(0, 0, 0, 0.55);
    backdrop-filter: blur(4px);
    border: none;
    border-radius: 50%;
    color: #fff;
    font-size: 13px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
    z-index: 2;
}

.fav-remove:hover {
    background: #ef4444;
}

.fav-card__body {
    padding: 14px 16px;
}

.fav-card__name {
    font-size: 15px;
    font-weight: 700;
    color: #1f1f1f;
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.fav-card__price {
    font-size: 13px;
    color: #e8960f;
    font-weight: 600;
}

/* ── БЕЙДЖИ ── */
.status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
    flex-shrink: 0;
}

.status-paid {
    background: #f0fdf4;
    color: #166534;
}

.status-unpaid {
    background: #fef2f2;
    color: #991b1b;
}

/* ── КНОПКИ ── */
.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 11px 18px;
    background: #faab34;
    color: #1f1f1f;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    min-height: 44px;
}

.btn-primary:hover {
    background: #e8960f;
    color: #1f1f1f;
    transform: translateY(-1px);
}

.btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

.btn-danger {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 11px 18px;
    background: transparent;
    color: #ef4444;
    border: 2px solid #ef4444;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    min-height: 44px;
}

.btn-danger:hover {
    background: #ef4444;
    color: #fff;
}

.btn-ghost {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 11px 18px;
    background: rgba(255, 255, 255, 0.55);
    color: #1f1f1f;
    border: 1.5px solid rgba(255, 255, 255, 0.5);
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    min-height: 44px;
    backdrop-filter: blur(6px);
}

.btn-ghost:hover {
    background: rgba(255, 255, 255, 0.8);
}

.btn-sm {
    padding: 8px 14px;
    font-size: 13px;
    min-height: 36px;
}

.w-full {
    width: 100%;
}

.mt-2 {
    margin-top: 8px;
}

/* ── ТАБЛИЦЫ ── */
.desktop-table-wrap {
    display: none;
}

.mobile-only {
    display: block;
}

.nice-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.nice-table th {
    background: #f1f5f9;
    color: #6b7280;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.nice-table td {
    padding: 14px 16px;
    font-size: 14px;
    color: #1f1f1f;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: middle;
}

.nice-table tbody tr:last-child td {
    border-bottom: none;
}

.nice-table tbody tr:hover td {
    background: #fafafa;
}

.nice-table--history tbody tr td {
    color: #6b7280;
}

.nice-table--history tbody tr:hover td {
    background: #f9fafb;
}

.table-empty {
    text-align: center;
    color: #6b7280;
    padding: 32px !important;
}

/* ── СОСТОЯНИЯ ── */
.empty-state {
    text-align: center;
    padding: 48px 20px;
    color: #6b7280;
}

.empty-state__icon {
    font-size: 48px;
    margin-bottom: 12px;
}

.empty-state p {
    font-size: 15px;
    margin: 0;
}

.loading-state {
    text-align: center;
    padding: 48px 20px;
}

.loading-state p {
    color: #6b7280;
    margin-top: 12px;
}

.error-msg {
    background: #fef2f2;
    color: #ef4444;
    border: 1px solid #fecaca;
    border-radius: 10px;
    padding: 14px 16px;
    margin-top: 16px;
    font-size: 14px;
}

/* ── МОДАЛ ОВЕРЛЕЙ ── */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: radial-gradient(ellipse at 30% 20%, rgba(250, 171, 52, 0.25) 0%, transparent 55%),
    radial-gradient(ellipse at 80% 80%, rgba(250, 171, 52, 0.18) 0%, transparent 50%),
    rgba(10, 8, 4, 0.72);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    z-index: 1000;
    padding: 0;
}

/* ── МОДАЛ БЛОК ── */
.modal-box {
    background: rgba(255, 255, 255, 0.82);
    backdrop-filter: blur(var(--glass-blur)) saturate(180%);
    -webkit-backdrop-filter: blur(var(--glass-blur)) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.7);
    border-bottom: none;
    box-shadow: 0 -4px 30px rgba(0, 0, 0, 0.18), 0 0 60px rgba(250, 171, 52, 0.12), inset 0 1px 0 rgba(255, 255, 255, 0.9);
    border-radius: 24px 24px 0 0;
    padding: 24px 20px 8px;
    width: 100%;
    max-height: 92vh;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    animation: slideUp 0.28s cubic-bezier(0.34, 1.1, 0.64, 1);
}

@keyframes slideUp {
    from {
        transform: translateY(60px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-box::-webkit-scrollbar {
    width: 4px;
}

.modal-box::-webkit-scrollbar-thumb {
    background: rgba(250, 171, 52, 0.45);
    border-radius: 4px;
}

.modal-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(250, 171, 52, 0.25);
}

.modal-top h3 {
    font-size: 18px;
    font-weight: 700;
    color: #1f1f1f;
    margin: 0;
}

.modal-x {
    width: 36px;
    height: 36px;
    background: rgba(250, 171, 52, 0.15);
    border: 1px solid rgba(250, 171, 52, 0.3);
    border-radius: 50%;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6b7280;
    transition: all 0.2s;
    flex-shrink: 0;
    backdrop-filter: blur(4px);
}

.modal-x:hover {
    background: rgba(250, 171, 52, 0.35);
    color: #1f1f1f;
    border-color: #faab34;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.f-input {
    padding: 12px 14px;
    border: 1.5px solid rgba(229, 231, 235, 0.8);
    border-radius: 10px;
    font-size: 15px;
    color: #1f1f1f;
    background: rgba(255, 255, 255, 0.65);
    backdrop-filter: blur(6px);
    width: 100%;
    box-sizing: border-box;
    -webkit-appearance: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.f-input:focus {
    outline: none;
    border-color: #faab34;
    background: rgba(255, 255, 255, 0.92);
    box-shadow: 0 0 0 3px rgba(250, 171, 52, 0.2);
}

.f-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.f-label {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.date-row {
    display: grid;
    grid-template-columns: 1fr 1.5fr 1fr;
    gap: 8px;
}

.modal-footer {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 20px 0 16px;
    margin-top: 4px;
    border-top: 1px solid rgba(250, 171, 52, 0.2);
}

.booking-summary {
    background: rgba(250, 171, 52, 0.12);
    border: 1px solid rgba(250, 171, 52, 0.35);
    backdrop-filter: blur(8px);
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 4px;
}

.bs-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 7px 0;
    font-size: 14px;
    border-bottom: 1px solid rgba(250, 171, 52, 0.2);
}

.bs-row:last-child {
    border-bottom: none;
}

.bs-label {
    color: #6b7280;
}

.bs-val {
    color: #1f1f1f;
    font-weight: 500;
}

.bs-val--price {
    color: #e8960f;
    font-size: 18px;
    font-weight: 800;
}

.modal-section {
    margin: 16px 0;
}

.modal-section__title {
    font-size: 12px;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 10px;
}

.check-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 14px;
    background: rgba(255, 255, 255, 0.55);
    border: 1.5px solid rgba(229, 231, 235, 0.7);
    backdrop-filter: blur(6px);
    border-radius: 10px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
}

.check-row:hover {
    border-color: #faab34;
    background: rgba(250, 171, 52, 0.08);
}

.check-row input[type="checkbox"] {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
    accent-color: #faab34;
}

.check-name {
    font-size: 14px;
    color: #1f1f1f;
    font-weight: 500;
}

.hint-text {
    font-size: 13px;
    color: #6b7280;
}

.text-center {
    text-align: center;
}

.spinner-wrap {
    text-align: center;
    padding: 24px;
}

.spinner-wrap p {
    color: #6b7280;
    margin-top: 10px;
    font-size: 14px;
}

.flights-scroll {
    max-height: 260px;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.flights-scroll::-webkit-scrollbar {
    width: 4px;
}

.flights-scroll::-webkit-scrollbar-thumb {
    background: rgba(250, 171, 52, 0.5);
    border-radius: 4px;
}

.flight-card {
    border: 1.5px solid rgba(229, 231, 235, 0.7);
    border-radius: 12px;
    padding: 12px;
    background: rgba(255, 255, 255, 0.55);
    backdrop-filter: blur(8px);
    transition: all 0.2s;
}

.flight-card:hover {
    border-color: #faab34;
    background: rgba(250, 171, 52, 0.07);
}

.flight-card--selected {
    border-color: #faab34;
    background: rgba(250, 171, 52, 0.14);
    box-shadow: 0 0 0 3px rgba(250, 171, 52, 0.2);
}

.flight-card__label {
    display: flex;
    gap: 10px;
    cursor: pointer;
    align-items: flex-start;
}

.flight-radio {
    width: 18px;
    height: 18px;
    margin-top: 4px;
    flex-shrink: 0;
    accent-color: #faab34;
}

.flight-legs {
    flex: 1;
    display: grid;
    grid-template-columns: 1fr;
    gap: 10px;
}

.leg-dir {
    font-size: 11px;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 4px;
}

.leg-route {
    font-size: 14px;
    font-weight: 700;
    color: #1f1f1f;
}

.leg-time {
    font-size: 13px;
    color: #e8960f;
    font-weight: 600;
}

.leg-info {
    font-size: 11px;
    color: #6b7280;
    margin-top: 2px;
}

.flight-price-tag {
    text-align: right;
    flex-shrink: 0;
    padding-top: 4px;
}

.price-plus {
    font-size: 14px;
    font-weight: 700;
    color: #e8960f;
}

.price-base {
    font-size: 12px;
    font-weight: 600;
    color: #22c55e;
    background: #f0fdf4;
    padding: 3px 8px;
    border-radius: 20px;
}

.modal-footer--booking {
    border-top: 1px solid rgba(250, 171, 52, 0.2);
    padding: 16px 0;
}

.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
    font-size: 15px;
    color: #6b7280;
}

.total-amount {
    font-size: 24px;
    font-weight: 800;
    color: #1f1f1f;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(250, 171, 52, 0.2);
    border-top-color: #faab34;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* ── ДЕСКТОП ── */
@media (min-width: 768px) {
    .profile-section {
        padding: 32px 0 80px;
    }

    .tabs {
        display: flex;
        grid-template-columns: unset;
        gap: 10px;
    }

    .tabs button {
        flex-direction: row;
        gap: 8px;
        padding: 12px 20px;
        min-height: 48px;
        flex: 1;
        max-width: 180px;
    }

    .tab-icon {
        font-size: 16px;
    }

    .desktop-table-wrap {
        display: block;
    }

    .mobile-only {
        display: none;
    }

    .bookings-list {
        display: none;
    }

    .history-list {
        display: none;
    }

    .modal-overlay {
        align-items: center;
        padding: 20px;
    }

    .modal-box {
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-bottom: 1px solid rgba(255, 255, 255, 0.7);
        width: 640px;
        max-width: 95%;
        max-height: 88vh;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2), 0 0 80px rgba(250, 171, 52, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.9);
    }

    .form-grid {
        grid-template-columns: 1fr 1fr;
    }

    .modal-footer {
        flex-direction: row;
        justify-content: flex-end;
    }

    .modal-footer .w-full {
        width: auto;
    }

    .modal-footer--booking {
        flex-direction: column;
    }

    .modal-footer--booking .w-full {
        width: 100%;
    }

    .flight-legs {
        grid-template-columns: 1fr 1fr;
    }
}

@media (min-width: 1024px) {
    .tabs button {
        flex: unset;
    }

    .modal-footer--booking {
        flex-direction: row;
        align-items: center;
    }

    .total-row {
        flex: 1;
        margin-bottom: 0;
    }

    .modal-footer--booking .w-full {
        width: auto;
    }
}
</style>

<style>
@media (max-width: 767px) {
    .user-profile-area.section-gap,
    .profile-section {
        padding-top: 24px !important;
    }
}
</style>
