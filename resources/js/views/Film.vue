<template>
    <section class="section-container">
        <div class="films-container">
            <div class="films-image" :style="{ 'background-image': film.backdrop ? 'url(' + film.backdrop + ')' : 'none', 'background-color': film.backdrop ? $purple : 'transparent' }">
                <div class="film-image-background">
                    <div class="breadcrumbs">
                        <router-link :to="{ name: 'Collections', query: { userID: this.$route.query.userID }}">Подборки</router-link>
                        <img src="../assets/images/arrowright.svg" alt="Стрелка" class="arrow-icon">
                        <span>{{ film.name }}</span>
                    </div>

                    <div class="film">
                        <img v-if="film.logo" :src="film.logo" alt="лого фильма" class="film-logo">
                        <h1 v-else class="film-name">{{ film.name }}</h1>
                        <h2 class="film-description">{{ film.shortDescription }}</h2>
                        <div class="film-data">
                            <span>{{film.year}}</span>
                            <div class="genres">
                                <span v-for="(genre, index) in film.genres" :key="genre.id">
                                    {{genre.name}}
                                    <span v-if="index !== film.genres.length - 1">,</span>
                                </span>
                            </div>
                        </div>
                        <div class="rating-container">
                            <div class="rating-moviq">
                                <span class="rating" :class="{'not-rating': !film.rating}">{{ film.rating ? roundRating(film.rating) : 'Рейтинга еще нет'}}</span>
                                <span>Рейтинг Moviq</span>
                            </div>
                            <div class="rating-kp">
                                <span class="rating">{{ roundRating(film.ratingKp) }}</span>
                                <span>Рейтинг Кинопоиск</span>
                            </div>

                            <div class="collection-container" @mouseenter="showTextBlock = true" @mouseleave="showTextBlock = false">
                                <img src="../assets/images/bookmark-icons.svg" alt="icon"/>
                                <div class="text-block-container" v-show="showTextBlock" @mouseenter="showListBlock = true">
                                    <div class="text-block">
                                        <p>Выберите подборку</p>
                                        <img src="../assets/images/arrowrightwhite.svg" alt="Стрелка" class="arrow-icon">
                                    </div>
                                    <div class="list-block" v-show="showListBlock" @mouseleave="showListBlock = false">
                                        <div class="collections">
                                            <div v-for="collectionData in collections" :key="collectionData.collection.id">
                                                <div class="collection-data" @click="selectCollection(collectionData.collection.id)">
                                                    <span>{{collectionData.collection.name}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rating-btn-container" @click="openRatingModal()" v-if="!showModal">
                            <div class="rating-btn">Поставить оценку</div>
                        </div>

                        <div class="parent-container" @click="toggleModal">
                            <div class="add-rating-container" v-if="showModal" v-click-outside="closeRatingModal">
                                <div class="add-rating" @click="selectRating(rating)" v-for="rating in ratings" :key="rating[index]" @click.stop>{{ rating }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import vClickOutside from 'v-click-outside';
import { getMovie, addRating, getCollections, addMovieToCollection } from "../services/films.js";

export default {
    data() {
        return {
            showModal: false,

            filmName: '',
            filmDescription: '',

            film: [],

            ratings: [1, 2 , 3, 4, 5, 6, 7, 8, 9, 10],

            showTextBlock: false,
            showListBlock: false,

            collections: [],
        }
    },

    methods: {
        async getMovieData() {
            const response = await getMovie(this.$route.query.id);
            return response.data;
        },

        async getCollection() {
            const response = await getCollections(this.$route.query.userID);
            return response.data;
        },

        roundRating(rating) {
            const numericRating = parseFloat(rating);
            if (!isNaN(numericRating || !rating)) {
                return numericRating.toFixed(1);
            } else {
                return 'Рейтинга еще нет';
            }
        },

        openRatingModal() {
            this.showModal = true;
            console.log(this.showModal);
        },

        toggleModal() {
            this.showModal = !this.showModal;
        },

        closeRatingModal() {
            this.showModal = false;
            console.log(this.showModal);
        },

        handleClickOutside(event) {
            if (this.$refs.modal && !this.$refs.modal.contains(event.target)) {
                this.showModal = false;
            }
        },

        async selectRating(rating) {
            this.selectedRating = rating;
            try {
                if (this.selectedRating) {
                    const response = await addRating(this.$route.query.userID, this.film.id, this.selectedRating);
                    console.log(response);

                    this.film = await this.getMovieData();
                    this.showModal = false;
                } else {
                    alert('Пожалуйста, заполните поле оценки');
                }

            } catch (error) {
                console.error('Ошибка при добавлении рейтинга:', error);
            }
        },

        async selectCollection(collection) {
            this.selectedCollection = collection;
            try {
                const response = await addMovieToCollection(this.$route.query.userID, this.$route.query.id, this.selectedCollection);
                if(response.status) {
                    alert(response.message);
                }
            } catch (error) {
                console.error('Ошибка при добавлении фильма в подборку:', error);
            }
        },
    },

    mounted() {
        document.addEventListener('click', this.handleClickOutside);
    },
    beforeDestroy() {
        document.removeEventListener('click', this.handleClickOutside);
    },

    computed: {
        getUser() {
            return this.$store.getters.getUser;
        },
    },

    async created() {
        this.film = await this.getMovieData();

        this.collections = await this.getCollection();
        console.log(this.collections);
    },

    directives: {
        ClickOutside: vClickOutside,
    },
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

    .films-container {
        min-height: 100vh;

        .films-image {
            background-color: $purple;
            background-size: cover;
            background-position: center;
            margin-left: -55px;
            margin-right: -55px;
            margin-top: -20px;

            transition: .3s;

            height: 720px;
            min-height: 100%;

            position: relative;

            .film-image-background {
                position: absolute;
                width: 100%;
                background: linear-gradient(to right, #000000CB 25%, #00000000);
                height: 100%;

                .breadcrumbs {
                    margin-top: 30px;
                    margin-left: 55px;
                }

                .film {
                    position: relative;
                    margin-top: 5%;
                    margin-left: 55px;
                    width: 40%;

                    .film-name {
                        margin-top: 150px;
                        color: #ff0b60;
                    }

                    .film-logo {
                        width: 80%;
                        height: 150px;
                    }

                    .film-description {
                        margin-top: 20px;
                        color: $whiteColor;
                        font-weight: $RegularWeight;
                        font-size: 16px;
                    }

                    .film-data {
                        display: flex;
                        color: $whiteColor;
                        font-size: 14px;
                        gap: 10px;

                        .genres {
                            display: flex;
                            gap: 2px;
                        }
                    }

                    .rating-container {
                        color: $whiteColor;
                        display: flex;
                        align-items: flex-end;
                        margin-top: 30px;
                        gap: 20px;
                        transition: .2s;
                        z-index: 0;

                        .rating-moviq, .rating-kp {
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: flex-end;
                            background-color: #181232;
                            padding: 25px;
                            border-radius: 5px;
                            font-weight: $MediumWeight;

                            .rating {
                                color: #ff0b60;
                                font-weight: $BoldWeight;
                                font-size: 75px;
                            }

                            .not-rating {
                                margin-bottom: 40px;
                                font-size: 14px;
                                font-weight: $RegularWeight;
                            }
                        }

                        .collection-container {
                            position: relative;
                            display: inline-flex;
                            background-color: #181232;
                            padding: 11px;
                            transition: .2s;
                            z-index: 0;

                            img {
                                width: 35px;
                            }

                            &:hover {
                                cursor: pointer;
                                transform: scale(1.1);
                                border-radius: 5px 0 0 5px;
                            }

                            .text-block-container {
                                position: absolute;
                                top: 0;
                                left: 100%;
                                display: none;
                                background-color: #181232;
                                padding: 2px 10px;
                                font-size: 12px;
                                font-weight: $MediumWeight;
                                border-radius: 0 5px 5px 0;

                                .text-block {
                                    display: flex;
                                    gap: 25px;
                                    line-height: 1.2;

                                    .arrow-icon {
                                        width: 15px;
                                    }
                                }

                                .list-block {
                                    position: absolute;
                                    top: 0;
                                    left: 100%;
                                    display: none;
                                    background-color: #181232;

                                    .collections {
                                        display: flex;
                                        flex-direction: column;
                                        padding: 5px;
                                        gap: 5px;
                                        height: 200px;
                                        overflow-y: auto;
                                        width: 600px;

                                        .collection-data {
                                            cursor: pointer;
                                            display: flex;
                                            align-items: center;
                                            background-color: #2b225b;
                                            padding: 8px 10px;
                                            border-radius: 2px;

                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                            max-width: 100%;
                                        }
                                    }
                                }

                                &:hover {
                                    display: flex;
                                    border-radius: 0 0 0 0;

                                    .list-block {
                                        display: block;
                                    }
                                }
                            }

                            &:hover .text-block-container {
                                display: block;
                            }
                        }
                    }

                    .rating-btn-container {
                        display: inline-block;
                        margin-top: 20px;
                        background-color: #b8b9e0;
                        border-radius: 50px;
                        padding: 12px 20px;
                        position: absolute;
                        cursor: pointer;
                        z-index: 0;
                        transition: .3s;

                        .rating-btn {
                            font-size: 14px;
                            font-weight: $SemiBoldWeight;
                            transition: .3s;
                        }

                        &:hover {
                            background-color: #cccde5;
                            transform: scale(1.1);
                        }
                    }

                    .add-rating-container {
                        display: flex;
                        gap: 20px;
                        margin-top: 20px;
                        margin-left: 10px;
                        background-color: #cccde5;
                        border-radius: 50px;
                        padding: 12px 48px;
                        position: absolute;
                        z-index: 0;
                        transition: .3s;
                        transform: scale(1.1);

                        .add-rating {
                            font-size: 14px;
                            font-weight: $BoldWeight;
                            cursor: pointer;
                        }
                    }
                }
            }
        }
    }
}
</style>
