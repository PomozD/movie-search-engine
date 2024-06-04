<template>
    <section class="section-container">
        <div class="container">
            <div class="wrapper">
                <div class="container__profile">
                    <div class="breadcrumbs">
                        <router-link :to="{ name: 'AdminPanel', query: { userID: this.$route.query.userID }}">Админ панель</router-link>
                        <img src="../assets/images/arrowright.svg" alt="Стрелка" class="arrow-icon">
                        <router-link :to="{ name: 'SuggestedMovies', query: { userID: this.$route.query.userID }}">Предложенные фильмы</router-link>
                        <img src="../assets/images/arrowright.svg" alt="Стрелка" class="arrow-icon">
                        <span>#{{ ticket.id }}</span>
                    </div>

                    <h1>Предложенный фильм: #{{ ticket.id }}</h1>
                    <div class="profile">
                        <div class="admin-container">
                            <div class="ticket-data">
                                <div class="title">Данные о фильме</div>
                                <div class="data-container">
                                    <div class="data">
                                        <h2>ID</h2>
                                        <p>{{ ticket.id }}</p>
                                    </div>

                                    <div class="data">
                                        <h2>Название</h2>
                                        <p>{{ ticket.name }}</p>
                                    </div>

                                    <div class="data">
                                        <h2>Название на английском</h2>
                                        <p>{{ ticket.alternativeName}}</p>
                                    </div>

                                    <div class="data">
                                        <h2>Описание</h2>
                                        <p>{{ ticket.description}}</p>
                                    </div>

                                    <div class="data">
                                        <h2>Короткое описание</h2>
                                        <p>{{ ticket.shortDescription}}</p>
                                    </div>

                                    <div class="data">
                                        <h2>Слоган</h2>
                                        <p>{{ ticket.slogan }}</p>
                                    </div>

                                    <div class="data">
                                        <h2>Год</h2>
                                        <p>{{ ticket.year }}</p>
                                    </div>

                                    <div class="data">
                                        <h2>Рейтинг MPAA</h2>
                                        <p>{{ ticket.ratingMpaa}}</p>
                                    </div>

                                    <div class="data">
                                        <h2>Бюджет</h2>
                                        <p>{{ ticket.budget }}</p>
                                    </div>

                                    <div class="data">
                                        <h2>Фильм(0) / сериал(1)</h2>
                                        <p>{{ ticket.isSeries }}</p>
                                    </div>

                                    <div class="data">
                                        <h2>Длительность фильма</h2>
                                        <p>{{ ticket.movieLength }}</p>
                                    </div>

                                    <div class="data">
                                        <h2>Длительность сериала</h2>
                                        <p>{{ ticket.seriesLength}}</p>
                                    </div>

                                    <div class="data">
                                        <h2>Дата предложения</h2>
                                        <p>{{ ticket.formatted_created_at }}</p>
                                    </div>

                                    <div class="data">
                                        <h2>Статус</h2>
                                        <p>{{ ticket.status }}</p>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <h2 class="title">Выберите ответ</h2>
                        <select v-model="selectedStatus" class="data-select">
                            <option v-for="option in status" :key="option">
                                {{option}}
                            </option>
                        </select>

                        <div class="buttons">
                            <button class="btn btn-primary" @click="answerFunc">Ответить</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { getCurrentSuggestion, postAnswerForAddMovie } from "../services/adminFunctions.js";
export default {
    data() {
        return {
            ticket: [],

            selectedStatus: '',
            status: ['review', 'accepted', 'rejected'],
        }
    },

    methods: {
        async getCurrentSuggestions() {
            const response = await getCurrentSuggestion(this.$route.query.userID, this.$route.query.suggestedUserID, this.$route.query.id);
            return response;
        },

        async answerFunc() {
            const response = await postAnswerForAddMovie(this.$route.query.userID, this.$route.query.id, this.selectedStatus);
            return response;
        },
    },

    async created() {
        this.ticket = await this.getCurrentSuggestions();
        console.log(this.ticket);

        if(this.status.length > 0) {
            this.selectedStatus = this.status[0];
        }
    },

}
</script>

<style scoped lang="scss">
@import "@/assets/styles/variables.scss";

.section-container {
    margin-left: 50px;
    min-height: 100vh;
    display: flex;
    flex-direction: column;

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
        margin-top: 30px;
        font-weight: $SemiBoldWeight;
        font-size: 18px;
    }

    .data-input {
        margin-top: 10px;
        font-size: 14px;
        color: $greyTextColor;
        border: 1px solid $greyTextColor;
        border-radius: 5px;
        outline: none;
        background: transparent;
        padding: 15px 15px;
        min-width: 30%;
        max-width: 60%;
        line-height: 0;
    }

    .data-select {
        margin-top: 10px;
        color: $greyTextColor;
        background: transparent;
        padding: 15px 15px;
        border-radius: 5px;
        border: 1px solid $greyTextColor;
    }

    .buttons {
        padding: 30px 0 20px 0;
        width: 30%;
    }

    .container {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;

        .wrapper {
            width: 100%;
            .container__profile {
                h2 {
                    color: $greyTextColor;
                    line-height: 0;
                }
                .profile {
                    display: flex;
                    flex-direction: column;
                    gap: 20px;
                    margin-top: 50px;

                    .admin-container {
                        width: 60%;
                        border-radius: 5px;
                        background-color: $extraColor;
                        color: $greyTextColor;
                        z-index: 1000;
                        transition: .2s;
                        .ticket-data {
                            .title {
                                background-color: #052731;
                                padding: 5px 10px;
                                font-size: 14px;
                            }

                            .data-container {
                                display: flex;
                                flex-direction: column;
                                padding: 20px 20px;

                                .data {

                                    h2 {
                                        padding-bottom: 10px;
                                        margin-top: 20px;
                                    }

                                    p {
                                        line-height: 0;
                                        color: $greyTextColor;
                                        font-weight: $RegularWeight;
                                        font-size: 14px;
                                        margin-bottom: 20px;

                                        a {
                                            text-decoration: underline;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    .data-select {
                        width: 10%;
                        color: $greyTextColor;
                        background: transparent;
                        padding: 15px 15px;
                        border-radius: 5px;
                        border: 1px solid $greyTextColor;
                    }

                    .buttons {
                        padding: 30px 0 20px 0;

                        .btn-primary {
                            border: none;
                            text-align: center;
                            border-radius: 4px;
                            cursor: pointer;
                            transition: .4s;
                            font-size: 12px;
                            font-weight: $MediumWeight;
                        }
                    }
                }
            }
        }
    }
}
</style>
