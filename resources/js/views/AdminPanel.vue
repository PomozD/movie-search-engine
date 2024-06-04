<template>
    <section class="section-container">

        <div v-if="state.user" class="container">
            <div class="wrapper">
                <!--Admin-->
                <div v-if="state.user.role === 'admin' || state.user.role === 'moderator'" class="container__profile">
                    <h1>Панель администратора</h1>

                    <!--Вывод всего-->
                    <div class="profile">
                        <div class="admin-container">
                            <details close class="admin-details">
                                <summary class="admin-title">
                                    <h2>Вывод фильмов</h2>
                                    <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                </summary>

                                <table>
                                    <tr class="title">
                                        <th class="thProducts">ID</th>
                                        <th class="thProducts">ID в api</th>
                                        <th class="thProducts">ID Кинопоиск</th>
                                        <th class="thProducts">Название</th>
                                        <th class="thProducts">Альт. название</th>
                                        <th class="thProducts">Описание</th>
                                        <th class="thProducts">Короткое описание</th>
                                        <th class="thProducts">Слоган</th>
                                        <th class="thProducts">Тип</th>
                                        <th class="thProducts">Возрастное ограничение</th>
                                        <th class="thProducts">Год</th>
                                        <th class="thProducts">Сериал/фильм</th>
                                        <th class="thProducts">Рейтинг на Кинопоиск</th>
                                        <th class="thProducts">Рейтинг MPAA</th>
                                        <th class="thProducts">Кол-во голосов на Кинопоиск</th>
                                        <th class="thProducts">Бюджет</th>
                                        <th class="thProducts">Длительность фильма</th>
                                        <th class="thProducts">Длительность сериала</th>
                                        <th class="thProducts">updatedAtDate</th>
                                        <th class="thProducts">createdAtDate</th>
                                        <th class="thProducts">Удаление</th>
                                        <th class="thProducts">Сохранить</th>
                                    </tr>
                                    <tr v-for="item in searchedMovie" :key="item">
                                        <td class="tdProducts"><p class="input__products">{{item['id']}}</p></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['apiID']" :placeholder="item['Name']" type="text"/></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['externalID']" pattern="\D [^0-9]" :placeholder="item['Size']" type="text"/></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['name']" pattern="\D [^0-9]" :placeholder="item['Sex']" type="text"/></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['alternativeName']" pattern="\D [^0-9]" :placeholder="item['Sex']" type="text"/></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['description']" pattern="\D [^0-9]" :placeholder="item['Type']" type="text"/></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['shortDescription']" min="0" pattern="^[ 0-9]+$" :placeholder="item['Price']" type="text"/></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['slogan']" min="0" pattern="^[ 0-9]+$" :placeholder="item['Count']" type="text"/></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['type_id']" :placeholder="item['Composition']"/></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['restrict_id']" :placeholder="item['Main_Photo']"/></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['year']" :placeholder="item['Secondary_Photo_first']"/></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['isSeries']" :placeholder="item['Secondary_Photo_second']"/></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['ratingKp']" :placeholder="item['Secondary_Photo_second']"/></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['ratingMpaa']" :placeholder="item['Secondary_Photo_second']"/></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['votesKp']" :placeholder="item['Secondary_Photo_second']"/></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['budget']" :placeholder="item['Secondary_Photo_second']"/></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['movieLength']" :placeholder="item['Secondary_Photo_second']"/></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['seriesLength']" :placeholder="item['Secondary_Photo_second']"/></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['updatedAtDate']" :placeholder="item['Secondary_Photo_second']"/></td>
                                        <td class="tdProducts"><input class="data-input" v-model.trim="item['createdAtDate']" :placeholder="item['Secondary_Photo_second']"/></td>
                                        <td class="tdProducts"><button @click="deleteProduct(item)" class="btn-delete">Delete</button></td>
                                        <td class="tdProducts"><button @click="saveEditProduct(item)" class="btn btn-primary">Save</button></td>
                                    </tr>
                                </table>
                                <div class="navigation">
                                    <button class="navigation-btn btn" @click="prevSearchMovies">Назад</button>
                                    <button class="navigation-btn btn" @click="nextSearchMovies">Дальше</button>
                                </div>
                            </details>
                        </div>
                        <hr>
                        <!--Проверка фильма-->
                        <div class="admin-container">
                            <details close class="admin-details">
                                <summary class="admin-title">
                                    <h2>Проверка фильма в базе данных</h2>
                                    <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                </summary>

                                <div class="data-container">
                                    <div class="title">Выберите данные</div>
                                    <div class="details-container">
                                        <details close class="details-data">
                                            <summary>
                                                <h2>ID на Кинопоиск</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="searchKpID" type="text" placeholder="Введите фильма ID на Кинопоиск" class="data-input"/>
                                        </details>
                                        <details close class="details-data">
                                            <summary>
                                                <h2>Название</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="searchNameMovie" type="text" placeholder="Введите название" class="data-input"/>
                                        </details>
                                        <details close class="details-data">
                                            <summary>
                                                <h2>Альтернативное название</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="searchAlternativeName" type="text" placeholder="Введите альтернативное название" class="data-input"/>
                                        </details>
                                        <details close class="details-data">
                                            <summary>
                                                <h2>Тип</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <div>
                                                <select v-model="searchMovieSelectedTypeOption" class="data-select">
                                                    <option v-for="option in movieTypeOptions" :key="option">
                                                        {{option['name']}}
                                                    </option>
                                                </select>
                                            </div>
                                        </details>
                                        <details close class="details-data">
                                            <summary>
                                                <h2>Год</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="searchYear" type="text" placeholder="Год" class="data-input"/>
                                        </details>

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Сериал(1) или фильм(0)</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="searchIsSeries" type="text" placeholder="Введите 1 или 0" class="data-input"/>
                                        </details>

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Длительность фильма</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="searchMovieLength" type="text" placeholder="Введите длительность фильма" class="data-input"/>
                                        </details>

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Длительность сериала</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="searchSeriesLength" type="text" placeholder="Введите длительность сериала" class="data-input"/>
                                        </details>

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Страна производства</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>

                                            <input v-model.trim="searchCountry" placeholder="Введите страну производства" type="text" class="data-input"/>

                                            <!--                        <div>
                                                                        <VAdminInput v-model="country" @update:country="updateCountry" placeholder="Введите страну производства" />
                                                                    </div>-->

                                            <!--                        <div v-for="(country, index) in countries.slice(1)" :key="index">
                                                                        <VAdminInput v-model.trim="countries[index]" :placeholder="'Страна ' + (index + 1)" @remove="removeCountry(index)" />
                                                                    </div>
                                                                    <button @click="addCountry">Добавить страну</button>

                                                                    <div v-for="country in countries" :key="country">
                                                                        <p>{{country}}</p>
                                                                    </div>-->
                                        </details>

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Жанр</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <select v-model="searchMovieSelectedGenreOption" class="data-select">
                                                <option v-for="option in movieGenreOptions" :key="option">
                                                    {{option['name']}}
                                                </option>
                                            </select>
                                        </details>

                                        <!--                    <details close>
                                                                <summary>
                                                                    <h2 style="font-family: 'DIN PRO Medium'">Участники фильма</h2>
                                                                </summary>
                                                                <input v-model.trim="newCategory" type="text" placeholder="Введите ФИО участника фильма" class="input__editInfo"/>
                                                                <input v-model.trim="newCategory" type="text" placeholder="Введите имя участника в фильме" class="input__editInfo"/>
                                                                <input v-model.trim="newCategory" type="text" placeholder="Введите имя участника фильма на английском" class="input__editInfo"/>
                                                                <input v-model.trim="newCategory" type="text" placeholder="Введите должность участника на английском" class="input__editInfo"/>
                                                                <input v-model.trim="newCategory" type="text" placeholder="Введите должность участника на русском" class="input__editInfo"/>
                                                                <input v-model.trim="newCategory" type="text" placeholder="Введите ссылку на фотографию" class="input__editInfo"/>
                                                            </details>-->

                                        <div class="buttons">
                                            <button class="btn" @click="searchMovieFunction" >Проверить</button>
                                        </div>
                                    </div>
                                </div>
                            </details>
                        </div>

                        <hr>
                        <!--Добавление фильма-->
                        <div class="admin-container">
                            <details close class="admin-details">
                                <summary class="admin-title">
                                    <h2>Добавление нового фильма</h2>
                                    <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                </summary>
                                <div class="data-container">
                                    <div class="title">Выберите данные</div>
                                    <div class="details-container">
                                        <details close class="details-data">
                                            <summary>
                                                <h2>ID на Кинопоиск</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="kpID" type="text" placeholder="Введите фильма ID на Кинопоиск" class="data-input"/>
                                        </details>
                                        <details close class="details-data">
                                            <summary>
                                                <h2>Название</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="nameMovie" type="text" placeholder="Введите название" class="data-input"/>
                                        </details>
                                        <details close class="details-data">
                                            <summary>
                                                <h2>Альтернативное название</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="alternativeName" type="text" placeholder="Введите альтернативное название" class="data-input"/>
                                        </details>
                                        <details close class="details-data">
                                            <summary>
                                                <h2>Описание</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="description" type="text" placeholder="Введите описание" class="data-input"/>
                                        </details>
                                        <details close class="details-data">
                                            <summary>
                                                <h2>Короткое описание</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="shortDescription" type="text" placeholder="Введите короткое описание" class="data-input"/>
                                        </details>
                                        <details close class="details-data">
                                            <summary>
                                                <h2>Слоган</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="slogan" type="text" placeholder="Введите слоган" class="data-input"/>
                                        </details>
                                        <details close class="details-data">
                                            <summary>
                                                <h2>Тип</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <div>
                                                <select v-model="movieSelectedTypeOption" class="data-select">
                                                    <option v-for="option in movieTypeOptions" :key="option">
                                                        {{option['name']}}
                                                    </option>
                                                </select>
                                            </div>
                                        </details>
                                        <details close class="details-data">
                                            <summary>
                                                <h2>Возрастное ограничение</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <select v-model="movieSelectedRestrictOption" class="data-select">
                                                <option v-for="option in movieRestrictOptions" :key="option">
                                                    {{option['restriction']}}
                                                </option>
                                            </select>
                                        </details>
                                        <details close class="details-data">
                                            <summary>
                                                <h2>Год</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="year" type="text" placeholder="Год" class="data-input"/>
                                        </details>

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Сериал(1) или фильм(0)</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="isSeries" type="text" placeholder="Введите 1 или 0" class="data-input"/>
                                        </details>

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Рейтинг на Кинопоиск</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="kpRating" type="text" placeholder="Введите рейтинг на Кинопоиск" class="data-input"/>
                                        </details>

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Рейтинг MPAA</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="ratingMpaa" type="text" placeholder="Введите рейтинг MPAA" class="data-input"/>
                                        </details>

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Количество голосов на Кинопоиск</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="votesKp" type="text" placeholder="Введите количество голосов на Кинопоиск" class="data-input"/>
                                        </details>

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Бюджет</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="budget" type="text" placeholder="Введите бюджет" class="data-input"/>
                                        </details>

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Длительность фильма</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="movieLength" type="text" placeholder="Введите длительность фильма" class="data-input"/>
                                        </details>

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Длительность сериала</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="seriesLength" type="text" placeholder="Введите длительность сериала" class="data-input"/>
                                        </details>

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Ссылка на фоновое изображение</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="backdrop" type="text" placeholder="Введите ссылку на фоновое изображение" class="data-input"/>
                                        </details>

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Страна производства</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>

                                            <input v-model.trim="country" placeholder="Введите страну производства" type="text" class="data-input"/>

                                            <!--                        <div>
                                                                        <VAdminInput v-model="country" @update:country="updateCountry" placeholder="Введите страну производства" />
                                                                    </div>-->

                                            <!--                        <div v-for="(country, index) in countries.slice(1)" :key="index">
                                                                        <VAdminInput v-model.trim="countries[index]" :placeholder="'Страна ' + (index + 1)" @remove="removeCountry(index)" />
                                                                    </div>
                                                                    <button @click="addCountry">Добавить страну</button>

                                                                    <div v-for="country in countries" :key="country">
                                                                        <p>{{country}}</p>
                                                                    </div>-->
                                        </details>

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Жанр</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <select v-model="movieSelectedGenreOption" class="data-select">
                                                <option v-for="option in movieGenreOptions" :key="option">
                                                    {{option['name']}}
                                                </option>
                                            </select>
                                        </details>

                                        <!--                    <details close>
                                                                <summary>
                                                                    <h2 style="font-family: 'DIN PRO Medium'">Участники фильма</h2>
                                                                </summary>
                                                                <input v-model.trim="newCategory" type="text" placeholder="Введите ФИО участника фильма" class="input__editInfo"/>
                                                                <input v-model.trim="newCategory" type="text" placeholder="Введите имя участника в фильме" class="input__editInfo"/>
                                                                <input v-model.trim="newCategory" type="text" placeholder="Введите имя участника фильма на английском" class="input__editInfo"/>
                                                                <input v-model.trim="newCategory" type="text" placeholder="Введите должность участника на английском" class="input__editInfo"/>
                                                                <input v-model.trim="newCategory" type="text" placeholder="Введите должность участника на русском" class="input__editInfo"/>
                                                                <input v-model.trim="newCategory" type="text" placeholder="Введите ссылку на фотографию" class="input__editInfo"/>
                                                            </details>-->

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Постер</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="poster" type="text" placeholder="Введите ссылку на постер фильма" class="data-input"/>
                                        </details>

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Премьера</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="yearRus" type="text" placeholder="Введите дату премьеры фильма в России" class="data-input"/>
                                            <input v-model.trim="yearWorld" type="text" placeholder="Введите дату премьеры фильма в мире" class="data-input"/>
                                        </details>

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Год начала/конца выпуска сериала</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="yearSerialStart" type="text" placeholder="Введите год начала выпуска сериала" class="data-input"/>
                                            <input v-model.trim="yearSerialEnd" type="text" placeholder="Введите год конца выпуска сериала" class="data-input"/>
                                        </details>

                                        <details close class="details-data">
                                            <summary>
                                                <h2>Трейлер</h2>
                                                <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                            </summary>
                                            <input v-model.trim="trailer" type="text" placeholder="Введите ссылку на трейлер фильма" class="data-input"/>
                                        </details>

                                        <div class="buttons">
                                            <button class="btn" @click="saveAddMovie">Добавить</button>
                                        </div>
                                    </div>
                                </div>
                            </details>
                        </div>

                        <hr>
                        <div class="admin-container">
                            <details close class="admin-details">
                                <summary class="admin-title">
                                    <h2>Добавление стран</h2>
                                    <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                </summary>
                                <input v-model.trim="newCountry" type="text" placeholder="Введите название страны" class="data-input"/>
                                <div class="buttons">
                                    <button class="btn" @click="saveAddCountry">Добавить</button>
                                </div>
                            </details>
                        </div>

                        <hr>
                        <div class="admin-container">
                            <details close class="admin-details">
                                <summary class="admin-title">
                                    <h2>Добавление жанров</h2>
                                    <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                </summary>
                                <input v-model.trim="newGenre" type="text" placeholder="Введите название жанра" class="data-input"/>
                                <div class="buttons">
                                    <button class="btn" @click="saveAddGenre">Добавить</button>
                                </div>
                            </details>
                        </div>

                        <hr>
                        <div class="admin-container">
                            <details close class="admin-details">
                                <summary class="admin-title">
                                    <h2>Добавление возрастного ограничения</h2>
                                    <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                </summary>
                                <input v-model.trim="newRestrict" type="text" placeholder="Введите возрастное ограничение" class="data-input"/>
                                <div class="buttons">
                                    <button class="btn" @click="saveAddRestrict">Добавить</button>
                                </div>
                            </details>
                        </div>

                        <hr>
                        <!--Выдача прав-->
                        <div class="admin-container" v-if="state.user.role === 'admin'">
                            <details close class="admin-details">
                                <summary class="admin-title">
                                    <h2>Выдача прав пользователям</h2>
                                    <img src="../assets/images/arrowdown.svg" class="arrowdown"/>
                                </summary>
                                <input v-model.trim="userEmail" type="text" placeholder="Введите email пользователя" class="data-input"/>
                                <div>
                                    <select v-model="selectedRoleOption" class="data-select">
                                        <option v-for="option in roleOptions" :key="option">
                                            {{option['name']}}
                                        </option>
                                    </select>
                                </div>
                                <div class="buttons">
                                    <button class="btn" @click="takeRoleFunction">Выдать</button>
                                </div>
                            </details>
                        </div>

                        <div class="btn-suggested" @click="navigateToSuggested">
                            Предложенные фильмы
                        </div>
                    </div>
                </div>

                <div v-if="state.user.role === 'user'" class="container__profile">
                    <h2>Авторизуйтесь для входа в панель администратора</h2>
                </div>
            </div>
        </div>
        <p v-else>Авторизируйтесь для доступа</p>
    </section>
</template>

<script>
/*import VFooter from "../components/v-footer.vue";*/
import VAdminInput from "../components/adminInput.vue"

import { getUserInfo, editUserData, suggestMovie } from "../services/profile.js";

import {
    getMoviesTypes,
    getMoviesRestricts,
    getMoviesGenres,
    getSearchMovie,
    addMovie,
    addCountry,
    addGenre,
    addRestrict,
    getRoles,
    takeRole,
} from "../services/adminFunctions.js";

import {mapGetters, mapMutations} from 'vuex';

import useVuelidate from '@vuelidate/core'
import { required, email, minLength } from '@vuelidate/validators'
import { reactive, computed } from 'vue'

export default {
    name: "ProfileView",

    data() {
        return {
            edited: '',
            reg: /\w+/,

            isAddMovie: false,

            searchKpID: '',
            searchNameMovie: '',
            searchAlternativeName: '',
            searchMovieSelectedTypeOption: '',
            searchYear: '',
            searchIsSeries: '',
            searchMovieLength: '',
            searchSeriesLength: '',
            searchMovieSelectedGenreOption: '',
            searchCountry: '',

            searchedMovie: [],
            currentPage: 1,

            movieSelectedTypeOption: '',
            movieTypeOptions: null,

            movieSelectedRestrictOption: '',
            movieRestrictOptions: null,

            movieSelectedGenreOption: '',
            movieGenreOptions: null,

            country: '',
            kpID: '',
            nameMovie: '',
            alternativeName: '',
            description: '',
            shortDescription: '',
            slogan: '',
            year: '',
            isSeries: '',
            kpRating: '',
            ratingMpaa: '',
            votesKp: '',
            budget: '',
            movieLength: '',
            seriesLength: '',
            backdrop: '',
            poster: '',
            yearRus: '',
            yearWorld: '',
            yearSerialStart: '',
            yearSerialEnd: '',
            trailer: '',

            newCountry: '',
            newGenre: '',
            newRestrict: '',

            userEmail: '',
            selectedRoleOption: '',
            roleOptions: null,
        }
    },

    setup() {
        const state = reactive({
            user: [],
            edit: false,

            newName: null,
            newEmail: null,
            newPassword: null,

            cart: [],

        })

        const rules = computed(() => {
            return {
                newName: { required },
                newEmail: { required, email },
                newPassword: { required, minLength: minLength(6) },
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
            console.log(this.$route.query.userID);
            const response = await getUserInfo(this.$route.query.userID);
            return response.userInfo;
        },

        editInfo() {
            this.state.edit = true;
        },

        async editData() {
            this.v$.$validate()
            if(!this.v$.$error){
                const response = await editUserData(this.$route.query.userID ,this.state.newName, this.state.newEmail, this.state.newPassword, this.state.user.email);
                this.state.edit = false;
                this.edited = response.message;
                // return newresponse.userInfo;
            }
            else {
                alert('Проверьте заполнение полей');
            }
        },

        logout() {
            this.$router.push({path: '/'});
            this.$store.state.user = null;
        },

        backEdit() {
            this.state.edit = false;
        },

        async getMovieTypes() {
            const response = await getMoviesTypes();
            /*console.log(response);*/
            return response;
        },

        async getMovieRestricts() {
            const response = await getMoviesRestricts();
            /*console.log(response);*/
            return response;
        },

        async getMovieGenres() {
            const response = await getMoviesGenres();
            /*console.log(response);*/
            return response;
        },

        addCountry() {
            this.countries.push('');
        },

        removeCountry(index) {
            this.countries.splice(index, 1);
        },

        updateCountry(value) {
            this.country = value;
        },

        navigateToSuggested() {
            this.$router.push({ name: 'SuggestedMovies', query: { userID: this.$route.query.userID } });
        },

        async searchMovieFunction() {
            this.currentPage = 1;
            const response = await getSearchMovie(
                this.searchKpID,
                this.searchNameMovie,
                this.searchAlternativeName,
                this.searchMovieSelectedTypeOption,
                this.searchYear,
                this.searchIsSeries,
                this.searchMovieLength,
                this.searchSeriesLength,
                this.searchMovieSelectedGenreOption,
                this.searchCountry,
                this.currentPage,
            );

            console.log(response.data);

            this.searchedMovie = response.data;
            return response.data;
        },

        async nextSearchMovies() {
            try {
                const nextPage = this.currentPage + 1;
                const response = await getSearchMovie(
                    this.searchKpID,
                    this.searchNameMovie,
                    this.searchAlternativeName,
                    this.searchMovieSelectedTypeOption,
                    this.searchYear,
                    this.searchIsSeries,
                    this.searchMovieLength,
                    this.searchSeriesLength,
                    this.searchMovieSelectedGenreOption,
                    this.searchCountry,
                    nextPage
                );

                this.searchedMovie = response.data;
                this.currentPage = nextPage;
            } catch (error) {
                console.error('Ошибка при загрузке следующих фильмов:', error);
            }
        },

        async prevSearchMovies() {
            try {
                const prevPage = this.currentPage - 1;
                const response = await getSearchMovie(
                    this.searchKpID,
                    this.searchNameMovie,
                    this.searchAlternativeName,
                    this.searchMovieSelectedTypeOption,
                    this.searchYear,
                    this.searchIsSeries,
                    this.searchMovieLength,
                    this.searchSeriesLength,
                    this.searchMovieSelectedGenreOption,
                    this.searchCountry,
                    prevPage
                );

                this.searchedMovie = response.data;
                this.currentPage = prevPage;
            } catch (error) {
                console.error('Ошибка при загрузке следующих фильмов:', error);
            }
        },

        async saveAddMovie() {
            const response = await addMovie(
                this.country,
                this.kpID,
                this.nameMovie,
                this.alternativeName,
                this.description,
                this.shortDescription,
                this.slogan,
                this.year,
                this.isSeries,
                this.kpRating,
                this.ratingMpaa,
                this.votesKp,
                this.budget,
                this.movieLength,
                this.seriesLength,
                this.backdrop,
                this.poster,
                this.yearRus,
                this.yearWorld,
                this.yearSerialStart,
                this.yearSerialEnd,
                this.trailer,
                this.movieSelectedTypeOption,
                this.movieSelectedRestrictOption,
                this.movieSelectedGenreOption,
            );

            if(response.data.type === 0) {
                this.isAddMovie = true;
                alert('Фильм добавлен')
            } else {
                console.log(response.data.type);
                alert('Фильм не добавлен');
            }
        },

        async saveAddCountry() {
            const response = await addCountry(this.newCountry);

            if(response.data.type === 0) {
                alert('Страна добавлена')
            } else {
                console.log(response.data.message);
                alert(response.data.message);
            }
        },

        async saveAddGenre() {
            const response = await addGenre(this.newGenre);

            if(response.data.type === 0) {
                alert('Жанр добавлен')
            } else {
                console.log(response.data.message);
                alert(response.data.message);
            }
        },

        async saveAddRestrict() {
            const response = await addRestrict(this.newRestrict);

            if(response.data.type === 0) {
                alert('Ограничение добавлено')
            } else {
                console.log(response.data.message);
                alert(response.data.message);
            }
        },

        async getRolesFunction() {
            const response = await getRoles();
            console.log(response);
            return response;
        },


        async takeRoleFunction() {
            const response = await takeRole(this.userEmail, this.selectedRoleOption);

            if(response.data.type === 0) {
                alert('Права доступа выданы')
            } else {
                console.log(response.data.message);
                alert(response.data.message);
            }
        },

        async suggestedMoviesFunc() {
            this.$router.push({
                name: 'SuggestedMovies',
                query: { userID: this.$route.query.userID }
            });
        }
    },

    async created(){
        this.state.user = await this.getInfo();

        /*const userId = this.$route.query.userID;
        if (userId) {
            this.$store.commit('updateUser', { userID: userId });
        }*/

        this.movieTypeOptions = await this.getMovieTypes();
        /*console.log(this.movieTypeOptions);*/

        this.roleOptions = await this.getRolesFunction();
        /*console.log(this.roleOptions);*/

        this.movieRestrictOptions = await this.getMovieRestricts();
        /*console.log(this.movieRestrictOptions);*/

        this.movieGenreOptions = await this.getMovieGenres();
        /*console.log(this.movieRestrictOptions);*/

    },

    computed: {
        getUser() {
            console.log(this.$store.getters.getUser);
            return this.$store.getters.getUser;
        },
    },

    watch: {
        "$route.params": {
            handler: async function () {
                /*this.sizeOptions = await this.getSizes();
                this.sexOptions = await this.getSexs();
                this.categoryOptions = await this.getCategories();*/

                this.movieTypeOptions = await this.getMovieTypes();

                this.roleOptions = await this.getRolesFunction();

                this.movieRestrictOptions = await this.getMovieRestricts();

                this.movieGenreOptions = await this.getMovieGenres();
            },
            immediate: true
        },

        /*countries: {
            handler: function (newCountries, oldCountries) {
                console.log(newCountries); // Выводим новые значения стран в консоль
            },
            deep: true // Наблюдаем за изменениями вложенных свойств массива
        },*/

        async edited(value) {
            if(value !== 'Пользователь с таким Email уже зарегистрирован') {
                const newresponse = await getUserInfo(this.$route.query.userID);
                this.updateUser(newresponse.userInfo);
                this.state.user = newresponse.userInfo;
                alert('Данные изменены');
            }
            else {
                alert("Пользователь с таким Email уже зарегистрирован");
            }
        },
    },

    components: {
        /*VFooter*/
        VAdminInput,
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
                    margin-top: 50px;

                    .admin-container {
                        .admin-details {
                            margin-bottom: 15px;

                            table {
                                width: 100%;
                                table-layout: fixed;
                                border-collapse: collapse;
                                background-color: $extraColor;

                                tr {
                                    color: $greyTextColor;

                                    th {
                                        background-color: #052731;
                                        font-size: 10px;
                                        word-wrap: break-word;
                                        white-space: normal;
                                    }

                                    td {
                                        background-color: transparent;
                                        padding: 5px;
                                        font-size: 12px;

                                        .data-input {
                                            font-size: 12px;
                                            color: $greyTextColor;
                                            border: 1px solid $greyTextColor;
                                            border-radius: 5px;
                                            outline: none;
                                            background: transparent;
                                            min-width: 30%;
                                            max-width: 100%;
                                            line-height: 0;
                                            padding: 5px;
                                        }

                                        .data-select {
                                            margin-top: 10px;
                                            color: $greyTextColor;
                                            background: transparent;
                                            padding: 15px 15px;
                                            border-radius: 5px;
                                            border: 1px solid $greyTextColor;
                                        }

                                        .btn-delete {
                                            background-color: #b8b9e0;
                                            color: #0a0a0a;
                                            border: none;
                                            text-align: center;
                                            border-radius: 4px;
                                            cursor: pointer;
                                            transition: .4s;
                                            font-size: 12px;
                                            font-weight: $MediumWeight;

                                            &:hover {
                                                background-color: #d6d8ec;
                                            }
                                        }

                                        .btn-primary {
                                            padding: 2px 0;
                                            width: 100%;
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

                            .navigation {
                                margin: 30px 0;
                                display: flex;
                                gap: 30px;

                                .navigation-btn {
                                    width: 10%;
                                    padding: 10px;
                                }
                            }

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

                                    .details-data {
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

                                    .details-data[open] {
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
                        }

                        .admin-details[open]{

                            .admin-title {
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
