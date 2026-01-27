<script>
export default {
    name: "Login",

    methods: {
        login() {
            axios.post()
        }
    },
}
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
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="flight" role="tabpanel"
                             aria-labelledby="flight-tab">
                            <form class="form-wrap">
                                <input type="text" class="form-control" name="phone" placeholder="Номер телефона "
                                       onfocus="this.placeholder = ''" onblur="this.placeholder = 'Номер телефона +7 . . . '">
                                <input type="password" class="form-control" name="password" placeholder="Пароль "
                                       onfocus="this.placeholder = ''" onblur="this.placeholder = 'Пароль '">
                                <input type="submit" class="primary-btn text-uppercase" value="Войти">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>

</style>
