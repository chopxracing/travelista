<script>
import axios from "axios";
export default {
    name: "Contacts",
    data() {
        return {
            name: null,
            email: null,
            theme: null,
            message: null,
        }
    },
    methods: {
        saveMessage() {
            axios.post('/api/contacts', {
                name: this.name,
                email: this.email,
                theme: this.theme,
                message: this.message,
            })
                .then(res => {
                    alert('Сообщение отправлено!');
                    this.name = this.email = this.theme = this.message = '';
                })
                .catch(err => {
                    alert('Ошибка при отправке. Попробуйте ещё раз.');
                    console.error(err);
                });
        }
    },
}

</script>

<template>
    <!-- Баннер -->
    <section class="relative about-banner">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="about-content col-lg-12">
                    <h1 class="text-white">Контакты</h1>
                    <p class="text-white link-nav">
                        <router-link to="/">Главная</router-link>
                        <span class="lnr lnr-arrow-right"></span>
                        <router-link :to="{name: 'contacts'}">Контакты</router-link>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Contact Info -->
    <section class="contact-page-area section-gap">
        <div class="container">
            <div class="row mb-50">
                <div class="col-lg-12 text-center">
                    <h2>Мы всегда на связи</h2>
                    <p>Напишите нам или позвоните — мы ответим максимально быстро и поможем с любыми вопросами.</p>
                </div>
            </div>

            <div class="row">
                <!-- Карточки контактов -->
                <div class="col-lg-4 d-flex flex-column address-wrap mb-30">
                    <div class="single-contact-address d-flex flex-row">
                        <div class="icon">
                            <span class="lnr lnr-map-marker"></span>
                        </div>
                        <div class="contact-details">
                            <h5>Москва, Россия</h5>
                            <p>ул. Пушкина, д. 10, офис 5</p>
                        </div>
                    </div>
                    <div class="single-contact-address d-flex flex-row">
                        <div class="icon">
                            <span class="lnr lnr-phone-handset"></span>
                        </div>
                        <div class="contact-details">
                            <h5>+7 (495) 123-45-67</h5>
                            <p>Пн–Пт: 9:00–18:00</p>
                        </div>
                    </div>
                    <div class="single-contact-address d-flex flex-row">
                        <div class="icon">
                            <span class="lnr lnr-envelope"></span>
                        </div>
                        <div class="contact-details">
                            <h5>support@travelista.ru</h5>
                            <p>Отправьте нам сообщение в любое время</p>
                        </div>
                    </div>

                    <!-- Социальные сети -->
                    <div class="mt-20">
                        <h6>Мы в соцсетях</h6>
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Форма -->
                <div class="col-lg-8">
                    <div class="form-area contact-form text-right" id="myForm">
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <input name="name" v-model="name" placeholder="Ваше имя" class="common-input mb-20 form-control" required="" type="text">
                                <input name="email" v-model="email" placeholder="Email" class="common-input mb-20 form-control" required="" type="email">
                                <input name="subject" v-model="theme" placeholder="Тема" class="common-input mb-20 form-control" required="" type="text">
                            </div>
                            <div class="col-lg-6 form-group">
                                <textarea class="common-textarea form-control" name="message" placeholder="Сообщение" v-model="message" required=""></textarea>
                            </div>
                            <div class="col-lg-12">
                                <div class="alert-msg" style="text-align: left;"></div>
                                <button class="genric-btn primary" @click="saveMessage" style="float: right;">Отправить сообщение</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Мини-блок доверия -->
            <div class="row mt-50">
                <div class="col-lg-12 text-center">
                    <h4>Мы сотрудничаем с крупнейшими туристическими компаниями и страховыми партнерами</h4>
                    <p>Allianz, Ergo, Росгосстрах, TravelPartner и многие другие — ваша безопасность и комфорт для нас на первом месте.</p>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.address-wrap .single-contact-address {
    margin-bottom: 20px;
}
.address-wrap .icon {
    font-size: 24px;
    color: #f8b600;
    margin-right: 15px;
}
.address-wrap h5 {
    font-weight: 600;
    margin-bottom: 5px;
}
.address-wrap p {
    font-size: 14px;
    color: #555;
}
.address-wrap a {
    margin-right: 10px;
    font-size: 18px;
    color: #555;
    transition: 0.3s;
}
.address-wrap a:hover {
    color: #f8b600;
}
</style>
