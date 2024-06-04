import axios from 'axios';

axios.defaults.headers.common = {
    "Content-Type": "application/json;charset=utf-8",
};

export const getMoviesTypes = async () => {
    const response = await axios.get('/api/admin/getMoviesTypes');
    /*console.log(response.data.items);*/
    return response.data?.items;
}

export const getMoviesRestricts = async () => {
    const response = await axios.get('/api/admin/getMoviesRestricts');
    /*console.log(response.data.items);*/
    return response.data?.items;
}

export const getMoviesGenres = async () => {
    const response = await axios.get('/api/admin/getMoviesGenres');
    /*console.log(response.data.items);*/
    return response.data?.items;
}

export const getRoles = async () => {
    const response = await axios.get('/api/admin/getRoles');
    /*console.log(response.data.items);*/
    return response.data?.items;
}

export const getSearchMovie = async (
    searchKpID,
    searchNameMovie,
    searchAlternativeName,
    searchMovieSelectedTypeOption,
    searchYear,
    searchIsSeries,
    searchMovieLength,
    searchSeriesLength,
    searchMovieSelectedGenreOption,
    searchCountry,
    currentPage
) => {
    console.log(currentPage);
    const response = await axios.get(
        '/api/admin/getSearchMovie?kpID=' + searchKpID +
        '&nameMovie=' + searchNameMovie +
        '&alternativeName=' + searchAlternativeName +
        '&type=' + searchMovieSelectedTypeOption +
        '&year=' + searchYear +
        '&isSeries=' + searchIsSeries +
        '&movieLength=' + searchMovieLength +
        '&seriesLength=' + searchSeriesLength +
        '&genre=' + searchMovieSelectedGenreOption +
        '&country=' + searchCountry +
        '&currentPage=' + currentPage
    );
    console.log(response.data.data);
    return response.data.data;
}

export const addMovie = async (
    country,
    kpID,
    nameMovie,
    alternativeName,
    description,
    shortDescription,
    slogan,
    year,
    isSeries,
    kpRating,
    ratingMpaa,
    votesKp,
    budget,
    movieLength,
    seriesLength,
    backdrop,
    poster,
    yearRus,
    yearWorld,
    yearSerialStart,
    yearSerialEnd,
    trailer,
    movieSelectedTypeOption,
    movieSelectedRestrictOption,
    movieSelectedGenreOption
) => {
    const data = {
        country,
        kpID,
        nameMovie,
        alternativeName,
        description,
        shortDescription,
        slogan,
        year,
        isSeries,
        kpRating,
        ratingMpaa,
        votesKp,
        budget,
        movieLength,
        seriesLength,
        backdrop,
        poster,
        yearRus,
        yearWorld,
        yearSerialStart,
        yearSerialEnd,
        trailer,
        movieSelectedTypeOption,
        movieSelectedRestrictOption,
        movieSelectedGenreOption
    };
    const response = await axios.post('/api/admin/addMovie', data, {
        headers: {
            "Content-Type": "application/json;charset=utf-8",
        }
    });
    console.log(response);
    return response;
}

export const addCountry = async (newCountry) => {
    const data = {newCountry};
    const response = await axios.post('/api/admin/addCountry', data, {
        headers: {
            "Content-Type": "application/json;charset=utf-8",
        }
    });
    console.log(response);
    return response;
}

export const addGenre = async (newGenre) => {
    const data = {newGenre};
    const response = await axios.post('/api/admin/addGenre', data, {
        headers: {
            "Content-Type": "application/json;charset=utf-8",
        }
    });
    console.log(response);
    return response;
}

export const addRestrict = async (newRestrict) => {
    const data = {newRestrict};
    const response = await axios.post('/api/admin/addRestrict', data, {
        headers: {
            "Content-Type": "application/json;charset=utf-8",
        }
    });
    console.log(response);
    return response;
}

export const takeRole = async (email, role) => {
    const data = {email, role};
    const response = await axios.post('/api/admin/takeRole', data, {
        headers: {
            "Content-Type": "application/json;charset=utf-8",
        }
    });
    console.log(response);
    return response;
}

export const getSuggestions = async () => {
    const response = await axios.get('/api/admin/getSuggestions');
    console.log(response);
    return response.data?.items;
}

export const getCurrentSuggestion = async (userID, suggestedUserID, ticketID) => {
    const response = await axios.get('/api/admin/getCurrentSuggestion?userID=' + userID + '&suggestedUserID=' + suggestedUserID + '&ticketID=' + ticketID);
    console.log(response);
    return response.data?.items[0];
}

export const postAnswerForAddMovie = async (userID, ticketID, status) => {
    const data = {userID, ticketID, status};
    const response = await axios.post('/api/admin/postAnswerForAddMovie', data, {
        headers: {
            "Content-Type": "application/json;charset=utf-8",
        }
    });
    console.log(response);
    return response;
}
