<template>
    <section class="section-container">
        <div class="breadcrumbs">
            <span>Подборки</span>
        </div>

        <h1>Привет, {{ getUser.nickname }}</h1>

        <h2>Твои подборки</h2>

        <div class="collections-container">
            <div class="collection-container" @click="openModal">
                <div class="collections-image add-collection" >
                    <div class="ellipse-container">
                        <img src="../assets/images/ellipse.svg" />
                        <div class="plus-container">
                            <img src="../assets/images/plus.svg"/>
                        </div>
                    </div>
                </div>

                <div class="span-container">
                    <span class="collection-name">Создать новую</span>
                </div>
            </div>

            <div v-for="collectionData in collections" :key="collectionData.collection.id" @click="navigateToCollection($event, collectionData.collection.id)">
                <div class="collection-container">
                    <div class="collections-image" :style="{ 'background-image': collectionData.movie.length > 0 ? 'url(' + collectionData.movie[0].url + ')' : 'none', 'background-color': collectionData.movie.length === 0 ? $purple : 'transparent' }">
                    </div>

                    <div class="span-container">
                        <span class="collection-name">{{ collectionData.collection.name }}</span>
                        <button class="delete-button" @click.stop="deleteCollection(collectionData.collection.id)">Удалить</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Включение компонента модального окна -->
        <v-modal-collection :value="showModal" @close="handleModalClose" @submit="handleModalSubmit"></v-modal-collection>
    </section>
</template>

<script>
import VModalCollection from "../components/v-modalCollection.vue";

import {addCollection, deleteCollectionFromCollections, getCollections} from "../services/collections.js"
import {mapGetters, mapMutations} from 'vuex';

export default {
    data() {
        return {
            showModal: false,

            collectionName: '',
            collectionDescription: '',

            collections: [],
        }
    },

    methods: {
        openModal() {
            this.showModal = true;
        },

        handleModalClose(value) {
            this.showModal = value;
        },

        async handleModalSubmit(data) {
            this.collectionName = data.name;
            this.collectionDescription = data.description;

            const response = addCollection(this.$route.query.userID, this.collectionName, this.collectionDescription);
            const fullResponse = response.then(result => {
                if (result.status) {
                    alert(result.message);
                }
            });

            this.collections = await this.getCollection();

            this.$nextTick(() => {
                this.animateCollections();
            });

            return fullResponse;
        },

        async getCollection() {
            const response = await getCollections(this.$route.query.userID);
            console.log(response.data);
            return response.data;
        },

        animateCollections() {
            const collections = document.querySelectorAll('.collection-container');
            collections.forEach((collection, index) => {
                setTimeout(() => {
                    collection.classList.add('animate');
                }, index * 200);
            });
        },

        async deleteCollection(collectionId) {
            const response = await deleteCollectionFromCollections(this.$route.query.userID, collectionId);
            console.log(response);

            this.collections = await this.getCollection();

            this.$nextTick(() => {
                this.animateCollections();
            });

            return response.data;
        },

        navigateToCollection(event, collectionId) {
            if (!event.target.closest('.span-container')) {
                this.$router.push({ name: 'Collection', query: { userID: this.$route.query.userID, id: collectionId } });
            }
        },

        async updateMetaTags() {
            document.title = 'Ваши подборки фильмов| Moviq';
            const description = 'На этой странице вы можете управлять своими подборками фильмов: добавлять новые и редактировать существующие.'

            const metaDescription = document.querySelector('meta[name="description"]');
            if (metaDescription) {
                    metaDescription.setAttribute('content', description);
            } else {
                const newMetaDescription = document.createElement('meta');
                newMetaDescription.name = 'description';
                newMetaDescription.content = description;
                document.head.appendChild(newMetaDescription);
            }
        }
    },

    computed: {
        getUser() {
            console.log(this.$store.getters.getUser);
            return this.$store.getters.getUser;
        },
    },

    async created() {
        console.log(this.$route.query.userID);

        this.collections = await this.getCollection();

        this.$nextTick(() => {
            this.animateCollections();
        });

        await this.updateMetaTags();
    },

    components: {
        VModalCollection,
    }
}
</script>

<style scoped lang="scss">
@import "@/assets/styles/variables.scss";

.section-container {
    margin-left: 50px;
    position: relative;
    z-index: 2;

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

    .collections-container {
        display: flex;
        flex-wrap: wrap;
        gap: 40px;

        .collection-container {
            background-color: #052731;
            border-radius: 5px;
            width: 395px;
            height: 240px;
            cursor: pointer;

            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 30px;

            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out, background-color 0.3s;

            &.animate {
                opacity: 1;
                transform: translateY(0);
            }

            .collections-image {
                width: 330px;
                height: 170px;
                background-color: $purple;

                background-size: cover;
                background-position: center;

                display: flex;
                justify-content: center;
                align-items: center;

                .ellipse-container {
                    position: relative;
                    display: flex;
                    justify-content: center;
                    align-items: center;

                    img {
                        width: 50px;
                    }

                    .plus-container {
                        margin-top: 5px;
                        position: absolute;

                        img {
                            width: 20px;
                        }
                    }
                }
            }

            .span-container {
                width: 100%;
                margin-top: 20px;
                margin-left: 70px;

                span {
                    font-weight: $MediumWeight;
                    font-size: 16px;
                    color: $greyTextColor;
                }

                .delete-button {
                    margin-top: 20px;
                    display: none;
                    background-color: #b8b9e0;
                    color: #0a0a0a;
                    border: none;
                    width: 50%;
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

            &:hover {
                transform: scale(1.1);
                background-color: $mainColor4;

                .delete-button {
                    display: block;
                    margin-bottom: 20px;
                }
            }
        }
    }
}

@media screen and (min-width: 767px) and (max-width: 1023px) {
    .section-container {
        margin-top: -200px;
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

        .collections-container {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;

            .collection-container {
                background-color: #052731;
                border-radius: 5px;
                width: 300px;
                height: 200px;
                cursor: pointer;

                display: flex;
                flex-direction: column;
                align-items: center;
                padding-top: 30px;

                opacity: 0;
                transform: translateY(-20px);
                transition: opacity 0.5s ease-out, transform 0.5s ease-out, background-color 0.3s;

                &.animate {
                    opacity: 1;
                    transform: translateY(0);
                }

                .collections-image {
                    width: 260px;
                    height: 150px;
                    background-color: $purple;

                    background-size: cover;
                    background-position: center;

                    display: flex;
                    justify-content: center;
                    align-items: center;

                    .ellipse-container {
                        position: relative;
                        display: flex;
                        justify-content: center;
                        align-items: center;

                        img {
                            width: 50px;
                        }

                        .plus-container {
                            margin-top: 5px;
                            position: absolute;

                            img {
                                width: 20px;
                            }
                        }
                    }
                }

                .span-container {
                    width: 100%;
                    margin-top: 15px;
                    margin-left: 50px;

                    span {
                        font-weight: $MediumWeight;
                        font-size: 16px;
                        color: $greyTextColor;
                    }

                    .delete-button {
                        margin-top: 20px;
                        display: none;
                        background-color: #b8b9e0;
                        color: #0a0a0a;
                        border: none;
                        width: 50%;
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

                &:hover {
                    transform: scale(1.1);
                    background-color: $mainColor4;

                    .delete-button {
                        display: block;
                        margin-bottom: 20px;
                    }
                }
            }
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

        .collections-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;

            .collection-container {
                background-color: #052731;
                border-radius: 5px;
                width: 325px;
                height: 240px;
                cursor: pointer;

                display: flex;
                flex-direction: column;
                align-items: center;
                padding-top: 30px;

                opacity: 0;
                transform: translateY(-20px);
                transition: opacity 0.5s ease-out, transform 0.5s ease-out, background-color 0.3s;

                &.animate {
                    opacity: 1;
                    transform: translateY(0);
                }

                .collections-image {
                    width: 265px;
                    height: 170px;
                    background-color: $purple;

                    background-size: cover;
                    background-position: center;

                    display: flex;
                    justify-content: center;
                    align-items: center;

                    .ellipse-container {
                        position: relative;
                        display: flex;
                        justify-content: center;
                        align-items: center;

                        img {
                            width: 50px;
                        }

                        .plus-container {
                            margin-top: 5px;
                            position: absolute;

                            img {
                                width: 20px;
                            }
                        }
                    }
                }

                .span-container {
                    width: 100%;
                    margin-top: 20px;
                    margin-left: 70px;

                    span {
                        font-weight: $MediumWeight;
                        font-size: 16px;
                        color: $greyTextColor;
                    }

                    .delete-button {
                        margin-top: 20px;
                        display: none;
                        background-color: #b8b9e0;
                        color: #0a0a0a;
                        border: none;
                        width: 50%;
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

                &:hover {
                    transform: scale(1.1);
                    background-color: $mainColor4;

                    .delete-button {
                        display: block;
                        margin-bottom: 20px;
                    }
                }
            }
        }
    }
}


</style>
