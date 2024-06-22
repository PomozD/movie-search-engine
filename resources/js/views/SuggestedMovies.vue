<template>
    <section class="section-container">
        <div class="container">
            <div class="wrapper">
                <div class="container__profile">
                    <div class="breadcrumbs">
                        <router-link :to="{ name: 'AdminPanel', query: { userID: this.$route.query.userID }}">Админ панель</router-link>
                        <img src="../assets/images/arrowright.svg" alt="Стрелка" class="arrow-icon">
                        <span>Предложенные фильмы</span>
                    </div>

                    <h1>Предложенные фильмы</h1>
                    <div class="profile">
                        <div class="admin-container">
                            <h2>ID</h2>
                            <div v-for="ticket in tickets" :key="ticket.id" class="ticket-data">
                                <p>
                                    <router-link :to="{ name: 'Suggestion', query: { userID: this.$route.query.userID, suggestedUserID: ticket.user_id, id: ticket.id }}">#{{ ticket.id }}</router-link>
                                </p>
                            </div>
                        </div>
                        <div class="admin-container">
                            <h2>Дата</h2>
                            <div v-for="ticket in tickets" :key="ticket.id" class="ticket-data">
                                <p>{{ ticket.formatted_created_at }}</p>
                            </div>
                        </div>
                        <div class="admin-container">
                            <h2>Название</h2>
                            <div v-for="ticket in tickets" :key="ticket.id" class="ticket-data">
                                <p>{{ ticket.name }}</p>
                            </div>
                        </div>
                        <div class="admin-container">
                            <h2>Статус</h2>
                            <div v-for="ticket in tickets" :key="ticket.id" class="ticket-data">
                                <p>{{ ticket.status }}</p>
                            </div>
                        </div>
                        <div class="admin-container">
                            <h2>Удаление</h2>
                            <div v-for="ticket in tickets" :key="ticket.id" class="ticket-data">
                                <button class="btn-delete">Удалить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { getSuggestions } from "../services/adminFunctions.js";

export default {
    data() {
        return {
            tickets: [],
        }
    },

    methods: {
        async getSuggestion() {
            const response = await getSuggestions();
            return response;
        }
    },

    async created() {
        this.tickets = await this.getSuggestion();
        console.log(this.tickets);
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
                    gap: 20px;
                    margin-top: 50px;

                    .admin-container {
                        .ticket-data {
                            padding-top: 10px;
                            display: flex;
                            p {
                                line-height: 0;
                                color: $greyTextColor;
                                font-weight: $RegularWeight;
                                font-size: 14px;

                                a {
                                    text-decoration: underline;
                                }
                            }

                            .btn-delete {
                                background-color: #b8b9e0;
                                color: #0a0a0a;
                                border: none;
                                height: 30px;
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
                }
            }
        }
    }
}

@media screen and (min-width: 767px) and (max-width: 1023px) {
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
            font-size: 16px;
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
                        gap: 20px;
                        margin-top: 50px;

                        .admin-container {
                            .ticket-data {
                                padding-top: 10px;
                                display: flex;
                                p {
                                    line-height: 0;
                                    color: $greyTextColor;
                                    font-weight: $RegularWeight;
                                    font-size: 16px;

                                    a {
                                        text-decoration: underline;
                                    }
                                }

                                .btn-delete {
                                    background-color: #b8b9e0;
                                    color: #0a0a0a;
                                    border: none;
                                    height: 20px;
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
                    }
                }
            }
        }
    }
}

@media screen and (max-width: 767px) {
    .section-container {
        margin-left: 0px;
        min-height: 100vh;
        display: flex;
        flex-direction: column;

        h1, h2 {
            color: $greyTextColor;
        }

        h1 {
            margin-left: -2px;
            margin-top: 40px;
            font-size: 35px;
            line-height: 1;
        }

        h2 {
            margin-top: 30px;
            font-weight: $SemiBoldWeight;
            font-size: 18px;
        }

        .data-input {
            margin-top: 10px;
            font-size: 16px;
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
                        gap: 20px;
                        margin-top: 50px;

                        .admin-container {
                            .ticket-data {
                                padding-top: 10px;
                                display: flex;
                                p {
                                    line-height: 0;
                                    color: $greyTextColor;
                                    font-weight: $RegularWeight;
                                    font-size: 16px;

                                    a {
                                        text-decoration: underline;
                                    }

                                    .btn-delete {
                                        background-color: #b8b9e0;
                                        color: #0a0a0a;
                                        border: none;
                                        height: 20px;
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
                        }
                    }
                }
            }
        }
    }
}
</style>
