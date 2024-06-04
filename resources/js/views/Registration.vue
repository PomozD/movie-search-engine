<template>
    <div class="container__reg">
        <div class="login-box">
            <div class="titles">
                <h1>
                    <router-link to="/authorization" class="custom-a auth">Авторизация</router-link>
                </h1>
                <h1>
                    <router-link to="/registration" class="custom-a reg">Регистрация</router-link>
                </h1>
            </div>

            <form @submit.prevent="registration">
                <div class="user-box">
                    <input v-model.trim="state.input__name" type="text" name="" required="">
                    <label>Имя</label>
                    <p v-if="v$.input__name.$error" class="invalid__data">Проверьте правильность полей</p>
                </div>
                <div class="user-box">
                    <input class="is__invalid" v-model.trim="state.input__email" type="text" name="" required="">
                    <label>Email</label>
                    <p v-if="v$.input__email.$error" class="invalid__data">Проверьте правильность полей</p>
                </div>
                <div class="user-box">
                    <input class="is__invalid" v-model.trim="state.input__password" type="password" name="" required="">
                    <label>Пароль</label>
                    <p v-if="v$.input__password.$error" class="invalid__data">Проверьте правильность полей</p>
                </div>
                <div class="user-box">
                    <input class="is__invalid" v-model.trim="state.input__password__repeat" type="password" name="" required="">
                    <label>Повторите пароль</label>
                    <p v-if="v$.input__password__repeat.$error" class="invalid__data">Поле пустое или пароли не совпадают</p>
                </div>
                <div class="button-form">

                    <button id="submit" class="btn">Зарегистрироваться</button>

                    <div id="auth">
                        У Вас есть аккаунт?
                        <router-link class="enter" to="/authorization">Войти </router-link>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <footer>
<!--        <v-footer/>-->
    </footer>
</template>

<script>
import {postUser} from "../services/reg_auth.js";
import useVuelidate from '@vuelidate/core'
import { required, email, minLength, sameAs } from '@vuelidate/validators'
import { reactive, computed } from 'vue'

/*import VFooter from "../components/v-footer.vue";*/
// import axios from "axios";

export default {
    name: "RegistrationView",

    data() {
        return {
            reg: /[А-Яа-яA-Za-z]/,
        }
    },

    setup() {
        const state = reactive({
            input__name: "",
            input__email: "",
            input__password: "",
            input__password__repeat: "",

        })

        const rules = computed(() => {
            return {
                input__name: { required },
                input__email: { required, email },
                input__password: { required, minLength: minLength(6) },
                input__password__repeat: { required, sameAs: sameAs(state.input__password) },
            }
        })

        const v$ = useVuelidate(rules, state)

        return {
            state, v$,
        }
    },

    methods: {
        async registration() {
            this.v$.$validate()
            console.log(!this.v$.$error, this.reg.test(this.state.input__name));
            if(!this.v$.$error && this.reg.test(this.state.input__name)){

                const response = await postUser(this.state.input__name, this.state.input__email, this.state.input__password);
                console.log(response[0].type);

                if(response[0].type === 1){
                    alert(response[0].message);
                }else {
                    alert('Вы зарегистрированы');
                    //document.location.href = './authorization';
                    this.$router.push("/authorization");
                }

            } else {
                alert('Проверьте правильность заполнения формы');
            }

        }
    },


    components: {
        /*VFooter,*/
    },
}
</script>

<style scoped lang="scss">
@import "@/assets/styles/_variables.scss";

.container__reg {
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
                    color: $greyTextColor;

                    &:hover {
                        color: $whiteColor;
                    }
                }

                .reg {
                    color: $whiteColor;
                }
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

                input:valid ~ label, input:focus ~ label {
                    top: -95px;
                    left: 0;
                }

                .invalid__data {
                    color: #d34339;
                    margin-top: -35px;
                    font-size: 14px;
                    margin-bottom: 50px;
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

                #auth {
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
</style>
