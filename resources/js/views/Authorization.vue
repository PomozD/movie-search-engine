<template>
    <div class="container__auth">
        <div class="login-box">

            <div class="titles">
                <h1>
                    <router-link to="/authorization" class="custom-a auth">Авторизация</router-link>
                </h1>
                <h1>
                    <router-link to="/registration" class="custom-a reg">Регистрация</router-link>
                </h1>
            </div>

            <div class="google-button" @click="googleAuthFunc">
                <button>
                    <img src="../assets/images/icons-google.svg"/>
                    Google
                </button>
            </div>

            <div class="auth-divider">
                <p>или</p>
            </div>

            <form @submit.prevent="authorization">
                <div class="user-box">
                    <input v-model.trim="state.input__email" type="text" name="" required="">
                    <label>Email</label>
                    <p v-if="v$.input__email.$error" class="invalid__data">Проверьте правильность полей</p>
                </div>
                <div class="user-box">
                    <input v-model.trim="state.input__password" type="password" name="" required="">
                    <label>Пароль</label>
                    <p v-if="v$.input__password.$error" class="invalid__data">Проверьте правильность полей</p>
                </div>
                <div class="button-form">

                    <button id="submit" class="btn">Войти</button>

                    <div id="reg">
                        У Вас еще нет аккаунта?
                        <router-link class="enter" to="/registration">Зарегистрироваться </router-link>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <footer>
        <v-footer/>
    </footer>
</template>

<script>
import {getUser, googleAuth, googleCreateOrAuth} from "../services/reg_auth.js";
import useVuelidate from '@vuelidate/core'
import {required, email, minLength} from '@vuelidate/validators'
import { reactive, computed } from 'vue'
import {mapMutations} from 'vuex'

/*import VFooter from "../components/v-footer.vue";*/

export default {
    name: "RegistrationView",

    setup() {
        const state = reactive({
            input__email: "",
            input__password: "",
        })

        const rules = computed(() => {
            return {
                input__email: { required, email },
                input__password: { required, minLength: minLength(6) },
            }
        })

        const v$ = useVuelidate(rules, state)

        return {
            state, v$,
        }
    },

    methods: {
        ...mapMutations(["updateUser"]),

        async authorization() {
            this.v$.$validate()
            if(!this.v$.$error){

                const response = await getUser(this.state.input__email, this.state.input__password);

                if(response[0].type === 1){
                    alert(response[0].message);
                } else {
                    this.$router.push("/collections?userID=" + response[0].user.id);
                }

                this.updateUser(response[0].user);
            }
        },

        async googleAuthFunc() {
            await googleAuth();
        },

        async handleAuthorization(code) {
            const response = await googleCreateOrAuth(code);
            console.log(response);

            if(response[0].type === 1){
                alert("Произошла ошибка авторизации, проверьте данные");
            } else {
                this.$router.push("/collections?userID=" + response[0].user.id);
            }

            this.updateUser(response[0].user);

            localStorage.setItem('user', JSON.stringify(user));
            localStorage.setItem('isAuthorized', true);
        }
    },

    async created() {
        const state = this.$route.query.state;
        const code = this.$route.query.code;
        const scope = this.$route.query.scope;
        const authuser = this.$route.query.authuser;
        const prompt = this.$route.query.prompt;

        if (state && code && scope && authuser && prompt) {
            // Параметры присутствуют в URL, выполните нужные действия
            this.handleAuthorization(code);
        } else {
            // Параметры отсутствуют в URL, выполните другие действия
        }
    },

    components: {
        /*VFooter,*/
    }

}
</script>

<style scoped lang="scss">
@import "@/assets/styles/_variables.scss";

.container__auth {
    width: 100%;
    height: 100vh;
    display: flex;
    flex-direction: column;

    .login-box {
        margin: 20px auto;
        width: 400px;
        border-radius: 10px;

        .titles {
            display: flex;
            justify-content: center;
            gap: 40px;

            h1 {
                .custom-a {
                    font-size: 18px;
                    font-weight: $MediumWeight;
                }

                .auth {
                    color: $whiteColor;
                }

                .reg {
                    color: $greyTextColor;

                    &:hover {
                        color: $whiteColor;
                    }
                }
            }
        }

        .google-button {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
            border: 2px solid $blue;
            text-align: center;

            button {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                background: none;
                border: none;
                padding: 8px 0;
                cursor: pointer;
                color: $btnColor;
                transition: .4s;
                font-size: 20px;
            }

            img {
                width: 40px;
                margin-right: 15px;
            }
        }

        .auth-divider {
            margin-top: 20px;
            text-align: center;

            p {
                color: $greyTextColor;
                font-weight: $MediumWeight;
                font-size: 14px;
            }
        }

        form {
            margin-top: 35px;

            .user-box {
                display: flex;
                flex-direction: column;
                margin-top: 10px;

                input {
                    position: relative;
                    font-size: 16px;
                    color: $greyTextColor;
                    border: 2px solid $greyTextColor;
                    border-radius: 5px;
                    outline: none;
                    background: linear-gradient(to right bottom, $mainColor4 30%, $mainColor1);
                    padding: 15px;
                }

                label {
                    position: relative;
                    left: 18px;
                    top: -46px;
                    padding: 18px 0;
                    font-size: 14px;
                    color: #a6a6a6;
                    pointer-events: none;
                    line-height: 0.2;
                    transition: .5s;
                }

                input:focus ~ label, input:valid ~ label {
                    top: -95px;
                    left: 0;
                }

                .invalid__data {
                    color: #d34339;
                    margin-top: -35px;
                    font-size: 14px;
                    margin-bottom: 25px;
                }
            }

            .button-form {
                display: flex;
                justify-content: center;
                flex-direction: column;
                align-items: center;
                margin: 20px auto;

                button {
                    width: 100%;
                    border: none;
                    font-weight: $MediumWeight;
                    font-size: 14px;
                }

                #reg {
                    margin: 15px auto;
                    color: $greyTextColor;

                    .enter {
                        color: $whiteColor;
                        transition: .2s;

                        &:hover {
                            color: $greyTextColor;
                        }
                    }
                }
            }
        }
    }
}

@media screen and (min-width: 767px) and (max-width: 1023px) {
    .container__auth {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;

        .login-box {
            margin: 20px auto;
            width: 500px;
            border-radius: 10px;

            .titles {
                display: flex;
                justify-content: center;
                gap: 40px;

                h1 {
                    .custom-a {
                        font-size: 23px;
                        font-weight: $MediumWeight;
                    }

                    .auth {
                        color: $whiteColor;
                    }

                    .reg {
                        color: $greyTextColor;

                        &:hover {
                            color: $whiteColor;
                        }
                    }
                }
            }

            .google-button {
                margin-top: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 5px;
                border: 2px solid $blue;
                text-align: center;

                button {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 100%;
                    background: none;
                    border: none;
                    padding: 8px 0;
                    cursor: pointer;
                    color: $btnColor;
                    transition: .4s;
                    font-size: 23px;
                }

                img {
                    width: 45px;
                    margin-right: 15px;
                }
            }

            .auth-divider {
                margin-top: 20px;
                text-align: center;

                p {
                    color: $greyTextColor;
                    font-weight: $MediumWeight;
                    font-size: 16px;
                }
            }

            form {
                margin-top: 35px;

                .user-box {
                    display: flex;
                    flex-direction: column;
                    margin-top: 10px;

                    input {
                        position: relative;
                        font-size: 18px;
                        color: $greyTextColor;
                        border: 2px solid $greyTextColor;
                        border-radius: 5px;
                        outline: none;
                        background: linear-gradient(to right bottom, $mainColor4 30%, $mainColor1);
                        padding: 15px;
                    }

                    label {
                        position: relative;
                        left: 18px;
                        top: -46px;
                        padding: 18px 0;
                        font-size: 16px;
                        color: #a6a6a6;
                        pointer-events: none;
                        line-height: 0.2;
                        transition: .5s;
                    }

                    input:focus ~ label, input:valid ~ label {
                        top: -95px;
                        left: 0;
                    }

                    .invalid__data {
                        color: #d34339;
                        margin-top: -35px;
                        font-size: 14px;
                        margin-bottom: 25px;
                    }
                }

                .button-form {
                    display: flex;
                    justify-content: center;
                    flex-direction: column;
                    align-items: center;
                    margin: 20px auto;

                    button {
                        width: 100%;
                        border: none;
                        font-weight: $MediumWeight;
                        font-size: 18px;
                    }

                    #reg {
                        font-size: 16px;
                        margin: 20px auto;
                        color: $greyTextColor;

                        .enter {
                            font-size: 16px;
                            color: $whiteColor;
                            transition: .2s;

                            &:hover {
                                color: $greyTextColor;
                            }
                        }
                    }
                }
            }
        }
    }
}

@media screen and (max-width: 767px) {
    .container__auth {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;

        .login-box {
            margin: 20px auto;
            width: 100%;
            border-radius: 10px;

            .titles {
                display: flex;
                justify-content: center;
                gap: 40px;

                h1 {
                    .custom-a {
                        font-size: 23px;
                        font-weight: $MediumWeight;
                    }

                    .auth {
                        color: $whiteColor;
                    }

                    .reg {
                        color: $greyTextColor;

                        &:hover {
                            color: $whiteColor;
                        }
                    }
                }
            }

            .google-button {
                margin-top: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 5px;
                border: 2px solid $blue;
                text-align: center;

                button {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 100%;
                    background: none;
                    border: none;
                    padding: 8px 0;
                    cursor: pointer;
                    color: $btnColor;
                    transition: .4s;
                    font-size: 23px;
                }

                img {
                    width: 45px;
                    margin-right: 15px;
                }
            }

            .auth-divider {
                margin-top: 20px;
                text-align: center;

                p {
                    color: $greyTextColor;
                    font-weight: $MediumWeight;
                    font-size: 18px;
                }
            }

            form {
                margin-top: 35px;

                .user-box {
                    display: flex;
                    flex-direction: column;
                    margin-top: 10px;

                    input {
                        position: relative;
                        font-size: 18px;
                        color: $greyTextColor;
                        border: 2px solid $greyTextColor;
                        border-radius: 5px;
                        outline: none;
                        background: linear-gradient(to right bottom, $mainColor4 30%, $mainColor1);
                        padding: 15px;
                    }

                    label {
                        position: relative;
                        left: 18px;
                        top: -46px;
                        padding: 18px 0;
                        font-size: 16px;
                        color: #a6a6a6;
                        pointer-events: none;
                        line-height: 0.2;
                        transition: .5s;
                    }

                    input:focus ~ label, input:valid ~ label {
                        top: -95px;
                        left: 0;
                    }

                    .invalid__data {
                        color: #d34339;
                        margin-top: -35px;
                        font-size: 14px;
                        margin-bottom: 25px;
                    }
                }

                .button-form {
                    display: flex;
                    justify-content: center;
                    flex-direction: column;
                    align-items: center;
                    margin: 20px auto;

                    button {
                        width: 100%;
                        border: none;
                        font-weight: $MediumWeight;
                        font-size: 18px;
                    }

                    #reg {
                        width: 100%;
                        font-size: 16px;
                        margin: 30px 0 0 0;
                        display: flex;
                        align-items: center;
                        flex-direction: column;
                        color: $greyTextColor;

                        .enter {
                            margin: 10px auto 0 auto;
                            font-size: 16px;
                            color: $whiteColor;
                            transition: .2s;

                            &:hover {
                                color: $greyTextColor;
                            }
                        }
                    }
                }
            }
        }
    }
}

</style>
