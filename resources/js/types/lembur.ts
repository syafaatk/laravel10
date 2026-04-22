import { User } from './auth';

export interface Lembur {
  id: number;
  user_id: number;
  tanggal: string;
  jenis: 'weekdays' | 'weekend';
  jam_mulai: string;
  jam_selesai: string;
  durasi_jam: number;
  keterangan: string;
  status: 'pending' | 'approved' | 'rejected';
  approver?: string;
  approved_by?: number;
  user: User;
  approved?: User;
  created_at: string;
  updated_at: string;
}
