<div class="space-y-4 p-2">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Personal Documents --}}
        <div class="space-y-3">
            <h3 class="font-semibold text-gray-900 border-b pb-2">Personal Documents</h3>
            @include('filament.partials.document-item', [
                'label' => 'CV/Resume',
                'path' => $scientist->cv_path,
                'icon' => '📄',
                'color' => 'blue'
            ])
            @include('filament.partials.document-item', [
                'label' => 'HCPC Registration',
                'path' => $scientist->hcpc_registration_path,
                'icon' => '🏥',
                'color' => 'green'
            ])
        </div>

        {{-- Compliance Documents --}}
        <div class="space-y-3">
            <h3 class="font-semibold text-gray-900 border-b pb-2">Compliance</h3>
            @include('filament.partials.document-item', [
                'label' => 'DBS Check',
                'path' => $scientist->enhanced_dbs_path,
                'icon' => '🔒',
                'color' => 'orange'
            ])
            @include('filament.partials.document-item', [
                'label' => 'Immunisation Record',
                'path' => $scientist->immunisation_record_path,
                'icon' => '💉',
                'color' => 'purple'
            ])
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Certificates --}}
        <div class="space-y-3">
            <h3 class="font-semibold text-gray-900 border-b pb-2">Certificates</h3>
            @include('filament.partials.document-item', [
                'label' => 'BLS Certificate',
                'path' => $scientist->bls_certificate_path,
                'icon' => '🫀',
                'color' => 'red'
            ])
            @include('filament.partials.document-item', [
                'label' => 'Health & Safety',
                'path' => $scientist->health_safety_certification_path,
                'icon' => '🛡️',
                'color' => 'yellow'
            ])
        </div>

        {{-- Insurance & Contract --}}
        <div class="space-y-3">
            <h3 class="font-semibold text-gray-900 border-b pb-2">Legal</h3>
            @include('filament.partials.document-item', [
                'label' => 'Insurance',
                'path' => $scientist->professional_indemnity_insurance_path,
                'icon' => '📊',
                'color' => 'indigo'
            ])
            @include('filament.partials.document-item', [
                'label' => 'Contract',
                'path' => $scientist->signed_contract_path,
                'icon' => '📝',
                'color' => 'green'
            ])
        </div>
    </div>
</div>
