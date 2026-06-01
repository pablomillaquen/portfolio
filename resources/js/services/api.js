import axios from 'axios';

export const api = axios.create({
    headers: {
        Accept: 'application/json',
    },
});

export const today = (date) =>
    date ? new Date(date).toLocaleDateString() : '';
