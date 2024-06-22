import axios from 'axios';

axios.defaults.headers.common = {
    "Content-Type": "application/json;charset=utf-8",
};

export const getUserInfo = async (id) => {
    const response = await axios.get('/api/users/profile?id=' + id);
    return response.data[0];
}

export const editUserData = async (userID, name, email, password, old_email) => {
    const data = {
        userID,
        name,
        email,
        password,
        old_email,
    };
    const response = await axios.post("/api/users/profile/edit", data, {
        headers: {
            "Content-Type": "application/json;charset=utf-8",
        }
    });
    console.log(response.data);
    return response.data;
}

export const suggestMovie = async (userMovieSelectedTypeOption,
                                   userIsSeries,
                                   userYear,
                                   userAlternativeName,
                                   userBudget,
                                   userDescription,
                                   userMovieLength,
                                   userMovieSelectedRestrictOption,
                                   userNameMovie,
                                   userRatingMpaa,
                                   userShortDescription,
                                   userSeriesLength,
                                   userSlogan,
                                   userID) => {
    const data = {
        userMovieSelectedTypeOption,
        userMovieSelectedRestrictOption,
        userIsSeries,
        userYear,
        userAlternativeName,
        userBudget,
        userDescription,
        userMovieLength,
        userNameMovie,
        userRatingMpaa,
        userShortDescription,
        userSeriesLength,
        userSlogan,
        userID
    };
    const response = await axios.post("/api/users/profile/suggestMovie", data, {
        headers: {
            "Content-Type": "application/json;charset=utf-8",
        }
    });
    console.log(response.data);
    return response.data;
}

export const getSuggestions = async (id) => {
    const response = await axios.get('/api/users/profile/getSuggestions?id=' + id);
    console.log(response);
    return response.data[0]?.items;
}

export const deleteSuggestion = async (id, tickedID) => {
    const response = await axios.get('/api/users/profile/deleteSuggestion?id=' + id + '&tickedID=' + tickedID);
    console.log(response.data);
    return response.data;
}
