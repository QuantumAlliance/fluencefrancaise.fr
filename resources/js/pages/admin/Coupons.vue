<template>
  <div class="">
    <div class="flex justify-end items-center mb-6">
      <button
        @click="showCouponForm = true; editingCoupon = null"
        class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors"
      >
        + Add New Coupon
      </button>
    </div>

    <!-- Coupons Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
      <div v-if="loading" class="p-12 text-center">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
        <p class="mt-2 text-gray-500">Loading coupons...</p>
      </div>

      <table v-else class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Coupon Name</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Coupon Code</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Discount</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Start Date</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">End Date</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Usage Limit</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Duration</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Applicable Packages</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="coupon in coupons" :key="coupon?.id || coupon?.code || Math.random()">
          <tr v-if="coupon" class="border-b border-gray-200 hover:bg-gray-50">
            <td class="px-6 py-4 font-medium text-gray-800">{{ coupon?.name || coupon?.coupon_name || '-' }}</td>
            <td class="px-6 py-4">
              <code class="px-2 py-1 bg-gray-100 rounded text-sm font-mono">{{ coupon?.code || '-' }}</code>
            </td>
            <td class="px-6 py-4 text-gray-700">
              {{ coupon?.discount_type === 'percentage' ? `${coupon?.discount_value || 0}%` : `$${coupon?.discount_value || 0}` }}
            </td>
            <td class="px-6 py-4 text-gray-700 text-sm">{{ formatDate(coupon?.start_date) }}</td>
            <td class="px-6 py-4 text-gray-700 text-sm">{{ formatDate(coupon?.expiry_date || coupon?.end_date) }}</td>
            <td class="px-6 py-4 text-gray-700">{{ coupon?.usage_limit || 'Unlimited' }}</td>
            <td class="px-6 py-4">
              <span
                :class="{
                  'bg-blue-100 text-blue-700': coupon?.duration === 'once',
                  'bg-purple-100 text-purple-700': coupon?.duration === 'forever',
                  'bg-orange-100 text-orange-700': coupon?.duration === 'repeating'
                }"
                class="px-2 py-1 rounded text-xs font-medium"
              >
                {{ coupon?.duration === 'once' ? 'First month' : coupon?.duration === 'forever' ? 'Forever' : coupon?.duration === 'repeating' ? `${coupon?.duration_in_months} months` : 'First month' }}
              </span>
            </td>
            <td class="px-6 py-4">
              <div v-if="coupon?.class_types && coupon.class_types.length > 0" class="flex flex-wrap gap-1">
                <span
                  v-for="ct in coupon.class_types"
                  :key="ct.id"
                  class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs font-medium"
                >
                  {{ ct.class_name || ct.name }}
                </span>
              </div>
              <span v-else class="text-gray-400 text-sm">All Packages</span>
            </td>
            <td class="px-6 py-4">
              <span
                :class="isCouponActive(coupon) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                class="px-3 py-1 rounded-full text-sm font-medium"
              >
                {{ isCouponActive(coupon) ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <button
                  @click="editCoupon(coupon)"
                  class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-sm transition-colors"
                >
                  Edit
                </button>
                <button
                  @click="deleteCoupon(coupon?.id)"
                  class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-sm transition-colors"
                  v-if="coupon?.id"
                >
                  Delete
                </button>
              </div>
            </td>
          </tr>
          </template>
        </tbody>
      </table>

      <div v-if="!loading && coupons.length === 0" class="p-12 text-center">
        <p class="text-gray-500">No coupons found</p>
      </div>
    </div>

    <!-- Add/Edit Coupon Modal -->
    <div
      v-if="showCouponForm"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto py-8"
    >
      <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-800">
            {{ editingCoupon ? 'Edit Coupon' : 'Add New Coupon' }}
          </h2>
          <button
            @click="cancelCouponForm"
            class="text-gray-500 hover:text-gray-700 text-2xl"
          >
            ×
          </button>
        </div>

        <form @submit.prevent="saveCoupon" class="space-y-6">
          <!-- Coupon Basics Section -->
          <div class="border-b pb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Coupon Basics</h3>
            
            <div class="space-y-4">
              <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                  Coupon Name <span class="text-red-500">*</span>
                  <span class="text-gray-400 text-xs ml-1">?</span>
                </label>
                <input
                  v-model="couponForm.name"
                  type="text"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                  placeholder="Enter coupon name"
                />
              </div>

              <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                  Coupon Code <span class="text-red-500">*</span>
                  <span class="text-gray-400 text-xs ml-1">?</span>
                </label>
                <input
                  v-model="couponForm.code"
                  type="text"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] uppercase"
                  :class="{ 'border-red-500': errors.code }"
                  placeholder="Enter coupon code"
                  @input="couponForm.code = couponForm.code.toUpperCase(); errors.code = ''"
                />
                <p v-if="errors.code" class="text-red-500 text-xs mt-1">{{ errors.code }}</p>
              </div>

              <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                  Coupon Amount <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-2">
                  <select
                    v-model="couponForm.discount_type"
                    required
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                  >
                    <option value="fixed">Flat($)</option>
                    <option value="percentage">Percentage(%)</option>
                  </select>
                  <div class="flex-1 relative flex items-center">
                    <span v-if="couponForm.discount_type === 'fixed'" class="absolute left-3 text-gray-500 z-10 pointer-events-none">$</span>
                    <input
                      v-model.number="couponForm.discount_value"
                      type="number"
                      step="0.01"
                      min="0"
                      :max="couponForm.discount_type === 'percentage' ? 100 : null"
                      required
                      :class="[
                        'w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]',
                        couponForm.discount_type === 'fixed' ? 'pl-8' : couponForm.discount_type === 'percentage' ? 'pr-12' : '',
                        errors.discount_value ? 'border-red-500' : ''
                      ]"
                      :placeholder="couponForm.discount_type === 'fixed' ? '0.00' : '0'"
                      @input="errors.discount_value = ''"
                    />
                    <span v-if="couponForm.discount_type === 'percentage'" class="absolute right-16 text-gray-500 text-sm pointer-events-none">%</span>
                    <span class="absolute right-3 text-gray-500 text-xs pointer-events-none bg-white px-1">CAD</span>
                  </div>
                </div>
                <p v-if="errors.discount_value" class="text-red-500 text-xs mt-1">{{ errors.discount_value }}</p>
              </div>
            </div>
          </div>

          <!-- Coupon Options Section -->
          <div class="border-b pb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Coupon Options</h3>
            
            <div class="space-y-4">
              <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                  Start Date
                  <span class="text-gray-400 text-xs ml-1">?</span>
                </label>
                <div class="relative">
                  <input
                    v-model="displayStartDate"
                    type="text"
                    class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] bg-white"
                    :class="{ 'border-red-500': errors.start_date }"
                    placeholder="DD-MM-YYYY"
                    maxlength="10"
                    @input="handleDateInput('start_date', $event)"
                    @blur="handleDateBlur('start_date')"
                    @keypress="formatDateInput($event, 'start_date')"
                    @keydown="handleDateKeydown($event, 'start_date')"
                  />
                  <input
                    ref="startDatePickerRef"
                    v-model="couponForm.start_date"
                    type="date"
                    :min="minDate"
                    :max="couponForm.expiry_date || maxDate"
                    class="absolute right-0 top-0 w-10 h-full opacity-0 cursor-pointer z-20"
                    @change="handleDateChange('start_date', $event)"
                    style="pointer-events: none;"
                  />
                  <svg 
                    @click.stop="openDatePicker('start')"
                    class="absolute right-3 top-2.5 w-5 h-5 text-gray-600 cursor-pointer hover:text-[#0055A4] z-30 transition-colors"
                    fill="none" 
                    stroke="currentColor" 
                    stroke-width="2"
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <p v-if="errors.start_date" class="text-red-500 text-xs mt-1">{{ errors.start_date }}</p>
                </div>
              </div>

              <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                  End Date
                  <span class="text-gray-400 text-xs ml-1">?</span>
                </label>
                <div class="relative">
                  <input
                    v-model="displayEndDate"
                    type="text"
                    class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4] bg-white"
                    :class="{ 'border-red-500': errors.expiry_date }"
                    placeholder="DD-MM-YYYY"
                    maxlength="10"
                    @input="handleDateInput('expiry_date', $event)"
                    @blur="handleDateBlur('expiry_date')"
                    @keypress="formatDateInput($event, 'expiry_date')"
                    @keydown="handleDateKeydown($event, 'expiry_date')"
                  />
                  <input
                    ref="endDatePickerRef"
                    v-model="couponForm.expiry_date"
                    type="date"
                    :min="couponForm.start_date || minDate"
                    :max="maxDate"
                    class="absolute right-0 top-0 w-10 h-full opacity-0 cursor-pointer z-20"
                    @change="handleDateChange('expiry_date', $event)"
                    style="pointer-events: none;"
                  />
                  <svg 
                    @click.stop="openDatePicker('end')"
                    class="absolute right-3 top-2.5 w-5 h-5 text-gray-600 cursor-pointer hover:text-[#0055A4] z-30 transition-colors"
                    fill="none" 
                    stroke="currentColor" 
                    stroke-width="2"
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <p v-if="errors.expiry_date" class="text-red-500 text-xs mt-1">{{ errors.expiry_date }}</p>
                </div>
              </div>

              <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                  Usage Limit
                  <span class="text-gray-400 text-xs ml-1">?</span>
                </label>
                <input
                  v-model.number="couponForm.usage_limit"
                  type="number"
                  min="1"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                  :class="{ 'border-red-500': errors.usage_limit }"
                  placeholder="Leave empty for unlimited"
                  @input="errors.usage_limit = ''"
                />
                <small class="text-gray-500 text-xs mt-1 block">Leave empty for unlimited usage</small>
                <p v-if="errors.usage_limit" class="text-red-500 text-xs mt-1">{{ errors.usage_limit }}</p>
              </div>

              <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                  Discount Duration
                  <span class="text-gray-400 text-xs ml-1">?</span>
                </label>
                <select
                  v-model="couponForm.duration"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                >
                  <option value="once">Once (First month only)</option>
                  <option value="forever">Forever (All recurring payments)</option>
                  <option value="repeating">Repeating (Specific months)</option>
                </select>
                <small class="text-gray-500 text-xs mt-1 block">
                  <span v-if="couponForm.duration === 'once'">Discount applies only to first billing</span>
                  <span v-else-if="couponForm.duration === 'forever'">Discount applies to every billing</span>
                  <span v-else>Discount applies for specified number of months</span>
                </small>
              </div>

              <div v-if="couponForm.duration === 'repeating'">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                  Duration in Months
                  <span class="text-red-500">*</span>
                </label>
                <input
                  v-model.number="couponForm.duration_in_months"
                  type="number"
                  min="1"
                  max="24"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                  placeholder="Number of months"
                />
                <small class="text-gray-500 text-xs mt-1 block">Discount will apply for this many months</small>
              </div>

              <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                  Applicable Packages
                  <span class="text-gray-400 text-xs ml-1">?</span>
                </label>
                <div v-if="loadingClassTypes" class="text-gray-500 text-sm py-2">Loading packages...</div>
                <div v-else-if="classTypes.length === 0" class="text-gray-500 text-sm py-2">No packages available</div>
                <div v-else class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3">
                  <label
                    v-for="classType in classTypes"
                    :key="classType.id"
                    class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded cursor-pointer"
                  >
                    <input
                      type="checkbox"
                      :value="classType.id"
                      v-model="couponForm.class_type_ids"
                      class="w-4 h-4 text-[#0055A4] border-gray-300 rounded focus:ring-[#0055A4]"
                    />
                    <div class="flex-1">
                      <span class="text-gray-800 font-medium">{{ classType.class_name || classType.name }}</span>
                      <span v-if="classType.price" class="text-gray-500 text-sm ml-2">${{ classType.price }}</span>
                    </div>
                  </label>
                </div>
                <small class="text-gray-500 text-xs mt-1 block">Leave empty to apply to all packages</small>
              </div>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="flex gap-4 pt-4">
            <button
              type="submit"
              :disabled="savingCoupon"
              class="flex-1 px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] disabled:opacity-50 text-white rounded-lg font-medium transition-colors"
            >
              {{ savingCoupon ? 'Saving...' : (editingCoupon ? 'Update Coupon' : 'Create Coupon') }}
            </button>
            <button
              type="button"
              @click="cancelCouponForm"
              class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition-colors"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useToast } from '../../composables/useToast'
import axios from 'axios'

const props = defineProps({
  noPadding: {
    type: Boolean,
    default: false
  }
})

const toast = useToast()
const coupons = ref([])
const loading = ref(false)
const showCouponForm = ref(false)
const editingCoupon = ref(null)
const savingCoupon = ref(false)
const errors = ref({})
const startDatePickerRef = ref(null)
const endDatePickerRef = ref(null)
const classTypes = ref([])
const loadingClassTypes = ref(false)

// Date constraints
const today = new Date()
const minDate = today.toISOString().split('T')[0] // Today's date in YYYY-MM-DD format
const maxDate = new Date(today.getFullYear() + 10, 11, 31).toISOString().split('T')[0] // 10 years from now

const couponForm = ref({
  name: '',
  code: '',
  discount_type: 'fixed',
  discount_value: 0,
  start_date: '', // Stores YYYY-MM-DD format for API
  expiry_date: '', // Stores YYYY-MM-DD format for API
  usage_limit: null,
  duration: 'once', // 'once', 'forever', 'repeating'
  duration_in_months: null,
  class_type_ids: []
})

// Display dates in DD-MM-YYYY format
const displayStartDate = ref('')
const displayEndDate = ref('')

// Convert YYYY-MM-DD to DD-MM-YYYY for display
const formatDateForDisplay = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString + 'T00:00:00')
  if (isNaN(date.getTime())) return ''
  const day = String(date.getDate()).padStart(2, '0')
  const month = String(date.getMonth() + 1).padStart(2, '0')
  const year = date.getFullYear()
  return `${day}-${month}-${year}`
}

// Convert DD-MM-YYYY to YYYY-MM-DD for storage
const formatDateForStorage = (dateString) => {
  if (!dateString) return ''
  
  // Remove any non-digit characters except dashes/slashes
  const cleaned = dateString.replace(/[^\d-\/]/g, '')
  
  // Check for DD-MM-YYYY or DD/MM/YYYY format
  if (/^\d{2}[-\/]\d{2}[-\/]\d{4}$/.test(cleaned)) {
    const parts = cleaned.split(/[-\/]/)
    return `${parts[2]}-${parts[1]}-${parts[0]}`
  }
  
  // Check for YYYY-MM-DD format (already correct)
  if (/^\d{4}-\d{2}-\d{2}$/.test(cleaned)) {
    return cleaned
  }
  
  return ''
}

const formatDate = (date) => {
  if (!date) return '-'
  return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}

const isCouponActive = (coupon) => {
  if (!coupon.start_date && !coupon.expiry_date) return true
  const now = new Date()
  const startDate = coupon.start_date ? new Date(coupon.start_date) : null
  const endDate = coupon.expiry_date ? new Date(coupon.expiry_date) : null
  
  if (startDate && now < startDate) return false
  if (endDate && now > endDate) return false
  return true
}

const loadCoupons = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/admin/coupons', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.data.success) {
      // Handle both paginated and non-paginated responses
      let couponsData = response.data.data
      // If paginated response, extract the data array
      if (couponsData && couponsData.data && Array.isArray(couponsData.data)) {
        couponsData = couponsData.data
      }
      // Filter out any null or invalid coupons
      coupons.value = (couponsData || []).filter(coupon => coupon && (coupon.id || coupon.code))
    } else {
      coupons.value = []
    }
  } catch (error) {
    console.error('Failed to load coupons:', error)
    const errorMessage = error.response?.data?.message || error.message || 'Failed to load coupons'
    toast.error(errorMessage)
    coupons.value = []
  } finally {
    loading.value = false
  }
}

const loadClassTypes = async () => {
  loadingClassTypes.value = true
  try {
    const response = await axios.get('/api/admin/class-types', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.data.success) {
      let data = response.data.data
      // Handle paginated response
      if (data && data.data && Array.isArray(data.data)) {
        data = data.data
      }
      classTypes.value = (data || []).filter(ct => ct && ct.id)
    } else {
      classTypes.value = []
    }
  } catch (error) {
    console.error('Failed to load class types:', error)
    classTypes.value = []
  } finally {
    loadingClassTypes.value = false
  }
}

const editCoupon = (coupon) => {
  if (!coupon || !coupon.id) {
    toast.error('Invalid coupon data')
    return
  }
  editingCoupon.value = coupon
  errors.value = {}
  
  // Format dates properly for date input (YYYY-MM-DD)
  let startDate = ''
  let expiryDate = ''
  
  if (coupon.start_date) {
    const start = new Date(coupon.start_date)
    if (!isNaN(start.getTime())) {
      startDate = start.toISOString().split('T')[0]
    }
  }
  
  if (coupon.expiry_date) {
    const expiry = new Date(coupon.expiry_date)
    if (!isNaN(expiry.getTime())) {
      expiryDate = expiry.toISOString().split('T')[0]
    }
  }
  
  couponForm.value = {
    name: coupon.name || coupon.coupon_name || '',
    code: coupon.code || '',
    discount_type: coupon.discount_type || 'fixed',
    discount_value: coupon.discount_value || 0,
    start_date: startDate,
    expiry_date: expiryDate,
    usage_limit: coupon.usage_limit || null,
    duration: coupon.duration || 'once',
    duration_in_months: coupon.duration_in_months || null,
    class_type_ids: coupon.class_types ? coupon.class_types.map(ct => ct.id) : []
  }
  
  // Update display dates (DD-MM-YYYY)
  displayStartDate.value = formatDateForDisplay(startDate)
  displayEndDate.value = formatDateForDisplay(expiryDate)
  
  showCouponForm.value = true
}

const formatDateInput = (event, field) => {
  // Only allow digits
  const char = String.fromCharCode(event.which)
  if (!/[0-9]/.test(char)) {
    event.preventDefault()
    return
  }
}

const handleDateKeydown = (event, field) => {
  const input = event.target
  const value = input.value
  const cursorPos = input.selectionStart
  
  // Handle backspace - if deleting a dash, delete the digit before it too
  if (event.key === 'Backspace' && cursorPos > 0 && value[cursorPos - 1] === '-') {
    event.preventDefault()
    const newValue = value.substring(0, cursorPos - 2) + value.substring(cursorPos)
    if (field === 'start_date') {
      displayStartDate.value = newValue
    } else {
      displayEndDate.value = newValue
    }
    setTimeout(() => {
      input.setSelectionRange(Math.max(0, cursorPos - 2), Math.max(0, cursorPos - 2))
    }, 0)
  }
  
  // Handle arrow keys - skip over dashes
  if (event.key === 'ArrowRight' && cursorPos < value.length && value[cursorPos] === '-') {
    event.preventDefault()
    input.setSelectionRange(cursorPos + 1, cursorPos + 1)
  }
  if (event.key === 'ArrowLeft' && cursorPos > 0 && value[cursorPos - 1] === '-') {
    event.preventDefault()
    input.setSelectionRange(cursorPos - 1, cursorPos - 1)
  }
}

const handleDateInput = (field, event) => {
  // Allow manual date entry - update the display value as user types
  let value = event.target.value.replace(/[^\d]/g, '') // Remove all non-digits first
  
  // Clear error when user starts typing
  if (errors.value[field]) {
    errors.value[field] = ''
  }
  
  // Limit to 8 digits (DDMMYYYY)
  if (value.length > 8) {
    value = value.substring(0, 8)
  }
  
  // Auto-format with dashes: DD-MM-YYYY
  let formattedValue = ''
  if (value.length > 0) {
    formattedValue = value.substring(0, 2)
    if (value.length > 2) {
      formattedValue += '-' + value.substring(2, 4)
      if (value.length > 4) {
        formattedValue += '-' + value.substring(4, 8)
      }
    }
  }
  
  // Update display value
  if (field === 'start_date') {
    displayStartDate.value = formattedValue
  } else {
    displayEndDate.value = formattedValue
  }
  
  // Auto-move cursor forward after dashes
  const input = event.target
  setTimeout(() => {
    const cursorPos = input.selectionStart
    const currentValue = input.value
    
    // If cursor is right before a dash, move it forward
    if (cursorPos < currentValue.length && currentValue[cursorPos] === '-') {
      input.setSelectionRange(cursorPos + 1, cursorPos + 1)
    }
  }, 0)
}

const openDatePicker = (type) => {
  const pickerRef = type === 'start' ? startDatePickerRef : endDatePickerRef
  
  if (pickerRef.value) {
    // Enable pointer events temporarily
    pickerRef.value.style.pointerEvents = 'auto'
    
    // Try modern showPicker() method first, fallback to click()
    if (typeof pickerRef.value.showPicker === 'function') {
      pickerRef.value.showPicker().catch(() => {
        // Fallback if showPicker fails
        pickerRef.value.click()
      })
    } else {
      pickerRef.value.click()
    }
    
    // Disable pointer events after a short delay
    setTimeout(() => {
      if (pickerRef.value) {
        pickerRef.value.style.pointerEvents = 'none'
      }
    }, 100)
  }
}

const handleDateChange = (field, event) => {
  // When date picker is used, update both storage and display
  const dateValue = event.target.value
  couponForm.value[field] = dateValue
  
  // Update display format
  if (field === 'start_date') {
    displayStartDate.value = formatDateForDisplay(dateValue)
  } else {
    displayEndDate.value = formatDateForDisplay(dateValue)
  }
  
  validateDateInput(field)
}

const handleDateBlur = (field) => {
  // Validate when user leaves the field
  validateDateInput(field)
}

const validateDateInput = (field) => {
  // Get display value
  const displayValue = field === 'start_date' ? displayStartDate.value : displayEndDate.value
  
  if (!displayValue || displayValue.trim() === '') {
    // Clear storage value if display is empty
    couponForm.value[field] = ''
    return // Empty is allowed (optional field)
  }

  // Convert DD-MM-YYYY to YYYY-MM-DD
  const formattedDate = formatDateForStorage(displayValue)
  
  if (!formattedDate) {
    errors.value[field] = 'Please enter a valid date in DD-MM-YYYY format'
    return
  }

  // Validate the date
  const date = new Date(formattedDate + 'T00:00:00')
  if (isNaN(date.getTime())) {
    errors.value[field] = 'Please enter a valid date in DD-MM-YYYY format'
    return
  }

  // Update storage value (YYYY-MM-DD)
  couponForm.value[field] = formattedDate
  
  // Update display value (DD-MM-YYYY) - ensure it's formatted correctly
  if (field === 'start_date') {
    displayStartDate.value = formatDateForDisplay(formattedDate)
  } else {
    displayEndDate.value = formatDateForDisplay(formattedDate)
  }

  // Validate constraints
  if (field === 'start_date') {
    const [year, month, day] = formattedDate.split('-')
    const startDate = new Date(year, parseInt(month) - 1, day)
    const today = new Date()
    today.setHours(0, 0, 0, 0)

    if (startDate < today) {
      errors.value.start_date = 'Start date cannot be in the past'
    } else {
      // Clear error if date is valid
      delete errors.value.start_date
    }

    if (couponForm.value.expiry_date) {
      const [eyear, emonth, eday] = couponForm.value.expiry_date.split('-')
      const expiryDate = new Date(eyear, parseInt(emonth) - 1, eday)
      if (startDate >= expiryDate) {
        errors.value.start_date = 'Start date must be before end date'
      }
    }
  } else if (field === 'expiry_date') {
    const [year, month, day] = formattedDate.split('-')
    const expiryDate = new Date(year, parseInt(month) - 1, day)

    if (couponForm.value.start_date) {
      const [syear, smonth, sday] = couponForm.value.start_date.split('-')
      const startDate = new Date(syear, parseInt(smonth) - 1, sday)
      if (expiryDate <= startDate) {
        errors.value.expiry_date = 'End date must be after start date'
      } else {
        // Clear error if date is valid
        delete errors.value.expiry_date
      }
    }
  }
}

const validateForm = () => {
  errors.value = {}
  let isValid = true

  // Validate coupon code
  if (!couponForm.value.code || couponForm.value.code.trim() === '') {
    errors.value.code = 'Coupon code is required'
    isValid = false
  } else if (couponForm.value.code.length < 3) {
    errors.value.code = 'Coupon code must be at least 3 characters'
    isValid = false
  }

  // Validate discount value
  if (!couponForm.value.discount_value || couponForm.value.discount_value <= 0) {
    errors.value.discount_value = 'Discount value must be greater than 0'
    isValid = false
  } else if (couponForm.value.discount_type === 'percentage' && couponForm.value.discount_value > 100) {
    errors.value.discount_value = 'Percentage cannot exceed 100%'
    isValid = false
  }

  // Validate dates
  if (couponForm.value.start_date || couponForm.value.expiry_date) {
    const todayDate = new Date()
    todayDate.setHours(0, 0, 0, 0)

    if (couponForm.value.start_date) {
      const [year, month, day] = couponForm.value.start_date.split('-')
      const startDate = new Date(year, parseInt(month) - 1, day)
      if (startDate < todayDate) {
        errors.value.start_date = 'Start date cannot be in the past'
        isValid = false
      }
    }

    if (couponForm.value.expiry_date) {
      const [year, month, day] = couponForm.value.expiry_date.split('-')
      const expiryDate = new Date(year, parseInt(month) - 1, day)
      if (couponForm.value.start_date) {
        const [syear, smonth, sday] = couponForm.value.start_date.split('-')
        const startDate = new Date(syear, parseInt(smonth) - 1, sday)
        if (startDate >= expiryDate) {
          errors.value.expiry_date = 'End date must be after start date'
          isValid = false
        }
      }
    }
  }

  // Validate usage limit
  if (couponForm.value.usage_limit !== null && couponForm.value.usage_limit < 1) {
    errors.value.usage_limit = 'Usage limit must be at least 1'
    isValid = false
  }

  return isValid
}

const saveCoupon = async () => {
  // Clear previous errors
  errors.value = {}
  
  // Validate form
  if (!validateForm()) {
    toast.error('Please fix the errors in the form')
    return
  }

  savingCoupon.value = true
  try {
    const data = {
      name: couponForm.value.name || null,
      code: couponForm.value.code.trim().toUpperCase(),
      discount_type: couponForm.value.discount_type,
      discount_value: parseFloat(couponForm.value.discount_value),
      start_date: couponForm.value.start_date || null,
      expiry_date: couponForm.value.expiry_date || null,
      usage_limit: couponForm.value.usage_limit ? parseInt(couponForm.value.usage_limit) : null,
      duration: couponForm.value.duration || 'once',
      duration_in_months: couponForm.value.duration === 'repeating' ? parseInt(couponForm.value.duration_in_months) : null,
      class_type_ids: couponForm.value.class_type_ids || []
    }

    if (editingCoupon.value) {
      const response = await axios.put(`/api/admin/coupons/${editingCoupon.value.id}`, data, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      })
      if (response.data.success) {
        toast.success('Coupon updated successfully')
        await loadCoupons()
        cancelCouponForm()
      }
    } else {
      const response = await axios.post('/api/admin/coupons', data, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      })
      if (response.data.success) {
        toast.success('Coupon created successfully')
        await loadCoupons()
        cancelCouponForm()
      }
    }
  } catch (error) {
    console.error('Failed to save coupon:', error)
    const errorMessage = error.response?.data?.message || error.response?.data?.error || 'Failed to save coupon'
    
    // Handle validation errors from backend
    if (error.response?.data?.errors) {
      errors.value = { ...errors.value, ...error.response.data.errors }
      toast.error('Please fix the validation errors')
    } else {
      toast.error(errorMessage)
    }
  } finally {
    savingCoupon.value = false
  }
}

const deleteCoupon = async (couponId) => {
  if (!confirm('Are you sure you want to delete this coupon?')) return

  try {
    const response = await axios.delete(`/api/admin/coupons/${couponId}`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.data.success) {
      toast.success('Coupon deleted successfully')
      await loadCoupons()
    }
  } catch (error) {
    console.error('Failed to delete coupon:', error)
    toast.error('Failed to delete coupon')
  }
}

const cancelCouponForm = () => {
  showCouponForm.value = false
  editingCoupon.value = null
  errors.value = {}
  couponForm.value = {
    name: '',
    code: '',
    discount_type: 'fixed',
    discount_value: 0,
    start_date: '',
    expiry_date: '',
    usage_limit: null,
    duration: 'once',
    duration_in_months: null,
    class_type_ids: []
  }
  displayStartDate.value = ''
  displayEndDate.value = ''
}

onMounted(async () => {
  await Promise.all([
    loadCoupons(),
    loadClassTypes()
  ])
})
</script>

<style scoped>
/* Ensure date inputs display properly */
input[type="date"] {
  position: relative;
}

input[type="date"]::-webkit-calendar-picker-indicator {
  opacity: 0;
  position: absolute;
  right: 0;
  width: 100%;
  height: 100%;
  cursor: pointer;
}

input[type="date"]::-webkit-inner-spin-button,
input[type="date"]::-webkit-clear-button {
  display: none;
}

/* Ensure date value is visible */
input[type="date"]:not(:placeholder-shown) {
  color: #1f2937;
}

input[type="date"]:invalid {
  color: #9ca3af;
}

/* Hide number input spinners */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type="number"] {
  -moz-appearance: textfield;
}

/* Ensure CAD label doesn't interfere */
input[type="number"] + span,
.relative span.pointer-events-none {
  user-select: none;
}
</style>

