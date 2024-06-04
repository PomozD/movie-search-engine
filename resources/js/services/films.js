import axios from "axios";

axios.defaults.headers.common = {
    "Content-Type": "application/json;charset=utf-8",
};

export const getMovie = async (id) => {
    console.log(id);
    const response = await axios.get('/api/movies/movie/getMovie?id=' + id);
    console.log(response);
    return response.data;
}

export const addRating = async (userID, movieID, rating) => {
    const response = await axios.get('/api/movies/recommendations/addRating?userID=' + userID + '&movieID=' + movieID + '&rating=' + rating);
    console.log(response.data);
    return response.data;
}

export const getCollections = async (userID) => {
    const response= await axios.get('/api/movies/collections/getCollections?userID=' + userID);
    console.log(response);
    return response.data;
}

export const addMovieToCollection = async (userID, movieID, collectionID) => {
    const response = await axios.post("/api/movies/collections/addMovieToCollection", {userID, movieID, collectionID}, {
        headers: {
            "Content-Type": "application/json;charset=utf-8",
        }
    });
    console.log(response.data);
    return response.data;
}
