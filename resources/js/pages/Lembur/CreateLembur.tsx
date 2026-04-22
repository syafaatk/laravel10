import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import LemburForm from './LemburForm';
import lemburService from '../../services/lemburService';
import { ChevronLeft } from 'lucide-react';

const CreateLembur: React.FC = () => {
  const [isLoading, setIsLoading] = useState(false);
  const navigate = useNavigate();

  const handleSubmit = async (data: any) => {
    setIsLoading(true);
    try {
      await lemburService.create(data);
      // Success - normally we'd show a toast here
      navigate('/react-test/lembur');
    } catch (error) {
      throw error; // Rethrow to let the form handle server errors
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="max-w-3xl mx-auto space-y-6">
      <div className="flex items-center space-x-2">
        <button 
          onClick={() => navigate('/react-test/lembur')}
          className="p-2 hover:bg-gray-100 rounded-lg text-gray-500 transition-colors"
        >
          <ChevronLeft size={20} />
        </button>
        <div>
          <h2 className="text-xl font-bold text-gray-900">Ajukan Lembur Baru</h2>
          <p className="text-sm text-gray-500">Lengkapi formulir di bawah untuk mengajukan lembur.</p>
        </div>
      </div>

      <div className="bg-white p-8 rounded-3xl border border-gray-100 shadow-xl shadow-indigo-50/20">
        <LemburForm onSubmit={handleSubmit} isLoading={isLoading} />
      </div>
    </div>
  );
};

export default CreateLembur;
