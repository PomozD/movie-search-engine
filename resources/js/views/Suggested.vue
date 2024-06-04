<template>
    <section class="section-container">

        <div v-if="state.user" class="container">
            <div class="wrapper">
                <div class="container__profile">
                    <div class="breadcrumbs">
                        <router-link :to="{ name: 'Profile', query: { userID: this.$route.query.userID }}">Личный кабинет</router-link>
                        <img src="../assets/images/arrowright.svg" alt="Стрелка" class="arrow-icon">
                        <span>Предложение фильма</span>
                    </div>

                    <h1>Предложение фильма</h1>

                    <div class="profile">
                        <!--Предложение фильма-->
                        <div class="suggest-container">
                            <details close class="suggest-details">
                                <summary class="suggest-title">
                                    <h2>Предложить фильм</h2>
                                    <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                </summary>

                                <div class="data-container">
                                    <div class="title">Выберите данные</div>

                                    <div class="details-container">
                                        <details close class="suggest-data">
                                            <summary>
                                                <h2>Название</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="userNameMovie" type="text" placeholder="Введите название" class="data-input"/>
                                        </details>
                                        <details close class="suggest-data">
                                            <summary>
                                                <h2>Название на английском языке</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="userAlternativeName" type="text" placeholder="Введите название" class="data-input"/>
                                        </details>
                                        <details close class="suggest-data">
                                            <summary class="suggest-data">
                                                <h2>Описание</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="userDescription" type="text" placeholder="Введите описание" class="data-input"/>
                                        </details>
                                        <details close class="suggest-data">
                                            <summary>
                                                <h2>Короткое описание</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="userShortDescription" type="text" placeholder="Введите короткое описание" class="data-input"/>
                                        </details>
                                        <details close class="suggest-data">
                                            <summary>
                                                <h2>Слоган</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="userSlogan" type="text" placeholder="Введите слоган" class="data-input"/>
                                        </details>
                                        <details close class="suggest-data">
                                            <summary>
                                                <h2>Тип</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <div>
                                                <select v-model="userMovieSelectedTypeOption" class="data-select">
                                                    <option v-for="option in movieTypeOptions" :key="option">
                                                        {{option['name']}}
                                                    </option>
                                                </select>
                                            </div>
                                        </details>
                                        <details close class="suggest-data">
                                            <summary>
                                                <h2>Возрастное ограничение</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <select v-model="userMovieSelectedRestrictOption" class="data-select">
                                                <option v-for="option in movieRestrictOptions" :key="option">
                                                    {{option['restriction']}}
                                                </option>
                                            </select>
                                        </details>
                                        <details close class="suggest-data">
                                            <summary>
                                                <h2>Год</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="userYear" type="text" placeholder="Год" class="data-input"/>
                                        </details>

                                        <details close class="suggest-data">
                                            <summary>
                                                <h2>Сериал(1) или фильм(0)</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="userIsSeries" type="text" placeholder="Введите 1 или 0" class="data-input"/>
                                        </details>

                                        <details close class="suggest-data">
                                            <summary>
                                                <h2>Рейтинг MPAA</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="userRatingMpaa" type="text" placeholder="Введите рейтинг MPAA" class="data-input"/>
                                        </details>

                                        <details close class="suggest-data">
                                            <summary>
                                                <h2>Бюджет</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="userBudget" type="text" placeholder="Введите бюджет" class="data-input"/>
                                        </details>

                                        <details close class="suggest-data">
                                            <summary>
                                                <h2>Длительность фильма</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="userMovieLength" type="text" placeholder="Введите длительность фильма" class="data-input"/>
                                        </details>

                                        <details close class="suggest-data">
                                            <summary>
                                                <h2>Длительность сериала</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="userSeriesLength" type="text" placeholder="Введите длительность сериала" class="data-input"/>
                                        </details>

                                        <div class="buttons">
                                            <div class="btn" @click="suggestMovieFunction">
                                                Предложить
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </details>
                        </div>
                        <!--Предложенные фильмы-->
                        <div class="suggest-container">
                            <details close class="suggest-details">
                                <summary class="suggest-title">
                                    <h2>Предложенные фильмы</h2>
                                    <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                </summary>

                                <div class="data-container">
                                    <div class="title">Ваши предложенные фильмы</div>
                                    <div class="tickets-container">
                                        <div>
                                            <h2>ID</h2>
                                            <div v-for="ticket in tickets" :key="ticket.id" class="ticket-data">
                                                <p>{{ ticket.id }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <h2>Дата</h2>
                                            <div v-for="ticket in tickets" :key="ticket.id" class="ticket-data">
                                                <p>{{ ticket.formatted_created_at }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <h2>Название</h2>
                                            <div v-for="ticket in tickets" :key="ticket.id" class="ticket-data">
                                                <p>{{ ticket.name }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <h2>Статус</h2>
                                            <div v-for="ticket in tickets" :key="ticket.id" class="ticket-data">
                                                <p>{{ ticket.status }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <h2>Удаление</h2>
                                            <div v-for="ticket in tickets" :key="ticket.id" class="ticket-data">
                                                <button @click="deleteSuggestionFunc(ticket.id)" class="btn-delete">Удалить</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </details>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p v-else>Авторизируйтесь для доступа</p>
    </section>
</template>

<script>
import { getUserInfo, editUserData, suggestMovie } from "../services/profile.js";

import {mapGetters, mapMutations} from 'vuex';

import useVuelidate from '@vuelidate/core'
import { required, email, minLength } from '@vuelidate/validators'
import { reactive, computed } from 'vue'
import { getSuggestions, deleteSuggestion } from "../services/profile.js";
import {getMoviesRestricts, getMoviesTypes} from "../services/adminFunctions.js";

export default {
    name: "ProfileView",

    data() {
        return {
            edited: '',
            reg: /\w+/,

            userMovieSelectedTypeOption: '',
            movieTypeOptions: null,
            userMovieSelectedRestrictOption: '',
            movieRestrictOptions: null,

            userNameMovie: '',
            userAlternativeName: '',
            userDescription: '',
            userShortDescription: '',
            userSlogan: '',
            userYear: '',
            userIsSeries: '',
            userRatingMpaa: '',
            userBudget: '',
            userMovieLength: '',
            userSeriesLength: '',

            tickets: [],
        }
    },

    setup() {
        const state = reactive({
            user: [],
            edit: false,

            isNameEditing: false,
            isEmailEditing: false,
            isPasswordEditing: false,

            newName: "",
            newEmail: "",
            newPassword: "",
        })

        const rules = computed(() => {
            return {
                newName: { minLength: minLength(3) },
                newEmail: { email },
                newPassword: { minLength: minLength(6) },
            }
        })

        const v$ = useVuelidate(rules, state)

        return {
            state, v$,
        }
    },

    methods: {
        ...mapMutations(["updateUser"]),
        ...mapGetters(["isAuthorize"]),

        async getInfo() {
            const response = await getUserInfo(this.$route.query.userID);
            return response.userInfo;
        },

        toggleEdit(field) {
            if(field === 'name') {
                this.state.isNameEditing = !this.state.isNameEditing;
                if (!this.state.isNameEditing) {
                    this.state.newName = '';
                }
            } else if(field === 'email') {
                this.state.isEmailEditing = !this.state.isEmailEditing;
                if (!this.state.isEmailEditing) {
                    this.state.newEmail = '';
                }
            } else if(field === 'password') {
                this.state.isPasswordEditing = !this.state.isPasswordEditing;
                if (!this.state.isPasswordEditing) {
                    this.state.newPassword = '';
                }
            }
        },

        async editData(field, state) {
            const fieldMapping = {
                name: 'isNameEditing',
                email: 'isEmailEditing',
                password: 'isPasswordEditing'
            };

            if (!fieldMapping[field]) {
                console.error('Invalid field provided');
                return;
            }

            const editingState = fieldMapping[field];

            if (state) {
                this.v$.$validate();
                if (!this.v$.$error) {
                    try {
                        let response;
                        if (field === 'name') {
                            response = await editUserData(this.$route.query.userID, this.state.newName, this.state.newEmail = '', this.state.newPassword = '', this.state.user.email);
                        } else if (field === 'email') {
                            response = await editUserData(this.$route.query.userID, this.state.newName = '', this.state.newEmail, this.state.newPassword = '', this.state.user.email);
                        } else if (field === 'password') {
                            response = await editUserData(this.$route.query.userID, this.state.user.name = '', this.state.newEmail = '', this.state.newPassword, this.state.user.email);
                        }

                        const newresponse = await getUserInfo(this.$route.query.userID);
                        this.updateUser(newresponse.userInfo);
                        this.state.user = newresponse.userInfo;
                        alert('Данные изменены');

                        this.state[editingState] = false;

                    } catch (error) {
                        console.error('Error updating user data:', error);
                        alert('Ошибка при обновлении данных пользователя');
                    }
                } else {
                    alert('Проверьте заполнение полей');
                }
            } else {
                this.state[editingState] = false;
            }
        },

        logout() {
            this.$router.push({path: '/'});
            this.$store.state.user = null;
        },

        navigateToSuggested() {
            this.$router.push({ name: 'Suggested', query: { userID: this.$route.query.userID } });
        },

        async getMovieTypes() {
            const response = await getMoviesTypes();
            return response;
        },

        async getMovieRestricts() {
            const response = await getMoviesRestricts();
            return response;
        },

        async getSuggestion() {
            const response = await getSuggestions(this.$route.query.userID);
            console.log(response);
            return response;
        },

        async suggestMovieFunction() {
            const response = await suggestMovie(
                this.userMovieSelectedTypeOption,
                this.userIsSeries,
                this.userYear,
                this.userAlternativeName,
                this.userBudget,
                this.userDescription,
                this.userMovieLength,
                this.userMovieSelectedRestrictOption,
                this.userNameMovie,
                this.userRatingMpaa,
                this.userShortDescription,
                this.userSeriesLength,
                this.userSlogan,
                this.$route.query.userID
            );

            if(response.type === 0) {
                alert('Фильм предложен');
                this.tickets = await this.getSuggestion();
            } else {
                alert('Фильм не предложен');
            }
        },

        async deleteSuggestionFunc(tickedID) {
            const response = await deleteSuggestion(this.$route.query.userID, tickedID);
            this.tickets = await this.getSuggestion();
            return response;
        },
    },

    async created(){
        this.state.user = await this.getInfo();

        /*const userId = this.$route.query.userID;
        if (userId) {
            this.$store.commit('updateUser', { userID: userId });
        }*/

        this.movieTypeOptions = await this.getMovieTypes();

        this.movieRestrictOptions = await this.getMovieRestricts();

        this.tickets = await this.getSuggestion();

    },

    computed: {
        getUser() {
            console.log(this.$store.getters.getUser);
            return this.$store.getters.getUser;
        },
    },

    components: {
        /*VFooter*/
    },

    watch: {
        /*"$route.params": {
            handler: async function () {
            },
            immediate: true*/
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
                    margin-top: 50px;

                    .suggest-container {
                        .suggest-details {
                            margin-bottom: 15px;

                            .data-container {
                                    width: 100%;
                                    margin-top: 10px;
                                    border-radius: 5px;
                                    background-color: $extraColor;
                                    color: $greyTextColor;
                                    z-index: 1000;
                                    transition: .2s;

                                .title {
                                    background-color: #052731;
                                    padding: 5px 10px;
                                    font-size: 14px;
                                }

                                .details-container {
                                    padding: 0 15px;

                                    .suggest-data {
                                        margin-top: 10px;
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
                                    }

                                    .suggest-data[open] {
                                        summary {
                                            img {
                                                transform: rotate(180deg);
                                            }
                                        }
                                    }

                                    .buttons {
                                        padding: 30px 0 20px 0;
                                        width: 30%;
                                    }
                                }
                            }

                            .tickets-container {
                                display: flex;
                                gap: 20px;
                                padding: 0 10px;

                                .ticket-data {
                                    font-size: 14px;
                                    padding: 10px 0;
                                }

                                .btn-delete {
                                    background-color: #b8b9e0;
                                    color: #0a0a0a;
                                    border: none;
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

                        .suggest-details[open]{

                            .suggest-title {
                                .arrowdown {
                                    transform: rotate(180deg);
                                }
                            }
                        }

                        summary {
                            cursor: pointer;
                            list-style: none;
                            display: flex;
                            align-items: center;
                            line-height: 0;
                            gap: 10px;

                            .arrowdown {
                                margin-top: 15px;
                                width: 25px;
                                transition: transform 0.3s ease;
                            }
                        }
                    }
                }
            }
        }
    }
}
</style>
