import React, { useEffect, useState } from 'react';
import { ColumnDef } from '@tanstack/react-table';
import { DataTable } from '../../components/ui/DataTable';
import { Badge } from '../../components/ui/Badge';
import { Button } from '../../components/ui/Button';
import { Eye, Edit2, Plus } from 'lucide-react';
import { useNavigate } from 'react-router-dom';
import lemburService from '../../services/lemburService';
import { Lembur } from '../../types/lembur';
import { format } from 'date-fns';
import { id } from 'date-fns/locale';

const LemburList: React.FC = () => {
  const [data, setData] = useState<Lembur[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    const fetchData = async () => {
      try {
        const result = await lemburService.getAll();
        setData(result);
      } catch (error) {
        console.error('Failed to fetch lembur data', error);
      } finally {
        setIsLoading(false);
      }
    };
    fetchData();
  }, []);

  const columns: ColumnDef<Lembur>[] = [
    {
      accessorKey: 'tanggal',
      header: 'Tanggal',
      cell: ({ row }) => format(new Date(row.original.tanggal), 'dd MMMM yyyy', { locale: id }),
    },
    {
      accessorKey: 'user.name',
      header: 'Karyawan',
    },
    {
      accessorKey: 'jenis',
      header: 'Jenis',
      cell: ({ row }) => (
        <span className="capitalize">{row.original.jenis}</span>
      ),
    },
    {
      accessorKey: 'durasi_jam',
      header: 'Durasi (Jam)',
      cell: ({ row }) => `${row.original.durasi_jam} jam`,
    },
    {
      accessorKey: 'status',
      header: 'Status',
      cell: ({ row }) => {
        const status = row.original.status;
        return (
          <Badge variant={status === 'approved' ? 'success' : status === 'pending' ? 'warning' : 'danger'}>
            {status.toUpperCase()}
          </Badge>
        );
      },
    },
    {
      id: 'actions',
      header: 'Aksi',
      cell: ({ row }) => (
        <div className="flex items-center space-x-2">
          <Button variant="ghost" size="sm" className="h-8 w-8 p-0">
            <Eye size={16} className="text-gray-500" />
          </Button>
          {row.original.status === 'pending' && (
            <Button variant="ghost" size="sm" className="h-8 w-8 p-0">
              <Edit2 size={16} className="text-indigo-500" />
            </Button>
          )}
        </div>
      ),
    },
  ];

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <p className="text-sm text-gray-500">Kelola riwayat dan pengajuan lembur Anda.</p>
        </div>
        <Button 
          onClick={() => navigate('/react-test/lembur/create')}
          className="flex items-center space-x-2 shadow-indigo-100 shadow-lg"
        >
          <Plus size={18} />
          <span>Ajukan Lembur</span>
        </Button>
      </div>

      <DataTable 
        columns={columns} 
        data={data} 
        isLoading={isLoading} 
        searchPlaceholder="Cari berdasarkan nama atau keterangan..." 
      />
    </div>
  );
};

export default LemburList;
