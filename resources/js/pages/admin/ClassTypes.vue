<template>
  <div class="">
    <div class="flex justify-end items-center mb-6">
      <button
        @click="showClassTypeForm = true; editingClassType = null"
        class="px-6 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg font-medium transition-colors"
      >
        + Add Class Type
      </button>
    </div>

    <!-- Saving Order Indicator -->
    <div v-if="savingOrder" class="mb-4 px-4 py-2 bg-blue-50 text-blue-700 rounded-lg flex items-center gap-2">
      <div class="animate-spin rounded-full h-4 w-4 border-2 border-blue-700 border-t-transparent"></div>
      <span>Saving order...</span>
    </div>

    <!-- Class Types Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
      <div v-if="loadingClassTypes" class="p-12 text-center">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-[#0055A4]"></div>
        <p class="mt-2 text-gray-500">Loading class types...</p>
      </div>

      <table v-else class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-3 py-4 text-left text-sm font-semibold text-gray-700 w-10"></th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Name</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Description</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Price</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Duration</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Order</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(classType, index) in classTypes"
            :key="classType.id"
            class="border-b border-gray-200 hover:bg-gray-50 transition-colors"
            :class="{
              'bg-blue-50 border-blue-300': dragOverIndex === index,
              'opacity-50': draggingIndex === index
            }"
            draggable="true"
            @dragstart="handleDragStart($event, index)"
            @dragend="handleDragEnd"
            @dragover.prevent="handleDragOver($event, index)"
            @dragleave="handleDragLeave"
            @drop.prevent="handleDrop($event, index)"
          >
            <!-- Drag Handle -->
            <td class="px-3 py-4 cursor-move">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
              </svg>
            </td>
            <td class="px-6 py-4 font-medium text-gray-800">{{ classType.class_name || classType.name }}</td>
            <td class="px-6 py-4 text-gray-600 text-sm">{{ classType.description || '-' }}</td>
            <td class="px-6 py-4 text-gray-700">
              {{ formatPrice(classType.price || 0, classType.currency || 'CAD') }}
            </td>
            <td class="px-6 py-4 text-gray-700 capitalize">{{ classType.duration || '-' }}</td>
            <td class="px-6 py-4 text-gray-700">{{ classType.display_order || 0 }}</td>
            <td class="px-6 py-4">
              <div class="flex flex-col gap-2">
              <span
                :class="classType.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                class="px-3 py-1 rounded-full text-sm font-medium"
              >
                {{ classType.is_active ? 'Active' : 'Inactive' }}
              </span>
                <span
                  v-if="classType.is_batch_full"
                  class="px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800"
                >
                  Batch Full
                </span>
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <button
                  @click="editClassType(classType)"
                  class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-sm transition-colors"
                >
                  Edit
                </button>
                <button
                  @click="toggleClassTypeStatus(classType)"
                  class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-sm transition-colors"
                >
                  {{ classType.is_active ? 'Deactivate' : 'Activate' }}
                </button>
                <button
                  @click="deleteClassType(classType.id)"
                  class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-sm transition-colors"
                >
                  Delete
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="!loadingClassTypes && classTypes.length === 0" class="p-12 text-center">
        <p class="text-gray-500 mb-4">No class types found</p>
        <button
          @click="showClassTypeForm = true"
          class="px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] text-white rounded-lg text-sm font-medium"
        >
          Add First Class Type
        </button>
      </div>
    </div>

    <!-- Add/Edit Class Type Modal -->
    <div
      v-if="showClassTypeForm"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto py-8"
    >
      <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-800">
            {{ editingClassType ? 'Edit Class Type' : 'Add New Class Type' }}
          </h2>
          <button
            @click="cancelClassTypeForm"
            class="text-gray-500 hover:text-gray-700 text-2xl"
          >
            ×
          </button>
        </div>

        <form @submit.prevent="saveClassType" class="space-y-4">
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">
              Class Name <span class="text-red-500">*</span>
            </label>
            <input
              v-model="classTypeForm.name"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              placeholder="e.g., Group Class, Private Class"
            />
            <p class="text-[10px] text-gray-500 mt-1">This will show on the registration page.</p>
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Homepage Title (Optional)</label>
            <input
              v-model="classTypeForm.homepage_title"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              placeholder="Custom title for homepage pricing cards"
            />
            <p class="text-[10px] text-gray-500 mt-1">If empty, "Class Name" will be used on the homepage.</p>
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Registration Page Description</label>
            <textarea
              v-model="classTypeForm.description"
              rows="3"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              placeholder="Describe this class type for the registration page..."
            />
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">Homepage Description (Short)</label>
            <textarea
              v-model="classTypeForm.homepage_description"
              rows="2"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              placeholder="Short catchy description for homepage card..."
            />
          </div>

          <div>
            <div class="flex items-center justify-between mb-2">
              <label class="block text-gray-700 text-sm font-semibold">Homepage Features List</label>
              <button 
                type="button" 
                @click="addFeature"
                class="text-xs bg-[#0055A4] text-white px-2 py-1 rounded hover:bg-[#003d7a]"
              >
                + Add Feature
              </button>
            </div>
            <div class="space-y-2">
              <div v-for="(feature, index) in classTypeForm.features" :key="index" class="flex gap-2">
                <input
                  v-model="classTypeForm.features[index]"
                  type="text"
                  class="flex-1 px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#0055A4]"
                  placeholder="e.g., Small group sizes (2-5 students)"
                />
                <button 
                  type="button" 
                  @click="removeFeature(index)"
                  class="text-red-500 hover:text-red-700"
                >
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
              <p v-if="classTypeForm.features.length === 0" class="text-xs text-gray-400 italic">No features added yet.</p>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">
                Price <span class="text-red-500">*</span>
              </label>
              <input
                v-model.number="classTypeForm.price"
                type="number"
                step="0.01"
                min="0"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                placeholder="0.00"
              />
            </div>

            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">
                Currency <span class="text-red-500">*</span>
              </label>
              <select
                v-model="classTypeForm.currency"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              >
                <option value="CAD">CAD</option>
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
                <option value="GBP">GBP</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2">
              Duration <span class="text-red-500">*</span>
            </label>
            <select
              v-model="classTypeForm.duration"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
            >
              <option value="">Select Duration</option>
              <option value="weekly">Weekly</option>
              <option value="monthly">Monthly</option>
              <option value="quarterly">Quarterly</option>
            </select>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Display Order</label>
              <input
                v-model.number="classTypeForm.display_order"
                type="number"
                min="0"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                placeholder="0"
              />
            </div>

            <div class="flex items-center pt-6">
              <label class="flex items-center cursor-pointer">
                <input
                  v-model="classTypeForm.is_active"
                  type="checkbox"
                  class="w-4 h-4 text-[#0055A4]"
                />
                <span class="ml-2 text-gray-700 text-sm font-medium">Active</span>
              </label>
            </div>
          </div>

          <div class="flex items-center">
            <label class="flex items-center cursor-pointer">
              <input
                v-model="classTypeForm.is_popular"
                type="checkbox"
                class="w-4 h-4 text-[#0055A4]"
              />
              <span class="ml-2 text-gray-700 text-sm font-medium">Mark as Popular (Homepage Highlighting)</span>
            </label>
          </div>

          <div class="flex items-center pt-2">
            <label class="flex items-center cursor-pointer">
              <input
                v-model="classTypeForm.is_batch_full"
                type="checkbox"
                class="w-4 h-4 text-[#0055A4]"
              />
              <span class="ml-2 text-gray-700 text-sm font-medium">Batch is Full (Not Clickable)</span>
            </label>
          </div>

          <div v-if="classTypeForm.is_batch_full">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Batch Full Message</label>
            <input
              v-model="classTypeForm.batch_full_message"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
              placeholder="This batch is full"
            />
            <p class="text-xs text-gray-500 mt-1">This message will be displayed when users try to select this batch</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Batch Start Date</label>
              <input
                v-model="classTypeForm.batch_date"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                placeholder="e.g., Nov 22, 2025"
              />
            </div>
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2">Batch Schedule Details</label>
              <input
                v-model="classTypeForm.batch_schedule"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0055A4]/20 focus:border-[#0055A4]"
                placeholder="e.g., Sat & Sun 7AM-9AM PST"
              />
            </div>
          </div>

          <div class="flex items-center pt-2">
            <label class="flex items-center cursor-pointer">
              <input
                v-model="classTypeForm.disable_coupons"
                type="checkbox"
                class="w-4 h-4 text-[#0055A4]"
              />
              <span class="ml-2 text-gray-700 text-sm font-medium">Disable Coupons (No discounts allowed for this class)</span>
            </label>
          </div>

          <div class="flex gap-4 pt-4 border-t">
            <button
              type="submit"
              :disabled="savingClassType"
              class="flex-1 px-4 py-2 bg-[#0055A4] hover:bg-[#003d7a] disabled:opacity-50 text-white rounded-lg font-medium transition-colors"
            >
              {{ savingClassType ? 'Saving...' : (editingClassType ? 'Update' : 'Create') }}
            </button>
            <button
              type="button"
              @click="cancelClassTypeForm"
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
const classTypes = ref([])
const loadingClassTypes = ref(false)
const showClassTypeForm = ref(false)
const editingClassType = ref(null)
const savingClassType = ref(false)

// Drag and drop state
const draggingIndex = ref(null)
const dragOverIndex = ref(null)
const savingOrder = ref(false)

const classTypeForm = ref({
  name: '',
  homepage_title: '',
  homepage_description: '',
  features: [],
  is_popular: false,
  description: '',
  price: 0,
  currency: 'CAD',
  duration: '',
  display_order: 0,
  is_active: true,
  is_batch_full: false,
  batch_full_message: 'This batch is full',
  batch_date: '',
  batch_schedule: '',
  disable_coupons: false
})

const addFeature = () => {
  classTypeForm.value.features.push('')
}

const removeFeature = (index) => {
  classTypeForm.value.features.splice(index, 1)
}

const formatPrice = (price, currency = 'CAD') => {
  if (!price) return '$0.00'
  return new Intl.NumberFormat('en-CA', {
    style: 'currency',
    currency: currency || 'CAD'
  }).format(price)
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
      classTypes.value = response.data.data || []
    } else {
      // If API returns success but no data, set empty array
      classTypes.value = []
    }
  } catch (error) {
    console.error('Failed to load class types:', error)
    // Only show error if it's not a 404 or empty table issue
    if (error.response?.status !== 404 && error.response?.status !== 500) {
      toast.error('Failed to load class types')
    }
    // Set empty array on error so UI doesn't break
    classTypes.value = []
  } finally {
    loadingClassTypes.value = false
  }
}

const editClassType = (classType) => {
  editingClassType.value = classType
  classTypeForm.value = {
    name: classType.class_name || classType.name || '',
    homepage_title: classType.homepage_title || '',
    homepage_description: classType.homepage_description || '',
    features: Array.isArray(classType.features) ? [...classType.features] : [],
    is_popular: classType.is_popular || false,
    description: classType.description || '',
    price: classType.price || 0,
    currency: classType.currency || 'CAD',
    duration: classType.duration || '',
    display_order: classType.display_order || 0,
    is_active: classType.is_active !== false,
    is_batch_full: classType.is_batch_full || false,
    batch_full_message: classType.batch_full_message || 'This batch is full',
    batch_date: classType.batch_date || '',
    batch_schedule: classType.batch_schedule || '',
    disable_coupons: classType.disable_coupons || false
  }
  showClassTypeForm.value = true
}

const toggleClassTypeStatus = async (classType) => {
  try {
    const updatedStatus = !classType.is_active
    const response = await axios.put(`/api/admin/class-types/${classType.id}`, {
      is_active: updatedStatus
    }, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    
    if (response.data.success) {
      toast.success(`Class type ${updatedStatus ? 'activated' : 'deactivated'} successfully`)
      await loadClassTypes()
    }
  } catch (error) {
    console.error('Failed to toggle status:', error)
    toast.error('Failed to toggle class type status')
  }
}

const deleteClassType = async (id) => {
  if (!confirm('Are you sure you want to delete this class type?')) return

  try {
    const response = await axios.delete(`/api/admin/class-types/${id}`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.data.success) {
      toast.success('Class type deleted successfully')
      await loadClassTypes()
    }
  } catch (error) {
    console.error('Failed to delete class type:', error)
    toast.error('Failed to delete class type')
  }
}

const saveClassType = async () => {
  savingClassType.value = true
  try {
    const data = {
      class_name: classTypeForm.value.name,
      homepage_title: classTypeForm.value.homepage_title || null,
      homepage_description: classTypeForm.value.homepage_description || null,
      features: classTypeForm.value.features.filter(f => f.trim() !== ''),
      is_popular: classTypeForm.value.is_popular || false,
      description: classTypeForm.value.description,
      price: classTypeForm.value.price,
      currency: classTypeForm.value.currency,
      duration: classTypeForm.value.duration || null,
      display_order: classTypeForm.value.display_order,
      is_active: classTypeForm.value.is_active,
      is_batch_full: classTypeForm.value.is_batch_full || false,
      batch_full_message: classTypeForm.value.batch_full_message || null,
      batch_date: classTypeForm.value.batch_date || null,
      batch_schedule: classTypeForm.value.batch_schedule || null,
      disable_coupons: classTypeForm.value.disable_coupons || false
    }

    if (editingClassType.value) {
      const response = await axios.put(`/api/admin/class-types/${editingClassType.value.id}`, data, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      })
      if (response.data.success) {
        toast.success('Class type updated successfully')
      }
    } else {
      const response = await axios.post('/api/admin/class-types', data, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      })
      if (response.data.success) {
        toast.success('Class type created successfully')
      }
    }
    
    await loadClassTypes()
    cancelClassTypeForm()
  } catch (error) {
    console.error('Failed to save class type:', error)
    toast.error(error.response?.data?.message || 'Failed to save class type')
  } finally {
    savingClassType.value = false
  }
}

const cancelClassTypeForm = () => {
  showClassTypeForm.value = false
  editingClassType.value = null
  classTypeForm.value = {
    name: '',
    homepage_title: '',
    homepage_description: '',
    features: [],
    is_popular: false,
    description: '',
    price: 0,
    currency: 'CAD',
    duration: '',
    display_order: 0,
    is_active: true,
    is_batch_full: false,
    batch_full_message: 'This batch is full',
    batch_date: '',
    batch_schedule: '',
    disable_coupons: false
  }
}

// Drag and drop handlers
const handleDragStart = (event, index) => {
  draggingIndex.value = index
  event.dataTransfer.effectAllowed = 'move'
  event.dataTransfer.setData('text/plain', index.toString())
}

const handleDragEnd = () => {
  draggingIndex.value = null
  dragOverIndex.value = null
}

const handleDragOver = (event, index) => {
  if (draggingIndex.value !== null && draggingIndex.value !== index) {
    dragOverIndex.value = index
  }
}

const handleDragLeave = () => {
  dragOverIndex.value = null
}

const handleDrop = async (event, dropIndex) => {
  const dragIndex = draggingIndex.value

  if (dragIndex === null || dragIndex === dropIndex) {
    dragOverIndex.value = null
    return
  }

  // Reorder the array
  const items = [...classTypes.value]
  const [draggedItem] = items.splice(dragIndex, 1)
  items.splice(dropIndex, 0, draggedItem)

  // Update display_order for all items based on new position
  items.forEach((item, index) => {
    item.display_order = index + 1
  })

  classTypes.value = items
  dragOverIndex.value = null
  draggingIndex.value = null

  // Save the new order to the server
  await saveOrder()
}

const saveOrder = async () => {
  savingOrder.value = true
  try {
    const orderData = classTypes.value.map((item, index) => ({
      id: item.id,
      display_order: index + 1
    }))

    await axios.post('/api/admin/class-types/reorder', {
      order: orderData
    }, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    toast.success('Order saved successfully')
  } catch (error) {
    console.error('Failed to save order:', error)
    toast.error('Failed to save order')
    // Reload to get the correct order from server
    await loadClassTypes()
  } finally {
    savingOrder.value = false
  }
}

onMounted(async () => {
  await loadClassTypes()
})
</script>

