import React from 'react';
import { Shield, Zap, Lock, BarChart, Globe, Users } from 'lucide-react';
import Card from '../components/Card';

interface Benefit {
  icon: React.ReactNode;
  title: string;
  description: string;
}

interface PlatformBenefitsProps {
  benefits?: Benefit[];
  className?: string;
}

const PlatformBenefits: React.FC<PlatformBenefitsProps> = ({
  benefits,
  className = '',
}) => {
  const defaultBenefits: Benefit[] = [
    {
      icon: <Shield className="w-6 h-6" />,
      title: 'Bank-Level Security',
      description: 'All transactions are secured with Cardano\'s blockchain technology and smart contracts.',
    },
    {
      icon: <Zap className="w-6 h-6" />,
      title: 'Instant Settlement',
      description: 'Repayments are processed instantly on the blockchain with immediate confirmation.',
    },
    {
      icon: <Lock className="w-6 h-6" />,
      title: 'No Middlemen',
      description: 'Direct peer-to-peer transactions with automated smart contract execution.',
    },
    {
      icon: <BarChart className="w-6 h-6" />,
      title: 'Transparent Fees',
      description: 'Clear breakdown of all fees with no hidden charges. Everything is on-chain.',
    },
    {
      icon: <Globe className="w-6 h-6" />,
      title: 'Global Access',
      description: 'Available worldwide with cryptocurrency. No bank account required.',
    },
    {
      icon: <Users className="w-6 h-6" />,
      title: 'Community Driven',
      description: 'Governed by the community with regular protocol improvements and updates.',
    },
  ];

  const displayBenefits = benefits || defaultBenefits;

  return (
    <Card className={className}>
      <h3 className="text-xl font-bold text-gray-900 mb-6">
        Why Choose e-Tsinda?
      </h3>
      
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        {displayBenefits.map((benefit, index) => (
          <div
            key={index}
            className="p-4 rounded-lg hover:bg-blue-50 transition-colors duration-200 group"
          >
            <div className="flex items-start gap-3">
              <div className="p-2 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors duration-200">
                <div className="text-blue-600">
                  {benefit.icon}
                </div>
              </div>
              <div>
                <h4 className="font-semibold text-gray-900 mb-1">{benefit.title}</h4>
                <p className="text-gray-600 text-sm">{benefit.description}</p>
              </div>
            </div>
          </div>
        ))}
      </div>

      <div className="mt-8 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg">
        <p className="text-gray-700 text-sm">
          <span className="font-semibold">Note:</span> All repayments are executed through secure smart contracts
          that automatically verify and distribute funds to lenders and protocol fees.
          No manual intervention required.
        </p>
      </div>
    </Card>
  );
};

export default PlatformBenefits;