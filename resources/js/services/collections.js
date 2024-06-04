import axios from "axios";

axios.defaults.headers.common = {
    "Content-Type": "application/json;charset=utf-8",
};

export const addCollection = async (userID, name, description) => {
    const response= await axios.get('api/movies/collections/addCollection?userID=' + userID + '&name=' + name + '&description=' + description);
    return response.data;
}

export const getCollections = async (userID) => {
    console.log(userID);
    const response= await axios.get('api/movies/collections/getCollections?userID=' + userID);
    console.log(response);
    return response.data;
}

export const getCollectionsMovie = async (userID, id) => {
    console.log(userID);
    const response= await axios.get('/api/movies/collections/getCollectionsMovie?userID=' + userID + '&id=' + id);
    console.log(response);
    return response.data;
}

export const searchMovies = async (search, page, selectedGenres, selectedYears, selectedCountries, ratingFrom, ratingTo) => {
    const response= await axios.get('/api/movies/collections/searchMovies?search=' + search + '&page=' + page + '&selectedGenres=' + selectedGenres + '&selectedYears=' + selectedYears + '&selectedCountries=' + selectedCountries + '&ratingFrom=' + ratingFrom + '&ratingTo=' + ratingTo);
    console.log(response);
    return response.data;
}

export const addMovieToCollection = async (userID, collectionID, movieID) => {
    const response = await axios.post("/api/movies/collections/addMovieToCollection", {userID, collectionID, movieID}, {
        headers: {
            "Content-Type": "application/json;charset=utf-8",
        }
    });
    console.log(response.data);
    return response.data;
}

export const deleteMovieFromCollection = async (userID, collectionID, movieID) => {
    const response = await axios.post("/api/movies/collections/deleteMovieFromCollection", {userID, collectionID, movieID}, {
        headers: {
            "Content-Type": "application/json;charset=utf-8",
        }
    });
    console.log(response.data);
    return response.data;
}

export const deleteCollectionFromCollections = async (userID, collectionID) => {
    const response = await axios.post("/api/movies/collections/deleteCollectionFromCollections", {userID, collectionID}, {
        headers: {
            "Content-Type": "application/json;charset=utf-8",
        }
    });
    console.log(response.data);
    return response.data;
}

/*export const searchMovies = async (search) => {
    const response = await axios.post("/api/movies/collections/searchMovies", {search}, {
        headers: {
            "Content-Type": "application/json;charset=utf-8",
        }
    });
    console.log(response);
    return response;
}*/
