<template>
    <div class="main-container">
        <div class="content-container">
            <div class="wrapper">
                <h1>Зарегистрируйтесь, чтобы быстро и точно выбрать фильм</h1>

                <div class="wrapper-text">
                    <p class="wrapper__p1">Подбирайте фильмы на основе своих интересов</p>
                    <p class="wrapper__p2">Создавайте свои собственные подборки фильмов</p>
                    <p class="wrapper__p3">Попробуйте новый полезный сервис</p>
                </div>

                <div class="btn" @click="auth()">
                    <router-link to="/authorization">Войти</router-link>
                </div>
            </div>

            <img src="../assets/images/test.png" alt="Пример страницы с подборками" title="Пример страницы с подборками">
        </div>
    </div>
</template>

<script>

export default {
    name: 'MainView',

    methods: {
        auth() {
            this.$router.push({path: '/authorization'});
        }
    }
}
</script>

<style scoped lang="scss">
@import "@/assets/styles/_variables.scss";

.main-container {
    .content-container {
        margin-top: 100px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;

        .wrapper {
            width: 40%;
            margin-left: 60px;

            h1 {
                font-weight: $SemiBoldWeight;
                color: $whiteColor;
                font-size: 40px;
            }

            .wrapper-text {
                margin-top: 50px;
                gap: 20px 0;

                p {
                    color: $whiteColor;
                    font-size: 18px;
                }

                .wrapper__p1 {
                    color: $blue;
                }

                .wrapper__p2 {
                    color: $cyan;
                }

                .wrapper__p3 {
                    color: $pink;
                }
            }

            .btn {
                margin-top: 50px;

                a {
                    color: $btnColor;
                }
            }
        }

        img {
            width: calc(100% - 40% - 60px);
            margin-right: -55px;
        }
    }
}

@media screen and (min-width: 767px) and (max-width: 1023px) {
    .main-container {
        z-index: 2;
        position: relative;
        margin-top: -100px;
        .content-container {
            margin-top: 100px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;

            .wrapper {
                width: 45%;
                margin-left: 0px;

                h1 {
                    font-weight: $SemiBoldWeight;
                    color: $whiteColor;
                    font-size: 35px;
                }

                .wrapper-text {
                    margin-top: 50px;
                    gap: 20px 0;

                    p {
                        color: $whiteColor;
                        font-size: 17px;
                    }

                    .wrapper__p1 {
                        color: $blue;
                    }

                    .wrapper__p2 {
                        color: $cyan;
                    }

                    .wrapper__p3 {
                        color: $pink;
                    }
                }

                .btn {
                    margin-top: 50px;

                    a {
                        color: $btnColor;
                    }
                }
            }

            img {
                width: 70%;
                margin-top: 50px;
                margin-right: -175px;
            }
        }
    }
}

@media screen and (max-width: 767px) {
    .main-container {
        .content-container {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;

            .wrapper {
                width: 100%;
                margin-left: 0px;

                h1 {
                    font-weight: $SemiBoldWeight;
                    color: $whiteColor;
                    font-size: 25px;
                }

                .wrapper-text {
                    margin-top: 50px;
                    gap: 20px 0;

                    p {
                        color: $whiteColor;
                        font-size: 16px;
                    }

                    .wrapper__p1 {
                        color: $blue;
                    }

                    .wrapper__p2 {
                        color: $cyan;
                    }

                    .wrapper__p3 {
                        color: $pink;
                    }
                }

                .btn {
                    margin-top: 50px;

                    a {
                        color: $btnColor;
                    }
                }
            }

            img {
                display: none;
                width: 70%;
                margin-top: 50px;
            }
        }
    }
}
</style>
