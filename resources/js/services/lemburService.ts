import api from './api';
import { Lembur } from '../types/lembur';

const lemburService = {
  async getAll(params?: any): Promise<Lembur[]> {
    const response = await api.get('/lemburs', { params });
    return response.data.data;
  },

  async create(data: any): Promise<Lembur> {
    const response = await api.post('/lemburs', data);
    return response.data.data;
  },

  async getOne(id: number): Promise<Lembur> {
    const response = await api.get(`/lemburs/${id}`);
    return response.data.data;
  },
};

export default lemburService;
