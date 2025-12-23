import { useState } from 'react';
import { 
  ArrowRight, 
  Users, 
  FileText, 
  Building, 
  Send, 
  CheckCircle,
  Download,
  Mail,
  UserCheck,
  Shield,
  Clock,
  Calendar,
  Percent,
  AlertCircle
} from 'lucide-react';
import { useNavigate } from 'react-router-dom';

// Components
import Card from '../../components/Card';
import Button from '../../components/Button';
import Input from '../../components/Input';
import StepIndicator from '../../components/StepIndicator';
import InfoCard from '../../components/InfoCard';
import RepaymentSummary from '../../components/RepaymentSummary';
import PlatformBenefits from '../../components/PlatformBenefits';

const RepayPage = () => {
  const [currentStep, setCurrentStep] = useState(1);
  const [isConnecting, setIsConnecting] = useState(false);
  const [isProcessing, setIsProcessing] = useState(false);
  const [selectedGroup, setSelectedGroup] = useState('');
  const [memberId, setMemberId] = useState('');
  const navigate = useNavigate();

  const steps = [
    { id: 1, title: 'Group Selection', description: 'Select your group and member ID' },
    { id: 2, title: 'Loan Details', description: 'Verify loan information' },
    { id: 3, title: 'Review & Summary', description: 'Verify all details' },
    { id: 4, title: 'Confirm Payment', description: 'Complete transaction' },
  ];

  // Mock data for groups
  const groups = [
    { id: 'group-001', name: 'Savings Circle Alpha', members: 12, totalSavings: 50000 },
    { id: 'group-002', name: 'Community Fund Beta', members: 8, totalSavings: 35000 },
    { id: 'group-003', name: 'Family Trust Gamma', members: 5, totalSavings: 25000 },
    { id: 'group-004', name: 'Business Pool Delta', members: 15, totalSavings: 75000 },
  ];

  // Mock loan data - This would come from your smart contract
  const loanData = {
    loanId: '4c4e2d31',
    groupId: 'group-001',
    memberId: 'MEM-001',
    memberName: 'John Doe',
    principal: 100,
    interest: 10,
    lateFee: 0,
    protocolFee: 2.2,
    totalDue: 112.2,
    dueDate: '2024-12-31',
    currentDate: '2024-12-25',
    isOverdue: false,
    daysLate: 0,
    interestRate: '10%',
    groupName: 'Savings Circle Alpha',
    groupBalance: 1250.50,
    availableForLoan: 500,
  };

  const handleNextStep = () => {
    if (currentStep < steps.length) {
      setCurrentStep(currentStep + 1);
    }
  };

  const handlePrevStep = () => {
    if (currentStep > 1) {
      setCurrentStep(currentStep - 1);
    }
  };

  const handleConnectToGroup = () => {
    if (!selectedGroup || !memberId) {
      alert('Please select a group and enter your member ID');
      return;
    }
    
    setIsConnecting(true);
    // Simulate connecting to group
    setTimeout(() => {
      setIsConnecting(false);
      handleNextStep();
    }, 1500);
  };

  const handleConfirmPayment = () => {
    setIsProcessing(true);
    // Simulate payment processing
    setTimeout(() => {
      setIsProcessing(false);
      handleNextStep();
    }, 3000);
  };

  const renderStepContent = () => {
    switch (currentStep) {
      case 1:
        return (
          <div className="space-y-8">
            <InfoCard
              type="security"
              title="Group Access Required"
              description="Please select your savings group and enter your member ID to access your loan details."
            />
            
            {/* Group Selection Section */}
            <div className="space-y-4">
              <h3 className="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <Building className="w-5 h-5 text-blue-600" />
                Select Your Savings Group
              </h3>
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                {groups.map((group) => (
                  <Card
                    key={group.id}
                    className={`
                      cursor-pointer transition-all duration-200 border-2
                      ${selectedGroup === group.id 
                        ? 'border-blue-500 bg-blue-50' 
                        : 'border-gray-200 hover:border-blue-300'
                      }
                    `}
                    onClick={() => setSelectedGroup(group.id)}
                  >
                    <div className="space-y-3">
                      <div className="flex items-center justify-between">
                        <div className="flex items-center gap-2">
                          <Users className="w-4 h-4 text-blue-600" />
                          <span className="font-semibold">{group.name}</span>
                        </div>
                        {selectedGroup === group.id && (
                          <UserCheck className="w-5 h-5 text-green-600" />
                        )}
                      </div>
                      
                      <div className="space-y-1 text-sm">
                        <div className="flex justify-between text-gray-600">
                          <span>Members:</span>
                          <span className="font-medium">{group.members}</span>
                        </div>
                        <div className="flex justify-between text-gray-600">
                          <span>Total Savings:</span>
                          <span className="font-medium">{group.totalSavings.toLocaleString()} ₳</span>
                        </div>
                      </div>
                    </div>
                  </Card>
                ))}
              </div>
            </div>

            {/* Member ID Input */}
            <div className="space-y-4">
              <h3 className="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <UserCheck className="w-5 h-5 text-blue-600" />
                Member Identification
              </h3>
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <Input
                  label="Member ID"
                  placeholder="Enter your member ID (e.g., MEM-001)"
                  value={memberId}
                  onChange={(e) => setMemberId(e.target.value)}
                  leftIcon={<UserCheck className="w-4 h-4" />}
                />
                <Input
                  label="Verification Code"
                  placeholder="Enter group verification code"
                  type="password"
                  leftIcon={<Shield className="w-4 h-4" />}
                  helperText="Provided by your group administrator"
                />
              </div>
            </div>

            {/* Action Button */}
            <div className="pt-4 border-t border-gray-200">
              <Button
                variant="group"
                fullWidth
                size="lg"
                onClick={handleConnectToGroup}
                isLoading={isConnecting}
                rightIcon={<ArrowRight className="w-5 h-5" />}
                disabled={!selectedGroup || !memberId}
              >
                Connect to Group
              </Button>
            </div>
          </div>
        );

      case 2:
        return (
          <div className="space-y-8">
            {/* Member & Group Info */}
            <Card>
              <div className="space-y-4">
                <div className="flex items-center justify-between">
                  <div>
                    <h3 className="text-lg font-semibold text-gray-900">Member Information</h3>
                    <p className="text-sm text-gray-500">Verified from group records</p>
                  </div>
                  <div className="p-2 bg-green-100 rounded-lg">
                    <UserCheck className="w-6 h-6 text-green-600" />
                  </div>
                </div>
                
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div className="space-y-2">
                    <p className="text-sm text-gray-500">Group Name</p>
                    <p className="font-semibold text-gray-900">{loanData.groupName}</p>
                  </div>
                  <div className="space-y-2">
                    <p className="text-sm text-gray-500">Member ID</p>
                    <p className="font-semibold text-gray-900">{loanData.memberId}</p>
                  </div>
                  <div className="space-y-2">
                    <p className="text-sm text-gray-500">Member Name</p>
                    <p className="font-semibold text-gray-900">{loanData.memberName}</p>
                  </div>
                  <div className="space-y-2">
                    <p className="text-sm text-gray-500">Group Balance</p>
                    <p className="font-semibold text-blue-600">{loanData.groupBalance.toFixed(2)} ₳</p>
                  </div>
                </div>
              </div>
            </Card>

            {/* Loan Details Form */}
            <div className="space-y-6">
              <h3 className="text-lg font-semibold text-gray-900">Loan Details</h3>
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <Input
                  label="Loan ID"
                  placeholder="Enter loan ID"
                  defaultValue={loanData.loanId}
                  leftIcon={<FileText className="w-4 h-4" />}
                  readOnly
                />
                <Input
                  label="Group ID"
                  placeholder="Group identifier"
                  defaultValue={loanData.groupId}
                  leftIcon={<Building className="w-4 h-4" />}
                  readOnly
                />
              </div>
              
              <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div className="space-y-2">
                  <p className="text-sm font-medium text-gray-700 flex items-center gap-2">
                    <Calendar className="w-4 h-4" />
                    Due Date
                  </p>
                  <p className="text-lg font-semibold text-gray-900">{loanData.dueDate}</p>
                </div>
                <div className="space-y-2">
                  <p className="text-sm font-medium text-gray-700 flex items-center gap-2">
                    <Clock className="w-4 h-4" />
                    Days Remaining
                  </p>
                  <p className="text-lg font-semibold text-green-600">6 days</p>
                </div>
                <div className="space-y-2">
                  <p className="text-sm font-medium text-gray-700 flex items-center gap-2">
                    <Percent className="w-4 h-4" />
                    Interest Rate
                  </p>
                  <p className="text-lg font-semibold text-gray-900">{loanData.interestRate}</p>
                </div>
              </div>

              <InfoCard
                type="info"
                title="Group Loan Information"
                description="Your loan is secured against the group's pooled funds. All repayments go back into the group savings pool."
              />
            </div>
          </div>
        );

      case 3:
        return (
          <div className="space-y-8">
            {/* Summary Component */}
            <RepaymentSummary
              loanId={loanData.loanId}
              principal={loanData.principal}
              interest={loanData.interest}
              lateFee={loanData.lateFee}
              protocolFee={loanData.protocolFee}
              totalDue={loanData.totalDue}
              dueDate={loanData.dueDate}
              currentDate={loanData.currentDate}
              isOverdue={loanData.isOverdue}
              daysLate={loanData.daysLate}
            />

            {/* Group Impact Section */}
            <Card>
              <h4 className="text-lg font-semibold text-gray-900 mb-4">Group Impact Analysis</h4>
              <div className="space-y-4">
                <div className="p-4 bg-blue-50 rounded-lg">
                  <div className="flex justify-between items-center">
                    <div>
                      <p className="font-medium text-gray-900">Current Group Balance</p>
                      <p className="text-sm text-gray-600">Before repayment</p>
                    </div>
                    <p className="text-xl font-bold text-blue-700">{loanData.groupBalance.toFixed(2)} ₳</p>
                  </div>
                </div>
                
                <div className="p-4 bg-green-50 rounded-lg">
                  <div className="flex justify-between items-center">
                    <div>
                      <p className="font-medium text-gray-900">After Repayment Balance</p>
                      <p className="text-sm text-gray-600">Group balance after repayment</p>
                    </div>
                    <p className="text-xl font-bold text-green-700">
                      {(loanData.groupBalance + loanData.totalDue).toFixed(2)} ₳
                    </p>
                  </div>
                </div>

                <div className="p-4 bg-purple-50 rounded-lg">
                  <div className="space-y-2">
                    <p className="font-medium text-gray-900">Distribution Breakdown</p>
                    <div className="space-y-1 text-sm">
                      <div className="flex justify-between">
                        <span className="text-gray-600">Principal to Group Pool</span>
                        <span className="font-medium">{loanData.principal.toFixed(2)} ₳</span>
                      </div>
                      <div className="flex justify-between">
                        <span className="text-gray-600">Interest to Group Members</span>
                        <span className="font-medium">{loanData.interest.toFixed(2)} ₳</span>
                      </div>
                      <div className="flex justify-between">
                        <span className="text-gray-600">Protocol Maintenance Fee</span>
                        <span className="font-medium">{loanData.protocolFee.toFixed(2)} ₳</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </Card>

            {/* Warning if late */}
            {loanData.isOverdue && (
              <InfoCard
                type="warning"
                title="Overdue Payment Notice"
                description={`Your payment is ${loanData.daysLate} days overdue. This affects the group's available funds and may impact other members.`}
                icon={<AlertCircle className="w-6 h-6 text-yellow-500" />}
              />
            )}
          </div>
        );

      case 4:
        return (
          <div className="space-y-8">
            <div className="text-center">
              <div className="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <CheckCircle className="w-12 h-12 text-green-600" />
              </div>
              
              <h3 className="text-3xl font-bold text-gray-900 mb-3">
                Payment Successful!
              </h3>
              
              <p className="text-gray-600 text-lg mb-8 max-w-2xl mx-auto">
                Your loan repayment has been recorded in the group ledger and funds have been distributed according to the smart contract rules.
              </p>
              
              <Card className="max-w-xl mx-auto mb-8">
                <div className="space-y-4">
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <p className="text-sm text-gray-500">Transaction ID</p>
                      <p className="font-mono text-sm truncate">d4e5f68790a1b2c3d4e5f6...</p>
                    </div>
                    <div>
                      <p className="text-sm text-gray-500">Block Height</p>
                      <p className="font-semibold">1,234,567</p>
                    </div>
                    <div>
                      <p className="text-sm text-gray-500">Group Ledger Updated</p>
                      <p className="font-semibold">Yes ✓</p>
                    </div>
                    <div>
                      <p className="text-sm text-gray-500">Timestamp</p>
                      <p className="font-semibold">Just now</p>
                    </div>
                  </div>
                  
                  <div className="p-3 bg-blue-50 rounded-lg">
                    <p className="text-sm text-blue-700">
                      The group balance has been updated. All members can view the updated ledger.
                    </p>
                  </div>
                </div>
              </Card>

              {/* Action Buttons */}
              <div className="flex flex-col sm:flex-row gap-4 justify-center">
                <Button
                  variant="outline"
                  size="lg"
                  leftIcon={<Download className="w-5 h-5" />}
                >
                  Download Receipt
                </Button>
                <Button
                  variant="group"
                  size="lg"
                  leftIcon={<Mail className="w-5 h-5" />}
                >
                  Email Confirmation
                </Button>
                <Button
                  variant="secondary"
                  size="lg"
                  leftIcon={<Users className="w-5 h-5" />}
                  onClick={() => navigate(`/group/${loanData.groupId}/ledger`)}
                >
                  View Group Ledger
                </Button>
              </div>
            </div>
          </div>
        );

      default:
        return null;
    }
  };

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header Section */}
      <div className="bg-white border-b border-gray-200">
        <div className="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
          <div className="text-center">
            <h1 className="text-4xl font-bold text-gray-900 mb-3">
              Group Loan Repayment Portal
            </h1>
            <p className="text-xl text-gray-600 mb-6 max-w-3xl mx-auto">
              Repay your group savings loans securely through collective smart contracts
            </p>
            
            {/* Quick Stats */}
            <div className="grid grid-cols-1 md:grid-cols-4 gap-4 max-w-3xl mx-auto mb-8">
              <div className="p-4 bg-blue-50 rounded-lg">
                <div className="text-2xl font-bold text-blue-700">{groups.length}</div>
                <div className="text-sm text-gray-600">Active Groups</div>
              </div>
              <div className="p-4 bg-green-50 rounded-lg">
                <div className="text-2xl font-bold text-green-700">4,250</div>
                <div className="text-sm text-gray-600">Total Members</div>
              </div>
              <div className="p-4 bg-purple-50 rounded-lg">
                <div className="text-2xl font-bold text-purple-700">425,000 ₳</div>
                <div className="text-sm text-gray-600">Total Group Savings</div>
              </div>
              <div className="p-4 bg-orange-50 rounded-lg">
                <div className="text-2xl font-bold text-orange-700">98.7%</div>
                <div className="text-sm text-gray-600">Repayment Rate</div>
              </div>
            </div>

            <Button
              variant="group"
              size="lg"
              rightIcon={<ArrowRight className="w-5 h-5" />}
              onClick={() => {
                document.getElementById('repayment-section')?.scrollIntoView({ 
                  behavior: 'smooth' 
                });
              }}
              className="mx-auto"
            >
              Start Repayment Process
            </Button>
          </div>
        </div>
      </div>

      {/* Main Repayment Section */}
      <div id="repayment-section" className="max-w-6xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
        <div className="mb-8">
          <h2 className="text-3xl font-bold text-gray-900 mb-3 text-center">
            Loan Repayment Process
          </h2>
          <p className="text-gray-600 text-center max-w-2xl mx-auto">
            Follow these steps to repay your group loan. All transactions are secured by smart contracts.
          </p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Left: Steps & Form */}
          <div className="lg:col-span-2">
            <Card className="mb-8" padding="lg" shadow="lg">
              <StepIndicator
                steps={steps}
                currentStep={currentStep}
                completedSteps={steps.slice(0, currentStep - 1).map(s => s.id)}
              />
            </Card>

            <Card padding="lg" shadow="lg" className="border border-gray-200">
              <div className="space-y-8">
                <div>
                  <h3 className="text-2xl font-bold text-gray-900 mb-2">
                    {steps[currentStep - 1].title}
                  </h3>
                  <p className="text-gray-600">
                    {steps[currentStep - 1].description}
                  </p>
                </div>

                {renderStepContent()}
              </div>

              {/* Navigation Buttons */}
              {currentStep < steps.length && currentStep !== 4 && (
                <div className="flex justify-between mt-8 pt-8 border-t border-gray-200">
                  <div>
                    {currentStep > 1 && (
                      <Button
                        variant="ghost"
                        size="lg"
                        onClick={handlePrevStep}
                        leftIcon={<ArrowRight className="w-5 h-5 rotate-180" />}
                      >
                        Previous Step
                      </Button>
                    )}
                  </div>
                  
                  <div>
                    {currentStep === 1 ? (
                      <Button
                        variant="group"
                        size="lg"
                        onClick={handleConnectToGroup}
                        isLoading={isConnecting}
                        rightIcon={<ArrowRight className="w-5 h-5" />}
                        disabled={!selectedGroup || !memberId}
                      >
                        Connect to Group
                      </Button>
                    ) : currentStep === 2 ? (
                      <Button
                        variant="group"
                        size="lg"
                        onClick={handleNextStep}
                        rightIcon={<ArrowRight className="w-5 h-5" />}
                      >
                        Verify & Continue
                      </Button>
                    ) : currentStep === 3 ? (
                      <Button
                        variant="group"
                        size="lg"
                        onClick={handleConfirmPayment}
                        isLoading={isProcessing}
                        rightIcon={<Send className="w-5 h-5" />}
                      >
                        Confirm & Pay {loanData.totalDue.toFixed(2)} ₳
                      </Button>
                    ) : null}
                  </div>
                </div>
              )}
            </Card>
          </div>

          {/* Right: Information Panel */}
          <div className="space-y-6">
            <PlatformBenefits 
              benefits={[
                {
                  icon: <Users className="w-6 h-6" />,
                  title: 'Collective Security',
                  description: 'Loans are secured by the entire group\'s pooled savings, reducing individual risk.',
                },
                {
                  icon: <Shield className="w-6 h-6" />,
                  title: 'Smart Contract Protection',
                  description: 'Automated, transparent repayment rules enforced by blockchain technology.',
                },
                {
                  icon: <Building className="w-6 h-6" />,
                  title: 'Group Governance',
                  description: 'Democratic decision-making with transparent voting on group rules.',
                },
                {
                  icon: <Percent className="w-6 h-6" />,
                  title: 'Better Rates',
                  description: 'Group pooling enables better interest rates for both borrowers and lenders.',
                },
              ]}
            />
            
            <InfoCard
              type="warning"
              title="Important Notice"
              description="Repayment amounts are calculated automatically based on the group's smart contract rules. Late payments may affect your group standing."
            />
            
            <InfoCard
              type="success"
              title="Smart Contract Verified"
              description="All group contracts are audited and verified on the Cardano blockchain for maximum security."
            />

            <Card padding="md" className="bg-gradient-to-r from-blue-50 to-blue-100">
              <h4 className="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <Users className="w-5 h-5" />
                Need Group Assistance?
              </h4>
              <div className="space-y-2">
                <Button variant="ghost" fullWidth className="justify-start text-left">
                  Contact Group Admin
                </Button>
                <Button variant="ghost" fullWidth className="justify-start text-left">
                  View Group Rules
                </Button>
                <Button variant="ghost" fullWidth className="justify-start text-left">
                  Access Group Forum
                </Button>
              </div>
            </Card>
          </div>
        </div>

        {/* Footer Stats */}
        <div className="mt-16 pt-8 border-t border-gray-200">
          <div className="text-center">
            <h3 className="text-xl font-semibold text-gray-900 mb-6">
              Trusted by Groups Worldwide
            </h3>
            <div className="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-3xl mx-auto">
              <div>
                <div className="text-3xl font-bold text-blue-600">500+</div>
                <div className="text-gray-600">Groups Created</div>
              </div>
              <div>
                <div className="text-3xl font-bold text-green-600">25,000+</div>
                <div className="text-gray-600">Loans Issued</div>
              </div>
              <div>
                <div className="text-3xl font-bold text-purple-600">99.2%</div>
                <div className="text-gray-600">Satisfaction Rate</div>
              </div>
              <div>
                <div className="text-3xl font-bold text-orange-600">₳ 5M+</div>
                <div className="text-gray-600">Total Value</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default RepayPage;