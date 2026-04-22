import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate, useLocation } from 'react-router-dom';
import { AuthProvider, useAuth } from './hooks/useAuth';
import AppLayout from './layouts/AppLayout';
import Dashboard from './pages/Dashboard';
import Login from './pages/Login';
import LemburList from './pages/Lembur/LemburList';
import CreateLembur from './pages/Lembur/CreateLembur';

const ProtectedRoute: React.FC<{ children: React.ReactNode; header?: string }> = ({ children, header }) => {
    const { user, isLoading } = useAuth();
    const location = useLocation();

    if (isLoading) {
        return (
            <div className="min-h-screen flex items-center justify-center bg-gray-50">
                <div className="flex flex-col items-center space-y-4">
                    <div className="w-12 h-12 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
                    <p className="text-gray-500 font-medium animate-pulse">Menyiapkan sesi Anda...</p>
                </div>
            </div>
        );
    }

    if (!user) {
        return <Navigate to="/react-test/login" state={{ from: location }} replace />;
    }

    return <AppLayout header={header}>{children}</AppLayout>;
};

const AppRoutes: React.FC = () => {
    return (
        <Routes>
            <Route path="/react-test/login" element={<Login />} />
            
            <Route path="/react-test" element={
                <ProtectedRoute header="Dashboard">
                    <Dashboard />
                </ProtectedRoute>
            } />

            <Route path="/react-test/lembur" element={
                <ProtectedRoute header="Pengajuan Lembur">
                    <LemburList />
                </ProtectedRoute>
            } />

            <Route path="/react-test/lembur/create" element={
                <ProtectedRoute header="Tambah Lembur">
                    <CreateLembur />
                </ProtectedRoute>
            } />
            
            <Route path="/react-test/*" element={
                <ProtectedRoute header="Halaman">
                    <div className="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm text-center">
                        <h2 className="text-xl font-semibold text-gray-900">Halaman Sedang Dikembangkan</h2>
                        <p className="text-gray-500 mt-2">Komponen Blade sedang dimigrasikan ke React.</p>
                    </div>
                </ProtectedRoute>
            } />

            <Route path="*" element={<Navigate to="/react-test" replace />} />
        </Routes>
    );
};

const App: React.FC = () => {
    return (
        <AuthProvider>
            <Router>
                <AppRoutes />
            </Router>
        </AuthProvider>
    );
};

export default App;
