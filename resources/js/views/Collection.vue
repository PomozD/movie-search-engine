<template>
    <section class="section-container">
        <div class="breadcrumbs">
            <router-link :to="{ name: 'Collections', query: { userID: this.$route.query.userID }}">Подборки</router-link>
            <img src="../assets/images/arrowright.svg" alt="Стрелка" class="arrow-icon">
            <span>{{ collections.collection.name }}</span>
        </div>

        <h1>Ваши фильмы</h1>
        <h2>Подборка: {{ collections.collection.name }}</h2>

        <div class="collections-container">
            <div class="collection-container" @click="openModal">
                <div class="collections-image add-collection" >
                    <div class="ellipse-container">
                        <img src="../assets/images/ellipse.svg" />
                        <div class="plus-container">
                            <img src="../assets/images/plus.svg"/>
                        </div>
                    </div>
                </div>

                <div class="span-container">
                    <span class="collection-name">Добавить фильм</span>
                </div>
            </div>

            <div v-for="data in collections.movie" :key="data.id" @click="navigateToFilm($event, data.id)">
                <div class="collection-container">
                    <div class="collections-image" :style="{ 'background-image': collections.movie.length > 0 ? 'url(' + data.url + ')' : 'none', 'background-color': collections.movie.length === 0 ? $purple : 'transparent' }">
                    </div>
                    <div class="span-container">
                        <span class="collection-name">{{ data.name }}</span>
                        <span class="collection-year">{{ data.year }}</span>
                        <button class="delete-button" @click.stop="deleteMovie(data.id)">Удалить</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Включение компонента модального окна -->
        <v-modal-movie :value="showModal" @close="handleModalClose" @submit="handleModalSubmit"></v-modal-movie>
    </section>
</template>

<script>
import VModalMovie from "../components/v-modalMovie.vue";

import { getCollectionsMovie, deleteMovieFromCollection } from "../services/collections.js"

export default {
    data() {
        return {
            showModal: false,

            collectionName: '',
            collectionDescription: '',

            collections: [],
        }
    },

    methods: {
        openModal() {
            this.showModal = true;
        },

        handleModalClose(value) {
            this.showModal = value;
        },

        async handleModalSubmit() {
            this.collections = await this.getCollectionMovies();

            this.$nextTick(() => {
                this.animateCollections();
            });
        },

        async getCollectionMovies() {
            const response = await getCollectionsMovie(this.$route.query.userID, this.$route.query.id);
            return response.data[0];
        },

        animateCollections() {
            const collections = document.querySelectorAll('.collection-container');
            collections.forEach((collection, index) => {
                setTimeout(() => {
                    collection.classList.add('animate');
                }, index * 200);
            });
        },

        async deleteMovie(movieId) {
            const response = await deleteMovieFromCollection(this.$route.query.userID, this.$route.query.id, movieId);
            console.log(response);

            this.collections = await this.getCollectionMovies();

            this.$nextTick(() => {
                this.animateCollections();
            });

            return response.data;
        },

        navigateToFilm(event, movieId) {
            if (!event.target.closest('.span-container')) {
                this.$router.push({ name: 'Film', query: { userID: this.$route.query.userID, id: movieId } });
            }
        },
    },

    async created() {
        this.collections = await this.getCollectionMovies();
        console.log(this.collections);

        this.$nextTick(() => {
            this.animateCollections();
        });

    },

    computed: {
        getUser() {
            return this.$store.getters.getUser;
        },
    },

    watch: {
        showModal(value) {
            if(!value) {
                this.handleModalSubmit();
            }
        },
    },

    components: {
        VModalMovie,
    }
}
</script>

<style scoped lang="scss">
@import "@/assets/styles/variables.scss";

.section-container {
    margin-left: 50px;

    h1, h2 {
        color: $greyTextColor;
    }

    h1 {
        margin-left: -2px;
        margin-top: 40px;
        font-size: 48px;
        line-height: 0;
    }

    h2 {
        margin-top: 80px;
        font-weight: $SemiBoldWeight;
        font-size: 18px;
    }

    .collections-container {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;

        .collection-container {
            margin-top: 20px;
            background-color: #b8b9e0;
            background: $mainColor1;
            border-radius: 5px;
            width: 220px;
            height: 350px;
            cursor: pointer;
            position: relative;
            z-index: 0;

            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 30px;

            opacity: 0;
            transform: translateY(-20px);
            transition: 0.3s;

            &.animate {
                opacity: 1;
                transform: translateY(0);
            }

            .collections-image {
                width: 170px;
                height: 270px;
                background-color: $purple;

                background-size: cover;
                background-position: center;

                display: flex;
                justify-content: center;
                align-items: center;

                transition: .3s;

                .ellipse-container {
                    position: relative;
                    display: flex;
                    justify-content: center;
                    align-items: center;

                    img {
                        width: 50px;
                    }

                    .plus-container {
                        margin-top: 5px;
                        position: absolute;

                        img {
                            width: 20px;
                        }
                    }
                }
            }

            .span-container {
                width: 100%;
                margin-top: 20px;
                margin-left: 50px;
                display: flex;
                flex-direction: column;
                transition: .3s;

                span {
                    font-weight: $MediumWeight;
                    font-size: 16px;
                    color: $greyTextColor;
                    transition: .3s;
                }

                .collection-name {
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    max-width: 83%;
                    transition: .3s;
                }

                .collection-year {
                    margin-top: 3px;
                    color: $greyTextExtraColor;
                    font-weight: $RegularWeight;
                    font-size: 12px;
                    transition: .3s;
                }

                .delete-button {
                    margin-top: 20px;
                    display: none;
                    background-color: #b8b9e0;
                    color: #0a0a0a;
                    border: none;
                    width: 50%;
                    height: 40px;
                    text-align: center;
                    border-radius: 4px;
                    cursor: pointer;
                    transition: .4s;
                    font-size: 14px;
                    font-weight: $MediumWeight;

                    &:hover {
                        background-color: #d6d8ec;
                    }
                }
            }

            &:hover {
                transform: scale(1.1);
                /*padding-bottom: 20px;*/
                z-index: 99;

                .collections-image {
                    width: 180px;
                    height: 300px;
                }

                .collection-name {
                    white-space: normal;
                }

                .delete-button {
                    display: block;
                    margin-bottom: 20px;
                }
            }
        }
    }
}

@media screen and (min-width: 767px) and (max-width: 1023px) {
    .section-container {
        margin-top: 0px;
        margin-left: 50px;

        h1, h2 {
            color: $greyTextColor;
        }

        h1 {
            margin-left: -2px;
            margin-top: 40px;
            font-size: 48px;
            line-height: 0;
        }

        h2 {
            margin-top: 80px;
            font-weight: $SemiBoldWeight;
            font-size: 18px;
        }

        .collections-container {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;

            .collection-container {
                background-color: #052731;
                border-radius: 5px;
                width: 300px;
                height: 220px;
                cursor: pointer;

                display: flex;
                flex-direction: column;
                align-items: center;
                padding-top: 30px;

                opacity: 0;
                transform: translateY(-20px);
                transition: opacity 0.5s ease-out, transform 0.5s ease-out, background-color 0.3s;

                &.animate {
                    opacity: 1;
                    transform: translateY(0);
                }

                .collections-image {
                    width: 260px;
                    height: 150px;
                    background-color: $purple;

                    background-size: cover;
                    background-position: center;

                    display: flex;
                    justify-content: center;
                    align-items: center;

                    .ellipse-container {
                        position: relative;
                        display: flex;
                        justify-content: center;
                        align-items: center;

                        img {
                            width: 50px;
                        }

                        .plus-container {
                            margin-top: 5px;
                            position: absolute;

                            img {
                                width: 20px;
                            }
                        }
                    }
                }

                .span-container {
                    width: 100%;
                    margin-top: 15px;
                    margin-left: 50px;

                    span {
                        font-weight: $MediumWeight;
                        font-size: 16px;
                        color: $greyTextColor;
                    }

                    .delete-button {
                        margin-top: 20px;
                        display: none;
                        background-color: #b8b9e0;
                        color: #0a0a0a;
                        border: none;
                        width: 50%;
                        height: 40px;
                        text-align: center;
                        border-radius: 4px;
                        cursor: pointer;
                        transition: .4s;
                        font-size: 14px;
                        font-weight: $MediumWeight;

                        &:hover {
                            background-color: #d6d8ec;
                        }
                    }
                }

                &:hover {
                    transform: scale(1.1);
                    background-color: $mainColor4;

                    .delete-button {
                        display: block;
                        margin-bottom: 20px;
                    }
                }
            }
        }
    }
}

@media screen and (max-width: 767px) {
    .section-container {
        margin-left: 0px;
        z-index: 2;
        position: relative;

        h1, h2 {
            color: $greyTextColor;
        }

        h1 {
            margin-left: -2px;
            margin-top: 40px;
            font-size: 35px;
            line-height: 0;
        }

        h2 {
            margin-top: 80px;
            font-weight: $SemiBoldWeight;
            font-size: 18px;
        }

        .collections-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;

            .collection-container {
                background-color: #052731;
                border-radius: 5px;
                width: 325px;
                height: 240px;
                cursor: pointer;

                display: flex;
                flex-direction: column;
                align-items: center;
                padding-top: 30px;

                opacity: 0;
                transform: translateY(-20px);
                transition: opacity 0.5s ease-out, transform 0.5s ease-out, background-color 0.3s;

                &.animate {
                    opacity: 1;
                    transform: translateY(0);
                }

                .collections-image {
                    width: 265px;
                    height: 170px;
                    background-color: $purple;

                    background-size: cover;
                    background-position: center;
                    background-repeat: no-repeat;

                    display: flex;
                    justify-content: center;
                    align-items: center;

                    .ellipse-container {
                        position: relative;
                        display: flex;
                        justify-content: center;
                        align-items: center;

                        img {
                            width: 50px;
                        }

                        .plus-container {
                            margin-top: 5px;
                            position: absolute;

                            img {
                                width: 20px;
                            }
                        }
                    }
                }

                .span-container {
                    width: 100%;
                    margin-top: 20px;
                    margin-left: 70px;

                    span {
                        font-weight: $MediumWeight;
                        font-size: 16px;
                        color: $greyTextColor;
                    }

                    .delete-button {
                        margin-top: 20px;
                        display: none;
                        background-color: #b8b9e0;
                        color: #0a0a0a;
                        border: none;
                        width: 50%;
                        height: 40px;
                        text-align: center;
                        border-radius: 4px;
                        cursor: pointer;
                        transition: .4s;
                        font-size: 14px;
                        font-weight: $MediumWeight;

                        &:hover {
                            background-color: #d6d8ec;
                        }
                    }
                }

                &:hover {
                    transform: scale(1.1);
                    background-color: $mainColor4;

                    .delete-button {
                        display: block;
                        margin-bottom: 20px;
                    }
                }
            }
        }
    }
}
</style>
