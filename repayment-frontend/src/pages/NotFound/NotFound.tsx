import React from 'react';
import { Home, Search, ArrowRight, AlertCircle } from 'lucide-react';

const NotFound: React.FC = () => {
  return (
    <div className="min-h-screen bg-gradient-to-b from-blue-50 to-white flex items-center justify-center p-4">
      <div className="max-w-md w-full text-center">
        {/* Icon Container */}
        <div className="relative mb-8">
          <div className="w-32 h-32 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto shadow-lg">
            <AlertCircle className="w-16 h-16 text-white" />
          </div>          
        </div>

        {/* Title */}
        <h1 className="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
          Page Not Found
        </h1>
        
        {/* Description */}
        <p className="text-gray-600 mb-8 text-lg">
          We couldn't find the page you're looking for. It might have been moved, deleted, or never existed.
        </p>

        {/* Error Code */}
        <div className="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-8">
          <div className="flex items-center justify-center space-x-2">
            <Search className="w-5 h-5 text-blue-500" />
            <span className="text-sm font-medium text-blue-700">
              Error 404: Resource not found
            </span>
          </div>
        </div>

        {/* CTA Buttons */}
        <div className="flex flex-col sm:flex-row gap-4 justify-center">
          <button
            onClick={() => window.history.back()}
            className="group flex items-center justify-center gap-2 px-6 py-3 border border-blue-500 text-blue-600 font-medium rounded-lg hover:bg-blue-50 transition-all duration-200 hover:shadow-md"
          >
            <ArrowRight className="w-4 h-4 rotate-180 group-hover:-translate-x-1 transition-transform" />
            Go Back
          </button>
          
          <button
            onClick={() => (window.location.href = '/')}
            className="group flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5"
          >
            <Home className="w-4 h-4 group-hover:scale-110 transition-transform" />
            Back to Homepage
            <ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
          </button>
        </div>

        {/* Additional Help */}
        <div className="mt-12 pt-8 border-t border-gray-200">
          <p className="text-gray-500 text-sm mb-4">
            Still can't find what you're looking for?
          </p>
          <div className="flex flex-wrap justify-center gap-3">
            <a
              href="/contact"
              className="text-blue-600 hover:text-blue-700 font-medium text-sm hover:underline"
            >
              Contact Support
            </a>
            <span className="text-gray-300">•</span>
            <a
              href="/sitemap"
              className="text-blue-600 hover:text-blue-700 font-medium text-sm hover:underline"
            >
              View Sitemap
            </a>
            <span className="text-gray-300">•</span>
            <a
              href="/search"
              className="text-blue-600 hover:text-blue-700 font-medium text-sm hover:underline"
            >
              Search Site
            </a>
          </div>
        </div>
      </div>
    </div>
  );
};

export default NotFound;