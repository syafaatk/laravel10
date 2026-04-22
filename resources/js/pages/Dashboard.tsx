import React from 'react';
import { LayoutDashboard, Users, Clock, FileCheck } from 'lucide-react';
import { Badge } from '../components/ui/Badge';

const Dashboard: React.FC = () => {
  const stats = [
    { label: 'Total Reimbursements', value: '12', icon: FileCheck, color: 'text-blue-600', bg: 'bg-blue-50' },
    { label: 'Sisa Cuti', value: '8 Hari', icon: Clock, color: 'text-emerald-600', bg: 'bg-emerald-50' },
    { label: 'Total Lembur', value: '24 Jam', icon: Users, color: 'text-indigo-600', bg: 'bg-indigo-50' },
  ];

  return (
    <div className="space-y-8">
      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        {stats.map((stat, idx) => (
          <div key={idx} className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div className="flex items-center justify-between mb-4">
              <div className={`p-3 rounded-xl ${stat.bg}`}>
                <stat.icon className={stat.color} size={24} />
              </div>
              <Badge variant="info">Bulan Ini</Badge>
            </div>
            <p className="text-sm font-medium text-gray-500">{stat.label}</p>
            <p className="text-3xl font-bold text-gray-900 mt-1">{stat.value}</p>
          </div>
        ))}
      </div>

      {/* Recent Activity */}
      <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div className="p-6 border-b border-gray-50 flex items-center justify-between">
          <h3 className="text-lg font-bold text-gray-900">Aktivitas Terakhir</h3>
          <button className="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Lihat Semua</button>
        </div>
        <div className="divide-y divide-gray-50">
          {[1, 2, 3].map((i) => (
            <div key={i} className="p-6 hover:bg-gray-50 transition-colors flex items-center justify-between">
              <div className="flex items-center space-x-4">
                <div className="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                  <FileCheck size={20} className="text-gray-500" />
                </div>
                <div>
                  <p className="text-sm font-semibold text-gray-900">Pengajuan Lembur Disetujui</p>
                  <p className="text-xs text-gray-500">2 jam yang lalu • Proyek Alpha</p>
                </div>
              </div>
              <Badge variant="success">Disetujui</Badge>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
