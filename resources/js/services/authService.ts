import axios from 'axios';
import api from './api';
import { User } from '../types/auth';

const authService = {
  async getCsrfToken() {
    return await axios.get('/sanctum/csrf-cookie');
  },

  async login(credentials: any) {
    await this.getCsrfToken();
    return await api.post('/login', credentials);
  },

  async logout() {
    return await api.post('/logout');
  },

  async getUser(): Promise<User> {
    const response = await api.get('/user');
    return response.data;
  },
};

export default authService;
