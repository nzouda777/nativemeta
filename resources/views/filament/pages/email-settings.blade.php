<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}
    </form>

    @if($this->testResult)
        <div class="mb-6 p-4 rounded-lg {{ $this->testingEmail ? 'bg-blue-50 border-blue-200' : (str_contains($this->testResult, 'succès') ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200') }}">
            <div class="flex">
                <div class="flex-shrink-0">
                    @if($this->testingEmail)
                        <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0h12c6.627 0 12 5.373 12v12c0 .627-.5 1.373-1 1.373H4c-.627 0-1-.5-1-1.373V4c0-.627.5-1.373 1-1.373h12C18.627 2.627 4 18 4v12c18 6.627 17.5 7.373 17 12z"></path>
                        </svg>
                    @elseif(str_contains($this->testResult, 'succès'))
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @else
                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    @endif
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium {{ str_contains($this->testResult, 'succès') ? 'text-green-800' : 'text-red-800' }}">
                        {{ $this->testResult }}
                    </p>
                    @if($this->testingEmail)
                        <p class="text-sm text-blue-600 mt-1">Envoi en cours...</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="space-y-6">
        <!-- Informations sur le service actuel -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Service actuel</h3>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    @switch($this->data['mail_mailer'])
                        @case('mailpit')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a22 22 0 003.5.5L21 8M5 12h14M5 12h14M5 12h14"></path>
                                </svg>
                                Mailpit (Dev)
                            </span>
                        @case('smtp')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h8M12 12h8"></path>
                                </svg>
                                SMTP (Prod)
                            </span>
                        @case('ses')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a22 22 0 003.5.5L21 8M5 12h14M5 12h14M5 12h14"></path>
                                </svg>
                                Amazon SES (Prod)
                            </span>
                    @endswitch
                </div>
                <div class="text-sm text-gray-600">
                    @switch($this->data['mail_mailer'])
                        @case('mailpit')
                            Service de développement local
                        @case('smtp')
                            Serveur SMTP professionnel
                        @case('ses')
                            Service email Amazon
                    @endswitch
                </div>
            </div>
        </div>

        <!-- Lien vers Mailpit si en développement -->
        @if($this->data['mail_mailer'] === 'mailpit')
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a22 22 0 003.5.5L21 8M5 12h14M5 12h14M5 12h14"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Mailpit est actif</p>
                        <p class="text-sm text-blue-600">Consultez les emails envoyés sur <a href="http://localhost:8025" target="_blank" class="underline font-medium hover:text-blue-800">http://localhost:8025</a></p>
                    </div>
                </div>
            @endif
    </div>
</x-filament-panels::page>
