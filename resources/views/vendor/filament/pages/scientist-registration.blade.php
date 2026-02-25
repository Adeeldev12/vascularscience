<x-filament-panels::page>
    <div class="fi-page-scientist-registration bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="w-20 h-20 bg-primary-600 rounded-full flex items-center justify-center shadow-lg">
                        <x-heroicon-o-user-plus class="w-10 h-10 text-white" />
                    </div>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-3">Join VascularScience</h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Complete your registration to start your journey with us as a healthcare professional</p>
            </div>

            <!-- Registration Form Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
                <form wire:submit="register" class="space-y-1">
                    {{ $this->form }}

                    <!-- Submit Button -->
                    <div class="bg-gray-50 px-8 py-6 border-t border-gray-200">
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-primary-600 to-primary-700 text-white py-4 px-6 rounded-xl font-semibold text-lg hover:from-primary-700 hover:to-primary-800 focus:ring-4 focus:ring-primary-200 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <div class="flex items-center justify-center">
                                <span>Complete Registration</span>
                                <x-heroicon-o-arrow-right class="w-5 h-5 ml-3" />
                            </div>
                        </button>

                        <p class="text-center text-gray-600 mt-4 text-sm">
                            Already have an account?
                            <a href="{{ url('/admin/scientist-login') }}"
                               class="text-primary-600 hover:text-primary-700 font-semibold transition-colors">
                                Sign in here
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Security Notice -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-500 flex items-center justify-center">
                    <x-heroicon-o-lock-closed class="w-4 h-4 mr-2" />
                    Your information is secure and encrypted
                </p>
            </div>
        </div>
    </div>

    <style>
    .fi-page-scientist-registration .fi-section {
        border: none !important;
        border-radius: 0.75rem;
        padding: 2rem !important;
        margin-bottom: 0 !important;
        border-bottom: 1px solid #e5e7eb !important;
    }

    .fi-page-scientist-registration .fi-section:last-of-type {
        border-bottom: none !important;
    }

    .fi-page-scientist-registration .fi-section-header {
        border-bottom: none !important;
        padding-bottom: 1rem !important;
        margin-bottom: 1.5rem !important;
    }

    .fi-page-scientist-registration .fi-section-header h3 {
        font-size: 1.5rem !important;
        font-weight: 700 !important;
        color: #1f2937 !important;
    }
    </style>
</x-filament-panels::page>
