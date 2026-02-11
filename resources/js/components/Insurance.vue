<script>
export default {
    name: "Insurance"
}
</script>

<template>
    <!-- Баннер -->
    <section class="about-banner relative">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="about-content col-lg-12">
                    <h1 class="text-white">Страхование путешествий</h1>
                    <p class="text-white link-nav">
                        <router-link to="/">Главная</router-link>
                        <span class="lnr lnr-arrow-right"></span>
                        <router-link :to="{name: 'insurance'}">Страхование</router-link>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Первая секция страхования -->
    <section class="insurence-one-area section-gap">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 insurence-left">
                    <img class="img-fluid img-one" :src="'img/insurence/i1.jpg'" alt="Страхование">
                </div>
                <div class="col-lg-6 insurence-right">
                    <h6 class="text-uppercase">Что важно знать</h6>
                    <h1>Страхование для путешествий</h1>
                    <p>
                        Мы сотрудничаем с ведущими страховыми компаниями, чтобы ваши поездки были максимально безопасными.
                        Наша цель — обеспечить спокойствие каждому клиенту, независимо от маршрута и длительности путешествия.
                    </p>
                    <div class="list-wrap">
                        <ul>
                            <li>Медицинская страховка по всему миру.</li>
                            <li>Страхование багажа и личных вещей.</li>
                            <li>Отмена или перенос поездки без потерь.</li>
                            <li>Сотрудничество с крупнейшими страховыми компаниями.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End insurence-one Area -->

    <!-- Вторая секция страхования -->
    <section class="insurence-two-area pb-120">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 insurence-left">
                    <h6 class="text-uppercase">Почему выбирают нас</h6>
                    <h1>Партнерство и надежность</h1>
                    <p>
                        Мы гордимся тем, что сотрудничаем с ведущими страховщиками: Allianz, Ergo, Росгосстрах и другими.
                        Это гарантирует надежную защиту, простое оформление и оперативную поддержку во время путешествия.
                    </p>
                    <div class="list-wrap">
                        <ul>
                            <li>Прозрачные условия страхования.</li>
                            <li>Быстрое оформление онлайн за пару минут.</li>
                            <li>Поддержка 24/7 на русском языке.</li>
                            <li>Персональный менеджер для корпоративных клиентов.</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 insurence-right">
                    <img class="img-fluid img-two" :src="'img/insurence/i2.jpg'" alt="Партнерство">
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.list-wrap ul {
    list-style: disc;
    padding-left: 20px;
    margin-top: 15px;
}

.list-wrap ul li {
    margin-bottom: 10px;
    font-size: 14px;
    color: #555;
}
</style>
