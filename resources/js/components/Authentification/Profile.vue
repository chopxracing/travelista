<script>
import { inject } from 'vue';
import axios from 'axios';

export default {
    name: "UserProfile",
    setup() {
        const currentUser = inject('currentUser'); // получаем пользователя из App.vue
        return { currentUser };
    },
    data() {
        return {
            bookings: [],
            cart: [],
            loading: false,
            error: null
        };
    },
    methods: {
        async fetchBookings() {
            if (!this.currentUser.user) return;

            this.loading = true;
            try {
                const res = await axios.get('http://localhost:8876/api/bookings');
                this.bookings = res.data;
            } catch (err) {
                console.error(err);
                this.error = 'Не удалось загрузить бронирования';
            } finally {
                this.loading = false;
            }
        },
        async fetchCart() {
            if (!this.currentUser.user) return;

            this.loading = true;
            try {
                const res = await axios.get('http://localhost:8876/api/cart');
                this.cart = res.data;
            } catch (err) {
                console.error(err);
                this.error = 'Не удалось загрузить корзину';
            } finally {
                this.loading = false;
            }
        }
    },
    mounted() {
        this.fetchBookings();
        this.fetchCart();
    }
};
</script>

<template>
    <section class="destinations-area section-gap">
        <div class="container">

            <!-- Информация о пользователе -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="single-destinations p-4 shadow-sm rounded">
                        <h3>Профиль пользователя</h3>
                        <p v-if="currentUser.user"><strong>Имя:</strong> {{ currentUser.user.name }}</p>
                        <p v-if="currentUser.user"><strong>Email:</strong> {{ currentUser.user.email }}</p>
                        <p v-if="currentUser.user"><strong>Телефон:</strong> {{ currentUser.user.phone }}</p>
                        <p v-else>Загрузка данных пользователя...</p>
                    </div>
                </div>
            </div>

            <!-- Кнопки управления -->
            <div class="row mb-4">
                <div class="col-lg-4 col-md-6 mb-3" v-for="(button, index) in [
                { title: 'Мои бронирования', desc: 'Посмотреть все ваши текущие бронирования', route: '/my-bookings' },
                { title: 'Корзина', desc: 'Посмотреть выбранные туры и отели', route: '/cart' },
                { title: 'Настройки профиля', desc: 'Изменить контактные данные и пароль', route: '/profile/settings' }
            ]" :key="index">
                    <div class="single-destinations text-center p-4 shadow-sm rounded">
                        <h4>{{ button.title }}</h4>
                        <p>{{ button.desc }}</p>
                        <button class="primary-btn" @click="$router.push(button.route)">Перейти</button>
                    </div>
                </div>
            </div>

            <!-- Мини-таблица бронирований -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="single-destinations p-4 shadow-sm rounded">
                        <h4>Последние бронирования</h4>
                        <table class="table table-bordered mt-3">
                            <thead>
                            <tr>
                                <th>Отель / Тур</th>
                                <th>Дата заезда</th>
                                <th>Дата выезда</th>
                                <th>Статус</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="booking in bookings" :key="booking.id">
                                <td>{{ booking.hotel_name || booking.tour_name }}</td>
                                <td>{{ booking.check_in }}</td>
                                <td>{{ booking.check_out }}</td>
                                <td>{{ booking.status }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Мини-таблица корзины -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="single-destinations p-4 shadow-sm rounded">
                        <h4>Корзина</h4>
                        <table class="table table-bordered mt-3">
                            <thead>
                            <tr>
                                <th>Название</th>
                                <th>Тип</th>
                                <th>Цена</th>
                                <th>Количество</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in cart" :key="item.id">
                                <td>{{ item.name }}</td>
                                <td>{{ item.type }}</td>
                                <td>{{ item.price }} руб.</td>
                                <td>{{ item.quantity }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div v-if="loading" class="text-center py-5">
                <div class="spinner"></div>
            </div>

            <div v-if="error" class="alert alert-danger">{{ error }}</div>

        </div>
    </section>
</template>

<style scoped>
.single-destinations {
    background: #fff;
    transition: all 0.3s ease;
    margin-bottom: 20px;
}
.single-destinations:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}
.single-destinations h3,
.single-destinations h4 {
    margin-bottom: 15px;
}
.single-destinations p {
    font-size: 14px;
    margin-bottom: 10px;
    color: #555;
}
.primary-btn {
    background: #faab34;
    color: #fff;
    border: none;
    padding: 10px 25px;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.2s;
}
.primary-btn:hover {
    background: #f98e00;
}
.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #ffffff33;
    border-top-color: #faab34;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
table {
    width: 100%;
    border-collapse: collapse;
}
table th, table td {
    padding: 10px;
    text-align: left;
    border: 1px solid #e1e1e1;
}
</style>
