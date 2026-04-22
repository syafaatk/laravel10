import React, { forwardRef } from 'react';
import { cn } from './Button';

interface SelectProps extends React.SelectHTMLAttributes<HTMLSelectElement> {
  label?: string;
  error?: string;
  options: { label: string; value: string | number }[];
}

export const Select = forwardRef<HTMLSelectElement, SelectProps>(
  ({ className, label, error, options, ...props }, ref) => {
    return (
      <div className="space-y-1.5">
        {label && (
          <label className="text-sm font-semibold text-gray-700">
            {label}
          </label>
        )}
        <select
          className={cn(
            "flex h-11 w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm transition-all outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 disabled:cursor-not-allowed disabled:opacity-50 appearance-none",
            error && "border-rose-500 focus:ring-rose-500/20 focus:border-rose-500",
            className
          )}
          ref={ref}
          {...props}
        >
          {options.map((opt) => (
            <option key={opt.value} value={opt.value}>
              {opt.label}
            </option>
          ))}
        </select>
        {error && (
          <p className="text-xs font-medium text-rose-500 animate-in fade-in slide-in-from-top-1 duration-200">
            {error}
          </p>
        )}
      </div>
    );
  }
);

Select.displayName = "Select";
