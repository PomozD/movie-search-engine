<template>
    <div class="modal" @click="closeModal" v-if="showModal">
        <div class="modal-container" @click.stop>
            <span class="close" @click="closeModal">&times;</span>
            <div class="modal-content">
                <h2>Добавить фильм</h2>
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
                </div>
                <p class="p__title">Название фильма:</p>
                <input type="text" v-model="searchQuery" @input="fetchMovies" class="input__name" placeholder="Поиск по названию">
                <form @submit.prevent="handleSubmit" class="modal-form">
                    <p v-if="filteredMovies.length > 0" class="p__title">Результаты поиска:</p>
                    <!-- Вывод результатов поиска -->
                    <ul v-if="filteredMovies.length > 0">
                        <li v-for="movie in filteredMovies" :key="movie.id">
                            <div class="movie__container">
                                <div class="movie-backdrop">
                                    <img :src="movie.backdrop"/>
                                </div>
                                <div class="movie__data">
                                    <p>
                                        <router-link :to="{ name: 'Film', query: { userID: this.$route.query.userID, id: movie.id }}">
                                            {{ movie.name }} ({{ movie.year }})
                                        </router-link>
                                    </p>
                                    <div class="film-data">
                                        <div class="genres">
                                            <span v-for="(genre, index) in movie.genres" :key="genre.id">
                                                {{genre.name}}
                                                <span v-if="index !== movie.genres.length - 1">,</span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="countries">
                                        <span v-for="(country, index) in movie.countries" :key="country.id">
                                            {{country.country}}
                                            <span v-if="index !== movie.countries.length - 1">,</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button @click="addMovie(movie.id)" type="button" class="movie__add">Добавить</button>
                        </li>
                    </ul>
                    <p v-else-if="filteredMovies.length === 0" class="p__title">Нет результатов поиска</p>
                    <p v-else class="p__title">Введите название фильма</p>
                    <div class="navigation" v-if="filteredMovies.length > 0">
                        <button @click="goToPreviousPage" type="button" :disabled="currentPage === 1" class="navigation-btn btn">Назад</button>
                        <button @click="goToNextPage" type="button" :disabled="currentPage === totalPages" class="navigation-btn btn">Вперед</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { searchMovies, addMovieToCollection } from "../services/collections.js";
import { getMoviesData } from "../services/recommendation.js";
export default {
    props: ['value'],
    emits: ['update:modelValue', 'close', 'submit'],
    data() {
        return {
            searchQuery: '',
            prevSearchQuery: '',

            movies: [],
            moviesData: [],

            selectedGenres: [],
            selectedYears: [],
            selectedCountries: [],

            ratingFrom: '',
            ratingTo: '',

            currentPage: 1,
            totalPages: 1
        };
    },

    computed: {
        showModal: {
            get() {
                return this.value;
            },
            set(newValue) {
                this.$emit('update:modelValue', newValue);
            }
        },
        filteredMovies() {
            if (!Array.isArray(this.movies)) {
                return [];
            }
            return this.movies;
        }
    },

    methods: {
        closeModal() {
            this.showModal = false;
            this.$emit('close', false);
            console.log(this.showModal);
        },
        handleSubmit() {
            this.showModal = false;
            console.log(this.showModal);
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

        async fetchMovies() {
            const isFiltersEmpty = !this.selectedGenres.length && !this.selectedYears.length && !this.selectedCountries.length && !this.ratingFrom && !this.ratingTo;
            const isSearchQueryEmpty = !this.searchQuery.trim();

            console.log(this.ratingFrom, this.ratingTo);
            if (isSearchQueryEmpty && isFiltersEmpty) {
                this.movies = [];
                this.currentPage = 1;
                this.totalPages = 1;
                return;
            }

            if (this.searchQuery || (this.searchQuery !== this.prevSearchQuery)) {
                this.currentPage = 1;
            }

            this.prevSearchQuery = this.searchQuery;

            const response = await searchMovies(this.searchQuery, this.currentPage, this.selectedGenres, this.selectedYears, this.selectedCountries, this.ratingFrom, this.ratingTo);
            console.log(response);
            this.movies = response.data.data; // Обновить список фильмов
            this.totalPages = response.data.last_page; // Обновить общее количество страниц
            console.log(this.totalPages);
        },

        goToPreviousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.fetchMovies(this.currentPage);
            }
            console.log(this.showModal);
        },

        goToNextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.fetchMovies(this.currentPage);
            }
            console.log(this.showModal);
        },

        async addMovie(movieID) {
            const response = await addMovieToCollection(this.$route.query.userID, this.$route.query.id, movieID);
            this.$emit('close', false);
            /*if (response.status) {
                alert(response.message);
            }*/
            console.log(response);
            return response;
        }
    },

    async created() {
        try {
            this.moviesData = await this.fetchMoviesData();
        } catch (error) {
            console.error('Ошибка при загрузке данных:', error);
        }
    },

    watch: {
        async selectedGenres(newVal) {
            try {
                this.fetchMovies();
            } catch (error) {
                console.error('Ошибка при загрузке фильмов:', error);
            }
        },

        async selectedYears(newVal) {
            try {
                this.fetchMovies();
            } catch (error) {
                console.error('Ошибка при загрузке фильмов:', error);
            }
        },

        async selectedCountries(newVal) {
            try {
                this.fetchMovies();
            } catch (error) {
                console.error('Ошибка при загрузке фильмов:', error);
            }
        },

        async ratingFrom(newVal) {
            try {
                this.fetchMovies();
            } catch (error) {
                console.error('Ошибка при загрузке фильмов:', error);
            }
        },

        async ratingTo(newVal) {
            try {
                this.fetchMovies();
            } catch (error) {
                console.error('Ошибка при загрузке фильмов:', error);
            }
        },
    },
};
</script>

<style scoped lang="scss">
@import "@/assets/styles/variables.scss";

.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;

    .modal-container {
        position: relative;
        margin-top: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 12px;
        color: $greyTextColor;
        border: 1px solid $greyTextColor;
        border-radius: 5px;
        outline: none;
        background-color: $mainColor2;
        padding: 6px 12px;
        font-weight: $MediumWeight;
        gap: 15px;
        transition: .2s;
        width: 75%;

        h1 {
            margin-left: -2px;
            margin-top: 40px;
            font-size: 38px;
            line-height: 0;
            color: $whiteColor;
        }

        h2 {
            color: $whiteColor;
            margin-top: -30px;
            font-size: 38px;
            text-align: center;
        }

        .close {
            position: absolute;
            top: 5px;
            right: 15px;
            cursor: pointer;
            font-size: 30px;
        }

        .modal-content {
            padding: 30px 30px; ///////////////
            width: 70%;

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
                            max-height: 300px;
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

            .p__title {
                margin-top: 30px;
                font-size: 14px;
                color: $greyTextColor;
                transition: .5s;
                line-height: 0.7;
            }

            .input__name {
                font-size: 14px;
                color: $greyTextColor;
                border: 1px solid $greyTextColor;
                border-radius: 5px;
                outline: none;
                background: linear-gradient(to right bottom, $mainColor4 30%, $mainColor1);
                padding: 14px;
                min-width: 100%;
            }

            .modal-form {
                display: flex;
                flex-direction: column;

                ul {
                    margin-top: 10px;
                    list-style: none;
                    padding: 0;
                    display: flex;
                    flex-direction: column;
                    gap: 20px;

                    li {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        width: 100%;

                        .movie__container {
                            display: flex;
                            .movie-backdrop {
                                width: 65px;
                                height: 65px;

                                img {
                                    width: 100%;
                                    height: 100%;
                                    object-fit: cover;
                                    border-radius: 5px;
                                }
                            }

                            .movie__data {
                                padding: 0 20px;
                                p {
                                    font-size: 14px;
                                    color: $whiteColor;
                                    line-height: 0;
                                }

                                .film-data {
                                    display: flex;
                                }
                            }
                        }

                        .movie__add {
                            margin-right: -30px;
                            background-color: #b8b9e0;
                            color: #0a0a0a;
                            border: none;
                            width: 15%;
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
                }

                .navigation {
                    display: flex;
                    justify-content: center;
                    gap: 30px;

                    .navigation-btn {
                        width: 20%;
                        padding: 10px;
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
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;

        .modal-container {
            position: relative;
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
            color: $greyTextColor;
            border: 1px solid $greyTextColor;
            border-radius: 5px;
            outline: none;
            background-color: $mainColor2;
            padding: 6px 12px;
            font-weight: $MediumWeight;
            gap: 15px;
            transition: .2s;
            width: 80%;
            overflow-y: auto;
            height: 500px;

            h1 {
                margin-left: -2px;
                margin-top: 40px;
                font-size: 23px;
                line-height: 0;
                color: $whiteColor;
            }

            h2 {
                color: $whiteColor;
                margin-top: 0px;
                font-size: 23px;
                text-align: center;
            }

            .close {
                position: absolute;
                top: -5px;
                right: 15px;
                cursor: pointer;
                font-size: 55px;
            }

            .modal-content {
                padding: 30px 0px;
                width: 95%;
                height: 500px;

                h2 {
                    margin-top: 20px;
                }

                .filters-container {
                    width: 100%;
                    display: flex;
                    justify-content: space-between;

                    .filters {
                        display: flex;
                        gap: 10px;
                        position: relative;
                        flex-direction: row;
                        flex-wrap: wrap;
                        width: 100%;

                        .filter-genres, .filter-countries, .filter-years, .filter-ratings {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            font-size: 16px;
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
                            width: 18%;

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
                                max-height: 300px;
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
                                            padding: 10px 6px;


                                            input[type="checkbox"] {
                                                accent-color: #094554;
                                            }

                                            .input__rating {
                                                font-size: 16px;
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
                                        columns: 5;
                                    }

                                    .countrie-columns {
                                        columns: 3;
                                        column-gap: 40px;
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
                                                width: 100%;
                                                background-color: $extraColor;
                                                color: $greyTextColor;
                                                padding: 5px 10px;
                                                border: 1px solid $greyTextColor;
                                                border-radius: 5px;
                                                outline: none;
                                                overflow-y: auto;

                                                option {
                                                    font-size: 13px;
                                                    padding: 0;
                                                }
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
                            font-size: 16px;
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

                .p__title {
                    margin-top: 30px;
                    font-size: 16px;
                    color: $greyTextColor;
                    transition: .5s;
                    line-height: 0.7;
                }

                .input__name {
                    font-size: 14px;
                    color: $greyTextColor;
                    border: 1px solid $greyTextColor;
                    border-radius: 5px;
                    outline: none;
                    background: linear-gradient(to right bottom, $mainColor4 30%, $mainColor1);
                    padding: 14px;
                    min-width: 90%;
                }

                .modal-form {
                    display: flex;
                    flex-direction: column;

                    ul {
                        margin-top: 10px;
                        list-style: none;
                        padding: 0;
                        display: flex;
                        flex-direction: column;
                        justify-content: flex-start;
                        gap: 30px;

                        li {
                            margin-top: 20px;
                            display: flex;
                            justify-content: center;
                            flex-direction: column;
                            align-items: flex-start;
                            width: 100%;

                            .movie__container {
                                display: flex;
                                .movie-backdrop {
                                    width: 120px;
                                    height: 120px;

                                    img {
                                        width: 100%;
                                        height: 100%;
                                        object-fit: cover;
                                        border-radius: 5px;
                                    }
                                }

                                .movie__data {
                                    padding: 0 20px;
                                    p {
                                        margin-top: 0px;
                                        font-size: 16px;
                                        color: $whiteColor;
                                        line-height: 1;

                                        a {
                                            font-size: 18px;
                                        }
                                    }

                                    .film-data {
                                        display: flex;

                                        .genres {
                                            display: flex;
                                            flex-direction: row;
                                            flex-wrap: wrap;
                                        }
                                    }

                                    .countries {
                                        margin-top: 5px;
                                    }
                                }
                            }

                            .movie__add {
                                margin-top: 20px;
                                margin-right: 0px;
                                margin-left: 0px;
                                background-color: #b8b9e0;
                                color: #0a0a0a;
                                border: none;
                                width: 40%;
                                height: 40px;
                                text-align: center;
                                border-radius: 4px;
                                cursor: pointer;
                                transition: .4s;
                                font-size: 16px;
                                font-weight: $MediumWeight;

                                &:hover {
                                    background-color: #d6d8ec;
                                }
                            }
                        }
                    }

                    .navigation {
                        display: flex;
                        justify-content: center;
                        gap: 30px;
                        margin-top: 30px;
                        margin-bottom: 50px;

                        .navigation-btn {
                            width: 50%;
                            padding: 10px;
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
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;

        .modal-container {
            position: relative;
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
            color: $greyTextColor;
            border: 1px solid $greyTextColor;
            border-radius: 5px;
            outline: none;
            background-color: $mainColor2;
            padding: 6px 12px;
            font-weight: $MediumWeight;
            gap: 15px;
            transition: .2s;
            width: 80%;
            overflow-y: auto;
            height: 500px;

            h1 {
                margin-left: -2px;
                margin-top: 40px;
                font-size: 23px;
                line-height: 0;
                color: $whiteColor;
            }

            h2 {
                color: $whiteColor;
                margin-top: 0px;
                font-size: 23px;
                text-align: center;
            }

            .close {
                position: absolute;
                top: -5px;
                right: 15px;
                cursor: pointer;
                font-size: 55px;
            }

            .modal-content {
                padding: 30px 0px;
                width: 95%;
                height: 500px;

                h2 {
                    margin-top: 20px;
                }

                .filters-container {
                    width: 100%;
                    display: flex;
                    justify-content: space-between;

                    .filters {
                        display: flex;
                        gap: 20px;
                        position: relative;
                        flex-direction: row;
                        flex-wrap: wrap;

                        .filter-genres, .filter-countries, .filter-years, .filter-ratings {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            font-size: 16px;
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
                            position: relative;

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
                                max-height: 300px;
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
                                            padding: 10px 0px;


                                            input[type="checkbox"] {
                                                accent-color: #094554;
                                            }

                                            .input__rating {
                                                font-size: 16px;
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
                                        columns: 2;
                                    }

                                    .year-columns {
                                        columns: 3;
                                    }

                                    .countrie-columns {
                                        columns: 2;
                                        column-gap: 30px;
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
                                                width: 100%;
                                                background-color: $extraColor;
                                                color: $greyTextColor;
                                                padding: 5px 10px;
                                                border: 1px solid $greyTextColor;
                                                border-radius: 5px;
                                                outline: none;
                                                overflow-y: auto;

                                                option {
                                                    font-size: 13px;
                                                    padding: 0;
                                                }
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
                            font-size: 16px;
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

                .p__title {
                    margin-top: 30px;
                    font-size: 16px;
                    color: $greyTextColor;
                    transition: .5s;
                    line-height: 0.7;
                }

                .input__name {
                    font-size: 14px;
                    color: $greyTextColor;
                    border: 1px solid $greyTextColor;
                    border-radius: 5px;
                    outline: none;
                    background: linear-gradient(to right bottom, $mainColor4 30%, $mainColor1);
                    padding: 14px;
                    min-width: 90%;
                }

                .modal-form {
                    display: flex;
                    flex-direction: column;

                    ul {
                        margin-top: 10px;
                        list-style: none;
                        padding: 0;
                        display: flex;
                        flex-direction: column;
                        gap: 30px;

                        li {
                            display: flex;
                            justify-content: space-between;
                            flex-direction: column;
                            align-items: center;
                            width: 100%;

                            .movie__container {
                                display: flex;
                                .movie-backdrop {
                                    width: 50%;
                                    height: 100px;

                                    img {
                                        width: 100%;
                                        height: 100%;
                                        object-fit: cover;
                                        border-radius: 5px;
                                    }
                                }

                                .movie__data {
                                    padding: 0 20px;
                                    p {
                                        margin-top: 0px;
                                        font-size: 16px;
                                        color: $whiteColor;
                                        line-height: 1;

                                        a {
                                            font-size: 18px;
                                        }
                                    }

                                    .film-data {
                                        display: flex;

                                        .genres {
                                            display: flex;
                                            flex-direction: row;
                                            flex-wrap: wrap;
                                        }
                                    }

                                    .countries {
                                        margin-top: 5px;
                                    }
                                }
                            }

                            .movie__add {
                                margin-top: 20px;
                                margin-right: 0px;
                                margin-left: -8px;
                                background-color: #b8b9e0;
                                color: #0a0a0a;
                                border: none;
                                width: 40%;
                                height: 40px;
                                text-align: center;
                                border-radius: 4px;
                                cursor: pointer;
                                transition: .4s;
                                font-size: 16px;
                                font-weight: $MediumWeight;

                                &:hover {
                                    background-color: #d6d8ec;
                                }
                            }
                        }
                    }

                    .navigation {
                        display: flex;
                        justify-content: center;
                        gap: 30px;
                        margin-top: 30px;
                        margin-bottom: 50px;

                        .navigation-btn {
                            width: 50%;
                            padding: 10px;
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
