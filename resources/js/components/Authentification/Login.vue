<script>
import axios from 'axios';
export default {
    data() {
        return {
            phone: '',
            password: '',
            error: null
        };
    },
    inject: ['fetchUser'],
    methods: {
        async login() {
            this.error = null;

            try {
                const response = await axios.post('/api/login', {
                    phone: this.phone,
                    password: this.password
                });

                const token = response.data.token;
                localStorage.setItem('api_token', token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                await this.fetchUser();
                this.$router.push('/');

            } catch (err) {
                // Разбор ошибок от сервера
                if (err.response) {
                    const status = err.response.status;
                    const message = err.response.data?.message;

                    if (status === 403) {
                        // Email не подтверждён
                        this.error = message || 'Email не подтверждён. Проверьте почту.';
                    } else if (status === 401) {
                        // Неверные данные
                        this.error = message || 'Неверный телефон или пароль';
                    } else {
                        // Другая ошибка сервера
                        this.error = message || 'Произошла ошибка при входе';
                    }
                } else {
                    // Сервер недоступен или сеть
                    this.error = 'Сервер недоступен';
                }

                console.error(err.response?.data);
            }
        }

    },
    mounted() {
        $('.fullscreen').css('height', $(window).height());
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        if (status === 'verified') {
            alert('Email успешно подтверждён! Можете войти.');
        } else if (status === 'invalid') {
            alert('Ссылка недействительна или устарела.');
        }
    }
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
                    <div v-if="error" class="error-box">
                        {{ error }}
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 banner-right">

                            <form class="form-wrap" @submit.prevent="login">
                                <input type="text" class="form-control" v-model="phone" placeholder="Номер телефона +7 . . ." />
                                <input type="password" class="form-control" v-model="password" placeholder="Пароль" />
                                <button type="submit" class="primary-btn text-uppercase min-vw-100">Войти</button>
                            </form>
                            <router-link :to="{name: 'register'}" class="d-flex justify-content-center primary-btn">
                                Нет аккаунта? Зарегистрироваться
                            </router-link>
                </div>
            </div>
        </div>
    </section>
</template>


<style scoped>
.error-box {
    background-color: #ff5656;
    color: #ffffff;
    padding: 12px;
    border-radius: 6px;
    margin-top: 10px;
    text-align: center;
    font-weight: 500;
}
</style>
