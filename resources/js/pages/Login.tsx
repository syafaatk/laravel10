import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../hooks/useAuth';
import GuestLayout from '../layouts/GuestLayout';
import { Button } from '../components/ui/Button';
import { Lock, Mail, AlertCircle } from 'lucide-react';

const Login: React.FC = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState<string | null>(null);
  const { login, isLoading } = useAuth();
  const navigate = useNavigate();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);
    try {
      await login({ email, password });
      navigate('/react-test');
    } catch (err: any) {
      setError(err.response?.data?.message || 'Login gagal. Periksa kembali email dan password Anda.');
    }
  };

  return (
    <GuestLayout>
      <div className="text-center mb-8">
        <h2 className="text-2xl font-bold text-gray-900">Selamat Datang</h2>
        <p className="text-gray-500 mt-2">Masuk ke akun Anda untuk melanjutkan</p>
      </div>

      <form onSubmit={handleSubmit} className="space-y-6">
        {error && (
          <div className="p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start space-x-3 text-rose-600 animate-in shake-in duration-300">
            <AlertCircle size={20} className="shrink-0" />
            <p className="text-sm font-medium">{error}</p>
          </div>
        )}

        <div>
          <label className="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
          <div className="relative">
            <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
              <Mail size={18} />
            </div>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className="block w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
              placeholder="nama@perusahaan.com"
              required
            />
          </div>
        </div>

        <div>
          <div className="flex items-center justify-between mb-2">
            <label className="block text-sm font-semibold text-gray-700">Password</label>
            <a href="#" className="text-xs font-semibold text-indigo-600 hover:text-indigo-700">Lupa password?</a>
          </div>
          <div className="relative">
            <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
              <Lock size={18} />
            </div>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="block w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
              placeholder="••••••••"
              required
            />
          </div>
        </div>

        <div className="flex items-center">
          <input
            id="remember-me"
            name="remember-me"
            type="checkbox"
            className="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
          />
          <label htmlFor="remember-me" className="ml-2 block text-sm text-gray-500">
            Ingat saya
          </label>
        </div>

        <Button
          type="submit"
          className="w-full py-4 text-base shadow-lg shadow-indigo-100"
          isLoading={isLoading}
        >
          Masuk Sekarang
        </Button>
      </form>
    </GuestLayout>
  );
};

export default Login;
