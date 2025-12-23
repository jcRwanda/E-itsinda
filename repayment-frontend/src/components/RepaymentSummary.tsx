import React from 'react';
import { Calendar, Clock, AlertCircle } from 'lucide-react';
import Card from '../components/Card';

interface RepaymentSummaryProps {
  loanId: string;
  principal: number;
  interest: number;
  lateFee: number;
  protocolFee: number;
  totalDue: number;
  dueDate: string;
  currentDate: string;
  isOverdue: boolean;
  daysLate?: number;
  className?: string;
}

const RepaymentSummary: React.FC<RepaymentSummaryProps> = ({
  loanId,
  principal,
  interest,
  lateFee,
  protocolFee,
  totalDue,
  dueDate,
  currentDate,
  isOverdue,
  daysLate = 0,
  className = '',
}) => {
  return (
    <Card className={className}>
      <div className="mb-6">
        <h3 className="text-xl font-bold text-gray-900 mb-2">Repayment Summary</h3>
        <p className="text-gray-600">Loan ID: <span className="font-mono">{loanId}</span></p>
      </div>

      {/* Time Information */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
          <Calendar className="w-5 h-5 text-blue-500" />
          <div>
            <p className="text-sm text-gray-500">Due Date</p>
            <p className="font-semibold">{dueDate}</p>
          </div>
        </div>
        <div className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
          <Clock className="w-5 h-5 text-blue-500" />
          <div>
            <p className="text-sm text-gray-500">Current Date</p>
            <p className="font-semibold">{currentDate}</p>
          </div>
        </div>
      </div>

      {/* Amount Breakdown */}
      <div className="space-y-3 mb-6">
        <div className="flex justify-between py-2 border-b">
          <span className="text-gray-600">Principal</span>
          <span className="font-semibold">{principal.toLocaleString()} ₳</span>
        </div>
        <div className="flex justify-between py-2 border-b">
          <span className="text-gray-600">Interest ({((interest / principal) * 100).toFixed(1)}%)</span>
          <span className="font-semibold">{interest.toLocaleString()} ₳</span>
        </div>
        {lateFee > 0 && (
          <div className="flex justify-between py-2 border-b">
            <span className="text-red-600">Late Fee ({daysLate} days)</span>
            <span className="font-semibold text-red-600">{lateFee.toLocaleString()} ₳</span>
          </div>
        )}
        <div className="flex justify-between py-2 border-b">
          <span className="text-gray-600">Protocol Fee</span>
          <span className="font-semibold">{protocolFee.toLocaleString()} ₳</span>
        </div>
      </div>

      {/* Total Due */}
      <div className="p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg mb-4">
        <div className="flex justify-between items-center">
          <span className="text-lg font-bold text-gray-900">Total Due</span>
          <span className="text-2xl font-bold text-blue-700">{totalDue.toLocaleString()} ₳</span>
        </div>
      </div>

      {/* Warning if overdue */}
      {isOverdue && (
        <div className="p-3 bg-yellow-50 border border-yellow-200 rounded-lg flex items-start gap-2">
          <AlertCircle className="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" />
          <div>
            <p className="text-yellow-800 font-medium">Payment is Overdue</p>
            <p className="text-yellow-700 text-sm mt-1">
              Your payment is {daysLate} days late. Late fees have been applied to the total.
            </p>
          </div>
        </div>
      )}
    </Card>
  );
};

export default RepaymentSummary;