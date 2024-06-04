<template>
    <section class="section-container">
        <div class="breadcrumbs">
            <span>Рекомендации</span>
        </div>

        <h1>Рекомендации</h1>

        <div class="recommendations-container">
            <div class="filters-container">
                <div class="filters">
                    <div class="filter-genres">
                        Жанр
                        <img src="../assets/images/arrowdown.svg"/>
                        <div class="dropdown">
                            <div class="filter-title">Выберите жанр</div>
                            <div class="filter-container">
                                <ul class="genre-columns">
                                    <li v-for="genre in moviesData['genres']">
                                        <input type="checkbox" name="genres" id="'checkbox-genre-' + genre" v-model="selectedGenres" :value="genre">
                                        <label :for="'checkbox-genre-' + genre">{{ genre }}</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="filter-years">
                        Год
                        <img src="../assets/images/arrowdown.svg"/>
                        <div class="dropdown">
                            <div class="filter-title">Выберите год</div>
                            <div class="filter-container">
                                <ul class="year-columns">
                                    <li v-for="year in moviesData['years']">
                                        <input type="checkbox" name="years" id="'checkbox-year-' + year" v-model="selectedYears" :value="year">
                                        <label for="checkbox-year">{{ year }}</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="filter-countries">
                        Страна
                        <img src="../assets/images/arrowdown.svg"/>
                        <div class="dropdown">
                            <div class="filter-title">Выберите год</div>
                            <div class="filter-container">
                                <ul class="countrie-columns">
                                    <li v-for="countrie in moviesData['countries']">
                                        <input type="checkbox" name="countries" id="'checkbox-countrie-' + countrie" v-model="selectedCountries" :value="countrie">
                                        <label for="checkbox-countrie">{{ countrie }}</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="filter-ratings">
                        Рейтинг
                        <img src="../assets/images/arrowdown.svg"/>
                        <div class="dropdown">
                            <div class="filter-title">Выберите рейтинг</div>
                            <div class="filter-container">
                                <div class="rating-columns">
                                    <div class="ratingfrom">
                                        <select v-model="ratingFrom" class="input__rating">
                                            <option disabled value="">От</option>
                                            <option v-for="n in 10" :key="n" :value="n">{{ n }}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <span class="span__minus">-</span>
                                    </div>
                                    <div class="ratingto">
                                        <select v-model="ratingTo" class="input__rating">
                                            <option disabled value="">До</option>
                                            <option v-for="n in 10" :key="n" :value="n">{{ n }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-search">
                    <input type="text" v-model="searchQuery" placeholder="Введите название" class="input__search">
                </div>
            </div>
            <div class="recommendation-container" :class="{ 'hidden': restrictModal }" v-if="recommendationMovies.length > 0" @click="navigateToFilm($event)">
                <div class="recommendation-data" :style="{ 'background-image': recommendationMovies[0].backdrop ? 'url(' + recommendationMovies[0].backdrop + ')' : 'none', 'background-color': recommendationMovies[0].backdrop ? $purple : 'transparent' }">
                    <div class="film-image-background">
                        <div class="film">
                            <div v-if="recommendationMovies[0].logo" class="film-logo">
                                <img :src="recommendationMovies[0].logo" alt="лого фильма" class="logo-img">
                            </div>
                            <div v-else class="film-name" :class="{'long-text': isLongText}">
                                <h1>{{ recommendationMovies[0].name }}</h1>
                            </div>
                            <h2 class="film-description">{{ recommendationMovies[0].shortDescription }}</h2>

                            <div class="film-data">
                                <span>{{recommendationMovies[0].year}}</span>
                                <div class="genres">
                                <span v-for="(genre, index) in recommendationMovies[0].genres" :key="genre.id">
                                    {{genre.name}}
                                    <span v-if="index !== recommendationMovies[0].genres.length - 1">,</span>
                                </span>
                                </div>
                            </div>

                            <div class="rating-container">
                                <div class="rating-moviq">
                                    <span class="rating" :class="{'not-rating': calculateRating === 'Рейтинга еще нет'}">{{ roundRating(calculateRating) ? roundRating(calculateRating) : 'Рейтинга еще нет'}}</span>
                                    <span>Рейтинг Moviq</span>
                                </div>
                                <div class="rating-kp">
                                    <span class="rating">{{ roundRating(recommendationMovies[0].ratingKp) }}</span>
                                    <span>Рейтинг Кинопоиск</span>
                                </div>
                            </div>

                            <div class="rating-btn-container" @click.stop="openRatingModal()" v-if="!showModal">
                                <div class="rating-btn">Поставить оценку</div>
                            </div>

                            <div class="parent-container" @click.stop="toggleModal">
                                <div class="add-rating-container" v-if="showModal" v-click-outside="closeRatingModal">
                                    <div class="add-rating" @click.stop="selectRating(rating)" v-for="rating in ratings" :key="rating[index]">{{ rating }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="navigate-recommendation-container">
                            <div class="navigate-recommendation previous-btn" v-if="showPreviousButton" @click.stop="previousMovie">
                                <div class="navigate-img">
                                    <img src="../assets/images/back-icons.svg" />
                                </div>
                            </div>

                            <div class="navigate-recommendation add-movie-btn" @mouseenter="showTextBlock = true" @mouseleave="showTextBlock = false">
                                <div class="navigate-img">
                                    <img src="../assets/images/plus-icons.svg" />
                                </div>

                                <div class="text-block-container" v-show="showTextBlock" @mouseenter="showListBlock = true">
                                    <div class="text-block">
                                        <p>Выберите подборку</p>
                                        <img src="../assets/images/arrowrightwhite.svg" alt="Стрелка" class="arrow-icon">
                                    </div>
                                    <div class="list-block" v-show="showListBlock" @mouseleave="showListBlock = false">
                                        <div class="collections">
                                            <div v-for="collectionData in collections" :key="collectionData.collection.id">
                                                <div class="collection-data" @click.stop="selectCollection(collectionData.collection.id)">
                                                    <span>{{collectionData.collection.name}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="navigate-recommendation next-btn" @click.stop="nextMovie">
                                <div class="navigate-img">
                                    <img src="../assets/images/next-icons.svg" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="recommendation-null" v-else>
                <h2>Извиняемся, таких фильмов нет</h2>
            </div>

            <div v-if="restrictModal" class="modal">
                <div class="modal-content">
                    <img src="../assets/images/logo.png"/>
                    <h2>Подтверждение возраста</h2>
                    <p>Для вас подобраны фильмы, которые могут иметь возрастное ограничение 18+. Подтвердите, что вам уже исполнилось 18 лет, чтобы просматривать данный контент.</p>
                    <p>Подтверждение возраста необходимо с целью соблюдения законодательства о защите несовершеннолетних и обеспечения безопасности пользователей. Мы ценим ваше понимание.</p>

                    <div class="modal-btn">
                        <button @click="confirmAge" class="btn btn-primary">Мне 18 лет или больше</button>
                        <button @click="closeRestrictModal" class="btn btn-close">Мне нет 18 лет</button>
                    </div>
                </div>
            </div>
            <br>
        </div>
    </section>
</template>

<script>
import vClickOutside from 'v-click-outside';
import { getRecommendationMovies, addRating, getRating, getMoviesData } from '../services/recommendation.js';
import { getCollections, addMovieToCollection } from '../services/films.js';

export default {
    name: "RegistrationView",
    data() {
        return {
            recommendationMovies: [],
            currentPage: 1,
            calculateRating: '',

            mark: '',

            showPreviousButton: false,

            showModal: false,
            ratings: [1, 2 , 3, 4, 5, 6, 7, 8, 9, 10],

            moviesData: [],

            selectedGenres: [],
            selectedYears: [],
            selectedCountries: [],

            ratingFrom: '',
            ratingTo: '',

            searchQuery: '',

            collections: [],

            showTextBlock: false,
            showListBlock: false,

            restrictModal: false,
            ageConfirmed: null,

        }
    },
    methods: {
        async fetchMovies() {
            try {
                const response = await getRecommendationMovies(this.currentPage, this.selectedGenres, this.selectedYears, this.selectedCountries, this.ratingFrom, this.ratingTo, this.searchQuery, this.ageConfirmed);
                console.log(response.data);

                if(response.data[0].restrict.restriction === 18) {
                    this.restrictModal = true;
                }

                return response.data;
            } catch (error) {
                console.error('Ошибка при загрузке фильмов:', error);
                return [];
            }
        },

        async nextMovie() {
            try {
                console.log(this.currentPage);
                const movies = await this.fetchMovies();
                if (movies.length > 0) {
                    this.recommendationMovies.shift(); // Удаляем первый элемент из массива
                    this.recommendationMovies.push(movies[0]); // Добавляем новый фильм в конец массива
                    this.currentPage++;
                    console.log(this.currentPage);
                    if (this.currentPage === 3) {
                        this.showPreviousButton = true;
                    }
                } else {
                    this.showPreviousButton = false;
                }

                this.checkAgeRestriction();

                try {
                    this.calculateRatingFunc();
                } catch (error) {
                    console.error('Ошибка при выводе рейтинга:', error);
                }
            } catch (error) {
                console.error('Ошибка при загрузке следующего фильма:', error);
            }
        },

        async previousMovie() {
            try {
                if(this.currentPage > 2) {
                    this.currentPage -= 2;
                    const movies = await this.fetchMovies();
                    if (movies.length > 0) {
                        this.recommendationMovies.pop(); // Удаляем первый элемент из массива
                        this.recommendationMovies.unshift(movies[0]); // Добавляем новый фильм в конец массива
                        if (this.currentPage === 3) {
                            this.showPreviousButton = true;
                        }
                    }

                    this.currentPage++;

                    if (this.currentPage === 2) {
                        this.showPreviousButton = false;
                    }

                    this.checkAgeRestriction();

                    try {
                        this.calculateRatingFunc();
                    } catch (error) {
                        console.error('Ошибка при выводе рейтинга:', error);
                    }

                } else {
                    this.currentPage = 2;
                }
            } catch (error) {
                console.error('Ошибка при загрузке предыдущего фильма:', error);
            }
        },

        async calculateRatingFunc() {
            try {
                const response = await getRating(this.recommendationMovies[0].id);
                this.calculateRating = response.data;

            } catch (error) {
                console.error('Ошибка при получении рейтинга:', error);
            }
        },

        roundRating(rating) {
            const numericRating = parseFloat(rating);
            if (!isNaN(numericRating || rating)) {
                return numericRating.toFixed(1);
            } else {
                return 'Рейтинга еще нет';
            }
        },

        async selectRating(rating) {
            this.selectedRating = rating;
            try {
                if (this.selectedRating) {
                    const response = await addRating(this.$route.query.userID, this.recommendationMovies[0].id, this.selectedRating);
                    this.calculateRating = response.data;

                    this.showModal = false;
                } else {
                    alert('Пожалуйста, заполните поле оценки');
                }

            } catch (error) {
                console.error('Ошибка при добавлении рейтинга:', error);
            }
        },

        openRatingModal() {
            this.showModal = true;
        },

        toggleModal() {
            this.showModal = !this.showModal;
        },

        closeRatingModal() {
            this.showModal = false;
        },

        handleClickOutside(event) {
            if (this.$refs.modal && !this.$refs.modal.contains(event.target)) {
                this.showModal = false;
            }
        },

        checkAgeRestriction() {
            if (this.recommendationMovies[0].restrict.restriction === '18' && !this.ageConfirmed) {
                this.restrictModal = true;
            }
        },

        confirmAge() {
            this.ageConfirmed = true;
            this.restrictModal = false;
        },

        closeRestrictModal() {
            this.currentPage = 1;
            this.ageConfirmed = false;
            this.restrictModal = false;
            this.nextMovie();
        },

        navigateToFilm(event) {
            // Проверяем, был ли клик по кнопке или по другим внутренним элементам
            if (!event.target.closest('.rating-btn-container') &&
                !event.target.closest('.parent-container') &&
                !event.target.closest('.navigate-recommendation')) {
                this.$router.push({ name: 'Film', query: { userID: this.$route.query.userID, id: this.recommendationMovies[0].id } });
            }
        },

        async fetchMoviesData() {
            try {
                const response = await getMoviesData();
                return response.data;
            } catch (error) {
                console.error('Ошибка при загрузке данных:', error);
                return [];
            }
        },

        async getCollection() {
            const response = await getCollections(this.$route.query.userID);
            return response.data;
        },

        async selectCollection(collection) {
            this.selectedCollection = collection;
            try {
                const response = await addMovieToCollection(this.$route.query.userID, this.recommendationMovies[0]['id'], this.selectedCollection);
                if(response.status) {
                    alert(response.message);
                }
            } catch (error) {
                console.error('Ошибка при добавлении фильма в подборку:', error);
            }
        },
    },

    computed: {
        isLongText() {
            return this.recommendationMovies.length > 0 && this.recommendationMovies[0].name.length > 20;
        }
    },

    mounted() {
        document.addEventListener('click', this.handleClickOutside);
    },

    beforeDestroy() {
        document.removeEventListener('click', this.handleClickOutside);
    },

    async created() {
        try {
            const movies = await this.fetchMovies();
            if (movies.length > 0) {
                this.recommendationMovies.push(movies[0]);
                this.currentPage++;
            }
        } catch (error) {
            console.error('Ошибка при загрузке следующего фильма:', error);
        }

        this.checkAgeRestriction();

        try {
            this.calculateRatingFunc();
        } catch (error) {
            console.error('Ошибка при выводе рейтинга:', error);
        }

        try {
            this.moviesData = await this.fetchMoviesData();
        } catch (error) {
            console.error('Ошибка при загрузке данных:', error);
        }

        this.collections = await this.getCollection();
    },

    watch: {
        async selectedGenres(newVal) {
            try {
                this.currentPage = 1;
                const movies = await this.fetchMovies();
                if (movies.length > 0) {
                    this.recommendationMovies = [movies[0]]; // Обновляем массив фильмов
                } else {
                    this.recommendationMovies = [];
                }
                this.checkAgeRestriction();
                this.calculateRatingFunc();
                this.currentPage = 2;
            } catch (error) {
                console.error('Ошибка при загрузке фильмов:', error);
            }
        },

        async selectedYears(newVal) {
            try {
                this.currentPage = 1;
                const movies = await this.fetchMovies();
                if (movies.length > 0) {
                    this.recommendationMovies = [movies[0]]; // Обновляем массив фильмов
                } else {
                    this.recommendationMovies = [];
                }
                this.checkAgeRestriction();
                this.calculateRatingFunc();
                this.currentPage = 2;
            } catch (error) {
                console.error('Ошибка при загрузке фильмов:', error);
            }
        },

        async selectedCountries(newVal) {
            try {
                this.currentPage = 1;
                const movies = await this.fetchMovies();
                if (movies.length > 0) {
                    this.recommendationMovies = [movies[0]]; // Обновляем массив фильмов
                } else {
                    this.recommendationMovies = [];
                }
                this.checkAgeRestriction();
                this.calculateRatingFunc();
                this.currentPage = 2;
            } catch (error) {
                console.error('Ошибка при загрузке фильмов:', error);
            }
        },

        async ratingFrom(newVal) {
            try {
                this.currentPage = 1;
                const movies = await this.fetchMovies();
                if (movies.length > 0) {
                    this.recommendationMovies = [movies[0]]; // Обновляем массив фильмов
                } else {
                    this.recommendationMovies = [];
                }
                this.checkAgeRestriction();
                this.calculateRatingFunc();
                this.currentPage = 2;
            } catch (error) {
                console.error('Ошибка при загрузке фильмов:', error);
            }
        },

        async ratingTo(newVal) {
            try {
                this.currentPage = 1;
                const movies = await this.fetchMovies();
                if (movies.length > 0) {
                    this.recommendationMovies = [movies[0]]; // Обновляем массив фильмов
                } else {
                    this.recommendationMovies = [];
                }
                this.checkAgeRestriction();
                this.calculateRatingFunc();
                this.currentPage = 2;
            } catch (error) {
                console.error('Ошибка при загрузке фильмов:', error);
            }
        },

        async searchQuery(newVal) {
            try {
                this.currentPage = 1;
                const movies = await this.fetchMovies();
                if (movies.length > 0) {
                    this.recommendationMovies = [movies[0]]; // Обновляем массив фильмов
                } else {
                    this.recommendationMovies = [];
                }
                this.checkAgeRestriction();
                this.calculateRatingFunc();
                this.currentPage = 2;
            } catch (error) {
                console.error('Ошибка при загрузке фильмов:', error);
            }
        },
    },

    directives: {
        ClickOutside: vClickOutside,
    },
};
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

    .hidden {
        display: none;
    }

    .recommendations-container {
        margin-top: 50px;
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        justify-content: center;
        align-items: center;
        flex-direction: column;

        .filters-container {
            width: 100%;
            display: flex;
            justify-content: space-between;

            .filters {
                display: flex;
                gap: 30px;
                position: relative;

                .filter-genres, .filter-countries, .filter-years, .filter-ratings {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    font-size: 12px;
                    color: $greyTextColor;
                    border: 1px solid $greyTextColor;
                    border-radius: 5px;
                    outline: none;
                    background: linear-gradient(to right bottom, $mainColor4 30%, $mainColor1);
                    padding: 6px 12px;
                    font-weight: $MediumWeight;
                    cursor: pointer;
                    gap: 15px;
                    transition: .2s;
                    width: 100%;

                    img {
                        margin-top: 3px;
                        width: 20px;
                    }

                    .dropdown {
                        display: none;
                        position: absolute;
                        top: 100%;
                        left: 0;
                        width: 100%;
                        margin-top: 10px;
                        border-radius: 5px;
                        background-color: $extraColor;
                        z-index: 1000;
                        transition: .2s;
                        max-height: 500px;
                        overflow-y: auto;

                        .filter-title {
                            background-color: #052731;
                            padding: 5px 10px;
                        }

                        .filter-container {
                            padding: 5px;
                            ul {
                                list-style-type: none;
                                line-height: 0;
                                padding: 0;
                                margin: 0;
                                column-gap: 20px;

                                li {
                                    display: inline-flex;
                                    align-items: center;
                                    gap: 10px;
                                    padding: 10px 10px;


                                    input[type="checkbox"] {
                                        accent-color: #094554;
                                    }

                                    .input__rating {
                                        font-size: 13px;
                                        color: $greyTextColor;
                                        border: 2px solid $greyTextColor;
                                        border-radius: 5px;
                                        outline: none;
                                        background: linear-gradient(to right bottom, $mainColor4 30%, $mainColor1);
                                        padding: 10px;
                                    }
                                }
                            }

                            .genre-columns {
                                columns: 3;
                            }

                            .year-columns {
                                columns: 4;
                            }

                            .countrie-columns {
                                columns: 3;
                                column-gap: 0;
                            }

                            .rating-columns {
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                gap: 20px;

                                .span__minus {
                                    font-size: 40px;
                                }

                                .ratingfrom, .ratingto {
                                    .input__rating {
                                        width: 100px;
                                        background-color: $extraColor;
                                        color: $greyTextColor;
                                        padding: 5px 10px;
                                        border: 1px solid $greyTextColor;
                                        border-radius: 5px;
                                        outline: none;
                                    }
                                }
                            }
                        }

                    }

                    &:hover::after {
                        content: "";
                        position: absolute;
                        top: 100%;
                        left: 0;
                        width: 100%;
                        height: 10px; /* Высота отступа */
                    }

                    &:hover {
                        background-color: $mainColor3;
                        .dropdown {
                            display: block;
                        }
                    }
                }
            }

            .filter-search {
                display: flex;
                gap: 10px;

                .input__search {
                    font-size: 12px;
                    color: $greyTextColor;
                    border: 1px solid $greyTextColor;
                    font-weight: $SemiBoldWeight;
                    border-radius: 5px;
                    outline: none;
                    background: linear-gradient(to right bottom, $mainColor4 30%, $mainColor1);
                    padding: 8px 12px;
                }

                .btn__search {
                    background-color: $greyTextExtraColor;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    font-weight: $MediumWeight;
                    padding: 5px 10px;
                }
            }

        }

        .recommendation-container {
            background-color: $mainColor3;
            border-radius: 10px;
            width: 70%;
            cursor: pointer;

            .recommendation-data {
                background-size: cover;
                background-position: center;
                border-radius: 10px;

                transition: .3s;

                height: 550px;
                min-height: 100%;

                position: relative;

                .film-image-background {
                    position: absolute;
                    background: linear-gradient(to right, #000000CB 25%, #00000000);
                    height: 100%;
                    border-radius: 10px;
                    width: 100%;

                    .film {
                        position: relative;
                        margin-left: 55px;
                        width: 60%;

                        .film-logo {
                            margin-top: 98px;
                            margin-bottom: -50px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            width: 500px;
                            height: 150px;
                            overflow: hidden;

                            img {
                                width: 100%;
                                height: 100%;
                                object-fit: contain;
                            }
                        }

                        .film-name {
                            margin-top: 150px;
                            width: 600px;

                            h1 {
                                color: #ff0b60;
                                line-height: 1;
                            }
                        }

                        .long-text {
                            margin-top: 122px;

                            h1 {
                                font-size: 38px;
                            }
                        }

                        .film-description {
                            color: $whiteColor;
                            font-weight: $RegularWeight;
                            font-size: 14px;
                            margin-top: 60px;
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
                            flex-direction: column;
                            margin-top: 30px;
                            gap: 10px;
                            transition: .2s;
                            z-index: 0;

                            .rating-moviq, .rating-kp {
                                display: flex;
                                flex-direction: row;
                                align-items: center;
                                gap: 10px;
                                font-weight: $MediumWeight;
                                font-size: 14px;

                                .rating, .not-rating {
                                    background-color: #181232;
                                    border-radius: 5px;
                                    padding: 10px;
                                }

                                .rating {
                                    color: #ff0b60;
                                    font-weight: $BoldWeight;
                                    font-size: 18px;
                                }

                                .not-rating {
                                    font-size: 15px;
                                    font-weight: $RegularWeight;
                                    padding: 12px 10px;
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
                            margin-top: 20px;
                            display: inline-block;
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

                    .navigate-recommendation-container {
                        margin-top: 25px;
                        width: 100%;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        border-radius: 50px;
                        cursor: pointer;
                        transition: .3s;
                        z-index: 0;
                        gap: 20px;

                        .navigate-recommendation {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            background-color: #cccde5;
                            border-radius: 50px;
                            padding: 10px;

                            .navigate-img {
                                padding: 5px;
                                border-radius: 50px;
                                height: 20px;

                                img {
                                    width: 20px;
                                }
                            }
                        }

                        .add-movie-btn {
                            position: relative;
                            display: inline-flex;
                            padding: 11px;
                            transition: .2s;
                            z-index: 0;

                            img {
                                width: 35px;
                            }

                            &:hover {
                                cursor: pointer;
                                transform: scale(1.1);
                                border-radius: 50px 0 0 50px;
                            }

                            .text-block-container {
                                position: absolute;
                                top: 0;
                                left: 100%;
                                display: none;
                                background-color: #cccde5;
                                padding: 2px 10px;
                                font-size: 12px;
                                font-weight: $MediumWeight;
                                border-radius: 0 5px 5px 0;
                                height: 48px;

                                .text-block {
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    gap: 25px;
                                    margin-top: 0;
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
                                    background-color: #cccde5;
                                    border-radius: 0 5px 5px 0;
                                    min-height: 42px;

                                    .collections {
                                        display: flex;
                                        flex-direction: column;
                                        padding: 5px;
                                        gap: 5px;
                                        min-height: 42px;
                                        max-height: 200px;
                                        overflow-y: auto;
                                        width: 300px;

                                        div {
                                            margin-top: 2px;
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
                                                color: $greyTextColor;
                                            }
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
                }
            }
        }

        .recommendation-null {
            height: 50vh;
        }

        .modal {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            color: $greyTextColor;
            border: 1px solid $greyTextColor;
            border-radius: 5px;
            outline: none;
            background-color: transparent;
            padding: 6px 12px;
            font-weight: $MediumWeight;
            cursor: pointer;
            gap: 15px;
            transition: .2s;
            width: 64%;

            .modal-content {
                padding: 50px 30px;
                text-align: center;

                img {
                    width: 180px;
                    display: block;
                    margin: 0 auto;
                }

                h2 {
                    color: $whiteColor;
                    margin-top: 50px;
                    font-size: 38px;
                    text-align: center;
                }

                p {
                    margin-top: -10px;
                    font-size: 16px;
                    text-align: left;
                }

                .modal-btn {
                    margin-top: 50px;
                    display: flex;
                    justify-content: center;
                    gap: 50px;

                    .btn-close {
                        background-color: #b8b9e0;
                        color: #0a0a0a;

                        &:hover {
                            background-color: #d6d8ec;
                        }
                    }
                }
            }
        }
    }

    .filters-container ul {
        list-style-type: none; /* Убираем точки */
        padding: 0;
        margin: 0;
    }
}

@media screen and (min-width: 767px) and (max-width: 1023px) {
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

        .hidden {
            display: none;
        }

        .recommendations-container {
            margin-top: 50px;
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            justify-content: center;
            align-items: center;
            flex-direction: column;

            .filters-container {
                width: 100%;
                display: flex;
                justify-content: space-between;

                .filters {
                    display: flex;
                    gap: 30px;
                    position: relative;

                    .filter-genres, .filter-countries, .filter-years, .filter-ratings {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        font-size: 12px;
                        color: $greyTextColor;
                        border: 1px solid $greyTextColor;
                        border-radius: 5px;
                        outline: none;
                        background: linear-gradient(to right bottom, $mainColor4 30%, $mainColor1);
                        padding: 6px 12px;
                        font-weight: $MediumWeight;
                        cursor: pointer;
                        gap: 15px;
                        transition: .2s;
                        width: 100%;

                        img {
                            margin-top: 3px;
                            width: 20px;
                        }

                        .dropdown {
                            display: none;
                            position: absolute;
                            top: 100%;
                            left: 0;
                            width: 100%;
                            margin-top: 10px;
                            border-radius: 5px;
                            background-color: $extraColor;
                            z-index: 1000;
                            transition: .2s;
                            max-height: 500px;
                            overflow-y: auto;

                            .filter-title {
                                background-color: #052731;
                                padding: 5px 10px;
                            }

                            .filter-container {
                                padding: 5px;
                                ul {
                                    list-style-type: none;
                                    line-height: 0;
                                    padding: 0;
                                    margin: 0;
                                    column-gap: 20px;

                                    li {
                                        display: inline-flex;
                                        align-items: center;
                                        gap: 10px;
                                        padding: 10px 10px;


                                        input[type="checkbox"] {
                                            accent-color: #094554;
                                        }

                                        .input__rating {
                                            font-size: 13px;
                                            color: $greyTextColor;
                                            border: 2px solid $greyTextColor;
                                            border-radius: 5px;
                                            outline: none;
                                            background: linear-gradient(to right bottom, $mainColor4 30%, $mainColor1);
                                            padding: 10px;
                                        }
                                    }
                                }

                                .genre-columns {
                                    columns: 3;
                                }

                                .year-columns {
                                    columns: 4;
                                }

                                .countrie-columns {
                                    columns: 3;
                                    column-gap: 0;
                                }

                                .rating-columns {
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    gap: 20px;

                                    .span__minus {
                                        font-size: 40px;
                                    }

                                    .ratingfrom, .ratingto {
                                        .input__rating {
                                            width: 100px;
                                            background-color: $extraColor;
                                            color: $greyTextColor;
                                            padding: 5px 10px;
                                            border: 1px solid $greyTextColor;
                                            border-radius: 5px;
                                            outline: none;
                                        }
                                    }
                                }
                            }

                        }

                        &:hover::after {
                            content: "";
                            position: absolute;
                            top: 100%;
                            left: 0;
                            width: 100%;
                            height: 10px; /* Высота отступа */
                        }

                        &:hover {
                            background-color: $mainColor3;
                            .dropdown {
                                display: block;
                            }
                        }
                    }
                }

                .filter-search {
                    display: flex;
                    gap: 10px;

                    .input__search {
                        font-size: 12px;
                        color: $greyTextColor;
                        border: 1px solid $greyTextColor;
                        font-weight: $SemiBoldWeight;
                        border-radius: 5px;
                        outline: none;
                        background: linear-gradient(to right bottom, $mainColor4 30%, $mainColor1);
                        padding: 8px 12px;
                    }

                    .btn__search {
                        background-color: $greyTextExtraColor;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        font-weight: $MediumWeight;
                        padding: 5px 10px;
                    }
                }

            }

            .recommendation-container {
                background-color: $mainColor3;
                border-radius: 10px;
                width: 90%;
                cursor: pointer;

                .recommendation-data {
                    background-size: cover;
                    background-position: center;
                    border-radius: 10px;

                    transition: .3s;

                    height: 550px;
                    min-height: 100%;

                    position: relative;

                    .film-image-background {
                        position: absolute;
                        background: linear-gradient(to right, #000000CB 25%, #00000000);
                        height: 100%;
                        border-radius: 10px;
                        width: 100%;

                        .film {
                            position: relative;
                            margin-left: 55px;
                            width: 60%;

                            .film-logo {
                                margin-top: 50px;
                                margin-bottom: -50px;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                width: 500px;
                                height: 150px;
                                overflow: hidden;

                                img {
                                    width: 100%;
                                    height: 100%;
                                    object-fit: contain;
                                }
                            }

                            .film-name {
                                margin-top: 100px;
                                width: 600px;

                                h1 {
                                    color: #ff0b60;
                                    line-height: 1;
                                }
                            }

                            .long-text {
                                margin-top: 122px;

                                h1 {
                                    font-size: 38px;
                                }
                            }

                            .film-description {
                                color: $whiteColor;
                                font-weight: $RegularWeight;
                                font-size: 14px;
                                margin-top: 60px;
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
                                flex-direction: column;
                                margin-top: 30px;
                                gap: 10px;
                                transition: .2s;
                                z-index: 0;

                                .rating-moviq, .rating-kp {
                                    display: flex;
                                    flex-direction: row;
                                    align-items: center;
                                    gap: 10px;
                                    font-weight: $MediumWeight;
                                    font-size: 14px;

                                    .rating, .not-rating {
                                        background-color: #181232;
                                        border-radius: 5px;
                                        padding: 10px;
                                    }

                                    .rating {
                                        color: #ff0b60;
                                        font-weight: $BoldWeight;
                                        font-size: 18px;
                                    }

                                    .not-rating {
                                        font-size: 15px;
                                        font-weight: $RegularWeight;
                                        padding: 12px 10px;
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
                                                width: 100px;

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
                                margin-top: 20px;
                                display: inline-block;
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

                        .navigate-recommendation-container {
                            margin-top: 60px;
                            width: 100%;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            border-radius: 50px;
                            cursor: pointer;
                            transition: .3s;
                            z-index: 0;
                            gap: 20px;

                            .navigate-recommendation {
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                background-color: #cccde5;
                                border-radius: 50px;
                                padding: 8px;

                                .navigate-img {
                                    padding: 5px;
                                    border-radius: 50px;
                                    height: 20px;

                                    img {
                                        width: 20px;
                                    }
                                }
                            }

                            .add-movie-btn {
                                position: relative;
                                display: inline-flex;
                                padding: 9px;
                                transition: .2s;
                                z-index: 0;

                                img {
                                    width: 35px;
                                }

                                &:hover {
                                    cursor: pointer;
                                    transform: scale(1.1);
                                    border-radius: 50px 0 0 50px;
                                }

                                .text-block-container {
                                    position: absolute;
                                    top: 0;
                                    left: 100%;
                                    display: none;
                                    background-color: #cccde5;
                                    padding: 2px 10px;
                                    font-size: 12px;
                                    font-weight: $MediumWeight;
                                    border-radius: 0 5px 5px 0;
                                    height: 48px;

                                    .text-block {
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                        gap: 25px;
                                        margin-top: 0;
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
                                        background-color: #cccde5;
                                        border-radius: 0 5px 5px 0;
                                        min-height: 42px;

                                        .collections {
                                            display: flex;
                                            flex-direction: column;
                                            padding: 5px;
                                            gap: 5px;
                                            min-height: 42px;
                                            max-height: 200px;
                                            overflow-y: auto;
                                            width: 300px;

                                            div {
                                                margin-top: 2px;
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
                                                    color: $greyTextColor;
                                                }
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
                    }
                }
            }

            .recommendation-null {
                height: 50vh;
            }

            .modal {
                margin-top: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 12px;
                color: $greyTextColor;
                border: 1px solid $greyTextColor;
                border-radius: 5px;
                outline: none;
                background-color: transparent;
                padding: 6px 12px;
                font-weight: $MediumWeight;
                cursor: pointer;
                gap: 15px;
                transition: .2s;
                width: 64%;

                .modal-content {
                    padding: 50px 30px;
                    text-align: center;

                    img {
                        width: 180px;
                        display: block;
                        margin: 0 auto;
                    }

                    h2 {
                        color: $whiteColor;
                        margin-top: 50px;
                        font-size: 38px;
                        text-align: center;
                    }

                    p {
                        margin-top: -10px;
                        font-size: 16px;
                        text-align: left;
                    }

                    .modal-btn {
                        margin-top: 50px;
                        display: flex;
                        justify-content: center;
                        gap: 50px;

                        .btn-close {
                            background-color: #b8b9e0;
                            color: #0a0a0a;

                            &:hover {
                                background-color: #d6d8ec;
                            }
                        }
                    }
                }
            }
        }

        .filters-container ul {
            list-style-type: none; /* Убираем точки */
            padding: 0;
            margin: 0;
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

        .hidden {
            display: none;
        }

        .recommendations-container {
            margin-top: 50px;
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            justify-content: center;
            align-items: center;
            flex-direction: column;

            .filters-container {
                width: 100%;
                display: flex;
                justify-content: space-between;
                flex-direction: column;
                gap: 20px;

                .filters {
                    display: flex;
                    flex-direction: row;
                    flex-wrap: wrap;
                    gap: 30px;
                    position: relative;

                    .filter-genres, .filter-countries, .filter-years, .filter-ratings {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        font-size: 12px;
                        color: $greyTextColor;
                        border: 1px solid $greyTextColor;
                        border-radius: 5px;
                        outline: none;
                        background: linear-gradient(to right bottom, $mainColor4 30%, $mainColor1);
                        padding: 6px 12px;
                        font-weight: $MediumWeight;
                        cursor: pointer;
                        gap: 15px;
                        transition: .2s;
                        width: 100%;

                        img {
                            margin-top: 3px;
                            width: 20px;
                        }

                        .dropdown {
                            display: none;
                            position: absolute;
                            top: 100%;
                            left: 0;
                            width: 100%;
                            margin-top: 10px;
                            border-radius: 5px;
                            background-color: $extraColor;
                            z-index: 1000;
                            transition: .2s;
                            max-height: 500px;
                            overflow-y: auto;

                            .filter-title {
                                background-color: #052731;
                                padding: 5px 10px;
                            }

                            .filter-container {
                                padding: 5px;
                                ul {
                                    list-style-type: none;
                                    line-height: 0;
                                    padding: 0;
                                    margin: 0;
                                    column-gap: 20px;

                                    li {
                                        display: inline-flex;
                                        align-items: center;
                                        gap: 10px;
                                        padding: 10px 10px;


                                        input[type="checkbox"] {
                                            accent-color: #094554;
                                        }

                                        .input__rating {
                                            font-size: 13px;
                                            color: $greyTextColor;
                                            border: 2px solid $greyTextColor;
                                            border-radius: 5px;
                                            outline: none;
                                            background: linear-gradient(to right bottom, $mainColor4 30%, $mainColor1);
                                            padding: 10px;
                                        }
                                    }
                                }

                                .genre-columns {
                                    columns: 3;
                                }

                                .year-columns {
                                    columns: 4;
                                }

                                .countrie-columns {
                                    columns: 3;
                                    column-gap: 0;
                                }

                                .rating-columns {
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    gap: 20px;

                                    .span__minus {
                                        font-size: 40px;
                                    }

                                    .ratingfrom, .ratingto {
                                        .input__rating {
                                            width: 100px;
                                            background-color: $extraColor;
                                            color: $greyTextColor;
                                            padding: 5px 10px;
                                            border: 1px solid $greyTextColor;
                                            border-radius: 5px;
                                            outline: none;
                                        }
                                    }
                                }
                            }

                        }

                        &:hover::after {
                            content: "";
                            position: absolute;
                            top: 100%;
                            left: 0;
                            width: 100%;
                            height: 10px; /* Высота отступа */
                        }

                        &:hover {
                            background-color: $mainColor3;
                            .dropdown {
                                display: block;
                            }
                        }
                    }
                }

                .filter-search {
                    display: flex;
                    gap: 10px;

                    .input__search {
                        width: 100%;
                        font-size: 12px;
                        color: $greyTextColor;
                        border: 1px solid $greyTextColor;
                        font-weight: $SemiBoldWeight;
                        border-radius: 5px;
                        outline: none;
                        background: linear-gradient(to right bottom, $mainColor4 30%, $mainColor1);
                        padding: 8px 12px;
                    }

                    .btn__search {
                        background-color: $greyTextExtraColor;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        font-weight: $MediumWeight;
                        padding: 5px 10px;
                    }
                }

            }

            .recommendation-container {
                background-color: $mainColor3;
                border-radius: 10px;
                width: 90%;
                cursor: pointer;

                .recommendation-data {
                    background-size: cover;
                    background-position: center;
                    border-radius: 10px;

                    transition: .3s;

                    height: 550px;
                    min-height: 100%;

                    position: relative;

                    .film-image-background {
                        position: absolute;
                        background: linear-gradient(to right, #000000CB 25%, #00000000);
                        height: 100%;
                        border-radius: 10px;
                        width: 100%;

                        .film {
                            position: relative;
                            margin-left: 20px;
                            width: 60%;

                            .film-logo {
                                margin-top: 50px;
                                margin-bottom: -50px;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                width: 200px;
                                height: 150px;
                                overflow: hidden;

                                img {
                                    width: 100%;
                                    height: 100%;
                                    object-fit: contain;
                                }
                            }

                            .film-name {
                                margin-top: 80px;
                                width: 200px;
                                word-wrap: break-word;
                                word-break: break-all;
                                word-break: break-word;

                                h1 {
                                    font-size: 28px;
                                    color: #ff0b60;
                                    line-height: 1;
                                    word-wrap: break-word;
                                    word-break: break-all;
                                    word-break: break-word;
                                }
                            }

                            .long-text {
                                margin-top: 20px;

                                h1 {
                                    font-size: 25px;
                                }
                            }

                            .film-description {
                                color: $whiteColor;
                                font-weight: $RegularWeight;
                                font-size: 14px;
                                margin-top: 30px;
                            }

                            .film-data {
                                display: flex;
                                flex-wrap: wrap;
                                color: $whiteColor;
                                font-size: 14px;
                                gap: 10px;

                                .genres {
                                    display: flex;
                                    flex-wrap: wrap;
                                    gap: 2px;
                                }
                            }

                            .rating-container {
                                color: $whiteColor;
                                display: flex;
                                flex-direction: column;
                                margin-top: 0px;
                                gap: 10px;
                                transition: .2s;
                                z-index: 0;

                                .rating-moviq, .rating-kp {
                                    display: flex;
                                    flex-direction: row;
                                    align-items: center;
                                    gap: 10px;
                                    font-weight: $MediumWeight;
                                    font-size: 14px;

                                    .rating, .not-rating {
                                        background-color: #181232;
                                        border-radius: 5px;
                                        padding: 10px;
                                    }

                                    .rating {
                                        color: #ff0b60;
                                        font-weight: $BoldWeight;
                                        font-size: 18px;
                                    }

                                    .not-rating {
                                        font-size: 15px;
                                        font-weight: $RegularWeight;
                                        padding: 12px 10px;
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
                                                width: 100px;

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
                                margin-top: 20px;
                                display: inline-block;
                                background-color: #b8b9e0;
                                border-radius: 50px;
                                padding: 12px 10px;
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
                                gap: 12px;
                                margin-top: 20px;
                                margin-left: 10px;
                                background-color: #cccde5;
                                border-radius: 50px;
                                padding: 12px 23px;
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

                        .navigate-recommendation-container {
                            margin-top: 120px;
                            width: 100%;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            border-radius: 50px;
                            cursor: pointer;
                            transition: .3s;
                            z-index: 0;
                            gap: 20px;

                            .navigate-recommendation {
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                background-color: #cccde5;
                                border-radius: 50px;
                                padding: 8px;

                                .navigate-img {
                                    padding: 5px;
                                    border-radius: 50px;
                                    height: 20px;

                                    img {
                                        width: 20px;
                                    }
                                }
                            }

                            .add-movie-btn {
                                position: relative;
                                display: inline-flex;
                                padding: 9px;
                                transition: .2s;
                                z-index: 0;

                                img {
                                    width: 35px;
                                }

                                &:hover {
                                    cursor: pointer;
                                    transform: scale(1.1);
                                    border-radius: 50px 0 0 50px;
                                }

                                .text-block-container {
                                    position: absolute;
                                    top: 0;
                                    left: 100%;
                                    display: none;
                                    background-color: #cccde5;
                                    padding: 2px 10px;
                                    font-size: 12px;
                                    font-weight: $MediumWeight;
                                    border-radius: 0 5px 5px 0;
                                    height: 48px;

                                    .text-block {
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                        gap: 25px;
                                        margin-top: 0;
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
                                        background-color: #cccde5;
                                        border-radius: 0 5px 5px 0;
                                        min-height: 42px;

                                        .collections {
                                            display: flex;
                                            flex-direction: column;
                                            padding: 5px;
                                            gap: 5px;
                                            min-height: 42px;
                                            max-height: 200px;
                                            overflow-y: auto;
                                            width: 300px;

                                            div {
                                                margin-top: 2px;
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
                                                    color: $greyTextColor;
                                                }
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
                    }
                }
            }

            .recommendation-null {
                height: 50vh;
            }

            .modal {
                margin-top: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 12px;
                color: $greyTextColor;
                border: 1px solid $greyTextColor;
                border-radius: 5px;
                outline: none;
                background-color: transparent;
                padding: 6px 12px;
                font-weight: $MediumWeight;
                cursor: pointer;
                gap: 15px;
                transition: .2s;
                width: 64%;

                .modal-content {
                    padding: 50px 30px;
                    text-align: center;

                    img {
                        width: 180px;
                        display: block;
                        margin: 0 auto;
                    }

                    h2 {
                        color: $whiteColor;
                        margin-top: 50px;
                        font-size: 38px;
                        text-align: center;
                    }

                    p {
                        margin-top: -10px;
                        font-size: 16px;
                        text-align: left;
                    }

                    .modal-btn {
                        margin-top: 50px;
                        display: flex;
                        justify-content: center;
                        gap: 50px;

                        .btn-close {
                            background-color: #b8b9e0;
                            color: #0a0a0a;

                            &:hover {
                                background-color: #d6d8ec;
                            }
                        }
                    }
                }
            }
        }

        .filters-container ul {
            list-style-type: none; /* Убираем точки */
            padding: 0;
            margin: 0;
        }
    }
}

</style>
