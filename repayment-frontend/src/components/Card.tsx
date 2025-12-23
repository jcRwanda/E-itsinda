import React from 'react';

interface CardProps {
  children: React.ReactNode;
  className?: string;
  shadow?: 'sm' | 'md' | 'lg' | 'xl' | '2xl' | 'none';
  padding?: 'none' | 'sm' | 'md' | 'lg' | 'xl';
  rounded?: 'none' | 'sm' | 'md' | 'lg' | 'xl' | '2xl' | 'full';
  border?: boolean;
  borderColor?: string;
  backgroundColor?: string;
  onClick?: () => void;
  onMouseEnter?: () => void;
  onMouseLeave?: () => void;
}

const Card: React.FC<CardProps> = ({
  children,
  className = '',
  shadow = 'md',
  padding = 'md',
  rounded = 'lg',
  border = true,
  borderColor = 'border-gray-200',
  backgroundColor = 'bg-white',
  onClick,
  onMouseEnter,
  onMouseLeave,
  ...props
}) => {
  const shadowClasses = {
    sm: 'shadow-sm',
    md: 'shadow-md',
    lg: 'shadow-lg',
    xl: 'shadow-xl',
    '2xl': 'shadow-2xl',
    none: '',
  };

  const paddingClasses = {
    none: 'p-0',
    sm: 'p-3',
    md: 'p-6',
    lg: 'p-8',
    xl: 'p-10',
  };

  const roundedClasses = {
    none: 'rounded-none',
    sm: 'rounded-sm',
    md: 'rounded-md',
    lg: 'rounded-lg',
    xl: 'rounded-xl',
    '2xl': 'rounded-2xl',
    full: 'rounded-full',
  };

  return (
    <div
      className={`
        ${shadowClasses[shadow]}
        ${paddingClasses[padding]}
        ${roundedClasses[rounded]}
        ${border ? `border ${borderColor}` : ''}
        ${backgroundColor}
        ${onClick ? 'cursor-pointer' : ''}
        transition-all duration-200
        ${className}
      `}
      onClick={onClick}
      onMouseEnter={onMouseEnter}
      onMouseLeave={onMouseLeave}
      {...props}
    >
      {children}
    </div>
  );
};

export default Card;