<script>
import axios from 'axios';

export default {
    name: 'UserProfile',

    inject: ['currentUser', 'fetchUser'], // из App.vue

    data() {
        return {
            activeTab: 'profile', // profile | bookings | cart | settings
            bookings: [],
            cart: [],
            loading: false,
            error: null,
            showTouristModal: false,
            showBookingModal: false,
            selectedBooking: null,
            selectedTourists: [], // выбранные туристы

            surname: '',
            name: '',
            last_name: '',
            passport_series: '',
            passport_number: '',
            passport_date: '',
            passport_org: '',
            passportDate: {
                day: '',
                month: '',
                year: '',
            },
            birthDate: {
                day: '',
                month: '',
                year: '',
            },

            birth_date: '',

        };
    },

    mounted() {
        this.fetchBookings();
        console.log(this.currentUser);
    },

    methods: {
        async fetchBookings() {
            this.loading = true;
            this.error = null;

            try {
                const res = await axios.get('/api/bookings');
                this.bookings = res.data.data;
                console.log(this.bookings)
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
            const today = new Date();
            const check = new Date(year, month - 1, 1);
            return check > today;
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

            this.passportDate = { day: '', month: '', year: '' };
            this.birthDate = { day: '', month: '', year: '' };
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
                birth_date: this.birth_date,
            })
                const res = await axios.get('/api/user'); // endpoint с текущим пользователем
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
                // Обновляем список туристов после удаления
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
                // Обновляем список после удаления
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
            this.selectedTourists = []; // сброс выбранных туристов
        },

        closeBookingModal() {
            this.showBookingModal = false;
            this.selectedBooking = null;
            this.selectedTourists = [];
        },

        async confirmBooking() {
            if (!this.selectedBooking) return;

            try {
                const res = await axios.post('/api/payments/create', {
                    booking_id: this.selectedBooking.id
                });

                window.location.href = res.data.url;

            } catch (error) {
                console.error(error);
                alert('Ошибка при создании платежа');
            }
        }
    },
    computed: {
        currentYear() {
            return new Date().getFullYear();
        },

        passportYears() {
            return Array.from(
                { length: this.currentYear - 1899 },
                (_, i) => this.currentYear - i
            );
        },

        birthYears() {
            return Array.from(
                { length: this.currentYear - 1899 },
                (_, i) => this.currentYear - i
            );
        },

        passportDays() {
            const { month, year } = this.passportDate;
            if (!month || !year) return [];

            // запрет будущего месяца/года
            if (this.isFutureDate(year, month)) return [];

            const days = new Date(year, month, 0).getDate();
            return Array.from({ length: days }, (_, i) => i + 1);
        },

        birthDays() {
            const { month, year } = this.birthDate;
            if (!month || !year) return [];

            if (this.isFutureDate(year, month)) return [];

            const days = new Date(year, month, 0).getDate();
            return Array.from({ length: days }, (_, i) => i + 1);
        },

    },
    watch: {
        passportDate: {
            deep: true,
            handler() {
                const { day, month, year } = this.passportDate;
                if (!day || !month || !year) return;

                const date = new Date(year, month - 1, day);
                if (date > new Date()) {
                    this.passport_date = '';
                    return;
                }

                this.passport_date =
                    `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            },
        },

        birthDate: {
            deep: true,
            handler() {
                const { day, month, year } = this.birthDate;
                if (!day || !month || !year) return;

                const date = new Date(year, month - 1, day);
                if (date > new Date()) {
                    this.birth_date = '';
                    return;
                }

                this.birth_date =
                    `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            },
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
                    <h1 class="text-white">
                        Привет, {{ currentUser.user?.name }}
                    </h1>
                    <p class="text-white link-nav">
                        <router-link :to="{ name: 'index'}" class="primary-btn">На главную</router-link>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="user-profile-area section-gap">
        <div class="container">

            <!-- Вкладки -->
            <div class="tabs mb-4">
                <button :class="{'active-tab': activeTab==='profile'}" @click="activeTab='profile'">Профиль</button>
                <button :class="{'active-tab': activeTab==='bookings'}" @click="activeTab='bookings'">Мои бронирования</button>
                <button :class="{'active-tab': activeTab==='settings'}" @click="activeTab='settings'">Настройки</button>
                <button :class="{'active-tab': activeTab==='tourists'}" @click="activeTab='tourists'">Данные</button>
            </div>

            <!-- Контент вкладок -->
            <div v-if="activeTab==='profile'" class="tab-content">
                <div class="profile-card p-4 shadow-sm rounded">
                    <h3>Привет, {{ currentUser.user?.name || 'Пользователь' }}!</h3>
                    <p v-if="currentUser.user"><strong>Email:</strong> {{ currentUser.user.email }}</p>
                    <p v-if="currentUser.user"><strong>Телефон:</strong> {{ currentUser.user.phone }}</p>
                    <p v-else>Загрузка данных пользователя...</p>
                    <button type="button" class="primary-btn" @click="logout">Выход</button>
                </div>
            </div>

            <div v-if="activeTab==='bookings'" class="tab-content">
                <div class="table-card p-4 shadow-sm rounded">
                    <h4>Мои бронирования</h4>
                    <table class="table mt-3">
                        <thead>
                        <tr>
                            <th>Отель / Тур</th>
                            <th>Дата заезда</th>
                            <th>Дата выезда</th>
                            <th>Статус</th>
                            <th>Стоимость</th>
                            <th>Статус оплаты</th>
                            <th>Оформление</th>
                            <th>Удалить</th>
                        </tr>
                        </thead>
                        <tbody v-if="bookings">
                        <tr v-for="booking in bookings" :key="booking.id">
                            <td>{{ booking.hotel.name || booking.tour.name }}</td>
                            <td>{{ booking.date_from }}</td>
                            <td>{{ booking.date_to }}</td>
                            <td>{{ booking.status.name }}</td>
                            <td>{{ booking.payment.amount }} руб.</td>
                            <td v-if="booking.is_paid == 0">Не оплачен</td>
                            <td v-else>Оплачен</td>
                            <td><button type="button" @click="openBookingModal(booking)" class="primary-btn">Перейти к оформлению</button></td>
                            <td><button type="button" @click="deleteBooking(booking.id)" class="btn btn-outline-danger">Удалить</button></td>
                        </tr>
                        <tr v-if="bookings.length===0">
                            <td colspan="4" class="text-center">Нет бронирований</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div v-if="activeTab==='tourists'" class="tab-content">
                <div class="table-card p-4 shadow-sm rounded">
                    <div class="row">
                    <h4 class="p-3">Мои данные</h4>
                        <button class="primary-btn ml-2" @click="openTouristModal">
                            Добавить
                        </button>
                    </div>
                    <table class="table mt-3">
                        <thead>
                        <tr>
                            <th>Фамилия</th>
                            <th>Имя</th>
                            <th>Отчество</th>
                            <th>Серия</th>
                            <th>Номер</th>
                            <th>Дата выдачи</th>
                            <th>Организация</th>
                            <th>Дата рождения</th>
                            <th>Удалить</th>
                        </tr>
                        </thead>
                        <tbody v-if="currentUser.user.tourists">
                        <tr v-for="tourist in currentUser.user.tourists" :key="tourist.id">
                            <td>{{ tourist.surname }}</td>
                            <td>{{ tourist.name }}</td>
                            <td>{{ tourist.last_name }}</td>
                            <td>{{ tourist.passport_series }}</td>
                            <td>{{ tourist.passport_number }}</td>
                            <td>{{ tourist.passport_date }}</td>
                            <td>{{ tourist.passport_org }}</td>
                            <td>{{ tourist.birth_date }}</td>
                            <td><button class="btn btn-danger" @click="deleteTourist(tourist.id)">Удалить</button></td>
                        </tr>
                        <tr v-if="bookings.length===0">
                            <td colspan="4" class="text-center">Нет бронирований</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-if="activeTab==='settings'" class="tab-content">
                <div class="profile-card p-4 shadow-sm rounded">
                    <h4>Настройки профиля</h4>
                    <p>Здесь можно будет изменить email, телефон и пароль.</p>
                    <button class="primary-btn">Редактировать профиль</button>
                </div>
            </div>

            <div v-if="loading" class="text-center py-5">
                <div class="spinner"></div>
            </div>
            <div v-if="error" class="alert alert-danger">{{ error }}</div>
        </div>
    </section>



    <div v-show="showTouristModal" class="modal-overlay">
        <div class="modal-card">
            <h4>Добавить туриста</h4>

            <div class="form-grid">
                <input type="text" v-model="surname" placeholder="Фамилия">
                <input type="text"  v-model="name" placeholder="Имя">
                <input type="text"  v-model="last_name" placeholder="Отчество">

                <input type="text"  v-model="passport_series" placeholder="Серия паспорта">
                <input type="text"  v-model="passport_number" placeholder="Номер паспорта">

                <div class="date-block">
                    <label class="date-label">Дата выдачи паспорта</label>
                    <div class="date-select">
                        <select v-model="passportDate.day" class="form-input">
                            <option value="">День</option>
                            <option v-for="d in passportDays" :key="d" :value="d">{{ d }}</option>
                        </select>

                        <select v-model="passportDate.month" class="form-input">
                            <option value="">Месяц</option>
                            <option v-for="m in 12" :key="m" :value="m">
                                {{ new Date(0, m - 1).toLocaleString('ru', { month: 'long' }) }}
                            </option>
                        </select>

                        <select v-model="passportDate.year" class="form-input">
                            <option value="">Год</option>
                            <option v-for="y in passportYears" :key="y" :value="y">{{ y }}</option>
                        </select>
                    </div>
                </div>
                <input type="text"  v-model="passport_org" placeholder="Кем выдан">

                <div class="date-block">
                    <label class="date-label">Дата рождения</label>
                    <div class="date-select">
                        <select v-model="birthDate.day" class="form-input">
                            <option value="">День</option>
                            <option v-for="d in birthDays" :key="d" :value="d">{{ d }}</option>
                        </select>

                        <select v-model="birthDate.month" class="form-input">
                            <option value="">Месяц</option>
                            <option v-for="m in 12" :key="m" :value="m">
                                {{ new Date(0, m - 1).toLocaleString('ru', { month: 'long' }) }}
                            </option>
                        </select>

                        <select v-model="birthDate.year" class="form-input">
                            <option value="">Год</option>
                            <option v-for="y in birthYears" :key="y" :value="y">{{ y }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-actions">
                <button class="primary-btn" @click="saveTourist">Сохранить</button>
                <button class="btn-cancel" @click="closeTouristModal">Отмена</button>
            </div>
        </div>
    </div>
    <div v-if="showBookingModal" class="modal-overlay">
        <div class="modal-card">
            <h4>Оформление бронирования</h4>

            <div class="form-grid">
                <p><strong>Отель / Тур:</strong> {{ selectedBooking.hotel?.name || selectedBooking.tour?.name }}</p>
                <p><strong>Дата заезда:</strong> {{ selectedBooking.date_from }}</p>
                <p><strong>Дата выезда:</strong> {{ selectedBooking.date_to }}</p>
                <p><strong>Стоимость:</strong> {{ selectedBooking.payment.amount }} руб.</p>

                <div class="date-block">
                    <label class="date-label">Выберите туристов</label>
                    <div v-if="currentUser.user?.tourists && currentUser.user.tourists.length">
                        <div v-for="tourist in currentUser.user.tourists" :key="tourist.id">
                            <label>
                                <input type="checkbox" :value="tourist.id" v-model="selectedTourists">
                                {{ tourist.surname }} {{ tourist.name }} {{ tourist.last_name }}
                            </label>
                        </div>
                    </div>
                    <p v-else>Нет добавленных туристов. Добавьте их в разделе "Данные".</p>
                </div>

            </div>

            <div class="modal-actions">
                <button class="primary-btn" @click="confirmBooking">Перейти к оплате</button>
                <button class="btn-cancel" @click="closeBookingModal">Отмена</button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.primary-btn {
    border-radius: 5px;
}
.btn-outline-danger {
min-height: 45px;
}
.tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}
.tabs button {
    background: #fff;
    border: 1px solid #ddd;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}
.tabs button.active-tab {
    background: #faab34;
    color: #fff;
    border-color: #faab34;
}
.tabs button:hover:not(.active-tab) {
    background: #f8f8f8;
}

/* Карточки и таблицы */
.profile-card, .table-card {
    background: #fff;
    transition: all 0.3s ease;
}
.profile-card:hover, .table-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}
.profile-card h3, .profile-card h4, .table-card h4 {
    margin-bottom: 15px;
    color: #333;
}
.profile-card p {
    font-size: 14px;
    color: #555;
}

/* Кнопки */

.primary-btn:hover {
    background: #f98e00;
}
.primary-btn:focus {
    outline: none;
    box-shadow: none;
}

/* Таблицы */
.table {
    width: 100%;
    border-collapse: collapse;
}
.table th, .table td {
    padding: 10px;
    text-align: left;
    border: 1px solid #e1e1e1;
    font-size: 14px;
}

/* Спиннер */
.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #ffffff33;
    border-top-color: #faab34;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto;
}
@keyframes spin { to { transform: rotate(360deg); } }


/*      МОДАЛЬНОЕ ОКНО  ТУРИСТА    */

.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-card {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    width: 600px;
    max-width: 95%;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-top: 15px;
}

.form-grid input {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

.btn-cancel {
    background: #eee;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
}
.date-select {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.form-input {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    background: #fff;
}
.date-block {
    grid-column: span 2;
}

.date-label {
    font-size: 13px;
    color: #666;
    margin-bottom: 6px;
    display: block;
}
</style>
