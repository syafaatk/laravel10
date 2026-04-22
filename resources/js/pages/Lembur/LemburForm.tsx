import React from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { Input } from '../../components/ui/Input';
import { Select } from '../../components/ui/Select';
import { Textarea } from '../../components/ui/Textarea';
import { Button } from '../../components/ui/Button';
import { Calendar, Clock, FileText } from 'lucide-react';

const lemburSchema = z.object({
  tanggal: z.string().min(1, 'Tanggal wajib diisi'),
  jenis: z.enum(['weekdays', 'weekend'], {
    errorMap: () => ({ message: 'Pilih jenis lembur' }),
  }),
  jam_mulai: z.string().min(1, 'Jam mulai wajib diisi'),
  jam_selesai: z.string().min(1, 'Jam selesai wajib diisi'),
  durasi_jam: z.preprocess((val) => Number(val), z.number().min(0.5, 'Minimal 0.5 jam')),
  keterangan: z.string().min(10, 'Keterangan minimal 10 karakter'),
});

type LemburFormData = z.infer<typeof lemburSchema>;

interface LemburFormProps {
  onSubmit: (data: LemburFormData) => Promise<void>;
  isLoading?: boolean;
  defaultValues?: Partial<LemburFormData>;
}

const LemburForm: React.FC<LemburFormProps> = ({ onSubmit, isLoading, defaultValues }) => {
  const {
    register,
    handleSubmit,
    formState: { errors },
    setError,
  } = useForm<LemburFormData>({
    resolver: zodResolver(lemburSchema),
    defaultValues: {
      jenis: 'weekdays',
      ...defaultValues,
    },
  });

  const handleFormSubmit = async (data: LemburFormData) => {
    try {
      await onSubmit(data);
    } catch (error: any) {
      if (error.response?.status === 422) {
        const serverErrors = error.response.data.errors;
        Object.keys(serverErrors).forEach((key) => {
          setError(key as any, {
            type: 'server',
            message: serverErrors[key][0],
          });
        });
      }
    }
  };

  return (
    <form onSubmit={handleSubmit(handleFormSubmit)} className="space-y-6">
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <Input
          label="Tanggal Lembur"
          type="date"
          icon={<Calendar size={18} />}
          error={errors.tanggal?.message}
          {...register('tanggal')}
        />

        <Select
          label="Jenis Hari"
          error={errors.jenis?.message}
          options={[
            { label: 'Hari Kerja (Weekdays)', value: 'weekdays' },
            { label: 'Hari Libur (Weekend)', value: 'weekend' },
          ]}
          {...register('jenis')}
        />

        <Input
          label="Jam Mulai"
          type="time"
          icon={<Clock size={18} />}
          error={errors.jam_mulai?.message}
          {...register('jam_mulai')}
        />

        <Input
          label="Jam Selesai"
          type="time"
          icon={<Clock size={18} />}
          error={errors.jam_selesai?.message}
          {...register('jam_selesai')}
        />

        <Input
          label="Estimasi Durasi (Jam)"
          type="number"
          step="0.5"
          placeholder="Contoh: 2.5"
          error={errors.durasi_jam?.message}
          {...register('durasi_jam')}
        />
      </div>

      <Textarea
        label="Uraian Pekerjaan / Keterangan"
        placeholder="Jelaskan pekerjaan yang dilakukan selama lembur..."
        error={errors.keterangan?.message}
        {...register('keterangan')}
      />

      <div className="flex justify-end space-x-3 pt-4 border-t border-gray-100">
        <Button variant="outline" type="button" onClick={() => window.history.back()}>
          Batal
        </Button>
        <Button type="submit" isLoading={isLoading}>
          Kirim Pengajuan
        </Button>
      </div>
    </form>
  );
};

export default LemburForm;
