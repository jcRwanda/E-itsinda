import { useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import { 
  Users, 
  ArrowLeft, 
  Download,   
  Search, 
  Calendar,
  TrendingUp,
  BarChart3,
  Shield,
  DollarSign, 
  Eye,
  FileText,
  RefreshCw
} from 'lucide-react';
import Card from '../../components/Card';
import Button from '../../components/Button';
import Input from '../../components/Input';

interface Transaction {
  id: string;
  type: 'loan' | 'repayment' | 'contribution' | 'withdrawal';
  memberId: string;
  memberName: string;
  amount: number;
  date: string;
  status: 'completed' | 'pending' | 'failed' | 'overdue';
  description: string;
  blockHeight?: number;
  transactionHash?: string;
}

interface Member {
  id: string;
  name: string;
  role: 'admin' | 'member' | 'treasurer';
  totalContributed: number;
  totalBorrowed: number;
  currentBalance: number;
  status: 'active' | 'inactive' | 'suspended';
  joinDate: string;
}

const GroupLedger = () => {
  const { groupId } = useParams<{ groupId: string }>();
  const [searchTerm, setSearchTerm] = useState('');
  const [filterType, setFilterType] = useState<string>('all');
  const [filterStatus, setFilterStatus] = useState<string>('all');
  const [viewMode, setViewMode] = useState<'transactions' | 'members' | 'analytics'>('transactions');

  // Mock data - This would come from your smart contract/API
  const groupData = {
    id: groupId || 'group-001',
    name: 'Savings Circle Alpha',
    description: 'Community savings group focused on mutual financial support',
    createdAt: '2024-01-15',
    totalMembers: 12,
    activeMembers: 11,
    totalPool: 1250.50,
    totalLoansIssued: 25,
    totalRepayments: 23,
    repaymentRate: 92,
    averageLoanAmount: 100,
    nextMeeting: '2024-12-30',
    rules: [
      'Maximum loan: 20% of total pool',
      'Repayment within 30 days',
      'Weekly contributions required',
      '2/3 majority for major decisions'
    ],
    admin: 'Alice Johnson',
    treasurer: 'Bob Smith'
  };

  // Mock transactions
  const transactions: Transaction[] = [
    { id: 'tx-001', type: 'loan', memberId: 'MEM-001', memberName: 'John Doe', amount: 100, date: '2024-12-01', status: 'completed', description: 'Personal loan', blockHeight: 1234567, transactionHash: 'abc123...' },
    { id: 'tx-002', type: 'repayment', memberId: 'MEM-001', memberName: 'John Doe', amount: 110, date: '2024-12-25', status: 'completed', description: 'Loan repayment with interest', blockHeight: 1234678 },
    { id: 'tx-003', type: 'contribution', memberId: 'MEM-002', memberName: 'Alice Johnson', amount: 50, date: '2024-12-20', status: 'completed', description: 'Weekly contribution' },
    { id: 'tx-004', type: 'loan', memberId: 'MEM-003', memberName: 'Bob Smith', amount: 200, date: '2024-12-15', status: 'pending', description: 'Business expansion loan' },
    { id: 'tx-005', type: 'withdrawal', memberId: 'MEM-004', memberName: 'Charlie Brown', amount: 75, date: '2024-12-18', status: 'completed', description: 'Emergency withdrawal' },
    { id: 'tx-006', type: 'repayment', memberId: 'MEM-005', memberName: 'Diana Prince', amount: 85, date: '2024-12-10', status: 'overdue', description: 'Late repayment' },
    { id: 'tx-007', type: 'contribution', memberId: 'MEM-001', memberName: 'John Doe', amount: 25, date: '2024-12-22', status: 'completed', description: 'Weekly contribution' },
    { id: 'tx-008', type: 'loan', memberId: 'MEM-006', memberName: 'Ethan Hunt', amount: 150, date: '2024-12-05', status: 'completed', description: 'Medical expenses loan' },
  ];

  // Mock members
  const members: Member[] = [
    { id: 'MEM-001', name: 'John Doe', role: 'member', totalContributed: 300, totalBorrowed: 100, currentBalance: 200, status: 'active', joinDate: '2024-01-15' },
    { id: 'MEM-002', name: 'Alice Johnson', role: 'admin', totalContributed: 500, totalBorrowed: 0, currentBalance: 500, status: 'active', joinDate: '2024-01-15' },
    { id: 'MEM-003', name: 'Bob Smith', role: 'treasurer', totalContributed: 400, totalBorrowed: 200, currentBalance: 200, status: 'active', joinDate: '2024-02-01' },
    { id: 'MEM-004', name: 'Charlie Brown', role: 'member', totalContributed: 250, totalBorrowed: 75, currentBalance: 175, status: 'active', joinDate: '2024-02-15' },
    { id: 'MEM-005', name: 'Diana Prince', role: 'member', totalContributed: 350, totalBorrowed: 100, currentBalance: 250, status: 'active', joinDate: '2024-03-01' },
    { id: 'MEM-006', name: 'Ethan Hunt', role: 'member', totalContributed: 200, totalBorrowed: 150, currentBalance: 50, status: 'inactive', joinDate: '2024-03-15' },
  ];

  // Filter transactions
  const filteredTransactions = transactions.filter(transaction => {
    const matchesSearch = transaction.memberName.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         transaction.memberId.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         transaction.description.toLowerCase().includes(searchTerm.toLowerCase());
    
    const matchesType = filterType === 'all' || transaction.type === filterType;
    const matchesStatus = filterStatus === 'all' || transaction.status === filterStatus;
    
    return matchesSearch && matchesType && matchesStatus;
  });

  const filteredMembers = members.filter(member =>
    member.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
    member.id.toLowerCase().includes(searchTerm.toLowerCase())
  );

  // Calculate statistics
  const totalContributions = transactions
    .filter(tx => tx.type === 'contribution')
    .reduce((sum, tx) => sum + tx.amount, 0);

  const totalLoans = transactions
    .filter(tx => tx.type === 'loan')
    .reduce((sum, tx) => sum + tx.amount, 0);

  const totalRepayments = transactions
    .filter(tx => tx.type === 'repayment')
    .reduce((sum, tx) => sum + tx.amount, 0);

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'completed': return 'bg-green-100 text-green-800';
      case 'pending': return 'bg-yellow-100 text-yellow-800';
      case 'failed': return 'bg-red-100 text-red-800';
      case 'overdue': return 'bg-orange-100 text-orange-800';
      default: return 'bg-gray-100 text-gray-800';
    }
  };

  const getTypeIcon = (type: string) => {
    switch (type) {
      case 'loan': return <DollarSign className="w-4 h-4" />;
      case 'repayment': return <ArrowLeft className="w-4 h-4" />;
      case 'contribution': return <TrendingUp className="w-4 h-4" />;
      case 'withdrawal': return <Download className="w-4 h-4" />;
      default: return null;
    }
  };

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-white border-b border-gray-200">
        <div className="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <div className="flex items-center gap-3">
                <Link to="/repay" className="text-blue-600 hover:text-blue-800">
                  <ArrowLeft className="w-5 h-5" />
                </Link>
                <div>
                  <h1 className="text-2xl font-bold text-gray-900">
                    {groupData.name} - Ledger
                  </h1>
                  <p className="text-gray-600">Group ID: {groupData.id}</p>
                </div>
              </div>
            </div>
            
            <div className="flex items-center gap-3">
              <Button variant="outline" leftIcon={<Download className="w-4 h-4" />}>
                Export Ledger
              </Button>
              <Button variant="primary" leftIcon={<RefreshCw className="w-4 h-4" />}>
                Refresh Data
              </Button>
            </div>
          </div>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        {/* Group Stats */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
          <Card className="bg-linear-to-r from-blue-50 to-blue-100">
            <div className="flex items-center gap-3">
              <div className="p-2 bg-blue-500 rounded-lg">
                <Users className="w-6 h-6 text-white" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Total Members</p>
                <p className="text-2xl font-bold text-gray-900">{groupData.totalMembers}</p>
                <p className="text-xs text-gray-500">{groupData.activeMembers} active</p>
              </div>
            </div>
          </Card>

          <Card className="bg-linear-to-r from-green-50 to-green-100">
            <div className="flex items-center gap-3">
              <div className="p-2 bg-green-500 rounded-lg">
                <DollarSign className="w-6 h-6 text-white" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Total Pool</p>
                <p className="text-2xl font-bold text-gray-900">{groupData.totalPool.toFixed(2)} ₳</p>
                <p className="text-xs text-gray-500">Available for loans</p>
              </div>
            </div>
          </Card>

          <Card className="bg-linear-to-r from-purple-50 to-purple-100">
            <div className="flex items-center gap-3">
              <div className="p-2 bg-purple-500 rounded-lg">
                <TrendingUp className="w-6 h-6 text-white" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Repayment Rate</p>
                <p className="text-2xl font-bold text-gray-900">{groupData.repaymentRate}%</p>
                <p className="text-xs text-gray-500">On-time repayments</p>
              </div>
            </div>
          </Card>

          <Card className="bg-linear-to-r from-orange-50 to-orange-100">
            <div className="flex items-center gap-3">
              <div className="p-2 bg-orange-500 rounded-lg">
                <Calendar className="w-6 h-6 text-white" />
              </div>
              <div>
                <p className="text-sm text-gray-600">Next Meeting</p>
                <p className="text-2xl font-bold text-gray-900">{groupData.nextMeeting}</p>
                <p className="text-xs text-gray-500">All members welcome</p>
              </div>
            </div>
          </Card>
        </div>

        {/* View Mode Toggle */}
        <Card className="mb-6">
          <div className="flex flex-wrap gap-2">
            <Button
              variant={viewMode === 'transactions' ? 'primary' : 'ghost'}
              onClick={() => setViewMode('transactions')}
              leftIcon={<FileText className="w-4 h-4" />}
            >
              Transactions
            </Button>
            <Button
              variant={viewMode === 'members' ? 'primary' : 'ghost'}
              onClick={() => setViewMode('members')}
              leftIcon={<Users className="w-4 h-4" />}
            >
              Members
            </Button>
            <Button
              variant={viewMode === 'analytics' ? 'primary' : 'ghost'}
              onClick={() => setViewMode('analytics')}
              leftIcon={<BarChart3 className="w-4 h-4" />}
            >
              Analytics
            </Button>
          </div>
        </Card>

        {/* Search and Filters */}
        <Card className="mb-6">
          <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div className="md:col-span-2">
              <Input
                placeholder="Search by member name, ID, or description..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                leftIcon={<Search className="w-4 h-4" />}
              />
            </div>
            
            <div>
              <select
                className="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                value={filterType}
                onChange={(e) => setFilterType(e.target.value)}
              >
                <option value="all">All Types</option>
                <option value="loan">Loans</option>
                <option value="repayment">Repayments</option>
                <option value="contribution">Contributions</option>
                <option value="withdrawal">Withdrawals</option>
              </select>
            </div>
            
            <div>
              <select
                className="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                value={filterStatus}
                onChange={(e) => setFilterStatus(e.target.value)}
              >
                <option value="all">All Status</option>
                <option value="completed">Completed</option>
                <option value="pending">Pending</option>
                <option value="failed">Failed</option>
                <option value="overdue">Overdue</option>
              </select>
            </div>
          </div>
        </Card>

        {/* Content based on view mode */}
        {viewMode === 'transactions' && (
          <div className="space-y-6">
            <div className="flex items-center justify-between">
              <h2 className="text-xl font-bold text-gray-900">Recent Transactions</h2>
              <p className="text-gray-600">
                Showing {filteredTransactions.length} of {transactions.length} transactions
              </p>
            </div>

            <Card className="overflow-hidden">
              <div className="overflow-x-auto">
                <table className="min-w-full divide-y divide-gray-200">
                  <thead className="bg-gray-50">
                    <tr>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Type
                      </th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Member
                      </th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Amount
                      </th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date
                      </th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                      </th>
                      <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                      </th>
                    </tr>
                  </thead>
                  <tbody className="bg-white divide-y divide-gray-200">
                    {filteredTransactions.map((transaction) => (
                      <tr key={transaction.id} className="hover:bg-gray-50">
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className="flex items-center gap-2">
                            <div className="p-1 bg-blue-100 rounded">
                              {getTypeIcon(transaction.type)}
                            </div>
                            <span className="capitalize font-medium">{transaction.type}</span>
                          </div>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div>
                            <div className="font-medium text-gray-900">{transaction.memberName}</div>
                            <div className="text-sm text-gray-500">{transaction.memberId}</div>
                          </div>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className={`font-bold ${transaction.type === 'loan' || transaction.type === 'withdrawal' ? 'text-red-600' : 'text-green-600'}`}>
                            {transaction.type === 'loan' || transaction.type === 'withdrawal' ? '-' : '+'}
                            {transaction.amount.toFixed(2)} ₳
                          </div>
                          <div className="text-xs text-gray-500">{transaction.description}</div>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className="text-gray-900">{transaction.date}</div>
                          {transaction.blockHeight && (
                            <div className="text-xs text-gray-500">Block: {transaction.blockHeight}</div>
                          )}
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <span className={`px-2 py-1 text-xs font-medium rounded-full ${getStatusColor(transaction.status)}`}>
                            {transaction.status}
                          </span>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <div className="flex items-center gap-2">
                            {transaction.transactionHash && (
                              <Button
                                variant="ghost"
                                size="sm"
                                leftIcon={<Eye className="w-3 h-3" />}
                              >
                                View
                              </Button>
                            )}
                          </div>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
              
              {filteredTransactions.length === 0 && (
                <div className="text-center py-12">
                  <Search className="w-12 h-12 text-gray-400 mx-auto mb-4" />
                  <p className="text-gray-500">No transactions found matching your criteria</p>
                </div>
              )}
            </Card>
          </div>
        )}

        {viewMode === 'members' && (
          <div className="space-y-6">
            <div className="flex items-center justify-between">
              <h2 className="text-xl font-bold text-gray-900">Group Members</h2>
              <p className="text-gray-600">
                {filteredMembers.length} members
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              {filteredMembers.map((member) => (
                <Card key={member.id} className="hover:shadow-lg transition-shadow duration-200">
                  <div className="space-y-4">
                    <div className="flex items-center justify-between">
                      <div>
                        <h3 className="font-bold text-gray-900">{member.name}</h3>
                        <p className="text-sm text-gray-500">{member.id}</p>
                      </div>
                      <div className="flex items-center gap-2">
                        <span className={`px-2 py-1 text-xs font-medium rounded-full ${
                          member.role === 'admin' ? 'bg-purple-100 text-purple-800' :
                          member.role === 'treasurer' ? 'bg-blue-100 text-blue-800' :
                          'bg-gray-100 text-gray-800'
                        }`}>
                          {member.role}
                        </span>
                        <span className={`px-2 py-1 text-xs font-medium rounded-full ${
                          member.status === 'active' ? 'bg-green-100 text-green-800' :
                          member.status === 'inactive' ? 'bg-gray-100 text-gray-800' :
                          'bg-red-100 text-red-800'
                        }`}>
                          {member.status}
                        </span>
                      </div>
                    </div>

                    <div className="space-y-2">
                      <div className="flex justify-between">
                        <span className="text-gray-600">Contributions</span>
                        <span className="font-semibold text-green-600">+{member.totalContributed.toFixed(2)} ₳</span>
                      </div>
                      <div className="flex justify-between">
                        <span className="text-gray-600">Loans Taken</span>
                        <span className="font-semibold text-red-600">-{member.totalBorrowed.toFixed(2)} ₳</span>
                      </div>
                      <div className="flex justify-between pt-2 border-t border-gray-200">
                        <span className="text-gray-800 font-medium">Current Balance</span>
                        <span className="font-bold text-blue-600">{member.currentBalance.toFixed(2)} ₳</span>
                      </div>
                    </div>

                    <div className="flex items-center justify-between pt-4 border-t border-gray-200">
                      <div className="text-sm text-gray-500">
                        Joined: {member.joinDate}
                      </div>
                      <Button variant="ghost" size="sm">
                        View Activity
                      </Button>
                    </div>
                  </div>
                </Card>
              ))}
            </div>
          </div>
        )}

        {viewMode === 'analytics' && (
          <div className="space-y-6">
            <h2 className="text-xl font-bold text-gray-900">Group Analytics</h2>
            
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <Card>
                <h3 className="text-lg font-semibold text-gray-900 mb-4">Financial Overview</h3>
                <div className="space-y-4">
                  <div className="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                    <span className="font-medium">Total Contributions</span>
                    <span className="text-xl font-bold text-blue-700">+{totalContributions.toFixed(2)} ₳</span>
                  </div>
                  <div className="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <span className="font-medium">Total Loans Issued</span>
                    <span className="text-xl font-bold text-green-700">-{totalLoans.toFixed(2)} ₳</span>
                  </div>
                  <div className="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                    <span className="font-medium">Total Repayments</span>
                    <span className="text-xl font-bold text-purple-700">+{totalRepayments.toFixed(2)} ₳</span>
                  </div>
                  <div className="flex justify-between items-center p-3 bg-linear-to-r from-blue-100 to-purple-100 rounded-lg">
                    <span className="font-medium">Net Group Balance</span>
                    <span className="text-2xl font-bold text-gray-900">{groupData.totalPool.toFixed(2)} ₳</span>
                  </div>
                </div>
              </Card>

              <Card>
                <h3 className="text-lg font-semibold text-gray-900 mb-4">Group Rules & Info</h3>
                <div className="space-y-3">
                  {groupData.rules.map((rule, index) => (
                    <div key={index} className="flex items-start gap-2">
                      <Shield className="w-4 h-4 text-blue-600 mt-0.5 shrink-0" />
                      <p className="text-gray-700">{rule}</p>
                    </div>
                  ))}
                  
                  <div className="pt-4 border-t border-gray-200 space-y-2">
                    <div className="flex justify-between">
                      <span className="text-gray-600">Admin:</span>
                      <span className="font-medium">{groupData.admin}</span>
                    </div>
                    <div className="flex justify-between">
                      <span className="text-gray-600">Treasurer:</span>
                      <span className="font-medium">{groupData.treasurer}</span>
                    </div>
                    <div className="flex justify-between">
                      <span className="text-gray-600">Created:</span>
                      <span className="font-medium">{groupData.createdAt}</span>
                    </div>
                  </div>
                </div>
              </Card>
            </div>

            <Card>
              <h3 className="text-lg font-semibold text-gray-900 mb-4">Transaction Distribution</h3>
              <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div className="text-center p-4 bg-blue-50 rounded-lg">
                  <div className="text-3xl font-bold text-blue-700">
                    {transactions.filter(t => t.type === 'loan').length}
                  </div>
                  <div className="text-gray-600">Loans Issued</div>
                </div>
                <div className="text-center p-4 bg-green-50 rounded-lg">
                  <div className="text-3xl font-bold text-green-700">
                    {transactions.filter(t => t.type === 'repayment').length}
                  </div>
                  <div className="text-gray-600">Repayments</div>
                </div>
                <div className="text-center p-4 bg-purple-50 rounded-lg">
                  <div className="text-3xl font-bold text-purple-700">
                    {transactions.filter(t => t.type === 'contribution').length}
                  </div>
                  <div className="text-gray-600">Contributions</div>
                </div>
                <div className="text-center p-4 bg-orange-50 rounded-lg">
                  <div className="text-3xl font-bold text-orange-700">
                    {transactions.filter(t => t.type === 'withdrawal').length}
                  </div>
                  <div className="text-gray-600">Withdrawals</div>
                </div>
              </div>
            </Card>
          </div>
        )}

        {/* Quick Actions */}
        <div className="mt-8">
          <Card>
            <div className="flex flex-wrap gap-4 justify-center">
              <Button variant="outline" leftIcon={<FileText className="w-4 h-4" />}>
                Request New Loan
              </Button>
              <Button variant="outline" leftIcon={<TrendingUp className="w-4 h-4" />}>
                Make Contribution
              </Button>
              <Button variant="outline" leftIcon={<Users className="w-4 h-4" />}>
                Invite Members
              </Button>
              <Button variant="primary" leftIcon={<Shield className="w-4 h-4" />}>
                View Smart Contract
              </Button>
            </div>
          </Card>
        </div>
      </div>
    </div>
  );
};

export default GroupLedger;