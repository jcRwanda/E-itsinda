import React from 'react';
import { AlertTriangle, CheckCircle, Info, Shield } from 'lucide-react';

interface InfoCardProps {
  type?: 'info' | 'success' | 'warning' | 'security';
  title: string;
  description: string;
  icon?: React.ReactNode;
  className?: string;
}

const InfoCard: React.FC<InfoCardProps> = ({
  type = 'info',
  title,
  description,
  icon,
  className = '',
}) => {
  const typeConfig = {
    info: {
      icon: <Info className="w-6 h-6 text-blue-500" />,
      bgColor: 'bg-blue-50',
      borderColor: 'border-blue-200',
    },
    success: {
      icon: <CheckCircle className="w-6 h-6 text-green-500" />,
      bgColor: 'bg-green-50',
      borderColor: 'border-green-200',
    },
    warning: {
      icon: <AlertTriangle className="w-6 h-6 text-yellow-500" />,
      bgColor: 'bg-yellow-50',
      borderColor: 'border-yellow-200',
    },
    security: {
      icon: <Shield className="w-6 h-6 text-purple-500" />,
      bgColor: 'bg-purple-50',
      borderColor: 'border-purple-200',
    },
  };

  const config = typeConfig[type];

  return (
    <div
      className={`
        ${config.bgColor}
        ${config.borderColor}
        border rounded-lg p-4
        flex gap-3 items-start
        ${className}
      `}
    >
      <div className="shrink-0">
        {icon || config.icon}
      </div>
      <div>
        <h4 className="font-semibold text-gray-900 mb-1">{title}</h4>
        <p className="text-gray-600 text-sm">{description}</p>
      </div>
    </div>
  );
};

export default InfoCard;