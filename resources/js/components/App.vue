<script>
import axios from "axios";
import { reactive, provide } from 'vue';
export default {
    name: "App",
    data() {
        return {
            isPageLoading: false,
            userState: reactive({ user: null }),
            filters: {
                tour_type_id: null,
            },
        };
    },
    provide() {
        return {
            currentUser: this.userState,
            fetchUser: this.fetchUser
        };
    },
    computed: {
        showLayout() {
            return this.$route.meta.layout !== false;
        },
        user() {
            return this.userState.user; // теперь доступно как `user` в шаблоне
        }
    },
    created() {
        // Глобальный спиннер при переходах
        this.$router.beforeEach((to, from, next) => {
            this.isPageLoading = true;
            next();
        });

        this.$router.afterEach(() => {
            setTimeout(() => {
                this.isPageLoading = false;
            }, 300); // плавность
        });
    },
    methods: {
        async fetchUser() {
            try {
                const res = await axios.get('/api/user');
                this.userState.user = res.data.data; // реактивно обновляется
            } catch {
                this.userState.user = null;
            }
        },
        async logout() {
            try {
                await axios.post('/api/logout');
            } catch (err) {
                console.error(err);
            }
            this.user = null;
            localStorage.removeItem('api_token');
            delete axios.defaults.headers.common['Authorization'];
            this.$router.push('/login');
        },

    },
    mounted() {
        // Берем токен и сразу ставим его в axios
        const token = localStorage.getItem('api_token');
        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        }

        // Теперь можем безопасно получить пользователя
        this.fetchUser();
    }
};
</script>

<template>
    <transition name="fade">
        <div v-if="isPageLoading" class="page-loader">
            <div class="spinner"></div>
        </div>
    </transition>
    <header v-if="showLayout" id="header">
        <div class="header-top">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-sm-6 col-6 header-top-left">
                        <ul>
                            <li v-if="user"> <router-link :to="{name: 'profile'}"> {{ user.surname }} {{ user.name }} </router-link>
                            </li>
                            <li v-else> <router-link :to="{name: 'login'}">Войти в личный кабинет</router-link>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-6 header-top-right">
                        <div class="header-social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-dribbble"></i></a>
                            <a href="#"><i class="fa fa-behance"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container main-menu">
            <div class="row align-items-center justify-content-between d-flex">
                <div id="logo">
                    <router-link to="/"><img :src="'/img/logo.png'" alt="" title="" /></router-link>
                </div>
                <nav id="nav-menu-container">
                    <ul class="nav-menu">
                        <li> <router-link to="/">Главная</router-link></li>
                        <li> <router-link to="/about">О нас</router-link> </li>
                        <li><router-link :to="{ name: 'tours'}">Туры</router-link></li>
                        <li><router-link to="/hotels">Отели</router-link></li>
                        <li><router-link :to="{name: 'insurance'}">Страхование</router-link></li>
                        <li><router-link :to="{name: 'blog'}">Наш Блог</router-link></li>
                        <li><router-link :to="{name: 'contacts'}">Контакты</router-link></li>
                    </ul>
                </nav><!-- #nav-menu-container -->
            </div>
        </div>
    </header>

    <router-view v-slot="{ Component }">
        <transition name="fade">
            <component :is="Component" />
        </transition>
    </router-view>



    <!-- End testimonial Area -->

    <!-- Start home-about Area -->
    <section v-if="showLayout" class="home-about-area">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-end">
                <div class="col-lg-6 col-md-12 home-about-left">
                    <h1>
                        Не нашли подходящий набор? <br>
                        Не переживайте! <br>
                        Мы создадим для вас идеальный вариант
                    </h1>
                    <p>
                        Если вы не нашли то, что искали, просто напишите нам. Мы подберем решение специально для вас, чтобы ваш опыт был максимально удобным и приятным.
                    </p>
                    <router-link :to="{name: 'contacts'}" class="primary-btn text-uppercase">Запросить цену</router-link>
                </div>
                <div class="col-lg-6 col-md-12 home-about-right no-padding">
                    <img class="img-fluid" :src="'img/about-img'.jpg" alt="">
                </div>
            </div>
        </div>
    </section>
    <footer v-if="showLayout" class="footer-area section-gap">
        <div class="container">

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="single-footer-widget">
                        <h6>О компании</h6>
                        <p>
                            Мир движется так быстро, что людям не хочется читать длинные тексты — проще увидеть презентацию и сразу понять суть. Мы создаём решения, которые упрощают вашу жизнь.
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="single-footer-widget">
                        <h6>Навигация</h6>
                        <div class="row">
                            <div class="col">
                                <ul>
                                    <li><router-link to="/">Главная</router-link></li>
                                    <li><router-link to="/about">О нас</router-link></li>
                                    <li><router-link to="/tours">Туры</router-link></li>
                                    <li><router-link to="/hotels">Отели</router-link></li>
                                </ul>
                            </div>
                            <div class="col">
                                <ul>
                                    <li><a href="#">Команда</a></li>
                                    <li><a href="#">Цены</a></li>
                                    <li><a href="#">Блог</a></li>
                                    <li><router-link :to="{name: 'contacts'}">Контакты</router-link></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="single-footer-widget">
                        <h6>Подписка на новости</h6>
                        <p>
                            Получайте полезные материалы, новости и специальные предложения на ваш email.
                        </p>
                        <div id="mc_embed_signup">
                            <form target="_blank" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01" method="get" class="subscription relative">
                                <div class="input-group d-flex flex-row">
                                    <input name="EMAIL" placeholder="Ваш email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Ваш email'" required="" type="email">
                                    <button class="btn bb-btn"><span class="lnr lnr-location"></span></button>
                                </div>
                                <div class="mt-10 info"></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="single-footer-widget mail-chimp">
                        <h6 class="mb-20">Instagram</h6>
                        <ul class="instafeed d-flex flex-wrap">
                            <li><img :src="'/img/i1.jpg'" alt=""></li>
                            <li><img :src="'/img/i2.jpg'" alt=""></li>
                            <li><img :src="'/img/i3.jpg'" alt=""></li>
                            <li><img :src="'/img/i4.jpg'" alt=""></li>
                            <li><img :src="'/img/i5.jpg'" alt=""></li>
                            <li><img :src="'/img/i6.jpg'" alt=""></li>
                            <li><img :src="'/img/i7.jpg'" alt=""></li>
                            <li><img :src="'/img/i8.jpg'" alt=""></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row footer-bottom d-flex justify-content-between align-items-center mt-4">
                <div class="col-lg-4 col-sm-12 footer-social">
                    <a href="#"><i class="fa fa-facebook"></i></a>
                    <a href="#"><i class="fa fa-twitter"></i></a>
                    <a href="#"><i class="fa fa-dribbble"></i></a>
                    <a href="#"><i class="fa fa-behance"></i></a>
                </div>
                <div class="col-lg-8 col-sm-12 text-right">
                    <p>© 2026 Все права защищены. Travelista</p>
                </div>
            </div>

        </div>
    </footer>
</template>

<style>
.page-loader {
    position: fixed;
    inset: 0;
    background: #000000aa;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #ffffff33;
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
