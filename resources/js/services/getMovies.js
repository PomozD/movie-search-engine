import axios from "axios";

axios.defaults.headers.common = {
    "Content-Type": "application/json;charset=utf-8",
};

export const getAllMovie = async () => {

    const response= await axios.get('api/movies');
    /*return response.data?.item;*/

    console.log(response.data);
    return response.data;
}
