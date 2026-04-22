import React from 'react';
import { Logo } from '../components/ui/Logo';

interface GuestLayoutProps {
  children: React.ReactNode;
}

const GuestLayout: React.FC<GuestLayoutProps> = ({ children }) => {
  return (
    <div className="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-4">
      <div className="mb-8 animate-in fade-in zoom-in duration-500">
        <Logo className="scale-125" />
      </div>
      
      <div className="w-full max-w-md bg-white rounded-3xl shadow-xl shadow-indigo-100/50 border border-gray-100 overflow-hidden animate-in slide-in-from-bottom-4 duration-700">
        <div className="p-8 sm:p-10">
          {children}
        </div>
        <div className="bg-gray-50 p-6 text-center border-t border-gray-100">
          <p className="text-xs text-gray-400">
            &copy; 2026 Admin Portal. All rights reserved.
          </p>
        </div>
      </div>
    </div>
  );
};

export default GuestLayout;
