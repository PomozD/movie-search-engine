<template>
    <section class="section-container">

        <div v-if="state.user" class="container">
            <div class="wrapper">
                <div class="container__profile">
                    <h1>Личный кабинет</h1>

                    <div class="profile">
                        <h2>Имя</h2>
                        <div class="data-container">
                            <input v-if="state.isNameEditing" v-model.trim="state.newName" placeholder="Введите новое имя" class="data-input"/>
                            <div v-if="!state.isNameEditing" class="data-name">
                                <p>{{state.user.nickname}}</p>
                            </div>
                            <button v-if="!state.isNameEditing" @click="toggleEdit('name')" class="btn__edit">Изменить</button>
                            <button v-if="state.isNameEditing" @click="editData('name', true)" class="btn-primary btn">Сохранить</button>
                            <button v-if="state.isNameEditing" @click="editData('name', false)" class="btn__edit">Отменить</button>
                        </div>
                        <p v-if="v$.newName.$error && state.isNameEditing" class="invalid__data">Проверьте правильность полей</p>

                        <h2>Email</h2>
                        <div class="data-container">
                            <input v-if="state.isEmailEditing" v-model.trim="state.newEmail" placeholder="Введите новый email" class="data-input"/>
                            <div v-if="!state.isEmailEditing" class="data-name">
                                <p>{{state.user.email}}</p>
                            </div>
                            <button v-if="!state.isEmailEditing" @click="toggleEdit('email')" class="btn__edit">Изменить</button>
                            <button v-if="state.isEmailEditing" @click="editData('email', true)" class="btn-primary btn">Сохранить</button>
                            <button v-if="state.isEmailEditing" @click="editData('email', false)" class="btn__edit">Отменить</button>
                        </div>
                        <p v-if="v$.newEmail.$error && state.isEmailEditing" class="invalid__data">Проверьте правильность полей</p>

                        <h2>Пароль</h2>
                        <div class="data-container">
                            <input v-if="state.isPasswordEditing" v-model.trim="state.newPassword" placeholder="Введите новый пароль" class="data-input"/>
                            <div v-if="!state.isPasswordEditing" class="data-name">
                                <p>Пароль</p>
                            </div>
                            <button v-if="!state.isPasswordEditing" @click="toggleEdit('password')" class="btn__edit">Изменить</button>
                            <button v-if="state.isPasswordEditing" @click="editData('password', true)" class="btn-primary btn">Сохранить</button>
                            <button v-if="state.isPasswordEditing" @click="editData('password', false)" class="btn__edit">Отменить</button>
                        </div>
                        <p v-if="v$.newPassword.$error && state.isPasswordEditing" class="invalid__data">Проверьте правильность полей</p>

                        <div class="btn-suggested" @click="navigateToSuggested">
                            Предложение фильмов
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
                }
                .profile {
                    margin-top: 50px;
                    .data-container {
                        display: flex;
                        align-items: center;
                        gap: 20px;

                        .data-name, .data-input {
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

                        .data-input {
                            padding: 19px 15px;
                        }

                        .btn__edit {
                            background-color: #b8b9e0;
                            color: #0a0a0a;
                            border: none;
                            height: 40px;
                            width: 10%;
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

                        .btn-primary {
                            height: 40px;
                            width: 12%;
                            padding: 0;
                        }
                    }

                    .invalid__data {
                        color: #d34339;
                        font-size: 14px;
                        margin-bottom: 25px;
                    }

                    .btn-suggested {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        margin-top: 80px;
                        background-color: #b8b9e0;
                        color: #0a0a0a;
                        border: none;
                        width: 270px;
                        height: 180px;
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
        }
    }
}
</style>
