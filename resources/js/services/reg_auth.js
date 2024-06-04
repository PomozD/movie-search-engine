import axios from 'axios';
import $ from 'jquery';

axios.defaults.headers.common = {
    "Content-Type": "application/json;charset=utf-8",
};

export const getUser = async (email, password) => {
    const response = await axios.get('/api/users?email=' + email + '&password=' + password );
    return response.data;
}

export const postUser = async (name, email, password) => {
    const data = {
        name,
        email,
        password,
    };
    const response = await axios.post("/api/users", data, {
        headers: {
            "Content-Type": "application/json;charset=utf-8",
        }
    });

    return response.data;
}

export const googleAuth = async () => {
    window.location.href= "http://localhost:8000/api/users/googleAuth";
}

export const googleCreateOrAuth = async (code) => {
    const response = await axios.get("/api/users/googleAuth/callback?code=" + code);
    console.log(response);
    return response.data;
}

