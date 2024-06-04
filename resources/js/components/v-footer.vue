<template>
    <div class="footer" :class="{ authorized: isAuthorized}">
        <div class="footer__container">
            <div class="footer__hr"></div>

            <div class="footer-content">
                <div class="footer-logo">
                    <router-link to="/">
                        <img class="logo-img" src="../assets/images/logo.png">
                    </router-link>
                </div>

                <div class="footer-social">
                    <a class="contact__facebook" href="https://vk.com/">
                        <img class="icon__facebook" src="../assets/images/icons-vk.svg" alt="facebook-icon">
                    </a>
                    <a class="contact__vk" href="https://telegram.org/">
                        <img class="icon__vk" src="../assets/images/icons-telegram.svg" alt="vk-icon">
                    </a>
                </div>
            </div>

        </div>

        <div class="footer__politicies">
            <p>© 2024 Moviq. БГТУ ДЭиВИ 4 курс. Помоз Даниил. Все права защищены.</p>
        </div>

    </div>
</template>

<script>
import {mapGetters, mapMutations} from "vuex";

export default {
    name: "v-footer",

    methods: {
        ...mapMutations(["updateUser"]),
        ...mapGetters(["isAuthorize"]),
    },

    computed: {
        isAuthorized() {
            return this.isAuthorize();
        },
    }
}
</script>

<style scoped lang="scss">
@import "@/assets/styles/variables.scss";

.footer {
    margin: 100px auto 0 auto;
    padding-bottom: 10px;

    .footer__container {
        .footer__hr {
            width: 100%;
            height: 1.7px;
            background-color: $blue;
            border-radius: 5px;
        }

        .footer-content {
            margin-top: 30px;
            display: flex;

            .footer-logo {
                img {
                    width: 100px;
                }
            }

            .footer-social {
                margin-left: auto;
                display: flex;
                gap: 20px;
                align-items: center;

                .icon__facebook, .icon__instagram, .icon__vk {
                    width: 30px;
                    height: 30px;
                }
            }
        }
    }

    .footer__politicies {
        text-align: center;
        font-weight: $RegularWeight;
        color: $greyTextColor;
        font-size: 16px;
        margin-bottom: 50px;
    }
}

.authorized {
    margin-left: 85px;
}

@media screen and (min-width: 767px) and (max-width: 1023px) {
    .footer {
        margin: 100px auto 0 auto;
        padding-bottom: 10px;

        .footer__container {
            .footer__hr {
                width: 100%;
                height: 1.7px;
                background-color: $blue;
                border-radius: 5px;
            }

            .footer-content {
                margin-top: 30px;
                display: flex;

                .footer-logo {
                    img {
                        width: 100px;
                    }
                }

                .footer-social {
                    margin-left: auto;
                    display: flex;
                    gap: 20px;
                    align-items: center;

                    .icon__facebook, .icon__instagram, .icon__vk {
                        width: 30px;
                        height: 30px;
                    }
                }
            }
        }
    }

    .authorized {
        margin-left: 85px;
    }
}

@media screen and (max-width: 767px) {
    .footer {
        margin: 100px auto 0 auto;
        padding-bottom: 10px;

        .footer__container {
            .footer__hr {
                width: 100%;
                height: 1.7px;
                background-color: $blue;
                border-radius: 5px;
            }

            .footer-content {
                margin-top: 30px;
                display: flex;

                .footer-logo {
                    img {
                        width: 100px;
                    }
                }

                .footer-social {
                    margin-left: auto;
                    display: flex;
                    gap: 20px;
                    align-items: center;

                    .icon__facebook, .icon__instagram, .icon__vk {
                        width: 30px;
                        height: 30px;
                    }
                }
            }
        }

        .footer__politicies {
            p {
                font-size: 14px;
            }
        }
    }

    .authorized {
        margin-left: 0;
    }
}
</style>
