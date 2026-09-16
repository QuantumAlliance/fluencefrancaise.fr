<template>
  <div class="">


    <!-- Settings Tabs -->
    <div class="bg-white rounded-lg shadow mb-8">
      <div class="flex border-b border-gray-200 overflow-x-auto">
        <button
          v-for="tab in tabs"
          :key="tab"
          @click="handleTabChange(tab)"
          :class="activeTab === tab ? 'border-b-2 border-[#0055A4] text-[#0055A4]' : 'text-gray-600 hover:text-gray-800'"
          class="px-6 py-4 font-medium transition-colors whitespace-nowrap"
        >
          {{ tab }}
        </button>
      </div>

      <!-- General Settings -->
      <div v-if="activeTab === 'General'" class="p-6 space-y-6">
        <div class="grid grid-cols-2 gap-6">
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Site Name</label>
            <input
              v-model="settings.siteName"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
          </div>
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Site URL</label>
            <input
              v-model="settings.siteUrl"
              type="url"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
          </div>
        </div>

        <div>
           <label class="block text-gray-700 text-sm font-semibold mb-2">Site Logo</label>
           <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
              <div v-if="settings.siteLogo" class="w-16 h-16 bg-white rounded-lg flex items-center justify-center overflow-hidden border border-gray-200 shadow-sm">
                 <img :src="`/storage/${settings.siteLogo}`" alt="Site Logo" class="w-full h-full object-contain" />
              </div>
              <div v-else class="w-16 h-16 bg-white rounded-lg flex items-center justify-center border border-gray-200 text-gray-400 shadow-sm">
                 <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
              </div>
              
              <div class="flex-1">
                 <input 
                   type="file" 
                   ref="logoInput"
                   @change="handleLogoUpload" 
                   accept="image/*"
                   :disabled="uploadingLogo"
                   class="block w-full text-sm text-gray-500
                     file:mr-4 file:py-2 file:px-4
                     file:rounded-full file:border-0
                     file:text-sm file:font-semibold
                     file:bg-[#0055A4]/10 file:text-[#0055A4]
                     hover:file:bg-[#0055A4]/20
                     cursor-pointer"
                 />
                 <p v-if="uploadingLogo" class="text-xs text-[#0055A4] mt-1 font-medium animate-pulse">Uploading...</p>
                 <p v-else class="text-xs text-gray-500 mt-1">Recommended size: 512x512px. Max size: 2MB.</p>
              </div>
           </div>
        </div>

        <div>
          <label class="block text-gray-700 text-sm font-semibold mb-2">Site Tagline</label>
          <textarea
            v-model="settings.siteTagline"
            rows="4"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
          />
        </div>

        <div>
          <label class="block text-gray-700 text-sm font-semibold mb-2">Robots Meta Tag</label>
          <select
            v-model="settings.robots"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
          >
            <option value="index, follow">Index, Follow</option>
            <option value="noindex, follow">Noindex, Follow</option>
            <option value="index, nofollow">Index, Nofollow</option>
            <option value="noindex, nofollow">Noindex, Nofollow</option>
          </select>
          <p class="text-xs text-gray-500 mt-1">Controls how search engines index your homepage and pages without specific robots settings.</p>
        </div>

        <div>
          <label class="block text-gray-700 text-sm font-semibold mb-2">Homepage Schema Type (Rich Snippets)</label>
          <select
            v-model="settings.homepageSchemaType"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
          >
            <option value="organization">Organization</option>
            <option value="webpage">WebPage</option>
            <option value="localbusiness">LocalBusiness</option>
            <option value="none">None</option>
          </select>
          <p class="text-xs text-gray-500 mt-1">Schema type for homepage rich snippets in Google search results. <code class="bg-gray-100 px-1 rounded">Organization</code> is recommended for business websites.</p>
        </div>

        <div class="grid grid-cols-3 gap-6">
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Default Currency</label>
            <select
              v-model="settings.currency"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            >
              <option value="USD">USD - US Dollar</option>
              <option value="EUR">EUR - Euro</option>
              <option value="GBP">GBP - British Pound</option>
              <option value="INR">INR - Indian Rupee</option>
            </select>
          </div>
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Language</label>
            <select
              v-model="settings.language"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            >
              <option value="en">English</option>
              <option value="fr">Français</option>
              <option value="es">Español</option>
              <option value="hi">हिन्दी</option>
            </select>
          </div>
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Timezone</label>
            <select
              v-model="settings.timezone"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            >
              <option value="UTC">UTC</option>
              <option value="EST">EST</option>
              <option value="PST">PST</option>
              <option value="IST">IST</option>
            </select>
          </div>
        </div>

        <div class="space-y-4">
          <Toggle v-model="settings.maintenanceMode" label="Maintenance Mode" />
          <Toggle v-model="settings.allowRegistration" label="Allow New Registrations" />
          <Toggle v-model="settings.emailVerification" label="Require Email Verification" />
        </div>

        <button
          @click="saveSettings"
          :disabled="loading"
          class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
        >
          <svg v-if="loading" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span>{{ loading ? 'Saving...' : 'Save Changes' }}</span>
        </button>
      </div>

      <!-- Email Settings -->
      <div v-if="activeTab === 'Email'" class="p-6 space-y-6">
        <!-- Connection Provider Selection -->
        <div class="mb-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-4">Connection Provider</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Google Workspace Card -->
            <div
              @click="emailProvider = 'google'"
              :class="emailProvider === 'google' ? 'border-blue-500 bg-blue-50/30' : 'border-gray-200 hover:border-blue-300'"
              class="relative flex flex-col p-5 border-2 rounded-xl cursor-pointer transition-all group"
            >
              <div class="flex items-center gap-4 mb-3">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                  <svg class="w-8 h-8" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                  </svg>
                </div>
                <div>
                  <h4 class="font-bold text-gray-800">Google Workspace</h4>
                  <p class="text-xs text-gray-500">Secure Gmail API Integration</p>
                </div>
                <div v-if="emailProvider === 'google'" class="ml-auto">
                  <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                  </div>
                </div>
              </div>
            </div>

            <!-- SMTP Card -->
            <div
              @click="emailProvider = 'smtp'"
              :class="emailProvider === 'smtp' ? 'border-[#0055A4] bg-[#0055A4]/5' : 'border-gray-200 hover:border-[#0055A4]/30'"
              class="relative flex flex-col p-5 border-2 rounded-xl cursor-pointer transition-all group"
            >
              <div class="flex items-center gap-4 mb-3">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                  <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                </div>
                <div>
                  <h4 class="font-bold text-gray-800">Other SMTP</h4>
                  <p class="text-xs text-gray-500">Custom SMTP Server (Outlook, etc.)</p>
                </div>
                <div v-if="emailProvider === 'smtp'" class="ml-auto">
                  <div class="w-6 h-6 bg-[#0055A4] rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- SMTP Settings (shown when SMTP is selected) -->
        <div v-if="emailProvider === 'smtp'" class="space-y-6">
          <h3 class="text-lg font-semibold text-gray-800">SMTP Settings</h3>
        <div class="grid grid-cols-2 gap-6">
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">SMTP Server</label>
            <input
              v-model="settings.smtpServer"
              type="text"
              placeholder="smtp.gmail.com"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
          </div>
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">SMTP Port</label>
            <input
              v-model="settings.smtpPort"
              type="number"
              placeholder="587"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
          </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Email Address</label>
            <input
              v-model="settings.emailAddress"
              type="email"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
          </div>
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Email Password</label>
            <input
              v-model="settings.emailPassword"
              type="password"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            />
          </div>
        </div>

        <div>
          <label class="block text-gray-700 text-sm font-semibold mb-2">From Name</label>
          <input
            v-model="settings.fromName"
            type="text"
            placeholder="FocusFrame"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
          />
        </div>

          <div class="pt-2 flex gap-3 items-center">
            <button
              @click="testSmtpConnection"
              :disabled="loading || testingSmtpConnection"
              class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
            >
              <svg v-if="testingSmtpConnection" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ testingSmtpConnection ? 'Testing...' : 'Test SMTP Connection' }}</span>
            </button>
            <button
              @click="testEmail"
              :disabled="loading"
              class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Send Test Email
            </button>
            <button
              @click="resetSmtpSettings"
              :disabled="loading"
              class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 ml-auto"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
              <span>Reset</span>
            </button>
          </div>
        </div>

        <!-- Google Workspace Settings (shown when Google is selected) -->
        <div v-if="emailProvider === 'google'" class="space-y-6">
          <h3 class="text-lg font-semibold text-gray-800">Gmail / Google Workspace API Settings</h3>
          
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            <p class="text-xs text-blue-700">
              Please check the documentation to create API keys on the Google Cloud Platform.
            </p>
          </div>

          <!-- Sender Settings for Google OAuth -->
          <div class="mb-6">
            <h4 class="text-md font-semibold text-gray-800 mb-4">Sender Settings</h4>
            <div class="grid grid-cols-2 gap-6">
              <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">From Email</label>
                <input
                  v-model="settings.googleFromEmail"
                  type="email"
                  placeholder="From Email"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                />
              </div>
              <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">From Name</label>
                <input
                  v-model="settings.googleFromName"
                  type="text"
                  placeholder="From Name"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                />
              </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-6">
              <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">Application Client ID</label>
                <input
                  v-model="settings.googleClientId"
                  type="text"
                  placeholder="xxxxx.apps.googleusercontent.com"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                />
                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                  </svg>
                  This input will be securely encrypted before saving.
                </p>
              </div>
              <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">Application Client Secret</label>
                <div class="relative">
                  <input
                    v-model="settings.googleClientSecret"
                    :type="showGoogleSecret ? 'text' : 'password'"
                    placeholder="GOCSPX-xxxxx"
                    class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                  />
                  <button
                    type="button"
                    @click="showGoogleSecret = !showGoogleSecret"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                  >
                    <svg v-if="showGoogleSecret" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0L3 3m3.29 3.29L3 3"/>
                    </svg>
                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                  </button>
                </div>
                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                  </svg>
                  This input will be securely encrypted before saving.
                </p>
              </div>
        </div>

            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Authorized Redirect URI</label>
              <input
                :value="googleRedirectUri"
                type="text"
                readonly
                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              />
              <p class="text-xs text-red-600 mt-1 font-medium">
                *** It is very important to put this URL in the Authorized Redirect URIs option in the Google Cloud Project.
              </p>
            </div>

            <div v-if="settings.googleAccessToken" class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm text-green-800">
                    <strong>Access Token:</strong> {{ settings.googleAccessToken.substring(0, 50) }}...
                  </p>
                  <p class="text-xs text-green-700 mt-1">Token is stored securely</p>
                </div>
        <button
                  @click="disconnectGoogle"
                  :disabled="loading"
                  class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
        >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                  <span>Disconnect</span>
        </button>
              </div>
            </div>

            <div class="pt-2 flex gap-3">
              <button
                @click="authenticateGoogle"
                :disabled="loading || authenticatingGoogle || !settings.googleClientId || !settings.googleClientSecret || settings.googleClientSecret === '••••••••••••'"
                class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                  <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                  <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                  <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span>Authenticate with Google & Get Access Token</span>
              </button>
              <button
                @click="testGoogleEmail"
                :disabled="loading || !settings.googleAccessToken"
                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Send Test Email</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Common Settings (shown for both providers) -->
        <div class="border-t border-gray-200 pt-6 mt-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-4">Email Settings</h3>
          <div class="space-y-4">
            <Toggle v-model="settings.emailNotifications" label="Enable Email Notifications" />
            <Toggle v-model="settings.emailTemplates" label="Use HTML Templates" />
          </div>
        </div>

        <!-- Save Button (shown for both providers) -->
        <div class="pt-2 flex gap-3">
        <button
          @click="saveSettings"
            :disabled="loading"
            class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
        >
          <svg v-if="loading" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span>{{ loading ? 'Saving...' : 'Save Changes' }}</span>
        </button>
        </div>
      </div>

      <!-- Stripe Settings -->
      <div v-if="activeTab === 'Stripe'" class="p-6 space-y-6">
        <div v-if="loadingStripe" class="text-center py-8">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#0055A4]"></div>
          <p class="text-gray-500 mt-2">Loading Stripe settings...</p>
        </div>

        <div v-else class="space-y-6">
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
          <p class="text-blue-800 text-sm">
            <strong>Note:</strong> Your Stripe keys are required for payment processing. Never share your secret key.
          </p>
        </div>

        <div>
          <label class="block text-gray-700 text-sm font-semibold mb-2">Stripe Publishable Key</label>
          <input
            v-model="settings.stripePublishableKey"
            type="text"
              placeholder="pk_live_... or pk_test_..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] font-mono text-sm"
              :disabled="loading"
          />
        </div>

        <div>
          <label class="block text-gray-700 text-sm font-semibold mb-2">Stripe Secret Key</label>
          <input
            v-model="settings.stripeSecretKey"
            type="password"
              placeholder="sk_live_... or sk_test_..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] font-mono text-sm"
              :disabled="loading"
          />
        </div>

          <div class="space-y-4">
            <div class="flex items-center gap-4">
              <span :class="!settings.stripeLiveMode ? 'text-gray-800 font-semibold' : 'text-gray-400'">Test Mode</span>
              <Toggle
                v-model="settings.stripeLiveMode"
                :disabled="loading"
              />
              <span :class="settings.stripeLiveMode ? 'text-green-600 font-semibold' : 'text-gray-400'">Live Mode</span>
            </div>
            <p class="text-xs text-gray-500">
              <span v-if="!settings.stripeLiveMode">Using test keys for development. No real charges will be made.</span>
              <span v-else class="text-orange-600 font-medium">Live payments enabled. Real charges will be processed!</span>
            </p>
        </div>

          <div class="pt-2">
        <button
          @click="saveSettings"
              :disabled="loading"
              class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
        >
              <svg v-if="loading" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ loading ? 'Saving...' : 'Save Changes' }}</span>
        </button>
          </div>
        </div>
      </div>

      <!-- Import Enrollment Data -->
      <div v-if="activeTab === 'Import'" class="p-6 space-y-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Import Enrollment Data</h3>
        <p class="text-gray-600 mb-6">Upload your JSON file to import student registrations.</p>
        <p class="text-gray-600 mb-6">All fields from the file will be automatically mapped to the correct database columns.</p>
        <p class="text-gray-600 mb-6">Please ensure your JSON follows the required structure.</p>

        <div
          class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-[#0055A4] transition-colors cursor-pointer"
          @click="$refs.importFile.click()"
          @dragover.prevent="dragOver = true"
          @dragleave.prevent="dragOver = false"
          @drop.prevent="handleDrop"
          :class="dragOver ? 'border-[#0055A4] bg-amber-50' : ''"
        >
          <input
            ref="importFile"
            type="file"
            accept=".sql"
            hidden
            @change="handleFileSelect"
          />
          <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
          </svg>
          <p class="text-gray-700 font-medium">Click to select JSON file or drag and drop</p>
          <p class="text-gray-500 text-sm mt-1">Supported format: Gravity Forms JSON export</p>
        </div>

        <div v-if="selectedFile" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
          <p class="text-blue-800 font-medium">Selected file: {{ selectedFile.name }}</p>
        </div>

        <div v-if="importProgress" class="bg-gray-50 rounded-lg p-4 mb-4">
          <div class="flex justify-between items-center mb-2">
            <p class="text-gray-700 font-medium">Import in progress…</p>
            <p class="text-gray-600">{{ importProgress.current }} / {{ importProgress.total }}</p>
          </div>
          <p class="text-gray-600 text-sm mb-3">Please wait while we read your file and validate the entries.</p>
          <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-[#0055A4] h-2 rounded-full transition-all" :style="{ width: (importProgress.current / importProgress.total * 100) + '%' }"></div>
          </div>
        </div>

        <div v-if="importMessage" :class="['p-4 rounded-lg whitespace-pre-line', importMessage.type === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : importMessage.type === 'warning' ? 'bg-yellow-50 border border-yellow-200 text-yellow-800' : 'bg-red-50 border border-red-200 text-red-800']">
          <div class="mb-3">{{ importMessage.text }}</div>
          <button
            v-if="importMessage.type === 'success' || importMessage.type === 'warning'"
            @click="router.push('/admin/enrollments')"
            class="mt-3 px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors text-sm"
          >
            View Enrollments →
          </button>
        </div>

        <button
          v-if="selectedFile"
          @click="importEnrollmentData"
          :disabled="loading"
          class="w-full px-6 py-3 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
        >
          <svg v-if="loading" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span>{{ loading ? 'Importing...' : 'Import Enrollment Data' }}</span>
        </button>

        <!-- Reset Import Data Section -->
        <div class="mt-12 pt-8 border-t border-gray-200">
          <h4 class="text-lg font-bold text-gray-800 mb-4">Reset Import Data</h4>
          <p class="text-gray-600 mb-6">Delete imported students and enrollments to start fresh. This will remove all students created from imports and their associated enrollments.</p>

          <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <p class="text-red-800 text-sm"><strong>Warning:</strong> This action cannot be undone. All imported student accounts and their enrollments will be permanently deleted.</p>
          </div>

          <div v-if="resetStats" class="bg-gray-50 rounded-lg p-4 mb-6">
            <p class="text-gray-700 font-medium mb-2">Current Import Data:</p>
            <ul class="text-gray-600 text-sm space-y-1">
              <li>Total Students (imported): {{ resetStats.studentCount }}</li>
              <li>Total Enrollments (from imports): {{ resetStats.enrollmentCount }}</li>
            </ul>
          </div>

          <div v-if="resetMessage" :class="['p-4 rounded-lg mb-4', resetMessage.type === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800']">
            {{ resetMessage.text }}
          </div>

          <button
            @click="showResetConfirm = true"
            :disabled="resetLoading || !resetStats || resetStats.studentCount === 0"
            class="px-6 py-2 bg-red-600 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors flex items-center gap-2"
          >
            <svg v-if="resetLoading" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ resetLoading ? 'Resetting...' : 'Reset All Import Data' }}</span>
          </button>
        </div>
      </div>

      <!-- Reset Confirmation Modal -->
      <div
        v-if="showResetConfirm"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        @click.self="showResetConfirm = false"
      >
        <div class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-4">
          <div class="border-b border-gray-200 p-6">
            <h3 class="text-xl font-bold text-gray-800">Confirm Reset</h3>
          </div>

          <div class="p-6">
            <p class="text-gray-700 mb-4">Are you sure you want to delete all imported data? This action <strong>cannot be undone</strong>.</p>
            <p class="text-gray-600 text-sm mb-4">
              This will permanently delete {{ resetStats?.studentCount || 0 }} student(s) and {{ resetStats?.enrollmentCount || 0 }} enrollment(s).
            </p>
          </div>

          <div class="border-t border-gray-200 p-6 bg-gray-50 flex justify-end gap-3">
            <button
              @click="showResetConfirm = false"
              :disabled="resetLoading"
              class="px-6 py-2 bg-gray-300 hover:bg-gray-400 disabled:opacity-50 disabled:cursor-not-allowed text-gray-800 rounded-lg font-medium transition-colors"
            >
              Cancel
            </button>
            <button
              @click="performReset"
              :disabled="resetLoading"
              class="px-6 py-2 bg-red-600 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors flex items-center gap-2"
            >
              <svg v-if="resetLoading" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ resetLoading ? 'Deleting...' : 'Delete All Data' }}</span>
            </button>
          </div>
        </div>
      </div>

      <!-- WP Passwords -->
      <div v-if="activeTab === 'WP Passwords'" class="p-6 space-y-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Import WordPress Passwords</h3>
        <p class="text-gray-600 mb-6">Upload your WordPress users SQL export to update passwords for existing users.</p>
        <p class="text-gray-600 mb-6">Passwords will be mapped to users by email address. Users can then login with their WordPress passwords.</p>
        <p class="text-gray-600 mb-6">Please ensure your SQL file contains INSERT statements with user_email and user_pass columns.</p>

        <div
          class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-[#0055A4] transition-colors cursor-pointer"
          @click="$refs.wpImportFile.click()"
          @dragover.prevent="wpDragOver = true"
          @dragleave.prevent="wpDragOver = false"
          @drop.prevent="handleWpDrop"
          :class="wpDragOver ? 'border-[#0055A4] bg-amber-50' : ''"
        >
          <input
            ref="wpImportFile"
            type="file"
            accept=".sql"
            hidden
            @change="handleWpFileSelect"
          />
          <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
          </svg>
          <p class="text-gray-700 font-medium">Click to select SQL file or drag and drop</p>
          <p class="text-gray-500 text-sm mt-1">Supported format: WordPress users SQL export</p>
        </div>

        <div v-if="selectedWpFile" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
          <p class="text-blue-800 font-medium">Selected file: {{ selectedWpFile.name }}</p>
        </div>

        <div v-if="wpImportProgress" class="bg-gray-50 rounded-lg p-4 mb-4">
          <div class="flex justify-between items-center mb-2">
            <p class="text-gray-700 font-medium">Import in progress…</p>
            <p class="text-gray-600">{{ wpImportProgress.current }} / {{ wpImportProgress.total }}</p>
          </div>
          <p class="text-gray-600 text-sm mb-3">Please wait while we update passwords for users.</p>
          <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-[#0055A4] h-2 rounded-full transition-all" :style="{ width: (wpImportProgress.current / wpImportProgress.total * 100) + '%' }"></div>
          </div>
        </div>

        <div v-if="wpImportMessage" :class="['p-4 rounded-lg whitespace-pre-line', wpImportMessage.type === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : wpImportMessage.type === 'warning' ? 'bg-yellow-50 border border-yellow-200 text-yellow-800' : 'bg-red-50 border border-red-200 text-red-800']">
          {{ wpImportMessage.text }}
        </div>

        <button
          v-if="selectedWpFile"
          @click="importWordPressPasswords"
          :disabled="wpLoading"
          class="w-full px-6 py-3 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
        >
          <svg v-if="wpLoading" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span>{{ wpLoading ? 'Importing...' : 'Import WP Passwords' }}</span>
        </button>
      </div>

      <!-- System Information -->
      <div v-if="activeTab === 'System'" class="p-6 space-y-6">
        <div class="grid grid-cols-2 gap-6 bg-gray-50 p-6 rounded-lg">
          <div>
            <p class="text-gray-600 text-sm font-medium">Platform Version</p>
            <p class="text-lg font-bold text-gray-800">{{ systemInfo.version }}</p>
          </div>
          <div>
            <p class="text-gray-600 text-sm font-medium">Build Date</p>
            <p class="text-lg font-bold text-gray-800">{{ systemInfo.buildDate }}</p>
          </div>
          <div>
            <p class="text-gray-600 text-sm font-medium">Database</p>
            <p class="text-lg font-bold text-gray-800">{{ systemInfo.database }}</p>
          </div>
          <div>
            <p class="text-gray-600 text-sm font-medium">Server Status</p>
            <p class="text-lg font-bold text-green-600">{{ systemInfo.status }}</p>
          </div>
        </div>

        <!-- Student Portal Maintenance Mode -->
        <div class="bg-gray-50 p-6 rounded-lg">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h3 class="text-lg font-bold text-gray-800">Student Portal Maintenance Mode</h3>
              <p class="text-sm text-gray-500">When enabled, students will see a maintenance message instead of the portal.</p>
            </div>
            <button
              @click="toggleMaintenanceMode"
              :disabled="savingMaintenance"
              class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#0055A4] focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
              :class="settings.studentPortalMaintenanceMode ? 'bg-red-600' : 'bg-gray-200'"
              role="switch"
              :aria-checked="settings.studentPortalMaintenanceMode"
            >
              <span
                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                :class="settings.studentPortalMaintenanceMode ? 'translate-x-5' : 'translate-x-0'"
              ></span>
            </button>
          </div>

          <div v-if="settings.studentPortalMaintenanceMode" class="space-y-4">
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Maintenance Message</label>
              <textarea
                v-model="settings.studentPortalMaintenanceMessage"
                rows="4"
                placeholder="Enter the message to display to students during maintenance..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              />
              <p class="text-xs text-gray-500 mt-1">This message will be shown to students when they try to access the portal.</p>
            </div>

            <button
              @click="saveMaintenanceSettings"
              :disabled="savingMaintenance"
              class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
            >
              <svg v-if="savingMaintenance" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ savingMaintenance ? 'Saving...' : 'Save Message' }}</span>
            </button>
          </div>

          <div v-if="settings.studentPortalMaintenanceMode" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
              </svg>
              <div>
                <p class="text-sm font-medium text-red-800">Maintenance Mode is Active</p>
                <p class="text-sm text-red-600">Students cannot access the portal right now. They will see the maintenance message.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Custom Scripts Tab -->
      <div v-if="activeTab === 'Custom Script'" class="p-6 space-y-6">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Custom Scripts</h3>
            <p class="text-sm text-gray-500">Add tracking scripts like Google Analytics, Facebook Pixel, etc. Choose where to place them (head, body, or footer).</p>
          </div>
          <button
            @click="addScript"
            class="px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors flex items-center gap-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Add Script</span>
          </button>
        </div>

        <div v-if="customScripts.length === 0" class="text-sm text-gray-500 italic py-8 text-center bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
          No scripts added yet. Click "Add Script" to add your first script.
        </div>

        <div v-else class="space-y-4">
          <div
            v-for="(script, index) in customScripts"
            :key="index"
            class="border border-gray-300 rounded-lg p-4 bg-gray-50"
          >
            <div class="flex items-start justify-between mb-3">
              <div class="flex items-center gap-3">
                <label class="block text-gray-700 text-sm font-semibold">Script {{ index + 1 }}</label>
                <select
                  v-model="customScripts[index].placement"
                  class="px-3 py-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] text-sm"
                >
                  <option value="head">Head</option>
                  <option value="body">Body</option>
                  <option value="footer">Footer</option>
                </select>
              </div>
              <button
                @click="removeScript(index)"
                class="text-red-600 hover:text-red-800 transition-colors"
                title="Remove script"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
              </button>
            </div>
            <textarea
              v-model="customScripts[index].code"
              rows="6"
              placeholder="Paste your script code here (e.g., Google Analytics, Facebook Pixel, etc.)"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] font-mono text-sm"
            />
          </div>
        </div>

        <button
          @click="saveSettings"
          :disabled="loading"
          class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
        >
          <svg v-if="loading" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span>{{ loading ? 'Saving...' : 'Save Changes' }}</span>
        </button>
      </div>

      <!-- Footer Settings -->
      <div v-if="activeTab === 'Footer'" class="p-6 space-y-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Footer Configuration</h3>
        
        <!-- Contact Info -->
        <div class="bg-gray-50 p-6 rounded-lg mb-6">
          <h4 class="text-md font-semibold text-gray-700 mb-4">Contact Information</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Contact Email</label>
              <input
                v-model="footerSettings.contact.email"
                type="email"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              />
            </div>
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Contact Phone</label>
              <input
                v-model="footerSettings.contact.phone"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              />
            </div>
            <div class="md:col-span-2">
              <label class="block text-gray-700 text-sm font-semibold mb-2">Address</label>
              <input
                v-model="footerSettings.contact.address"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              />
            </div>
          </div>
        </div>

        <!-- Social Media -->
        <div class="bg-gray-50 p-6 rounded-lg mb-6">
          <h4 class="text-md font-semibold text-gray-700 mb-4">Social Media Links</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div v-for="(url, platform) in footerSettings.social" :key="platform">
              <label class="block text-gray-700 text-sm font-semibold mb-2 capitalize">{{ platform }}</label>
              <input
                v-model="footerSettings.social[platform]"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              />
            </div>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="bg-gray-50 p-6 rounded-lg mb-6">
          <div class="flex justify-between items-center mb-4">
            <h4 class="text-md font-semibold text-gray-700">Quick Links</h4>
            <button @click="addQuickLink" class="text-sm text-[#0055A4] hover:text-[#003d7a] font-medium">+ Add Link</button>
          </div>
          <div class="space-y-4">
            <div v-for="(link, index) in footerSettings.quickLinks" :key="index" class="flex gap-4 items-start">
              <div class="flex-1">
                <input v-model="link.text" placeholder="Label" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" />
              </div>
              <div class="flex-1">
                <input v-model="link.url" placeholder="URL (e.g. /courses or #about)" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" />
              </div>
              <button @click="removeQuickLink(index)" class="text-red-500 hover:text-red-700 p-2" title="Delete">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
              </button>
            </div>
            <div v-if="footerSettings.quickLinks.length === 0" class="text-gray-500 text-sm italic">
              No links added.
            </div>
          </div>
        </div>

        <!-- Resources -->
        <div class="bg-gray-50 p-6 rounded-lg mb-6">
          <div class="flex justify-between items-center mb-4">
            <h4 class="text-md font-semibold text-gray-700">Resources</h4>
            <button @click="addResourceLink" class="text-sm text-[#0055A4] hover:text-[#003d7a] font-medium">+ Add Link</button>
          </div>
          <div class="space-y-4">
            <div v-for="(link, index) in footerSettings.resources" :key="index" class="flex gap-4 items-start">
              <div class="flex-1">
                <input v-model="link.text" placeholder="Label" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" />
              </div>
              <div class="flex-1">
                <input v-model="link.url" placeholder="URL" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" />
              </div>
              <button @click="removeResourceLink(index)" class="text-red-500 hover:text-red-700 p-2" title="Delete">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
              </button>
            </div>
            <div v-if="footerSettings.resources.length === 0" class="text-gray-500 text-sm italic">
              No links added.
            </div>
          </div>
        </div>

        <!-- Legal Links -->
        <div class="bg-gray-50 p-6 rounded-lg">
          <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-800">Legal</h3>
            <button @click="addLegalLink" class="text-sm text-[#0055A4] hover:text-[#003d7a] font-medium">+ Add Link</button>
          </div>
          <div class="space-y-4">
            <div v-for="(link, index) in footerSettings.legalLinks" :key="index" class="flex gap-4 items-start">
              <div class="flex-1">
                <input v-model="link.text" placeholder="Label" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" />
              </div>
              <div class="flex-1">
                <input v-model="link.url" placeholder="URL" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" />
              </div>
              <button @click="removeLegalLink(index)" class="text-red-500 hover:text-red-700 p-2" title="Delete">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
              </button>
            </div>
            <div v-if="footerSettings.legalLinks.length === 0" class="text-gray-500 text-sm italic">
              No links added.
            </div>
          </div>
        </div>
<!-- Footer Credits -->        <div class="bg-gray-50 p-6 rounded-lg mb-6">          <h4 class="text-md font-semibold text-gray-700 mb-4">Footer Credits</h4>          <div class="space-y-4">            <div>              <label class="block text-gray-700 text-sm font-semibold mb-2">Copyright Text (Left Side)</label>              <input                v-model="footerSettings.copyrightText"                type="text"                placeholder="Leave empty for default: © 2025 SiteName. All rights reserved."                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"              />              <p class="text-xs text-gray-500 mt-1">Use {year} for current year, {siteName} for site name</p>            </div>            <div>              <label class="block text-gray-700 text-sm font-semibold mb-2">Credit Text (Right Side)</label>              <input                v-model="footerSettings.creditText"                type="text"                placeholder="e.g. Made with love for French learners"                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"              />              <p class="text-xs text-gray-500 mt-1">Use &lt;i class="fas fa-heart text-red-500"&gt;&lt;/i&gt; for heart icon</p>            </div>          </div>        </div>

        <button
          @click="saveSettings"
          :disabled="loading"
          class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
        >
          <svg v-if="loading" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span>{{ loading ? 'Saving...' : 'Save Changes' }}</span>
        </button>
      </div>

    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useToast } from '../../composables/useToast'
import { useRouter } from 'vue-router'
import { useSettingsStore } from '../../stores/settings'
import axios from 'axios'

const toast = useToast()
const router = useRouter()
const settingsStore = useSettingsStore()

// Tab name to hash mapping
const tabs = ref(['General', 'Email', 'Stripe', 'Footer', 'Import', 'WP Passwords', 'System', 'Custom Script'])
const tabToHash = {
  'General': 'general',
  'Email': 'email',
  'Stripe': 'stripe',
  'Footer': 'footer',
  'Import': 'import',
  'WP Passwords': 'import-wordpress-passwords',
  'System': 'system',
  'Custom Script': 'custom-script'
}

// Hash to tab name mapping (reverse)
const hashToTab = Object.fromEntries(
  Object.entries(tabToHash).map(([tab, hash]) => [hash, tab])
)

// Get initial tab from hash or default to 'General'
const getInitialTab = () => {
  const hash = window.location.hash.replace('#', '')
  return hashToTab[hash] || 'General'
}

const activeTab = ref(getInitialTab())
const loading = ref(false)
const savingMaintenance = ref(false)
const loadingStripe = ref(false)
const testingSmtpConnection = ref(false)
// Initialize emailProvider - will be loaded from API
const emailProvider = ref('smtp')

// Watch for changes and persist to database
import { watch } from 'vue'
watch(emailProvider, async (newVal, oldVal) => {
  // Skip saving if it's the initial load (oldVal would be the default)
  if (oldVal && newVal !== oldVal) {
    try {
      await axios.put('/api/preferences/admin_email_provider', { value: newVal })
    } catch (error) {
      console.error('Failed to save email provider preference:', error)
    }
  }
})

// Load email provider preference from database
const loadEmailProviderPreference = async () => {
  try {
    const response = await axios.get('/api/preferences/admin_email_provider')
    if (response.data.success && response.data.data?.value) {
      emailProvider.value = response.data.data.value
    }
  } catch (error) {
    console.error('Failed to load email provider preference:', error)
  }
}

const showGoogleSecret = ref(false) // Toggle to show/hide Google client secret

// Google OAuth authentication in progress flag
const authenticatingGoogle = ref(false)

// Google OAuth Redirect URI - uses port 8001 (backend) for local development
const googleRedirectUri = computed(() => {
  // If we're on localhost, always use 8001 for the OAuth redirect to hit the backend
  if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    return `http://localhost:8001/api/admin/google-oauth/callback`
  }
  return `${window.location.origin}/api/admin/google-oauth/callback`
})

// Footer Settings Helpers
const addQuickLink = () => {
  footerSettings.value.quickLinks.push({ text: '', url: '' })
}

const removeQuickLink = (index) => {
  footerSettings.value.quickLinks.splice(index, 1)
}

const addResourceLink = () => {
  footerSettings.value.resources.push({ text: '', url: '' })
}

const removeResourceLink = (index) => {
  footerSettings.value.resources.splice(index, 1)
}

const addLegalLink = () => {
  footerSettings.value.legalLinks.push({ text: '', url: '' })
}

const removeLegalLink = (index) => {
  footerSettings.value.legalLinks.splice(index, 1)
}

const settings = ref({
  siteName: settingsStore.siteName || '',
  siteLogo: '',
  siteUrl: settingsStore.siteUrl || '',
  siteTagline: '',
  robots: 'index, follow',
  homepageSchemaType: 'organization',
  currency: 'USD',
  language: 'en',
  timezone: 'UTC',
  maintenanceMode: false,
  allowRegistration: true,
  emailVerification: true,
  smtpServer: '',
  smtpPort: 587,
  emailAddress: '',
  emailPassword: '',
  fromName: '',
  googleClientId: '',
  googleClientSecret: '',
  googleRedirectUri: '',
  googleAccessToken: '',
  googleFromEmail: '',
  googleFromName: '',
  emailNotifications: true,
  emailTemplates: true,
  stripePublishableKey: '',
  stripeSecretKey: '',
  stripeTestMode: true,
  stripeLiveMode: false,
  studentPortalMaintenanceMode: false,
  studentPortalMaintenanceMessage: 'The student portal is currently under maintenance and will not be accessible until further notice. Thank you for your patience and understanding.',
})

const footerSettings = ref({
  quickLinks: [
    { text: 'Home', url: '/#home' },
    { text: 'Courses', url: '/#courses' },
    { text: 'Referral', url: '/#referral' },
    { text: 'FAQ', url: '/#faq' }
  ],
  resources: [
    { text: 'Blog', url: '#' },
    { text: 'Free Resources', url: '#' },
    { text: 'Success Stories', url: '#' },
    { text: 'FAQ', url: '/#faq' }
  ],
  legalLinks: [
    { text: 'Privacy Policy', url: '/new-policies' }
  ],
  contact: {
    email: 'info@focusframe.com',
    phone: '+1 (555) 123-4567',
    address: '123 Learning St, Education City'
  },
  social: {
    facebook: '#',
    twitter: '#',
    instagram: '#',
    linkedin: '#'
  },
  copyrightText: '',
  creditText: 'Made with <i class="fas fa-heart text-red-500"></i> for French learners'
})

const customScripts = ref([])

// Script management functions
const addScript = () => {
  customScripts.value.push({ code: '', placement: 'head' })
}

const removeScript = (index) => {
  customScripts.value.splice(index, 1)
}

const systemInfo = ref({
  version: 'Loading...',
  buildDate: 'Loading...',
  database: 'Loading...',
  status: 'Checking...'
})

// Load system info from API
const loadSystemInfo = async () => {
  try {
    const response = await axios.get('/api/utility/system-status')
    if (response.data.success) {
      const data = response.data.data
      systemInfo.value = {
        version: data.version || '2.0.0',
        buildDate: data.build_date || 'Unknown',
        database: data.database || 'Unknown',
        status: data.status || 'Unknown'
      }
    }
  } catch (error) {
    console.error('Failed to load system info:', error)
    systemInfo.value = {
      version: 'Error',
      buildDate: 'Error',
      database: 'Error',
      status: 'Error'
    }
  }
}

const selectedFile = ref(null)
const importProgress = ref(null)
const importMessage = ref(null)
const dragOver = ref(false)

// WordPress password import state
const selectedWpFile = ref(null)
const wpImportProgress = ref(null)
const wpImportMessage = ref(null)
const wpDragOver = ref(false)
const wpLoading = ref(false)

// Reset state variables
const showResetConfirm = ref(false)
const resetLoading = ref(false)
const resetStats = ref(null)
const resetMessage = ref(null)

const handleFileSelect = (event) => {
  const file = event.target.files?.[0]
  if (file) {
    selectedFile.value = file
    importMessage.value = null
  }
}

const handleDrop = (event) => {
  dragOver.value = false
  const file = event.dataTransfer.files?.[0]
  if (file && file.type === 'application/json') {
    selectedFile.value = file
    importMessage.value = null
  } else {
    importMessage.value = {
      type: 'error',
      text: 'Please drop a JSON file'
    }
  }
}

const BATCH_SIZE = 100 // Process 100 records per batch

const importEnrollmentData = async () => {
  if (!selectedFile.value) return

  loading.value = true
  importMessage.value = null
  importProgress.value = { current: 0, total: 0 }

  try {
    const reader = new FileReader()
    reader.onload = async (e) => {
      try {
        const jsonData = JSON.parse(e.target.result)

        // Convert to array format if needed
        let dataArray = Array.isArray(jsonData) ? jsonData : Object.values(jsonData)

        if (dataArray.length === 0) {
          importMessage.value = {
            type: 'error',
            text: 'Import failed.\n\nYour file could not be processed due to missing or invalid data.\n\nPlease check the JSON structure and try again.'
          }
          loading.value = false
          importProgress.value = null
          return
        }

        // Show processing status
        importProgress.value.total = dataArray.length
        importProgress.value.current = 0

        let totalImported = 0
        let totalSkipped = 0
        let hasDuplicates = false

        // Process in batches
        for (let i = 0; i < dataArray.length; i += BATCH_SIZE) {
          const batch = dataArray.slice(i, i + BATCH_SIZE)

          try {
            const response = await axios.post('/api/admin/import-enrollments', {
              data: batch
            }, {
              headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
              },
              timeout: 60000
            })

            if (response.data.success) {
              totalImported += response.data.importedCount || 0
              totalSkipped += response.data.skipped || 0
              importProgress.value.current = totalImported
              // Track if any batch had duplicates or missing class types
              if (response.data.hasDuplicates) {
                hasDuplicates = true
              }
            } else {
              throw new Error(response.data.message || 'Batch import failed')
            }
          } catch (batchError) {
            let errorText = 'Import failed.\n\nYour file could not be processed due to missing or invalid data.\n\nPlease check the JSON structure and try again.'
            
            // Show more detailed error if available
            if (batchError.response?.data?.message) {
              errorText = batchError.response.data.message
            } else if (batchError.message) {
              errorText = 'Import failed.\n\n' + batchError.message
            }
            
            importMessage.value = {
              type: 'error',
              text: errorText
            }
            importProgress.value = null
            loading.value = false
            return
          }
        }

        // All batches completed
        // Hide progress bar immediately when import completes
        importProgress.value = null
        
        // Check if there were duplicates (from batch responses or if skipped > 0 and imported > 0)
        const hasDuplicatesFlag = hasDuplicates || (totalSkipped > 0 && totalImported > 0)
        
        if (hasDuplicatesFlag && totalImported > 0) {
          // Partial success - some records skipped
          importMessage.value = {
            type: 'warning',
            text: `The import was partially completed.\n\n${totalImported} enrollment(s) were successfully imported.\n${totalSkipped} record(s) were skipped (duplicate entry IDs, missing class types, or missing required fields).`
          }
        } else if (totalImported > 0) {
          // Full success
          importMessage.value = {
            type: 'success',
            text: `Import completed successfully!\n\n${totalImported} enrollment(s) from the JSON file have been mapped to the appropriate fields and saved in the system.\n\nYou can now review the newly added registrations in your dashboard.`
          }
        } else {
          // No records imported
          importMessage.value = {
            type: 'error',
            text: 'Import failed.\n\nYour file could not be processed due to missing or invalid data.\n\nPlease check the JSON structure and try again.'
          }
        }
        selectedFile.value = null
      } catch (parseError) {
        importMessage.value = {
          type: 'error',
          text: 'Import failed.\n\nYour file could not be processed due to missing or invalid data.\n\nPlease check the JSON structure and try again.'
        }
        importProgress.value = null
      } finally {
        loading.value = false
      }
    }
    reader.readAsText(selectedFile.value)
  } catch (error) {
    importMessage.value = {
      type: 'error',
      text: 'Import failed.\n\nYour file could not be processed due to missing or invalid data.\n\nPlease check the JSON structure and try again.'
    }
    loading.value = false
    importProgress.value = null
  }
}

const handleWpFileSelect = (event) => {
  const file = event.target.files?.[0]
  if (file) {
    selectedWpFile.value = file
    wpImportMessage.value = null
  }
}

const handleWpDrop = (event) => {
  wpDragOver.value = false
  const file = event.dataTransfer.files?.[0]
  if (file && (file.name.endsWith('.sql') || file.type === 'text/plain' || file.type === 'application/sql')) {
    selectedWpFile.value = file
    wpImportMessage.value = null
  } else {
    wpImportMessage.value = {
      type: 'error',
      text: 'Please drop a SQL file'
    }
  }
}

const importWordPressPasswords = async () => {
  if (!selectedWpFile.value) return

  wpLoading.value = true
  wpImportMessage.value = null
  wpImportProgress.value = { current: 0, total: 0 }

  try {
    const reader = new FileReader()
    reader.onload = async (e) => {
      try {
        const sqlContent = e.target.result

        // Parse SQL INSERT statements
        // Format: INSERT INTO `wp_users` (`user_email`, `user_pass`) VALUES ('email@example.com', '$wp$2y$10$...'), ...
        const wpUsers = []
        
        // Find all INSERT statements for wp_users table
        const insertRegex = /INSERT\s+INTO\s+[`"]?wp_users[`"]?\s*\([^)]+\)\s*VALUES\s*([^;]+);/gis
        let match
        
        while ((match = insertRegex.exec(sqlContent)) !== null) {
          const valuesString = match[1].trim()
          
          // Parse value tuples: ('email', 'hash'), ('email2', 'hash2')
          // Handle quoted strings that may contain special characters
          const parseTuple = (str) => {
            const result = []
            let i = 0
            let inQuotes = false
            let quoteChar = null
            let current = ''
            let values = []
            
            while (i < str.length) {
              const char = str[i]
              const prevChar = i > 0 ? str[i - 1] : null
              
              if ((char === '"' || char === "'") && prevChar !== '\\') {
                if (!inQuotes) {
                  inQuotes = true
                  quoteChar = char
                  // Don't add opening quote to current
                } else if (char === quoteChar) {
                  inQuotes = false
                  quoteChar = null
                  // Don't add closing quote to current
                  // End of quoted value - push without quotes
                  values.push(current)
                  current = ''
          } else {
                  current += char
                }
              } else if (char === ',' && !inQuotes) {
                if (current.trim()) {
                  values.push(current.trim())
                  current = ''
                }
              } else if (char === '(' && !inQuotes) {
                // Start of new tuple, reset
                values = []
                current = ''
              } else if (char === ')' && !inQuotes) {
                // End of tuple
                if (current.trim()) {
                  values.push(current.trim())
                }
                if (values.length >= 2) {
                  // Remove quotes from values
                  const email = values[0].replace(/^['"]|['"]$/g, '').replace(/\\'/g, "'").replace(/\\"/g, '"')
                  const password = values[1].replace(/^['"]|['"]$/g, '').replace(/\\'/g, "'").replace(/\\"/g, '"')
                  if (email && password) {
                    result.push({ user_email: email, user_pass: password })
                  }
                }
                values = []
                current = ''
                // Skip comma and whitespace after closing paren
                i++
                while (i < str.length && (str[i] === ',' || str[i] === ' ' || str[i] === '\n' || str[i] === '\r')) {
                  i++
                }
                continue
        } else {
                current += char
              }
              i++
            }
            
            return result
          }
          
          const tuples = parseTuple(valuesString)
          wpUsers.push(...tuples)
        }

        if (wpUsers.length === 0) {
          wpImportMessage.value = {
            type: 'error',
            text: 'Import failed.\n\nNo user data found in the SQL file.\n\nPlease check the SQL structure and ensure it contains INSERT statements with user_email and user_pass columns.'
          }
          wpLoading.value = false
          wpImportProgress.value = null
          return
        }

        // Show processing status
        wpImportProgress.value.total = wpUsers.length
        wpImportProgress.value.current = 0

        const BATCH_SIZE = 50 // Process 50 passwords per batch
        let totalUpdated = 0
        let totalNotFound = 0
        let allNotFoundEmails = [] // Collect all emails that weren't found

        // Process in batches
        for (let i = 0; i < wpUsers.length; i += BATCH_SIZE) {
          const batch = wpUsers.slice(i, i + BATCH_SIZE)

          try {
            const response = await axios.post('/api/admin/import-wordpress-passwords', {
              data: batch
            }, {
              headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
              },
              timeout: 30000
            })

            if (response.data.success) {
              totalUpdated += response.data.updatedCount || 0
              totalNotFound += response.data.notFoundCount || 0
              // Collect emails that weren't found from this batch
              if (response.data.notFoundEmails && Array.isArray(response.data.notFoundEmails)) {
                allNotFoundEmails = allNotFoundEmails.concat(response.data.notFoundEmails)
              }
              wpImportProgress.value.current = totalUpdated + totalNotFound
            } else {
              throw new Error(response.data.message || 'Batch import failed')
            }
          } catch (batchError) {
            let errorText = 'Import failed.\n\nYour file could not be processed.\n\nPlease check the SQL structure and try again.'
            
            if (batchError.response?.data?.message) {
              errorText = batchError.response.data.message
            } else if (batchError.message) {
              errorText = 'Import failed.\n\n' + batchError.message
            }
            
            wpImportMessage.value = {
              type: 'error',
              text: errorText
            }
            wpImportProgress.value = null
            wpLoading.value = false
            return
          }
        }

        // All batches completed
        wpImportProgress.value = null

        // Build message with details
        let messageText = ''
        
        if (totalUpdated > 0) {
          messageText = `Import completed successfully!\n\n${totalUpdated} user password(s) were updated.`
          if (totalNotFound > 0) {
            messageText += `\n\n${totalNotFound} user(s) were not found in the system.`
            if (allNotFoundEmails.length > 0 && allNotFoundEmails.length <= 20) {
              // Show emails if 20 or fewer
              messageText += `\n\nEmails not found:\n${allNotFoundEmails.join(', ')}`
            } else if (allNotFoundEmails.length > 20) {
              // Show first 20 if more than 20
              messageText += `\n\nFirst 20 emails not found:\n${allNotFoundEmails.slice(0, 20).join(', ')}\n... and ${allNotFoundEmails.length - 20} more.`
            }
          }
          wpImportMessage.value = {
            type: totalNotFound > 0 ? 'warning' : 'success',
            text: messageText
          }
        } else if (totalNotFound > 0) {
          messageText = `Import completed with warnings.\n\nNo passwords were updated. ${totalNotFound} user(s) were not found in the system.\n\nPlease ensure the email addresses in the SQL file match existing users.`
          if (allNotFoundEmails.length > 0 && allNotFoundEmails.length <= 20) {
            messageText += `\n\nEmails not found:\n${allNotFoundEmails.join(', ')}`
          } else if (allNotFoundEmails.length > 20) {
            messageText += `\n\nFirst 20 emails not found:\n${allNotFoundEmails.slice(0, 20).join(', ')}\n... and ${allNotFoundEmails.length - 20} more.`
          }
          wpImportMessage.value = {
            type: 'warning',
            text: messageText
          }
        } else {
          wpImportMessage.value = {
            type: 'error',
            text: 'Import failed.\n\nNo passwords were updated.\n\nPlease check the JSON structure and try again.'
          }
        }
        selectedWpFile.value = null
      } catch (parseError) {
        wpImportMessage.value = {
          type: 'error',
          text: 'Import failed.\n\nYour file could not be processed due to invalid JSON format.\n\nPlease check the JSON structure and try again.'
        }
        wpImportProgress.value = null
      } finally {
        wpLoading.value = false
      }
    }
    reader.readAsText(selectedWpFile.value)
  } catch (error) {
    wpImportMessage.value = {
      type: 'error',
      text: 'Import failed.\n\nYour file could not be processed.\n\nPlease check the JSON structure and try again.'
    }
    wpLoading.value = false
    wpImportProgress.value = null
  }
}

// Load Stripe settings from API
const loadStripeSettings = async () => {
  loadingStripe.value = true
  try {
    const response = await axios.get('/api/admin/stripe-settings', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.data.success) {
      const data = response.data.data
      settings.value.stripePublishableKey = data.stripe_publishable_key || ''
      settings.value.stripeSecretKey = data.stripe_secret_key || ''
      settings.value.stripeTestMode = data.stripe_test_mode !== undefined ? data.stripe_test_mode : true
      settings.value.stripeLiveMode = data.stripe_live_mode !== undefined ? data.stripe_live_mode : false
    }
  } catch (error) {
    console.error('Failed to load Stripe settings:', error)
    // Don't show error toast on initial load, just use defaults
  } finally {
    loadingStripe.value = false
  }
}

// Save Stripe settings to API
const saveStripeSettings = async () => {
  loading.value = true
  try {
    // Only send secret key if it's not masked (contains dots) and not empty
    const secretKey = settings.value.stripeSecretKey && 
                      !settings.value.stripeSecretKey.includes('••••') && 
                      settings.value.stripeSecretKey.trim() !== ''
      ? settings.value.stripeSecretKey.trim()
      : null
    
    const response = await axios.put('/api/admin/stripe-settings', {
      stripe_publishable_key: settings.value.stripePublishableKey?.trim() || '',
      stripe_secret_key: secretKey, // Only send if changed (not masked/empty)
      stripe_test_mode: !settings.value.stripeLiveMode, // Derived: opposite of live mode
      stripe_live_mode: settings.value.stripeLiveMode,
    }, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (response.data.success) {
      toast.success('Stripe settings saved successfully!')
      // Reload settings to get updated values
      await loadStripeSettings()
    }
  } catch (error) {
    console.error('Failed to save Stripe settings:', error)
    toast.error(error.response?.data?.message || 'Failed to save Stripe settings')
  } finally {
    loading.value = false
  }
}

// Student Portal Maintenance Mode functions
const toggleMaintenanceMode = async () => {
  savingMaintenance.value = true
  try {
    const newValue = !settings.value.studentPortalMaintenanceMode
    await axios.put('/api/admin/settings/student_portal_maintenance_mode', {
      value: newValue.toString()
    }, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    settings.value.studentPortalMaintenanceMode = newValue
    toast.success(newValue ? 'Student portal maintenance mode enabled' : 'Student portal maintenance mode disabled')
  } catch (error) {
    console.error('Failed to toggle maintenance mode:', error)
    toast.error('Failed to toggle maintenance mode')
  } finally {
    savingMaintenance.value = false
  }
}

const saveMaintenanceSettings = async () => {
  savingMaintenance.value = true
  try {
    await axios.put('/api/admin/settings/student_portal_maintenance_message', {
      value: settings.value.studentPortalMaintenanceMessage
    }, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    toast.success('Maintenance message saved successfully')
  } catch (error) {
    console.error('Failed to save maintenance message:', error)
    toast.error('Failed to save maintenance message')
  } finally {
    savingMaintenance.value = false
  }
}

const saveSettings = async () => {
  loading.value = true
  try {
    if (activeTab.value === 'Stripe') {
      await saveStripeSettings()
    } else if (activeTab.value === 'General') {
      // Save general settings
      const generalSettings = {
        site_name: settings.value.siteName,
        site_url: settings.value.siteUrl,
        site_tagline: settings.value.siteTagline,
        robots: settings.value.robots,
        currency: settings.value.currency,
        language: settings.value.language,
        timezone: settings.value.timezone,
        maintenance_mode: settings.value.maintenanceMode,
        allow_registration: settings.value.allowRegistration,
        email_verification: settings.value.emailVerification
      }

      for (const [key, value] of Object.entries(generalSettings)) {
        await axios.put(`/api/admin/settings/${key}`, { value: value.toString() }, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        })
      }

      // Save Custom Scripts (only if in General tab, but we'll handle it in System tab)
      // This will be handled in the System tab save

      // Update the settings store to reflect changes immediately
      settingsStore.updateSiteName(settings.value.siteName)
      settingsStore.updateSiteUrl(settings.value.siteUrl)

      toast.success('General settings saved successfully')
    } else if (activeTab.value === 'Custom Script') {
      // Save Custom Scripts
      const scriptsToSave = customScripts.value
        .filter(script => script.code && script.code.trim() !== '')
        .map(script => ({
          code: script.code.trim(),
          placement: script.placement || 'head'
        }))
      
      await axios.put('/api/admin/settings/custom_scripts', { 
        value: JSON.stringify(scriptsToSave)
      }, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      })
      
      // Reload scripts to ensure they're displayed correctly
      await loadGeneralSettings()
      
      toast.success('Custom scripts saved successfully')
    } else if (activeTab.value === 'Footer') {
      try {
        await axios.put('/api/admin/settings/footer_settings', { 
          value: JSON.stringify(footerSettings.value) 
        }, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        })
        toast.success('Footer settings saved successfully')
        // Refresh settings
        await loadGeneralSettings()
      } catch (error) {
        console.error('Failed to save footer settings:', error)
        toast.error('Failed to save footer settings')
      }
    } else if (activeTab.value === 'Email') {
      // Save email settings
      const emailData = {
        smtp_host: settings.value.smtpServer?.trim() || '',
        smtp_port: parseInt(settings.value.smtpPort) || 587,
        smtp_username: settings.value.emailAddress?.trim() || '',
        from_name: settings.value.fromName?.trim() || '',
        from_email: settings.value.emailAddress?.trim() || '',
        google_client_id: settings.value.googleClientId?.trim() || null,
        google_client_secret: settings.value.googleClientSecret && settings.value.googleClientSecret !== '••••••••••••' ? settings.value.googleClientSecret.trim() : null,
        google_from_email: settings.value.googleFromEmail?.trim() || null,
        google_from_name: settings.value.googleFromName?.trim() || null,
      }
      
      // Include password if provided (not masked placeholder)
      // Remove spaces from Gmail app passwords (they shouldn't have spaces)
      const passwordValue = settings.value.emailPassword?.trim() || ''
      if (passwordValue && passwordValue !== '••••••••••••') {
        emailData.smtp_password = passwordValue.replace(/\s+/g, '')
      }
      
      // Validate required fields based on selected provider
      const usingGoogleOAuth = emailProvider.value === 'google' && emailData.google_client_id && emailData.google_client_secret
      
      if (emailProvider.value === 'smtp') {
        // SMTP validation
        if (!emailData.smtp_host) {
          loading.value = false
          toast.error('SMTP Server is required')
          return
        }
        if (!emailData.smtp_username) {
          loading.value = false
          toast.error('Email Address is required')
          return
        }
        if (!emailData.smtp_password) {
          loading.value = false
          toast.error('Email Password is required for SMTP')
          return
        }
      } else if (emailProvider.value === 'google') {
        // Google OAuth validation
        if (!emailData.google_client_id) {
          loading.value = false
          toast.error('Application Client ID is required')
          return
        }
        if (!emailData.google_client_secret) {
          loading.value = false
          toast.error('Application Client Secret is required')
          return
        }
      }
      
      // If using Google OAuth, SMTP password is optional
      // If using SMTP, password is required (already checked above)
      
      const response = await axios.put('/api/admin/email-settings', emailData, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      })

      if (response.data.success) {
        // Reload email settings to get updated data (without password)
        await loadEmailSettings()
        toast.success('Email settings saved successfully')
      } else {
        toast.error(response.data.message || 'Failed to save email settings')
      }
    }
  } catch (error) {
    console.error('Failed to save settings:', error)
    toast.error(error.response?.data?.message || 'Failed to save settings')
  } finally {
    loading.value = false
  }
}

const testSmtpConnection = async () => {
  testingSmtpConnection.value = true
  try {
    // First save the current settings
    const emailData = {
        smtp_host: settings.value.smtpServer,
      smtp_port: parseInt(settings.value.smtpPort) || 587,
        smtp_username: settings.value.emailAddress,
        from_name: settings.value.fromName,
        from_email: settings.value.emailAddress,
    }
    
    // Only include password if it was changed
    if (settings.value.emailPassword && settings.value.emailPassword !== '••••••••••••') {
      emailData.smtp_password = settings.value.emailPassword
    }

    await axios.put('/api/admin/email-settings', emailData, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    // Then test the connection
    const response = await axios.post('/api/admin/test-smtp-connection', {}, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (response.data.success) {
      toast.success(response.data.message || 'SMTP connection successful!')
    } else {
      toast.error(response.data.message || 'SMTP connection failed')
    }
  } catch (error) {
    console.error('SMTP connection test failed:', error)
    toast.error(error.response?.data?.message || 'Failed to test SMTP connection. Please check your settings.')
  } finally {
    testingSmtpConnection.value = false
  }
}

const authenticateGoogle = async () => {
  // #region agent log
  fetch('http://127.0.0.1:7242/ingest/97e5de2e-11a4-4cca-9598-14ca243d09d7',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'Settings.vue:1781',message:'authenticateGoogle called',data:{hasClientId:!!settings.value.googleClientId,hasClientSecret:!!settings.value.googleClientSecret,clientSecretMasked:settings.value.googleClientSecret === '••••••••••••',alreadyAuthenticating:authenticatingGoogle.value},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'A'})}).catch(()=>{});
  // #endregion
  
  // Prevent multiple simultaneous calls
  if (authenticatingGoogle.value) {
    // #region agent log
    fetch('http://127.0.0.1:7242/ingest/97e5de2e-11a4-4cca-9598-14ca243d09d7',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'Settings.vue:1785',message:'Already authenticating - ignoring duplicate call',data:{},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'A'})}).catch(()=>{});
    // #endregion
    return
  }
  
  authenticatingGoogle.value = true
  loading.value = true
  
  try {
    if (!settings.value.googleClientId || !settings.value.googleClientSecret || settings.value.googleClientSecret === '••••••••••••') {
      // #region agent log
      fetch('http://127.0.0.1:7242/ingest/97e5de2e-11a4-4cca-9598-14ca243d09d7',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'Settings.vue:1783',message:'Validation failed - early return',data:{hasClientId:!!settings.value.googleClientId,hasClientSecret:!!settings.value.googleClientSecret,clientSecretMasked:settings.value.googleClientSecret === '••••••••••••'},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'B'})}).catch(()=>{});
      // #endregion
      toast.error('Please enter both Client ID and Client Secret first')
      return
    }

    // #region agent log
    fetch('http://127.0.0.1:7242/ingest/97e5de2e-11a4-4cca-9598-14ca243d09d7',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'Settings.vue:1788',message:'Validation passed - proceeding',data:{hasClientId:!!settings.value.googleClientId,hasClientSecret:!!settings.value.googleClientSecret},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'A'})}).catch(()=>{});
    // #endregion

    // Save settings in background (non-blocking) - don't wait for restart
    // #region agent log
    fetch('http://127.0.0.1:7242/ingest/97e5de2e-11a4-4cca-9598-14ca243d09d7',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'Settings.vue:1799',message:'Using redirect URI',data:{redirectUri:googleRedirectUri.value},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'I'})}).catch(()=>{});
    // #endregion
    
    const emailData = {
      smtp_host: settings.value.smtpServer?.trim() || '',
      smtp_port: parseInt(settings.value.smtpPort) || 587,
      smtp_username: settings.value.emailAddress?.trim() || '',
      from_name: settings.value.fromName?.trim() || '',
      from_email: settings.value.emailAddress?.trim() || '',
      google_client_id: settings.value.googleClientId?.trim() || null,
      google_client_secret: settings.value.googleClientSecret && settings.value.googleClientSecret !== '••••••••••••' ? settings.value.googleClientSecret.trim() : null,
      google_redirect_uri: googleRedirectUri.value,
      google_from_email: settings.value.googleFromEmail?.trim() || null,
      google_from_name: settings.value.googleFromName?.trim() || null,
    }

    // Save settings in background without blocking (fire and forget)
    // Save settings in background without blocking (fire and forget)
    // removed as it causes server restart issues
    // axios.put('/api/admin/email-settings', emailData)...
    
    // #region agent log
    fetch('http://127.0.0.1:7242/ingest/97e5de2e-11a4-4cca-9598-14ca243d09d7',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'Settings.vue:1816',message:'Settings save initiated (non-blocking)',data:{},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'C'})}).catch(()=>{});
    // #endregion

    // Retry mechanism for authorize request with longer backoff
    let response;
    let retries = 5;
    // #region agent log
    fetch('http://127.0.0.1:7242/ingest/97e5de2e-11a4-4cca-9598-14ca243d09d7',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'Settings.vue:1844',message:'Starting authorization request retry loop',data:{retries},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'D'})}).catch(()=>{});
    // #endregion
    while (retries > 0) {
      try {
        console.log(`Attempting Google OAuth authorization (Retries left: ${retries - 1})...`)
        // #region agent log
        fetch('http://127.0.0.1:7242/ingest/97e5de2e-11a4-4cca-9598-14ca243d09d7',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'Settings.vue:1847',message:'Making authorization request',data:{retries,hasClientId:!!settings.value.googleClientId,hasClientSecret:!!settings.value.googleClientSecret},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'D'})}).catch(()=>{});
        // #endregion
        response = await axios.post('/api/admin/google-oauth/authorize', {
          client_id: settings.value.googleClientId?.trim(),
          client_secret: settings.value.googleClientSecret && settings.value.googleClientSecret !== '••••••••••••' ? settings.value.googleClientSecret.trim() : null,
          redirect_uri: googleRedirectUri.value,
        }, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        })
        // #region agent log
        fetch('http://127.0.0.1:7242/ingest/97e5de2e-11a4-4cca-9598-14ca243d09d7',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'Settings.vue:1855',message:'Authorization request succeeded',data:{status:response?.status,hasSuccess:!!response?.data?.success,hasAuthUrl:!!response?.data?.auth_url},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'D'})}).catch(()=>{});
        // #endregion
        break; // Success, exit loop
      } catch (err) {
        // #region agent log
        fetch('http://127.0.0.1:7242/ingest/97e5de2e-11a4-4cca-9598-14ca243d09d7',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'Settings.vue:1857',message:'Authorization request failed',data:{retries,error:err?.message,status:err?.response?.status,responseData:err?.response?.data},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'D'})}).catch(()=>{});
        // #endregion
        console.warn('Authorization attempt failed, retrying in 3 seconds...', err)
        retries--;
        if (retries === 0) throw err;
        await new Promise(resolve => setTimeout(resolve, 3000));
      }
    }

    // #region agent log
    fetch('http://127.0.0.1:7242/ingest/97e5de2e-11a4-4cca-9598-14ca243d09d7',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'Settings.vue:1864',message:'Checking response before redirect',data:{hasResponse:!!response,hasSuccess:!!response?.data?.success,hasAuthUrl:!!response?.data?.auth_url,responseData:response?.data},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'E'})}).catch(()=>{});
    // #endregion

    if (response.data.success && response.data.auth_url) {
      // #region agent log
      fetch('http://127.0.0.1:7242/ingest/97e5de2e-11a4-4cca-9598-14ca243d09d7',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'Settings.vue:1865',message:'Redirecting to Google OAuth',data:{authUrl:response.data.auth_url},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'F'})}).catch(()=>{});
      // #endregion
      window.location.href = response.data.auth_url
    } else {
      // #region agent log
      fetch('http://127.0.0.1:7242/ingest/97e5de2e-11a4-4cca-9598-14ca243d09d7',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'Settings.vue:1867',message:'Response missing success or auth_url',data:{hasSuccess:!!response?.data?.success,hasAuthUrl:!!response?.data?.auth_url,message:response?.data?.message},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'E'})}).catch(()=>{});
      // #endregion
      toast.error(response.data?.message || 'Failed to initiate Google OAuth')
    }
  } catch (error) {
    // #region agent log
    fetch('http://127.0.0.1:7242/ingest/97e5de2e-11a4-4cca-9598-14ca243d09d7',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'Settings.vue:1875',message:'Error caught in catch block',data:{error:error?.message,status:error?.response?.status,responseData:error?.response?.data},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'D'})}).catch(()=>{});
    // #endregion
    console.error('Google OAuth failed:', error)
    const errorMessage = error.response?.data?.message || error.message || 'Failed to authenticate with Google'
    toast.error(errorMessage)
    console.error('Full error:', error.response?.data)
  } finally {
    authenticatingGoogle.value = false
    loading.value = false
    // #region agent log
    fetch('http://127.0.0.1:7242/ingest/97e5de2e-11a4-4cca-9598-14ca243d09d7',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'Settings.vue:1883',message:'authenticateGoogle finally block - cleanup',data:{},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'A'})}).catch(()=>{});
    // #endregion
  }
}

const disconnectGoogle = async () => {
  if (!confirm('Are you sure you want to disconnect Google OAuth? This will remove your access token and you will need to authenticate again.')) {
    return
  }

  loading.value = true
  try {
    const emailData = {
      google_access_token: null,
      google_refresh_token: null,
    }

    await axios.put('/api/admin/email-settings', emailData, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    settings.value.googleAccessToken = ''
    toast.success('Google OAuth disconnected successfully')
    await loadEmailSettings()
  } catch (error) {
    console.error('Disconnect Google OAuth failed:', error)
    toast.error(error.response?.data?.message || 'Failed to disconnect Google OAuth')
  } finally {
    loading.value = false
  }
}

const resetSmtpSettings = async () => {
  if (!confirm('Are you sure you want to reset SMTP settings? This will clear all SMTP configuration fields.')) {
    return
  }

  loading.value = true
  try {
    const emailData = {
      smtp_host: null,
      smtp_port: null,
      smtp_username: null,
      smtp_password: null,
      from_email: null,
      from_name: null,
    }

    await axios.put('/api/admin/email-settings', emailData, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    // Clear local form fields
    settings.value.smtpServer = ''
    settings.value.smtpPort = 587
    settings.value.emailAddress = ''
    settings.value.emailPassword = ''
    settings.value.fromName = ''

    toast.success('SMTP settings reset successfully')
    await loadEmailSettings()
  } catch (error) {
    console.error('Reset SMTP settings failed:', error)
    toast.error(error.response?.data?.message || 'Failed to reset SMTP settings')
  } finally {
    loading.value = false
  }
}

const testGoogleEmail = async () => {
  if (!settings.value.googleAccessToken) {
    toast.error('Please authenticate with Google first to get an access token')
    return
  }

  loading.value = true
  try {
    const response = await axios.post('/api/admin/test-email', {
      email: settings.value.googleFromEmail || null,
      use_google_oauth: true
    }, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (response.data.success) {
      toast.success(response.data.message || 'Test email sent successfully!')
    } else {
      toast.error(response.data.message || 'Failed to send test email')
    }
  } catch (error) {
    console.error('Test Google email failed:', error)
    toast.error(error.response?.data?.message || 'Failed to send test email. Please check your Google OAuth configuration.')
  } finally {
    loading.value = false
  }
}

const testEmail = async () => {
  try {
    // First save the current settings
    const emailData = {
      smtp_host: settings.value.smtpServer,
      smtp_port: parseInt(settings.value.smtpPort) || 587,
      smtp_username: settings.value.emailAddress,
      from_name: settings.value.fromName,
      from_email: settings.value.emailAddress,
    }
    
    // Only include password if it was changed
    if (settings.value.emailPassword && settings.value.emailPassword !== '••••••••••••') {
      emailData.smtp_password = settings.value.emailPassword
    }

    await axios.put('/api/admin/email-settings', emailData, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    // Then send test email
    const response = await axios.post('/api/admin/test-email', {
      email: settings.value.emailAddress || null
    }, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (response.data.success) {
      toast.success(response.data.message || 'Test email sent successfully!')
    } else {
      toast.error(response.data.message || 'Failed to send test email')
    }
  } catch (error) {
    console.error('Test email failed:', error)
    toast.error(error.response?.data?.message || 'Failed to send test email. Please check your SMTP settings.')
  }
}

const logoInput = ref(null)
const uploadingLogo = ref(false)

const handleLogoUpload = async (event) => {
  const file = event.target.files[0]
  if (!file) return

  uploadingLogo.value = true
  const formData = new FormData()
  formData.append('logo', file)

  try {
    const response = await axios.post('/api/admin/settings/upload-logo', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (response.data.success) {
      settings.value.siteLogo = response.data.data.site_logo
      // Also update the store if available
      if (settingsStore) {
        settingsStore.siteLogo = response.data.data.site_logo
      }
      toast.success('Logo uploaded successfully')
    }
  } catch (error) {
    console.error('Logo upload error:', error)
    toast.error('Failed to upload logo. Make sure it is an image and less than 2MB.')
  } finally {
    uploadingLogo.value = false
    // Clear the input
    if (logoInput.value) {
      logoInput.value.value = ''
    }
  }
}

const loadGeneralSettings = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/admin/settings', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.data.success && response.data.data) {
      const data = response.data.data
      // Map API settings to form fields
      settings.value.siteName = data.site_name || settings.value.siteName
      settings.value.siteLogo = data.site_logo || settings.value.siteLogo
      settings.value.siteUrl = data.site_url || settings.value.siteUrl
      settings.value.siteTagline = data.site_tagline || settings.value.siteTagline
      settings.value.robots = data.robots || 'index, follow'
      settings.value.homepageSchemaType = data.homepage_schema_type || 'organization'
      settings.value.currency = data.currency || settings.value.currency
      settings.value.language = data.language || settings.value.language
      settings.value.timezone = data.timezone || settings.value.timezone
      settings.value.maintenanceMode = data.maintenance_mode || false
      settings.value.allowRegistration = data.allow_registration !== undefined ? data.allow_registration : true
      settings.value.emailVerification = data.email_verification !== undefined ? data.email_verification : true

      // Load Student Portal Maintenance settings
      settings.value.studentPortalMaintenanceMode = data.student_portal_maintenance_mode === 'true' || data.student_portal_maintenance_mode === true
      settings.value.studentPortalMaintenanceMessage = data.student_portal_maintenance_message || 'The student portal is currently under maintenance and will not be accessible until further notice. Thank you for your patience and understanding.'
      
      // Load Footer Settings
      if (data.footer_settings) {
        try {
          const parsedFooter = typeof data.footer_settings === 'string' 
            ? JSON.parse(data.footer_settings) 
            : data.footer_settings
          footerSettings.value = { ...footerSettings.value, ...parsedFooter }
        } catch (e) {
          console.error('Error parsing footer settings:', e)
        }
      }
      
      // Load Custom Scripts
      if (data.custom_scripts) {
        try {
          const parsedScripts = typeof data.custom_scripts === 'string' 
            ? JSON.parse(data.custom_scripts) 
            : data.custom_scripts
          if (Array.isArray(parsedScripts)) {
            // Migrate old format (strings) to new format (objects with code and placement)
            customScripts.value = parsedScripts.map(script => {
              if (typeof script === 'string') {
                return { code: script, placement: 'head' }
              }
              return script
            })
          } else {
            customScripts.value = []
          }
        } catch (e) {
          console.error('Error parsing custom scripts:', e)
          customScripts.value = []
        }
      } else {
        customScripts.value = []
      }
    }
  } catch (error) {
    console.error('Failed to load general settings:', error)
    // Use defaults if API fails
  } finally {
    loading.value = false
  }
}

const loadEmailSettings = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/admin/email-settings', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.data.success && response.data.data) {
      const data = response.data.data
      // Map API response fields to form fields
      settings.value.smtpServer = data.smtp_host || data.smtp_server || ''
      settings.value.smtpPort = data.smtp_port || 587
      settings.value.emailAddress = data.smtp_username || data.from_email || data.email_address || ''
      settings.value.fromName = data.from_name || ''
      settings.value.googleClientId = data.google_client_id || ''
      settings.value.googleFromEmail = data.google_from_email || ''
      settings.value.googleFromName = data.google_from_name || ''
      // Google client secret - show actual value if available (user can edit it)
      if (data.google_client_secret) {
        settings.value.googleClientSecret = data.google_client_secret
      } else {
        settings.value.googleClientSecret = ''
      }
      // Google access token (partial, for display only)
      settings.value.googleAccessToken = data.google_access_token || ''

      // Email provider preference is loaded separately via loadEmailProviderPreference()

      // Password is never returned from API for security
      // Keep empty or masked password placeholder
      if (!settings.value.emailPassword || settings.value.emailPassword === '') {
        settings.value.emailPassword = '••••••••••••'
      }
      settings.value.emailNotifications = data.email_notifications !== undefined ? data.email_notifications : true
      settings.value.emailTemplates = data.email_templates !== undefined ? data.email_templates : true
    }
  } catch (error) {
    console.error('Failed to load email settings:', error)
    // Keep default values on error
  } finally {
    loading.value = false
  }
}

// Load reset stats when Import tab is clicked
const loadResetStats = async () => {
  try {
    const response = await axios.get('/api/admin/reset-import-stats', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.data.success) {
      resetStats.value = response.data.data
    }
  } catch (error) {
    console.error('Failed to load reset stats:', error)
    resetStats.value = { studentCount: 0, enrollmentCount: 0 }
  }
}

// Perform the reset
const performReset = async () => {
  resetLoading.value = true
  resetMessage.value = null

  try {
    const response = await axios.post('/api/admin/reset-import-data', {}, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (response.data.success) {
      resetMessage.value = {
        type: 'success',
        text: response.data.message || 'Import data reset successfully'
      }
      showResetConfirm.value = false

      // Reload stats
      await loadResetStats()

      // Clear message after 3 seconds
      setTimeout(() => {
        resetMessage.value = null
      }, 3000)
    } else {
      resetMessage.value = {
        type: 'error',
        text: response.data.message || 'Failed to reset data'
      }
    }
  } catch (error) {
    resetMessage.value = {
      type: 'error',
      text: error.response?.data?.message || error.message || 'Failed to reset data'
    }
    console.error('Reset error:', error)
  } finally {
    resetLoading.value = false
  }
}

// Load Stripe settings when Stripe tab is clicked
const handleTabChange = (tab) => {
  activeTab.value = tab
  
  // Update URL hash without reloading page
  const hash = tabToHash[tab] || 'general'
  window.history.pushState(null, '', `#${hash}`)
  
  if (tab === 'Stripe') {
    loadStripeSettings()
  } else if (tab === 'General') {
    loadGeneralSettings()
  } else if (tab === 'System') {
    loadSystemInfo()
    loadGeneralSettings() // Load maintenance settings
  } else if (tab === 'Custom Script') {
    loadGeneralSettings() // Load scripts from general settings
  } else if (tab === 'Email') {
    loadEmailSettings()
    loadEmailProviderPreference()
  } else if (tab === 'Import') {
    loadResetStats()
  } else if (tab === 'WP Passwords') {
    // Reset WordPress import state when tab is clicked
    selectedWpFile.value = null
    wpImportMessage.value = null
    wpImportProgress.value = null
  }
}

// Handle browser back/forward button for hash changes
const handleHashChange = () => {
  const hash = window.location.hash.replace('#', '')
  const tab = hashToTab[hash] || 'General'
  if (tab !== activeTab.value) {
    activeTab.value = tab
    handleTabChange(tab)
  }
}

onMounted(() => {
  // Set initial hash if not present
  if (!window.location.hash) {
    window.history.replaceState(null, '', `#${tabToHash[activeTab.value] || 'general'}`)
  }

  // Load settings based on active tab
  if (activeTab.value === 'Stripe') {
    loadStripeSettings()
  } else if (activeTab.value === 'General') {
    loadGeneralSettings()
  } else if (activeTab.value === 'System') {
    loadSystemInfo()
    loadGeneralSettings() // Load maintenance settings
  } else if (activeTab.value === 'Custom Script') {
    loadGeneralSettings() // Load scripts from general settings
  } else if (activeTab.value === 'Email') {
    loadEmailSettings()
    loadEmailProviderPreference()
  } else if (activeTab.value === 'Import') {
    loadResetStats()
  }

  // Listen for hash changes (browser back/forward)
  window.addEventListener('hashchange', handleHashChange)

  // Removed pending_google_auth check - no longer needed since we don't wait for server restart

  // Check for OAuth callback messages
  const urlParams = new URLSearchParams(window.location.search)
  const success = urlParams.get('success')
  const error = urlParams.get('error')
  const message = urlParams.get('message')

  if (success === 'oauth_success') {
    toast.success(decodeURIComponent(message || 'Google OAuth authentication successful!'))
    // Reload email settings to get the new access token
    loadEmailSettings()
    // Clean URL but preserve hash
    const hash = window.location.hash
    window.history.replaceState({}, document.title, window.location.pathname + hash)
  } else if (error) {
    toast.error(decodeURIComponent(message || 'Google OAuth authentication failed'))
    // Clean URL but preserve hash
    const hash = window.location.hash
    window.history.replaceState({}, document.title, window.location.pathname + hash)
  }
})

// Cleanup event listener on unmount
onUnmounted(() => {
  window.removeEventListener('hashchange', handleHashChange)
})

</script>

<style scoped>
@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.7; }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
