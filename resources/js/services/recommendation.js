import axios from 'axios';

axios.defaults.headers.common = {
    "Content-Type": "application/json;charset=utf-8",
};

export const getRecommendationMovies = async (currentPage, selectedGenres, selectedYears, selectedCountries, ratingFrom, ratingTo, searchQuery, ageConfirm) => {
    const response = await axios.get('/api/movies/recommendations?currentPage=' + currentPage + '&selectedGenres=' + selectedGenres + '&selectedYears=' + selectedYears + '&selectedCountries=' + selectedCountries + '&ratingFrom=' + ratingFrom + '&ratingTo=' + ratingTo + '&searchQuery=' + searchQuery + '&ageConfirm=' + ageConfirm);

    return response.data;
}

export const addRating = async (userID, movieID, rating) => {
    const response = await axios.get('/api/movies/recommendations/addRating?userID=' + userID + '&movieID=' + movieID + '&rating=' + rating);
    console.log(response.data);
    return response.data;
}

export const getRating = async (id) => {
    const response = await axios.get('/api/movies/recommendations/getRating?id=' + id);
    return response.data;
}

export const getMoviesData = async (id) => {
    const response = await axios.get('/api/movies/recommendations/getMoviesData');
    console.log(response);
    return response.data;
}
