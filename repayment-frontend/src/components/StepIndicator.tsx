import React from 'react';
import { Check } from 'lucide-react';

interface Step {
  id: number;
  title: string;
  description?: string;
}

interface StepIndicatorProps {
  steps: Step[];
  currentStep: number;
  completedSteps?: number[];
  className?: string;
}

const StepIndicator: React.FC<StepIndicatorProps> = ({
  steps,
  currentStep,
  completedSteps = [],
  className = '',
}) => {
  return (
    <div className={`w-full ${className}`}>
      <div className="flex justify-between mb-4">
        {steps.map((step, index) => {
          const isActive = step.id === currentStep;
          const isCompleted = completedSteps.includes(step.id);
          const isLast = index === steps.length - 1;

          return (
            <div key={step.id} className={`flex flex-col items-center ${!isLast ? 'flex-1' : ''}`}>
              <div className="flex items-center w-full">
                {/* Step Circle */}
                <div
                  className={`
                    w-10 h-10 rounded-full flex items-center justify-center
                    border-2 transition-all duration-300
                    ${isActive ? 'border-blue-600 bg-blue-600 text-white' : ''}
                    ${isCompleted ? 'border-green-500 bg-green-500 text-white' : ''}
                    ${!isActive && !isCompleted ? 'border-gray-300 bg-white text-gray-400' : ''}
                  `}
                >
                  {isCompleted ? (
                    <Check className="w-5 h-5" />
                  ) : (
                    <span className="font-semibold">{step.id}</span>
                  )}
                </div>

                {/* Connector Line */}
                {!isLast && (
                  <div
                    className={`
                      flex-1 h-0.5 mx-2
                      ${completedSteps.includes(steps[index + 1].id) || isCompleted ? 'bg-green-500' : 'bg-gray-300'}
                    `}
                  />
                )}
              </div>

              {/* Step Title & Description */}
              <div className="mt-2 text-center">
                <h3 className={`
                  text-sm font-medium
                  ${isActive ? 'text-blue-600' : isCompleted ? 'text-green-600' : 'text-gray-500'}
                `}>
                  {step.title}
                </h3>
                {step.description && (
                  <p className="text-xs text-gray-500 mt-1">{step.description}</p>
                )}
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
};

export default StepIndicator;