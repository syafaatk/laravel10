import React, { useState } from 'react';
import Navbar from '../components/Navbar';
import Sidebar from '../components/Sidebar';

interface AppLayoutProps {
  children: React.ReactNode;
  header?: string;
}

const AppLayout: React.FC<AppLayoutProps> = ({ children, header }) => {
  const [isSidebarOpen, setIsSidebarOpen] = useState(false);

  return (
    <div className="min-h-screen bg-gray-50/50">
      <Navbar onToggleSidebar={() => setIsSidebarOpen(!isSidebarOpen)} />
      
      <Sidebar isOpen={isSidebarOpen} onClose={() => setIsSidebarOpen(false)} />

      <main 
        className="pt-16 lg:pl-64 min-h-screen transition-all duration-300"
      >
        <div className="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
          {header && (
            <div className="mb-8 animate-in slide-in-from-left duration-500">
              <h1 className="text-2xl font-bold text-gray-900 tracking-tight">{header}</h1>
              <div className="h-1 w-12 bg-indigo-600 rounded-full mt-2"></div>
            </div>
          )}
          
          <div className="animate-in fade-in duration-700">
            {children}
          </div>
        </div>
      </main>
    </div>
  );
};

export default AppLayout;
