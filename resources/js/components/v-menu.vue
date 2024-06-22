<template>
    <div class="container-menu">
        <div class="logo" v-if="!isAuthorize() || isMobileView">
            <router-link to="/">
                <img class="logo-img" src="../assets/images/logo.png">
            </router-link>
        </div>

        <div v-if="!isAuthorize()" class="auth">
            <router-link to="/authorization">Вход</router-link>
            <router-link to="/registration">Регистрация</router-link>
        </div>

        <div class="sidebar-menu" v-if="verticalMenu" @mouseover="toggleLabels(true)" @mouseleave="toggleLabels(false)">
            <div class="sidebar-logo">
                <router-link :to="{ name: 'Collections', query: { userID: this.$route.query.userID}}">
                    <img class="mini-logo" src="../assets/images/extra_logo.png" alt="logo">
                </router-link>
            </div>

            <div class="menu-icon-container" :class="{ collections: isCollectionsPage}">
                <div class="menu-icon">
                    <router-link :to="{ name: 'Collections', query: { userID: this.$route.query.userID}}">
                        <img src="../assets/images/collections_icon.svg" alt="logo">
                        <span class="label" v-if="showRecommendationsLabel">Подборки</span>
                    </router-link>
                </div>
            </div>
            <div class="menu-icon-container" :class="{ recommendations: isRecommendationsPage}">
                <div class="menu-icon">
                    <router-link :to="{ name: 'Recommendations', query: { userID: this.$route.query.userID}}">
                        <img src="../assets/images/recommendation-icon.svg" alt="logo">
                        <span class="label" v-if="showRecommendationsLabel">Рекомендации</span>
                    </router-link>
                </div>
            </div>

            <div class="menu-icon-container" :class="{ admin: isAdmin}" v-if="getUser.role_id === 1 || getUser.role_id === 3">
                <div class="menu-icon">
                    <router-link :to="{ name: 'AdminPanel', query: { userID: this.$route.query.userID}}">
                        <img src="../assets/images/administrator.svg" alt="logo">
                        <span class="label" v-if="showRecommendationsLabel">Адм. панель</span>
                    </router-link>
                </div>
            </div>

            <div class="menu-icon-container logout">
                <div class="menu-icon">
                    <a @click="logout()" class="logout-btn">
                        <img src="../assets/images/logout-icons.svg" alt="logo">
                        <span class="label" v-if="showRecommendationsLabel">Выйти</span>
                    </a>
                </div>
            </div>
        </div>

        <div v-if="isAuthorize()"
            @mouseenter="toggleDropdown(true)"
             @mouseleave="toggleDropdown(false)"
            class="menu-user-container">

            <div class="toggle">
                <p class="menu-user-text">{{ getUser.nickname }}</p>
                <img class="arrow" src="../assets/images/arrowdown.svg">
            </div>

            <div v-show="dropdownVisible" class="dropdown">
                <ul>
                    <li><router-link :to="{ name: 'Profile', query: { userID: this.$route.query.userID}}">Профиль</router-link></li>
                    <li><p class="logout" @click="logout()">Выйти</p></li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapMutations} from 'vuex';

export default {
    name: "v-menu",

    data() {
        return {
            dropdownVisible: false,

            verticalMenu: false,

            showRecommendationsLabel: false,

            isMobileView: window.innerWidth <= 767,
        }
    },

    methods: {
        ...mapMutations(["updateUser"]),
        ...mapGetters(["isAuthorize"]),

        toggleDropdown(show) {
            this.dropdownVisible = show;
        },

        logout() {
            this.$store.state.user = null;
            this.$router.push({path: '/'});
        },

        toggleLabels(show) {
            if(!this.isMobileView) {
                this.showRecommendationsLabel = show;
            }
        },

        handleResize() {
            this.isMobileView = window.innerWidth <= 767;
            this.showRecommendationsLabel = false;
            console.log('isMobileView:', this.isMobileView);
            console.log('isAuthorize:', this.isAuthorize());
        },
    },

    computed: {
        getUser() {
            console.log(this.$store.getters.getUser);
            return this.$store.getters.getUser;
        },

        verticalMenu() {
            return this.isAuthorize();
        },

        isCollectionsPage() {
            return this.$route.path === '/collections';
        },

        isRecommendationsPage() {
            return this.$route.path === '/recommendations';
        },

        isAdmin() {
            return this.$route.path === '/admin';
        }
    },

    async created() {
        console.log(this.isAuthorize(), this.getUser);

        this.verticalMenu = this.isAuthorize();
        console.log(this.verticalMenu);

        window.addEventListener('resize', this.handleResize);
    },

    beforeDestroy() {
        window.removeEventListener('resize', this.handleResize);
    }
}
</script>

<style scoped lang="scss">
@import "@/assets/styles/variables.scss";

.container-menu {
    max-width: 1500px;
    z-index: 9999;
    display: flex;
    width: 100%;
    padding: 30px 0;
    justify-content: space-between;
    position: relative;

    /* logo */

    .logo {
        .logo-img {
            width: 143px;
        }
    }

    .auth {
        display: flex;
        gap: 0 30px;
        margin-left: auto;
    }

    .menu-user-container {
        position: relative;
        margin-left: auto;
        margin-top: -20px;

        .toggle {
            display: flex;
            gap: 10px;
            cursor: pointer;

            p {
                color: $greyTextColor;
                font-weight: $SemiBoldWeight;
                font-family: $Manrope;
                transition: .3s;
                font-size: 16px;
            }

            img {
                width: 20px;
            }
        }

        .dropdown {
            position: absolute;
            padding: 15px 10px;
            top: 100%;
            right: 0;
            border-radius: 5px;
            background-color: $extraColor;
            z-index: 1000;

            ul {
                list-style-type: none;
                line-height: 0;
                padding: 0;
                margin: 0;

                li {
                    padding: 15px 20px;
                    cursor: pointer;

                    .logout {
                        color: $greyTextColor;
                        font-weight: $MediumWeight;
                        font-family: $Manrope;
                        font-size: 14px;

                        &:hover {
                            color: $whiteColor;
                        }
                    }

                }
            }
        }
    }

    .sidebar-menu {
        position: fixed;
        height: 100vh;
        background-color: $extraColor;
        display: flex;
        flex-direction: column;
        left: 0;
        top: 0;
        transition: width .5s;
        margin-left: 0;

        .menu-icon-container {
            width: 100%;
            text-align: center;
            cursor: pointer;
            transition: .5s;

            .menu-icon {
                margin-left: 15px;
                padding: 13px 20px;
                display: flex;
                transition: padding 1s, font-size 1s, width 1s;
                align-items: center;
                text-align: center;

                .logout-btn {
                    display: flex;
                    justify-content: center;
                    img {
                        width: 20px;
                        transition: .5s;
                    }
                }

                a {
                    display: flex;
                    justify-content: center;
                    img {
                        width: 20px;
                        transition: .5s;
                    }
                }
            }

            &:hover {
                background-color: $mainColor4;
            }
        }

        .label {
            display: none;
            transition: opacity 1s ease-in-out, transform 1s ease-in-out;
            opacity: 0;
            transform: translateY(-20px);
            margin-left: 10px;
            color: $whiteColor;
            font-weight: $RegularWeight;
            font-size: 14px;
        }

        .sidebar-logo {
            margin: 30px auto;
            padding: 0 20px;

            a {
                img {
                    width: 50px;
                }
            }
        }

        .collections {
            background: linear-gradient(to right, $mainColor3, $mainColor2);
        }

        .recommendations {
            background: linear-gradient(to right, $mainColor4, $mainColor2);
        }

        .admin {
            background: linear-gradient(to right, $mainColor4, $mainColor2);
        }

        .logout {
            margin: auto auto 20px auto;
        }

        &:hover {
            width: 200px;

            .label {
                display: inline-block;
                opacity: 1;
                transform: translateY(0);
            }
        }

    }
}

@media screen and (min-width: 767px) and (max-width: 1023px) {
    .container-menu {

        .logo {
            margin-top: 10px;
            .logo-img {
                width: 100%;
                height: 45px;
            }
        }

        .auth {
            display: flex;
            gap: 0 30px;
            margin-left: auto;
            margin-top: 15px;

            a {
                font-size: 18px;
            }
        }

        .sidebar-menu {
            position: fixed;
            height: 100vh;
            background-color: $extraColor;
            display: flex;
            flex-direction: column;
            left: 0;
            top: 0;
            transition: width .5s;
            margin-left: 0;

            .menu-icon-container {
                width: 100%;
                text-align: center;
                cursor: pointer;
                transition: .5s;

                .menu-icon {
                    margin-left: 5px;
                    padding: 13px 20px;
                    display: flex;
                    transition: padding 1s, font-size 1s, width 1s;
                    align-items: center;
                    text-align: center;

                    .logout-btn {
                        display: flex;
                        justify-content: center;
                        img {
                            margin-left: 2px;
                            width: 20px;
                            transition: .5s;
                        }
                    }

                    a {
                        display: flex;
                        justify-content: center;
                        img {
                            width: 20px;
                            transition: .5s;
                        }
                    }
                }

                &:hover {
                    background-color: $mainColor4;
                }
            }

            .label {
                display: none;
                transition: opacity 1s ease-in-out, transform 1s ease-in-out;
                opacity: 0;
                transform: translateY(-20px);
                margin-left: 10px;
                color: $whiteColor;
                font-weight: $RegularWeight;
                font-size: 14px;
            }

            .sidebar-logo {
                margin: 30px auto;
                padding: 0 20px;

                a {
                    img {
                        width: 35px;
                    }
                }
            }

            .logout {
                margin: auto auto 10px auto;
            }

            &:hover {
                width: 200px;

                .label {
                    display: inline-block;
                    opacity: 1;
                    transform: translateY(0);
                }
            }

        }
    }
}

@media screen and (max-width: 767px) {
    /*.container-menu {
        z-index: 2;

        .logo {
            margin-top: 10px;
            .logo-img {
                width: 100%;
                height: 35px;
                margin-top: -10px;
            }
        }

        .auth {
            display: flex;
            gap: 0 15px;
            margin-left: auto;
            margin-top: 8px;

            a {
                font-size: 18px;
            }
        }

        .sidebar-menu {
            background-color: transparent;
            position: fixed;
            bottom: 0;
            width: 100%;
            height: auto;
            display: flex;
            flex-direction: row;
            padding: 15px 0;

            .menu-icon-container {
                width: 100%;
                text-align: start;
                cursor: pointer;
                transition: .5s;
                display: flex;
                align-items: end;
                justify-content: center;

                .menu-icon {
                    margin-left: 0;
                    padding: 0;
                    display: flex;
                    transition: padding 1s, font-size 1s, width 1s;
                    align-items: center;
                    text-align: center;
                    justify-content: center;

                    .logout-btn {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        text-align: center;
                        flex-direction: column;
                        img {
                            width: 20px;
                            transition: .5s;
                        }
                    }

                    a {
                        display: flex;
                        justify-content: center;
                        flex-direction: column;
                        align-items: center;
                        gap: 10px;
                        img {
                            width: 20px;
                            transition: .5s;
                        }
                    }
                }

                &:hover {
                    background-color: transparent;
                }
            }

            .label {
                display: none;
                transition: opacity 1s ease-in-out, transform 1s ease-in-out;
                opacity: 0;
                transform: translateY(-20px);
                margin-left: 10px;
                color: $whiteColor;
                font-weight: $RegularWeight;
                font-size: 14px;
            }

            .sidebar-logo {
                display: none;
            }

            .logout {
                margin: 0;
            }

            .collections {
                background: transparent;
            }

            .recommendations {
                background: transparent;
            }

            .admin {
                background: transparent;
            }

            &:hover {
                width: 100%;
            }

        }
    }*/

    .container-menu {
        z-index: 3;
        position: relative;

        .logo {
            margin-top: 10px;
            .logo-img {
                width: 100%;
                height: 35px;
                margin-top: -10px;
            }
        }

        .auth {
            display: flex;
            gap: 0 15px;
            margin-left: auto;
            margin-top: 8px;

            a {
                font-size: 18px;
            }
        }

        .sidebar-menu {
            height: 50px;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            top: 92%;
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            box-shadow: 0 -1px 5px rgba(0, 0, 0, 0.1);
            padding: 10px 0;
            z-index: 10;
            margin-left: 0;

            .menu-icon-container {
                flex: 1;
                text-align: center;

                .menu-icon {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    margin-left: 0px;
                    padding: 13px 20px;
                    transition: padding 1s, font-size 1s, width 1s;
                    text-align: center;

                    .logout-btn {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        text-align: center;
                        flex-direction: column;
                        img {
                            width: 20px;
                            transition: .5s;
                        }
                    }

                    a {
                        display: flex;
                        justify-content: center;
                        flex-direction: column;
                        align-items: center;
                        gap: 10px;
                        img {
                            width: 20px;
                            transition: .5s;
                        }
                    }
                }

                &:hover {
                    background-color: transparent;
                }
            }

            .label {
                display: none;
                transition: opacity 1s ease-in-out, transform 1s ease-in-out;
                opacity: 0;
                transform: translateY(-20px);
                margin-left: 0px;
                color: $whiteColor;
                font-weight: $RegularWeight;
                font-size: 14px;
            }

            .sidebar-logo {
                display: none;
            }

            .logout {
                margin: 0;
            }

            .collections {
                background: transparent;
            }

            .recommendations {
                background: transparent;
            }

            .admin {
                background: transparent;
            }

            &:hover {
                width: 100%;
            }

        }
    }
}


</style>
