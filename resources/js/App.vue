<template>
    <div class="main" :class="containerClasses">
        <div class="container">
            <header>
                <v-menu/>
            </header>

            <main>
                <RouterView/>
            </main>

            <footer>
                <v-footer/>
            </footer>
        </div>
    </div>
</template>

<script>
import { RouterView } from "vue-router";
import vMenu from "./components/v-menu.vue";
import vFooter from './components/v-footer.vue';


export default {
    name: 'app',

    computed: {
        containerClasses() {
            return {
                'container--home': this.$route.path === '/',
                'container--authorization': this.$route.path === '/authorization',
                'container--registration': this.$route.path === '/registration',
                'container--collections': this.$route.path === '/collections',
                'container--collection': this.$route.path === '/collections/collection',
                'container--film': this.$route.path === '/film',
                'container--recommendations': this.$route.path === '/recommendations',
                'container--profile': this.$route.path === '/profile',
                'container--suggested': this.$route.path === '/profile/suggested',
                'container--admin': this.$route.path === '/admin',
                'container--suggestedmovies': this.$route.path === '/admin/suggestedmovies',
                'container--suggestion': this.$route.path === '/admin/suggestedmovies/suggestion',
                'container--404': this.$route.name === 'NotFound',
            };
        },
    },

    components: {
        vMenu,
        vFooter,
    }
}
</script>

<style scoped lang="scss">
@import "@/assets/styles/_variables.scss";

#app {
    display: flex;
    flex-direction: column;
    justify-content: space-around;
}

.main {
    flex: 1;
    max-width: 1500px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
}

.container {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    max-width: 1500px;
    min-height: 100vh;
    margin: 0 55px;

    main {
        padding-left: 40px;
    }

    footer {
        position: relative;
        z-index: 1;
    }
}

.container--home {
    background: linear-gradient(to right, $mainColor1, $mainColor2);
    min-height: 100vh;
}

.container--authorization {
    background: $mainColor2;
}

.container--registration {
    background: $mainColor2;
}

.container--collections, .container--collection {
    background: linear-gradient(to bottom, $mainColor3, $mainColor2 30%);
}

.container--film {
    background: $mainColor2;
}

.container--recommendations {
    background: linear-gradient(to bottom, $mainColor4, $mainColor2 30%);
}

.container--profile, .container--suggested, .container--admin, .container--suggestedmovies, .container--suggestion, .container--404 {
    background: $mainColor2;
}

@media screen and (min-width: 767px) and (max-width: 1023px) {
    .main {
        max-width: 1500px;
        margin: 0 auto;
    }

    .container {
        margin-top: -100px ;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        max-width: 1500px;
        min-height: 100vh;
        margin: 0 55px;

        main {
            padding-left: 0px;
        }

        footer {
            position: relative;
            z-index: 1;
        }
    }
}

@media screen and (max-width: 767px) {
    .main {
        max-width: 1500px;
        margin: 0 auto;
    }

    .container {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        max-width: 1500px;
        min-height: 100vh;
        margin: 0 30px;

        main {
            padding-left: 0px;
        }

        footer {
            position: relative;
            z-index: 1;
        }
    }
}
</style>
