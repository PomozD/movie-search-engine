<template>
    <div class="modal" @click="closeModal" v-if="showModal">
        <div class="modal-container" @click.stop>
            <span class="close" @click="closeModal">&times;</span>
            <div class="modal-content">
                <h1>Добавить подборку</h1>
                <form @submit.prevent="handleSubmit" class="modal-form">
                    <label for="collectionName">Название подборки:</label>
                    <input type="text" id="collectionName" v-model="collectionName" required class="input__name" placeholder="Название">
                    <button type="submit" class="btn">Добавить</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['value'],
    emits: ['update:modelValue', 'close', 'submit'],
    data() {
        return {
            collectionName: '',
            collectionDescription: ''
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
        }
    },
    methods: {
        closeModal() {
            this.showModal = false;
            this.$emit('close', false);
        },
        handleSubmit() {
            this.showModal = false;
            this.$emit('submit', { name: this.collectionName, description: this.collectionDescription });
            this.$emit('close', false);
        }
    }
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
        width: 50%;

        h1 {
            margin-left: -2px;
            margin-top: 40px;
            font-size: 38px;
            line-height: 0;
            color: $whiteColor;
        }

        h2 {
            color: $whiteColor;
            margin-top: 50px;
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
            padding: 50px 30px;
            width: 70%;

            .modal-form {
                display: flex;
                flex-direction: column;

                .input__name, .input__description {
                    font-size: 16px;
                    color: $greyTextColor;
                    border: 1px solid $greyTextColor;
                    border-radius: 5px;
                    outline: none;
                    background: linear-gradient(to right bottom, $mainColor4 30%, $mainColor1);
                    padding: 15px;
                    min-width: 100%;
                }

                .input__description {
                    max-width: 100%;
                    max-height: 200px;
                    min-height: 50px;
                }

                label {
                    margin-top: 20px;
                    margin-bottom: 10px;
                    font-size: 14px;
                    color: $greyTextColor;
                    transition: .5s;
                }

                .btn {
                    margin-top: 50px;
                }
            }
        }
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
        margin-left: 30px;

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
            width: 60%;

            h1 {
                margin-left: -2px;
                margin-top: 40px;
                font-size: 23px;
                line-height: 0;
                color: $whiteColor;
            }

            h2 {
                color: $whiteColor;
                margin-top: 50px;
                font-size: 38px;
                text-align: center;
            }

            .close {
                position: absolute;
                top: 5px;
                right: 15px;
                cursor: pointer;
                font-size: 55px;
            }

            .modal-content {
                padding: 50px 0px;
                width: 90%;

                .modal-form {
                    display: flex;
                    flex-direction: column;

                    .input__name, .input__description {
                        font-size: 16px;
                        color: $greyTextColor;
                        border: 1px solid $greyTextColor;
                        border-radius: 5px;
                        outline: none;
                        background: linear-gradient(to right bottom, $mainColor4 30%, $mainColor1);
                        padding: 15px;
                        min-width: 80%;
                    }

                    .input__description {
                        max-width: 100%;
                        max-height: 200px;
                        min-height: 50px;
                    }

                    label {
                        margin-top: 20px;
                        margin-bottom: 10px;
                        font-size: 16px;
                        color: $greyTextColor;
                        transition: .5s;
                    }

                    .btn {
                        margin-top: 50px;
                        font-size: 16px;
                    }
                }
            }
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

            h1 {
                margin-left: -2px;
                margin-top: 40px;
                font-size: 23px;
                line-height: 0;
                color: $whiteColor;
            }

            h2 {
                color: $whiteColor;
                margin-top: 50px;
                font-size: 38px;
                text-align: center;
            }

            .close {
                position: absolute;
                top: 5px;
                right: 15px;
                cursor: pointer;
                font-size: 55px;
            }

            .modal-content {
                padding: 50px 0px;
                width: 90%;

                .modal-form {
                    display: flex;
                    flex-direction: column;

                    .input__name, .input__description {
                        font-size: 16px;
                        color: $greyTextColor;
                        border: 1px solid $greyTextColor;
                        border-radius: 5px;
                        outline: none;
                        background: linear-gradient(to right bottom, $mainColor4 30%, $mainColor1);
                        padding: 15px;
                        min-width: 80%;
                    }

                    .input__description {
                        max-width: 100%;
                        max-height: 200px;
                        min-height: 50px;
                    }

                    label {
                        margin-top: 20px;
                        margin-bottom: 10px;
                        font-size: 16px;
                        color: $greyTextColor;
                        transition: .5s;
                    }

                    .btn {
                        margin-top: 50px;
                        font-size: 16px;
                    }
                }
            }
        }
    }
}

</style>
