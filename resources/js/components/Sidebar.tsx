import React from 'react';
import { NavLink } from 'react-router-dom';
import { 
  LayoutDashboard, 
  FileText, 
  Calendar, 
  Clock, 
  UserCircle, 
  Settings, 
  ChevronRight,
  ClipboardList,
  UserCheck
} from 'lucide-react';
import { cn } from './ui/Button';

interface SidebarProps {
  isOpen: boolean;
  onClose: () => void;
}

const Sidebar: React.FC<SidebarProps> = ({ isOpen, onClose }) => {
  const menuGroups = [
    {
      title: 'Main',
      items: [
        { name: 'Dashboard', icon: LayoutDashboard, path: '/react-test' },
      ],
    },
    {
      title: 'Layanan Karyawan',
      items: [
        { name: 'Reimbursements', icon: FileText, path: '/react-test/reimbursements' },
        { name: 'Cuti', icon: Calendar, path: '/react-test/cuti' },
        { name: 'Lembur', icon: Clock, path: '/react-test/lembur' },
        { name: 'Slip Gaji', icon: ClipboardList, path: '/react-test/slip-gaji' },
        { name: 'Curriculum Vitae', icon: UserCircle, path: '/react-test/cv' },
      ],
    },
    {
      title: 'Administrasi',
      items: [
        { name: 'Manajemen User', icon: UserCheck, path: '/react-test/admin/users' },
        { name: 'Penilaian Pegawai', icon: Settings, path: '/react-test/admin/penilaian' },
      ],
    },
  ];

  return (
    <>
      {/* Mobile Overlay */}
      {isOpen && (
        <div
          className="fixed inset-0 bg-gray-900/20 backdrop-blur-sm z-40 lg:hidden"
          onClick={onClose}
        ></div>
      )}

      <aside
        className={cn(
          'fixed top-16 left-0 bottom-0 w-64 bg-white border-r border-gray-100 z-40 transition-transform duration-300 lg:translate-x-0',
          isOpen ? 'translate-x-0' : '-translate-x-full'
        )}
      >
        <div className="h-full overflow-y-auto py-6 px-4 space-y-8">
          {menuGroups.map((group, idx) => (
            <div key={idx} className="space-y-2">
              <h3 className="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                {group.title}
              </h3>
              <div className="space-y-1">
                {group.items.map((item) => (
                  <NavLink
                    key={item.path}
                    to={item.path}
                    className={({ isActive }) =>
                      cn(
                        'flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group',
                        isActive
                          ? 'bg-indigo-50 text-indigo-700 shadow-sm shadow-indigo-100'
                          : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'
                      )
                    }
                    onClick={() => window.innerWidth < 1024 && onClose()}
                  >
                    <div className="flex items-center space-x-3">
                      <item.icon size={18} className="transition-transform group-hover:scale-110" />
                      <span>{item.name}</span>
                    </div>
                    <ChevronRight size={14} className="opacity-0 group-hover:opacity-100 transition-opacity" />
                  </NavLink>
                ))}
              </div>
            </div>
          ))}
        </div>
      </aside>
    </>
  );
};

export default Sidebar;
