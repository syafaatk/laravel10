import React from 'react';
import { cn } from './Button';

interface BadgeProps {
  variant?: 'success' | 'warning' | 'danger' | 'info' | 'gray';
  children: React.ReactNode;
  className?: string;
}

const Badge: React.FC<BadgeProps> = ({ variant = 'gray', children, className }) => {
  const variants = {
    success: 'bg-emerald-100 text-emerald-700 border-emerald-200',
    warning: 'bg-amber-100 text-amber-700 border-amber-200',
    danger: 'bg-rose-100 text-rose-700 border-rose-200',
    info: 'bg-sky-100 text-sky-700 border-sky-200',
    gray: 'bg-gray-100 text-gray-700 border-gray-200',
  };

  return (
    <span
      className={cn(
        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border transition-colors duration-200',
        variants[variant],
        className
      )}
    >
      {children}
    </span>
  );
};

export { Badge };
