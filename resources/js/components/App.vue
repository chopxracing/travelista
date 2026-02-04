<script>
import axios from "axios";
import { reactive, provide } from 'vue';
export default {
    name: "App",
    data() {
        return {
            isPageLoading: false,
            userState: reactive({ user: null })
        };
    },
    provide() {
        return {
            currentUser: this.userState
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
                const res = await axios.get('http://localhost:8876/api/user');
                this.userState.user = res.data; // реактивно обновляется
            } catch {
                this.userState.user = null;
            }
        },
        async logout() {
            try {
                await axios.post('http://localhost:8876/api/logout');
            } catch (err) {
                console.error(err);
            }
            this.user = null;
            localStorage.removeItem('api_token');
            delete axios.defaults.headers.common['Authorization'];
            this.$router.push('/login');
        }
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
                        <li><a href="insurance.html">Insurence</a></li>
                        <li class="menu-has-children"><a href="">Blog</a>
                            <ul>
                                <li><a href="blog-home.html">Blog Home</a></li>
                                <li><a href="blog-single.html">Blog Single</a></li>
                            </ul>
                        </li>
                        <li class="menu-has-children"><a href="">Pages</a>
                            <ul>
                                <li><a href="elements.html">Elements</a></li>
                                <li class="menu-has-children"><a href="">Level 2 </a>
                                    <ul>
                                        <li><a href="#">Item One</a></li>
                                        <li><a href="#">Item Two</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="contact.html">Contact</a></li>
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

    <footer v-if="showLayout" class="footer-area section-gap">
        <div class="container">

            <div class="row">
                <div class="col-lg-3  col-md-6 col-sm-6">
                    <div class="single-footer-widget">
                        <h6>About Agency</h6>
                        <p>
                            The world has become so fast paced that people don’t want to stand by reading a page of information, they would much rather look at a presentation and understand the message. It has come to a point
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="single-footer-widget">
                        <h6>Navigation Links</h6>
                        <div class="row">
                            <div class="col">
                                <ul>
                                    <li><a href="#">Home</a></li>
                                    <li><a href="#">Feature</a></li>
                                    <li><a href="#">Services</a></li>
                                    <li><a href="#">Portfolio</a></li>
                                </ul>
                            </div>
                            <div class="col">
                                <ul>
                                    <li><a href="#">Team</a></li>
                                    <li><a href="#">Pricing</a></li>
                                    <li><a href="#">Blog</a></li>
                                    <li><a href="#">Contact</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3  col-md-6 col-sm-6">
                    <div class="single-footer-widget">
                        <h6>Newsletter</h6>
                        <p>
                            For business professionals caught between high OEM price and mediocre print and graphic output.
                        </p>
                        <div id="mc_embed_signup">
                            <form target="_blank" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01" method="get" class="subscription relative">
                                <div class="input-group d-flex flex-row">
                                    <input name="EMAIL" placeholder="Email Address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email Address '" required="" type="email">
                                    <button class="btn bb-btn"><span class="lnr lnr-location"></span></button>
                                </div>
                                <div class="mt-10 info"></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3  col-md-6 col-sm-6">
                    <div class="single-footer-widget mail-chimp">
                        <h6 class="mb-20">InstaFeed</h6>
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

            <div class="row footer-bottom d-flex justify-content-between align-items-center">
                <p class="col-lg-8 col-sm-12 footer-text m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    Copyright &copy; All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                <div class="col-lg-4 col-sm-12 footer-social">
                    <a href="#"><i class="fa fa-facebook"></i></a>
                    <a href="#"><i class="fa fa-twitter"></i></a>
                    <a href="#"><i class="fa fa-dribbble"></i></a>
                    <a href="#"><i class="fa fa-behance"></i></a>
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
